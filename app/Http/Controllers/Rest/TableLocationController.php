<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use App\Models\TableLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TableLocationController extends Controller
{
    
    public function index()
    {

        if (!auth()->user()->can('table-location.view')) {
            abort(403, 'Unauthorized action.');
        }

        $tableLocation = TableLocation::get();
        $tableLocation = $tableLocation->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'location_name' => $item->location_name,
                'status' => $item->status,
                'action' => 1
            ];
        })->toArray();
        return view('rest.table_location.index')
            ->with('tableLocation', json_encode($tableLocation, JSON_NUMERIC_CHECK));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('table-location.store')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id; // id

        if ($id == 0) {

            $tableLocation = new TableLocation();
            $tableLocation->location_name = $request->name;
            $tableLocation->status = $request->has('Status') ? 'Active' : 'Inactive';
            $tableLocation->save();

            return redirect()->route('table_location.index')
                ->with('success', 'tableLocation successfully Created!!');
        } else {
            if (!auth()->user()->can('table-location.update')) {
                abort(403, 'Unauthorized action.');
            }

            $tableLocation = TableLocation::find($id);
            $tableLocation->location_name = $request->name;
            $tableLocation->status = $request->has('status') ? 'Active' : 'Inactive';
            $tableLocation->save();
            return redirect()->route('table_location.index')
                ->with('success', 'tableLocation successfully Updated!!');
        }
    }


    public function delete(Request $request)
    {
        if (!auth()->user()->can('table-location.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $tableLocation =  TableLocation::whereIn('id', $ids)->delete();;

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
