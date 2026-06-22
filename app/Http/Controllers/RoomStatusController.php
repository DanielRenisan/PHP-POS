<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomAssign;
use App\Models\Room;
use App\Models\Floor;

class RoomStatusController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status') ?? '';
        $floor = $request->get('floor') ?? '';
        $available_room = Room::pluck('room_type')->unique()->toArray();
        $rooms = RoomAssign::orderBy('id')->whereIn('room_type', $available_room);
        if(!empty($status))
        {
            $rooms->where('status', $status);
        }
        if(!empty($floor))
        {
            $rooms->where('floor_id', $floor);
        }
        $rooms = $rooms->paginate(12);
        $floors = Floor::get();
        return view('room-status.index', compact('rooms','floors'));
    }
}
