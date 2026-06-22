<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('unit.view') && !auth()->user()->can('unit.create')) {
            abort(403, 'Unauthorized action.');
        }
        $Parent = Unit::all();
        $unit = Unit::select('units.*','units.unit_parent_id as parent_name')->get();
        $unit = $unit->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'short_code' => $item->short_code,
                'allow_decimal' => $item->allow_decimal,
                'unit_parent_id' => $item->unit_parent_id,
                'add_shortcode_for_otherunit' => $item->add_shortcode_for_otherunit,
                'value' => $item->value,
                'parent_name' => $item->parent->name ?? null,
                'status' => $item->status,
                'action' => 1
            ];
        })->toArray();
        return view('rest.unit.index',compact('Parent'))
            ->with('unit', json_encode($unit, JSON_NUMERIC_CHECK));
    }



    public function store(Request $request)
    {
        if (!auth()->user()->can('unit.create')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id; // id
        try {
            if (!isset($id)) {
                $unit = new Unit();
                $unit->short_code = $request->short_code;
                $unit->name = $request->name;
                $unit->allow_decimal = $request->allow_decimal;
                $unit->add_shortcode_for_otherunit = $request->add_shortcode_for_otherunit ?? 1;
                $unit->unit_parent_id = $request->unit_parent_id;

                if ($request->SelectParent) {
                    $unit->unit_parent_id = $request->SelectParent;
                }

                $unit->value = $request->value;
                $unit->status = $request->has('Status') ? 'Active' : 'Inactive';
                $unit->save();

                return redirect()->route('unit.index')
                    ->with('success', 'Unit successfully created!');
            }
            else {
                if (!auth()->user()->can('unit.update')) {
                    abort(403, 'Unauthorized action.');
                }

                $unit = Unit::find($id);
                $unit->short_code = isset($request->short_code) ? $request->short_code : $unit->short_code ;
                $unit->name = $request->name;
                $unit->allow_decimal = $request->allow_decimal;
                $unit->add_shortcode_for_otherunit = $request->add_shortcode_for_otherunit ?? 1;
                $unit->unit_parent_id = $request->unit_parent_id;
                if ($request->SelectParent) {
                    $unit->unit_parent_id = $request->SelectParent;
                }
                $unit->value = $request->value;
                $unit->status = $request->has('Status') ? 'Active' : 'Inactive';
                $unit->save();
                return redirect()->route('unit.index')
                    ->with('success', 'unit successfully Updated!!');
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }




    public function delete(Request $request)
    {
        if (!auth()->user()->can('unit.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $unit =  Unit::whereIn('id', $ids)->delete();;

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
