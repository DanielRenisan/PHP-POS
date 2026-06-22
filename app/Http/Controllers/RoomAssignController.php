<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FloorPlan;
use App\Models\RoomAssign;
use App\Models\Room;
class RoomAssignController extends Controller
{
    public function index($id)
    {
        if (!auth()->user()->can('room.assign')) {
            abort(403, 'Unauthorized action.');
        }
        $room = Room::findOrFail($id);
        return view('room.assign.index', compact('room'));
    }

    public function assign(Request $request)
    {
        if (!auth()->user()->can('room.assign')) {
            abort(403, 'Unauthorized action.');
        }
        $msg  = 'Assigned';
        $rooms = $request->rooms;
        RoomAssign::where('room_type', $request->room_type)->delete();
        foreach($rooms ?? [] as $floor => $room)
        {
            foreach($room as $key => $roomId)
            {
                $assign = new RoomAssign();
                $assign->room_type = $request->room_type;
                $assign->floor_id = $floor;
                $assign->room_id = $roomId;
                $assign->save();

            }
        }
        
        return redirect('rooms')->with("msg",$msg);
    }
}
