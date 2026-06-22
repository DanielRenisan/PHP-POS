<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class CustomerTypeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('customer-type.view') && !auth()->user()->can('customer-type.create')) {
            abort(403, 'Unauthorized action.');
        }
        $customer_type = CustomerType::get();
        $customer_type = $customer_type->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $item->status,
                'action' => 1
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($customer_type)
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
        return view('rest.customer_types.index');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('customer-type.create')) {
            abort(403, 'Unauthorized action.');
        }
        $customer_type = new CustomerType();
        $customer_type->name = $request->name;
        $customer_type->status = $request->has('status') ? 'Active' : 'Inactive';
        $customer_type->save();

        return redirect()->route('customer_type.index')
                ->with('success', 'customer_type successfully Created!!');
        
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('customer-type.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id; // id

        $customer_type = CustomerType::find($id);
        $customer_type->name = $request->name;
        $customer_type->status = $request->has('status') ? 'Active' : 'Inactive';
        $customer_type->save();
        return redirect()->route('customer_type.index')
            ->with('success', 'customer_type successfully Updated!!');
    }

    public function show($id)
    {
      $customer_type = CustomerType::find($id);

      return $customer_type;
    }

    public function delete(Request $request)
    {
        if (!auth()->user()->can('customer-type.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $customer_type =  CustomerType::whereIn('id', $ids)->delete();;

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
