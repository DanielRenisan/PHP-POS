<?php

namespace App\Services;

use App\Models\Station;
use App\Models\Transactions;
use Illuminate\Support\Collection;

class StationRoutingService
{
    public function __construct(
        private StationTicketService $ticketService,
        private PrinterResolverService $printerResolver,
        private PrintDispatchService $printDispatcher,
    ) {}

    /**
     * Group transaction sell lines by station and create one ticket per station.
     *
     * @return Collection<int, \App\Models\StationOrderTicket>
     */
    public function createTicketsForTransaction(Transactions $transaction, ?int $createdBy = null): Collection
    {
        $transaction->loadMissing(['lines_of_sell.product.stations']);

        $grouped = [];
        foreach ($transaction->lines_of_sell as $line) {
            $product = $line->product;
            if (!$product) {
                continue;
            }
            $stations = $product->stations;
            if ($stations->isEmpty()) {
                continue;
            }

            foreach ($stations as $station) {
                if (!$station->is_active) {
                    continue;
                }
                $grouped[$station->id]['station'] = $station;
                $grouped[$station->id]['lines'][] = [
                    'transaction_sell_line_id' => $line->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name ?? ('Product #' . $product->id),
                    'variation_name' => $line->variation_name ?? null,
                    'quantity' => $line->quantity ?? 1,
                    'unit_name' => $line->unit ?? null,
                    'notes' => $line->note ?? null,
                ];
            }
        }

        $tickets = collect();
        foreach ($grouped as $stationId => $bucket) {
            /** @var Station $station */
            $station = $bucket['station'];
            $printer = $this->printerResolver->resolveForStation($station->id);

            $ticket = $this->ticketService->create([
                'transaction_id' => $transaction->id,
                'station' => $station,
                'printer' => $printer,
                'ticket_no' => $transaction->invoice_no ?? null,
                'lines' => $bucket['lines'],
            ], $createdBy);

            $this->printDispatcher->dispatch($ticket);

            $tickets->push($ticket);
        }

        return $tickets;
    }
}
