<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Floor;
use App\Models\FloorPlan;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class FloorPlaneController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('floor-plan.view') && !auth()->user()->can('floor-plan.create')) {
            abort(403, 'Unauthorized action.');
        }
      
        $plans = FloorPlan::
        join('floors', 'floor_plans.floor_id', '=', 'floors.id')
        ->select([
            'floors.name', 
            'floor_plans.start_room_no',
            'floor_plans.no_of_rooms', 
            'floor_plans.id',
            'floor_plans.floor_id'])->get();
        $plans = $plans->transform(function($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'start_room_no' => $item->start_room_no,
                'no_of_rooms' => $item->no_of_rooms,
                'action' => 1,
                'floor_id' => $item->floor_id,
            ];
        })->toArray();
        $floors = Floor::forDropdown();
        return view('plan.index', compact('floors'))
        ->with('plans',json_encode($plans,JSON_NUMERIC_CHECK));
    }

    public function create()
    {
        if (!auth()->user()->can('floor-plan.create')) {
            abort(403, 'Unauthorized action.');
        }
        $floors = Floor::forDropdown();
        return view('plan.create', compact('floors'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('floor-plan.create')) {
            abort(403, 'Unauthorized action.');
        }
        $plan = new FloorPlan();
        $msg  = 'Created';
        $plan->floor_id = $request->floor_id;
        $plan->start_room_no = $request->start_room_no;
        $plan->no_of_rooms = $request->no_of_rooms;
        $plan->save();

        return redirect("floor-plans")->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('floor-plan.update')) {
            abort(403, 'Unauthorized action.');
        }
        $plan =  FloorPlan::findOrFail($id);
        $floors = Floor::forDropdown();
        return view('plan.edit', compact('plan', 'floors'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('floor-plan.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $msg  = 'Updated';
            $plan =  FloorPlan::findOrFail($id);
            $plan->floor_id = $request->floor_id;
            $plan->start_room_no = $request->start_room_no;
            $plan->no_of_rooms = $request->no_of_rooms;
            $plan->save();
            $output = ['success' => true,
                            'msg' => $msg
                            ];
            return $output;
        }
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('floor-plan.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $plan =  FloorPlan::whereIn('id', $ids)->delete();

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
