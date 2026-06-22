<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use App\Models\ContactType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactTypeController extends Controller
{
    public function index()
    {
        $contact_type = DB::table('contact_types')
            ->select('contact_types.*')
            ->get();
        return view('rest.contact_type.index', compact('contact_type'));
    }

    public function store(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $contact_type = new ContactType();
            $contact_type->name = $request->Name;
            $contact_type->status = 'Active';
            $contact_type->save();

            return redirect()->route('contact_type.index')
                ->with('success', 'contact_type successfully Created!!');
        } else {

            $contact_type = ContactType::find($id);
            $contact_type->name = $request->Name;
            $contact_type->status = $request->Status;
            $contact_type->save();
            return redirect()->route('contact_type.index')
                ->with('success', 'contact_type successfully Updated!!');
        }
    }


    //delete
    public function delete($id)
    {
        //delete 
        $contact_type = ContactType::find($id);
        $contact_type->delete();

        return response()->json(['message' => 'contact_type deleted successfully'], 204);
    }


    public function changeStatus(Request $request)
    {
        $itemId = $request->input('id');
        $status = $request->input('status');

        // Assuming you have a 'countries' table and 'status' field
        $contact_type = ContactType::find($itemId);

        if (!$contact_type) {
            return response()->json(['error' => 'contact_type not found'], 404);
        }

        // Toggle the status
        $contact_type->status = $status === 'Active' ? 'Deactive' : 'Active';
        $contact_type->save();

        return response()->json(['message' => 'Status updated successfully']);
    }
}
