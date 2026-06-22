<?php

namespace App\Services;

use App\Models\Printer;
use App\Models\Station;
use App\Models\StationOrderTicket;
use App\Models\StationOrderTicketLine;
use Illuminate\Support\Facades\DB;

class StationTicketService
{
    /**
     * Create a station ticket and its lines from a grouped payload.
     *
     * @param  array{
     *   transaction_id: int,
     *   station: \App\Models\Station,
     *   printer: \App\Models\Printer|null,
     *   ticket_no: string|null,
     *   lines: array<int, array{
     *      transaction_sell_line_id?: int|null,
     *      product_id?: int|null,
     *      product_name: string,
     *      variation_name?: string|null,
     *      quantity: float|int,
     *      unit_name?: string|null,
     *      notes?: string|null,
     *   }>
     * }  $data
     */
    public function create(array $data, ?int $createdBy = null): StationOrderTicket
    {
        /** @var Station $station */
        $station = $data['station'];
        /** @var Printer|null $printer */
        $printer = $data['printer'] ?? null;

        return DB::transaction(function () use ($data, $station, $printer, $createdBy) {
            $ticket = StationOrderTicket::create([
                'transaction_id' => $data['transaction_id'],
                'station_id' => $station->id,
                'printer_id' => $printer?->id,
                'ticket_code' => $station->code,
                'ticket_name' => $station->ticket_name,
                'ticket_no' => $data['ticket_no'] ?? null,
                'status' => StationOrderTicket::STATUS_PENDING,
                'payload_json' => $this->buildPayload($data),
                'created_by' => $createdBy,
            ]);

            foreach ($data['lines'] as $line) {
                StationOrderTicketLine::create([
                    'station_order_ticket_id' => $ticket->id,
                    'transaction_sell_line_id' => $line['transaction_sell_line_id'] ?? null,
                    'product_id' => $line['product_id'] ?? null,
                    'product_name' => $line['product_name'],
                    'variation_name' => $line['variation_name'] ?? null,
                    'quantity' => $line['quantity'],
                    'unit_name' => $line['unit_name'] ?? null,
                    'notes' => $line['notes'] ?? null,
                    'status' => StationOrderTicketLine::STATUS_PENDING,
                ]);
            }

            return $ticket->fresh('lines');
        });
    }

    public function markPrinted(StationOrderTicket $ticket): void
    {
        $ticket->update([
            'status' => StationOrderTicket::STATUS_PRINTED,
            'printed_at' => now(),
            'failed_reason' => null,
        ]);
    }

    public function markFailed(StationOrderTicket $ticket, string $reason): void
    {
        $ticket->update([
            'status' => StationOrderTicket::STATUS_FAILED,
            'failed_reason' => $reason,
        ]);
    }

    public function markReprinted(StationOrderTicket $ticket): void
    {
        $ticket->increment('retry_count');
        $ticket->update([
            'status' => StationOrderTicket::STATUS_REPRINTED,
            'printed_at' => now(),
            'failed_reason' => null,
        ]);
    }

    private function buildPayload(array $data): array
    {
        return [
            'station_code' => $data['station']->code,
            'station_name' => $data['station']->name,
            'ticket_name' => $data['station']->ticket_name,
            'lines' => array_map(function ($line) {
                return [
                    'product_name' => $line['product_name'],
                    'variation_name' => $line['variation_name'] ?? null,
                    'quantity' => $line['quantity'],
                    'unit_name' => $line['unit_name'] ?? null,
                    'notes' => $line['notes'] ?? null,
                ];
            }, $data['lines']),
        ];
    }
}
