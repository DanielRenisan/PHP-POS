<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
class TaxController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('tax.view') && !auth()->user()->can('tax.create')) {
            abort(403, 'Unauthorized action.');
        }
        $taxes = Tax::all();
        $tax = Tax::select('taxes.*','taxes.group_parent_id')->get();
        $tax = $tax->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'group_parent_id' => $item->parent->name ?? null,
                'amount' => $item->amount,
                'status' => $item->status,
                'action' => 1
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($tax)
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
            ->editColumn(
                'amount',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$amount}}">{{$amount}}</span>'
            )
            ->rawColumns(['action', 'status','amount'])
            ->make(true);
        }
        return view('rest.tax.index',compact('taxes'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('tax.create')) {
            abort(403, 'Unauthorized action.');
        }
        $tax = new Tax();
        $tax->name = $request->name;
        $tax->group_parent_id = $request->group_parent_id;
        if ($request->SelectParent) {
            $tax->group_parent_id = $request->SelectParent;
        }
        $tax->amount = $request->amount;
        $tax->status = $request->has('status') ? 'Active' : 'Inactive';
        $tax->save();

        return redirect()->route('tax.index')
            ->with('success', 'tax successfully Created!!');
        
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('tax.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id; // id
        $tax = Tax::find($id);
        $tax->name = $request->name;
        $tax->group_parent_id = $request->group_parent_id;
        if ($request->SelectParent) {
            $tax->group_parent_id = $request->SelectParent;
        }
        $tax->amount = $request->amount;
        $tax->status = $request->has('status') ? 'Active' : 'Inactive';
        $tax->save();
        return redirect()->route('tax.index')
            ->with('success', 'tax successfully Updated!!');
    }

    public function show($id)
    {
        $tax = Tax::find($id);
        return $tax;    
    }
    //delete
    public function delete(Request $request)
    {
        if (!auth()->user()->can('tax.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $tax =  Tax::whereIn('id', $ids)->delete();;

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
