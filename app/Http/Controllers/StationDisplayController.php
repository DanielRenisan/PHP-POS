<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\StationOrderTicket;
use App\Models\StationOrderTicketLine;
use Illuminate\Http\Request;

class StationDisplayController extends Controller
{
    public function index(string $slug)
    {
        if (!auth()->user()->can('station.display.view')) {
            abort(403, 'Unauthorized action.');
        }

        $station = Station::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $tickets = StationOrderTicket::with(['lines', 'transaction'])
            ->where('station_id', $station->id)
            ->whereNotIn('status', [StationOrderTicket::STATUS_CANCELLED])
            ->orderByDesc('id')
            ->limit(100)
            ->get();

        return view('station-display.index', compact('station', 'tickets'));
    }

    public function updateLineStatus(Request $request, int $lineId)
    {
        if (!auth()->user()->can('station.display.view')) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', StationOrderTicketLine::statuses())],
        ]);

        $line = StationOrderTicketLine::findOrFail($lineId);
        $line->status = $data['status'];
        $line->save();

        return ['success' => true, 'status' => $line->status];
    }
}
