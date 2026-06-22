<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Transactions;
use App\Models\TransactionPayment;
use Yajra\DataTables\Facades\DataTables;
class SupplierController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('supplier.view') && !auth()->user()->can('supplier.create')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            
            $suppliers = Supplier::
                select(
                    'id',
                    'name',
                    'email',
                    'contact_no',
                    'address',
                );

            return Datatables::of($suppliers)
                ->addColumn(
                    'action',
                    '@can("supplier.update")
                    <a href="{{action(\'SupplierController@edit\', [$id])}}" class="btn btn-info btn-sm"  ><i class="fa fa-edit"></i></a>
                    @endcan
                    @can("supplier.delete")
                    <a href="{{action(\'SupplierController@destroy\', [$id])}}" class="btn btn-danger btn-sm delete-supplier"><i class="fa fa-trash"></i></a>
                    @endcan'
                )
                 ->addColumn('balance', function ($row) {
                    $transaction_ids = Transactions::where('type', 'purchase')
                    ->where('contact_id', $row->id)->pluck('id')->toArray();
                    $final_total = Transactions::where('type', 'purchase')
                    ->where('contact_id', $row->id)->sum('final_total');
                    $paid_amount = TransactionPayment::whereIn('transaction_id', $transaction_ids)->sum('amount');
                    $due = $final_total - $paid_amount;
                    return '<span class="display_currency payment_due" data-currency_symbol="true" data-orig-value="' . $due . '">' . $due . '</span>';
                 })
                
                ->rawColumns(['balance', 'action'])
                ->make(true);
        }
        return view('supplier.index');
    }

    public function create()
    {
        if (!auth()->user()->can('supplier.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('supplier.create')) {
            abort(403, 'Unauthorized action.');
        }
        if( !is_null($request->input('supplier_id')) )
        {
            $supplier = Supplier::find($request->input('supplier_id'));
            $msg  = ' Updated';
        }
        else
        {
            $rules = [
                "email" => "required|email|unique:suppliers,email",
            ];
            $request->validate($rules);
            $supplier = new Supplier();
            $msg  = 'Created';
        }
        
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->contact_no = $request->contact_no;
        $supplier->address = $request->address;
        $supplier->save();

        return redirect("suppliers")->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('supplier.update')) {
            abort(403, 'Unauthorized action.');
        }
        $supplier = Supplier::find($id);
        return view('supplier.create', compact('supplier'));
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('supplier.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {

                $supplier = Supplier::findOrFail($id);
                $supplier->delete();

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
