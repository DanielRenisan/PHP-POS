<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventTypeController extends Controller
{
    public function index()
    {
        $EventType = DB::table('event_types')
            ->select('event_types.*')
            ->get();
        return view('rest.event_type.index', compact('EventType'));
    }

    public function store(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $EventType = new EventType();
            $EventType->name = $request->Name;
            $EventType->status = 'Active';
            $EventType->save();

            return redirect()->route('event_type.index')
                ->with('success', 'EventType successfully Created!!');
        } else {

            $EventType = EventType::find($id);
            $EventType->name = $request->Name;
            $EventType->status = $request->Status;
            $EventType->save();
            return redirect()->route('event_type.index')
                ->with('success', 'EventType successfully Updated!!');
        }
    }


    //delete
    public function delete($id)
    {
        //delete 
        $EventType = EventType::find($id);
        $EventType->delete();

        return response()->json(['message' => 'EventType deleted successfully'], 204);
    }


    public function changeStatus(Request $request)
    {
        $itemId = $request->input('id');
        $status = $request->input('status');

        // Assuming you have a 'countries' table and 'status' field
        $EventType = EventType::find($itemId);

        if (!$EventType) {
            return response()->json(['error' => 'EventType not found'], 404);
        }

        // Toggle the status
        $EventType->status = $status === 'Active' ? 'Deactive' : 'Active';
        $EventType->save();

        return response()->json(['message' => 'Status updated successfully']);
    }
}
