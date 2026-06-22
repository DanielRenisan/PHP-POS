<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomFacility;
use App\Models\Customer;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class RoomFacilityController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('room-facility.view') && !auth()->user()->can('room-facility.create')) {
            abort(403, 'Unauthorized action.');
        }
        $facilities = RoomFacility::select(['name','id'])->get();
        if (request()->ajax()) {
        // $facilities = $facilities->transform(function($item){
        //     return [
        //         'id' => $item->id,
        //         'name' => $item->name,
        //         'action' => 1,
        //     ];
        // })->toArray();
        return Datatables::of($facilities)
        ->addColumn('action', function ($row) {
            $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row->id.'"/>';
            return $html;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
        return view('facility.index')
        ->with('facilities',json_encode($facilities,JSON_NUMERIC_CHECK));
    }

    public function create()
    {
        if (!auth()->user()->can('room-facility.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('facility.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('room-facility.create')) {
            abort(403, 'Unauthorized action.');
        }
        $msg  = 'Created';
        $facility = new RoomFacility();
        $facility->name = $request->name;
        $facility->save();
        return redirect('room-facilities')->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('room-facility.update')) {
            abort(403, 'Unauthorized action.');
        }
        $facility =  RoomFacility::findOrFail($id);
        return view('facility.edit', compact('facility'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('room-facility.update')) {
            abort(403, 'Unauthorized action.');
        }
        $msg  = 'updated';
        $id  = $request->facility_id;
        $facility =  RoomFacility::findOrFail($id);
        $facility->name = $request->name;
        $facility->save();
        $output = ['success' => true,
                        'msg' => $msg
                        ];
        return redirect('room-facilities')->with("msg",$msg);
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('room-facility.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $facility =  RoomFacility::whereIn('id', $ids)->delete();

                $output = ['success' => true,
                            'msg' => __("Deleted Success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("Something Went Wrong")
                        ];
            }

            return $output;
        }
    }

    public function show($id)
    {
        $facility =  RoomFacility::findOrFail($id);
        return $facility;
    }
    
}
