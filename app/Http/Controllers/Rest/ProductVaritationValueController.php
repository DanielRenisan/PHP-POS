<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\ProductVariationValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductVaritationValueController extends Controller
{
    public function index()
    {


        $type = DB::table('product_variation_values')
            ->select('product_variation_values.*')
            ->get();
        return view('rest.product_variation_value.index', compact('type'));
    }

    public function store(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $type = new ProductVariationValue();
            $type->name = $request->Name;
           
                if ($request->hasfile('Image')) {
                    $cover1 = $request->file('Image');
                    $extension = $cover1->getClientOriginalExtension();
                    Storage::disk('images')->put($cover1->getFilename() . '.' . $extension, File::get($cover1));
                    $type->icon = $cover1->getFilename() . '.' . $extension;
                }

            $type->status = 'Active';
            $type->save();

            return redirect()->route('types.index')
                ->with('success', 'type successfully Created!!');
        } else {

            $type = ProductVariationValue::find($id);
            $type->name = $request->Name;
           
                if ($request->hasfile('Image')) {
                    $cover1 = $request->file('Image');
                    $extension = $cover1->getClientOriginalExtension();
                    Storage::disk('images')->put($cover1->getFilename() . '.' . $extension, File::get($cover1));
                    $type->icon = $cover1->getFilename() . '.' . $extension;
                }
            $type->status = $request->Status;
            $type->save();
            return redirect()->route('types.index')
                ->with('success', 'type successfully Updated!!');
        }
    }


    //delete
    public function delete($id)
    {
        //delete 
        $type = ProductVariationValue::find($id);
        $type->delete();

        return response()->json(['message' => 'type deleted successfully'], 204);
    }


    public function changeStatus(Request $request)
    {
        $itemId = $request->input('id');
        $status = $request->input('status');

        // Assuming you have a 'countries' table and 'status' field
        $type = ProductVariationValue::find($itemId);

        if (!$type) {
            return response()->json(['error' => 'type not found'], 404);
        }

        // Toggle the status
        $type->status = $status === 'Active' ? 'Deactive' : 'Active';
        $type->save();

        return response()->json(['message' => 'Status updated successfully']);
    }
}

