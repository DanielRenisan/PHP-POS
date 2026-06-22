<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomSizeType;
use App\Models\Bed;
use App\Models\Room;
use App\Models\RoomType;
use DB;
use Yajra\DataTables\Facades\DataTables;
class RoomController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('room.view') && !auth()->user()->can('room.create')) {
            abort(403, 'Unauthorized action.');
        }
        $rooms = Room::leftjoin('beds', 'rooms.bed_id', '=', 'beds.id')
                ->leftjoin('room_size_types', 'rooms.room_size_id', '=', 'room_size_types.id')
                ->select([
                    'rooms.room_type', 
                    'rooms.rate', 
                    'rooms.bed_charge', 
                    'rooms.person_charge', 
                'rooms.capacity', 
                'rooms.extra_capacity',
                'rooms.bed_id',
                'rooms.room_size_id',
                'rooms.room_size as size',
                DB::raw("CONCAT(COALESCE(rooms.room_size, ''),' ',COALESCE(room_size_types.name, '')) as room_size"),
                'rooms.bed_no',
                'rooms.description',
                'rooms.condition',
                'beds.name as bet_type', 'rooms.review', 'rooms.id'])->get();
        $rooms = $rooms->transform(function($item){
            return [
                'id' => $item->id,
                'room_type' => $item->room_type,
                'rate' => $item->rate,
                'bed_charge' => $item->bed_charge,
                'person_charge' => $item->person_charge,
                'capacity' => $item->capacity,
                'extra_capacity' => $item->extra_capacity,
                'room_size' => $item->room_size,
                'size' => $item->size,
                'bed_no' => $item->bed_no,
                'bet_type' => $item->bet_type,
                'review' => $item->review,
                'action' => 1,
                'bed_id' => $item->bed_id,
                'room_size_id' => $item->room_size_id,
                'description' => $item->description,
                'condition' => $item->condition,
                'url' => action('RoomAssignController@index', [$item->id])
            ];
        })->toArray();        

        $sizes = RoomSizeType::forDropdown();
        $beds = Bed::forDropdown();
        $room_types = RoomType::pluck('name')->toArray();
        return view('room.index', compact('sizes', 'beds', 'room_types'))
        ->with('rooms',json_encode($rooms,JSON_NUMERIC_CHECK));
    }

    public function create()
    {
        if (!auth()->user()->can('room.create')) {
            abort(403, 'Unauthorized action.');
        }
        $sizes = RoomSizeType::forDropdown();
        $beds = Bed::forDropdown();
        $room_types = RoomType::pluck('name')->toArray();
        return view('room.create', compact('sizes', 'beds', 'room_types'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('room.create')) {
            abort(403, 'Unauthorized action.');
        }
        $msg  = 'Created';
        $room = new Room();
        $room->room_type = $request->room_type;
        $room->capacity = $request->capacity;
        $room->extra_capacity = $request->extra_capacity;
        $room->rate = $request->rate;
        $room->bed_charge = $request->bed_charge;
        $room->person_charge = $request->person_charge;
        $room->room_size = $request->room_size;
        $room->room_size_id = $request->room_size_id;
        $room->bed_no = $request->bed_no;
        $room->bed_id = $request->bed_id;
        $room->review = $request->review;
        $room->description = $request->description;
        $room->condition = $request->condition;
        $room->save();
        return redirect('rooms')->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('room.update')) {
            abort(403, 'Unauthorized action.');
        }
        $sizes = RoomSizeType::forDropdown();
        $beds = Bed::forDropdown();
        $room = Room::findOrFail($id);
        $room_types = RoomType::pluck('name')->toArray();
        return view('room.edit', compact('sizes', 'beds', 'room','room_types'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('room.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $msg  = 'Updated';
            $room = Room::findOrFail($id);
            $room->room_type = $request->room_type;
            $room->capacity = $request->capacity;
            $room->extra_capacity = $request->extra_capacity;
            $room->rate = $request->rate;
            $room->bed_charge = $request->bed_charge;
            $room->person_charge = $request->person_charge;
            $room->room_size = $request->room_size;
            $room->room_size_id = $request->room_size_id;
            $room->bed_no = $request->bed_no;
            $room->bed_id = $request->bed_id;
            $room->review = $request->review;
            $room->description = $request->description;
            $room->condition = $request->condition;
            $room->save();
            $output = ['success' => true,
                            'msg' => $msg
                            ];
            return $output;
        }
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('room.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $room = Room::whereIn('id', $ids)->delete();

                $output = ['success' => true,
                            'msg' => __("Deleted Success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    public function available()
    {
        return view('room_reservation.room.available');
    }
}
