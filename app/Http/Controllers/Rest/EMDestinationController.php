<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use App\Models\EMDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EMDestinationController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('em-destination.view') && !auth()->user()->can('em-destination.create')) {
            abort(403, 'Unauthorized action.');
        }

        $items = $request->items ?? 25;
        $destinations = EMDestination::orderBy('id', 'DESC')->paginate($items);
        $destinations->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $item->status,
                'action' => 1
            ];
        })->toArray();
        return view('rest.em_destination.index', compact('destinations'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('em-destination.create')) {
            abort(403, 'Unauthorized action.');
        }
        
        $Em_destination = new EMDestination();
        $Em_destination->name = $request->name;
        $Em_destination->status = $request->has('status') ? 'Active' : 'Inactive';
        $Em_destination->save();

        return redirect()->route('destination.index')
            ->with('success', 'destination successfully Created!!');
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('em-destination.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id; // id
        $Em_destination = EMDestination::find($id);
        $Em_destination->name = $request->name;
        $Em_destination->status = $request->has('status') ? 'Active' : 'Inactive';
        $Em_destination->save();
        return redirect()->route('destination.index')
            ->with('success', 'destination successfully Updated!!');
    }

    public function show($id)
    {
        $destination = EMDestination::find($id);
        return $destination;
    }
    //delete
    public function delete(Request $request)
    {
        if (!auth()->user()->can('em-destination.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $Em_destination =  EMDestination::whereIn('id', $ids)->delete();;

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

}