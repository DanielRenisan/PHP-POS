<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use App\Models\ContactDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactDocumentController extends Controller
{
    public function index()
    {
        $contactDocument = DB::table('contact_documents')
            ->select('contact_documents.*')
            ->get();
        return view('rest.category.index', compact('contactDocument'));
    }

    public function store(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $contactDocument = new ContactDocument();
            $contactDocument->contact_type_id = $request->ContactType;
            $contactDocument->document = $request->Document;
            $contactDocument->save();

            return redirect()->route('contact_document.index')
                ->with('success', 'contactDocument successfully Created!!');
        } else {

            $contactDocument = ContactDocument::find($id);
            $contactDocument->contact_type_id = $request->ContactType;
            $contactDocument->document = $request->Document;
            $contactDocument->save();
            return redirect()->route('contact_document.index')
                ->with('success', 'contactDocument successfully Updated!!');
        }
    }


    //delete
    public function delete($id)
    {
        //delete 
        $contactDocument = ContactDocument::find($id);
        $contactDocument->delete();

        return response()->json(['message' => 'contactDocument deleted successfully'], 204);
    }


}
