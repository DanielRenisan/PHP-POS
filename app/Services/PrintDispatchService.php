<?php

namespace App\Services;

use App\Jobs\PrintStationTicket;
use App\Models\StationOrderTicket;
use Illuminate\Support\Facades\Log;

class PrintDispatchService
{
    public function __construct(
        private StationTicketService $ticketService,
    ) {}

    /**
     * Send a ticket to its assigned printer. Failure here must NOT throw —
     * the order has already been saved; the ticket simply ends up in
     * "failed" state and can be reprinted.
     */
    public function dispatch(StationOrderTicket $ticket): void
    {
        try {
            PrintStationTicket::dispatch($ticket->id);
        } catch (\Throwable $e) {
            Log::warning('Station ticket print dispatch failed', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
            ]);
            $this->ticketService->markFailed($ticket, $e->getMessage());
        }
    }

    /**
     * Reprint flow: dispatch the job with the reprint flag and let the job
     * mark the ticket on success/failure. Do NOT mark reprinted up-front
     * — that hides real failures behind a successful-looking status.
     */
    public function reprint(StationOrderTicket $ticket): void
    {
        try {
            PrintStationTicket::dispatch($ticket->id, true);
        } catch (\Throwable $e) {
            Log::warning('Station ticket reprint dispatch failed', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
            ]);
            $this->ticketService->markFailed($ticket, $e->getMessage());
        }
    }
}
