<?php

namespace App\Http\Controllers;

use App\Models\StationOrderTicket;
use App\Services\PrintDispatchService;
use App\Services\PrinterResolverService;
use Illuminate\Http\Request;

class StationTicketController extends Controller
{
    public function __construct(
        private PrintDispatchService $printDispatcher,
        private PrinterResolverService $printerResolver,
    ) {}

    public function show(int $id)
    {
        if (!auth()->user()->can('station.display.view')) {
            abort(403, 'Unauthorized action.');
        }

        $ticket = StationOrderTicket::with(['lines', 'station', 'printer', 'transaction'])
            ->findOrFail($id);

        return ['success' => true, 'ticket' => $ticket];
    }

    public function reprint(Request $request, int $id)
    {
        if (!auth()->user()->can('station.ticket.reprint')) {
            abort(403, 'Unauthorized action.');
        }

        $ticket = StationOrderTicket::findOrFail($id);

        // Re-resolve printer in case the assignment changed
        if (!$ticket->printer_id) {
            $printer = $this->printerResolver->resolveForStation($ticket->station_id);
            if ($printer) {
                $ticket->printer_id = $printer->id;
                $ticket->save();
            }
        }

        $this->printDispatcher->reprint($ticket->fresh());

        return [
            'success' => true,
            'msg' => __('Reprint dispatched'),
            'ticket' => $ticket->fresh(),
        ];
    }
}
