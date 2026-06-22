<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class CustomerGroupController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('customer-group.view') && !auth()->user()->can('customer-group.create')) {
            abort(403, 'Unauthorized action.');
        }
        $customerGroup = CustomerGroup::get();
        $customerGroup = $customerGroup->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $item->status,
                'action' => 1
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($customerGroup)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->editColumn('status', function ($row) {
                $span = '<span class="btn btn-danger btn-sm" style="width:80px;color:#fff;">InActive</span>';
                if($row['status'] == 'Active')
                {
                    $span = '<span class="btn btn-success btn-sm" style="width:50px;color:#fff;">Active</span>';
                }
                return $span;
            })
            ->rawColumns(['action', 'status','amount'])
            ->make(true);
        }
        return view('rest.customer_group.index');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('customer-group.create')) {
            abort(403, 'Unauthorized action.');
        }
        $customerGroup = new CustomerGroup();
        $customerGroup->name = $request->name;
        $customerGroup->status = $request->has('status') ? 'Active' : 'Inactive';
        $customerGroup->save();

        return redirect()->route('customer_group.index')
            ->with('success', 'customerGroup successfully Created!!');
        
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('customer-group.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id;

        $customerGroup = CustomerGroup::find($id);
        $customerGroup->name = $request->name;
        $customerGroup->status = $request->has('status') ? 'Active' : 'Inactive';
        $customerGroup->save();
        return redirect()->route('customer_group.index')
            ->with('success', 'customerGroup successfully Updated!!');
    }

    public function show($id)
    {
        $customerGroup = CustomerGroup::find($id);

        return $customerGroup;
    }


    //delete
    public function delete(Request $request)
    {
        if (!auth()->user()->can('customer-group.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $customerGroup =  CustomerGroup::whereIn('id', $ids)->delete();;

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
