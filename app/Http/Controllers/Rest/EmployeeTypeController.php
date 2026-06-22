<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\EmployeeType;
use Illuminate\Http\Request;

class EmployeeTypeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('employee-type.view')  && !auth()->user()->can('employee-type.create') ) {
            abort(403, 'Unauthorized action.');
        }
        $items = $request->items ?? 25;
        $employeeTypes = EmployeeType::orderBy('id', 'DESC')->paginate($items);
        $employeeTypes->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $item->status,
                'action' => 1
            ];
        });
        return view('rest.employee_type.index', compact('employeeTypes'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('employee-type.create')) {
            abort(403, 'Unauthorized action.');
        }
        $EmployeeType = new EmployeeType();
        $EmployeeType->name = $request->name;
        $EmployeeType->status = $request->has('status') ? 'Active' : 'Inactive';
        $EmployeeType->save();

        return redirect()->route('employee_type.index')
            ->with('success', 'customerGroup successfully Created!!');
        
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('employee-type.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id;

        $EmployeeType = EmployeeType::find($id);
        $EmployeeType->name = $request->name;
        $EmployeeType->status = $request->has('status') ? 'Active' : 'Inactive';
        $EmployeeType->save();
        return redirect()->route('employee_type.index')
            ->with('success', 'customerGroup successfully Updated!!');
    }


    //delete
    public function delete(Request $request)
    {
        if (!auth()->user()->can('employee-type.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $EmployeeType =  EmployeeType::whereIn('id', $ids)->delete();;

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

    public function show($id)
    {
        $employee = EmployeeType::find($id);
        return $employee;
    }


}
