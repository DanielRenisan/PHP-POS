<?php

namespace App\Jobs;

use App\Models\Printer;
use App\Models\StationOrderTicket;
use App\Services\EscPosService;
use App\Services\PrinterResolverService;
use App\Services\StationTicketService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PrintStationTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $ticketId,
        public bool $isReprint = false,
    ) {}

    public function backoff(): array
    {
        return [5, 15, 30];
    }

    public function handle(StationTicketService $ticketService, PrinterResolverService $resolver): void
    {
        $ticket = StationOrderTicket::with(['printer', 'station', 'lines'])->find($this->ticketId);
        if (!$ticket) {
            return;
        }

        $candidates = $this->candidatePrinters($ticket, $resolver);

        if ($candidates->isEmpty()) {
            $reason = 'No active printer assigned to station #' . $ticket->station_id;
            Log::warning('Station ticket: ' . $reason, ['ticket_id' => $ticket->id]);
            $ticketService->markFailed($ticket, $reason);
            return;
        }

        $errors = [];
        foreach ($candidates as $printer) {
            try {
                $bytes = $this->buildTicket($ticket, $printer);
                $bytes->sendTo($printer);

                if ($ticket->printer_id !== $printer->id) {
                    $ticket->forceFill(['printer_id' => $printer->id])->save();
                }

                $this->isReprint
                    ? $ticketService->markReprinted($ticket)
                    : $ticketService->markPrinted($ticket);

                if (count($errors) > 0) {
                    Log::info('Station ticket printed via fallback printer', [
                        'ticket_id' => $ticket->id,
                        'printer_id' => $printer->id,
                        'previous_errors' => $errors,
                    ]);
                }
                return;

            } catch (\Throwable $e) {
                $errors[] = "printer #{$printer->id} ({$printer->name}): " . $e->getMessage();
                Log::warning('Station ticket print attempt failed', [
                    'ticket_id' => $ticket->id,
                    'printer_id' => $printer->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $ticketService->markFailed($ticket, 'All station printers failed: ' . implode(' | ', $errors));
    }

    /**
     * Build the candidate printer list: assigned printer first, then any other
     * active printers at the same station (so a downed primary auto-fails over).
     */
    private function candidatePrinters(StationOrderTicket $ticket, PrinterResolverService $resolver)
    {
        $stationId = (int) $ticket->station_id;
        $all = $stationId > 0 ? $resolver->resolveAllForStation($stationId) : collect();

        $primary = $ticket->printer;
        if ($primary && $primary->is_active && !$all->contains('id', $primary->id)) {
            $all->prepend($primary);
        }

        if ($primary) {
            $all = $all->sortByDesc(fn (Printer $p) => $p->id === $primary->id ? 1 : 0)->values();
        }

        return $all;
    }

    private function buildTicket(StationOrderTicket $ticket, Printer $printer): EscPosService
    {
        $cols = (int) ($printer->char_per_line ?: 42);
        $esc  = new EscPosService($cols);

        $stationName = optional($ticket->station)->ticket_name
            ?? optional($ticket->station)->name
            ?? $ticket->ticket_name
            ?? 'ORDER';

        if (!$this->isReprint) {
            $esc->beep();
        }

        $esc->blank()
            ->bigCenter($stationName)
            ->blank();

        if ($this->isReprint) {
            $esc->boldCenter('*** REPRINT ***')->blank();
        }

        if ($ticket->ticket_no) {
            $esc->boldCenter('Ticket #' . $ticket->ticket_no);
        }

        $esc->center(now()->format('d/m/Y  H:i:s'))
            ->divider();

        foreach ($ticket->lines as $line) {
            $qty  = rtrim(rtrim(number_format((float) $line->quantity, 3), '0'), '.');
            $name = $line->product_name;
            if ($line->variation_name) {
                $name .= ' (' . $line->variation_name . ')';
            }
            $esc->row($name, 'x' . $qty);

            if (!empty($line->notes)) {
                $esc->left('  ** ' . $line->notes);
            }
        }

        $esc->divider()->blank();

        return $esc;
    }
}
