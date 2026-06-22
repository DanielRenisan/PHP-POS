<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use App\Models\ContactEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactEventController extends Controller
{
    public function index()
    {
        $contactEvent = DB::table('contact_events')
            ->select('contact_events.*')
            ->get();
        return view('rest.contact_event.index', compact('contactEvent'));
    }

    public function store(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $contactEvent = new ContactEvent();
            $contactEvent->contact_type_id = $request->ContactType;
            $contactEvent->event_type_id = $request->EventType;
            $contactEvent->save();

            return redirect()->route('contact_event.index')
                ->with('success', 'contact_type successfully Created!!');
        } else {

            $contactEvent = ContactEvent::find($id);
            $contactEvent->contact_type_id = $request->ContactType;
            $contactEvent->event_type_id = $request->EventType;
            $contactEvent->save();
            return redirect()->route('contact_event.index')
                ->with('success', 'contactEvent successfully Updated!!');
        }
    }


    //delete
    public function delete($id)
    {
        //delete 
        $contactEvent = ContactEvent::find($id);
        $contactEvent->delete();

        return response()->json(['message' => 'contactEvent deleted successfully'], 204);
    }


    public function changeStatus(Request $request)
    {
        $itemId = $request->input('id');
        $status = $request->input('status');

        // Assuming you have a 'countries' table and 'status' field
        $contactEvent = ContactEvent::find($itemId);

        if (!$contactEvent) {
            return response()->json(['error' => 'contactEvent not found'], 404);
        }

        // Toggle the status
        $contactEvent->status = $status === 'Active' ? 'Deactive' : 'Active';
        $contactEvent->save();

        return response()->json(['message' => 'Status updated successfully']);
    }
}
