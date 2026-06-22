<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\TransactionLine;
use App\Models\Supplier;

use Yajra\DataTables\Facades\DataTables;
class TransactionController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('purchase.view') && !auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $transactions = Transactions::join('suppliers', 'transactions.contact_id','=', 'suppliers.id')
               ->where('transactions.type', 'purchase')
                ->select(
                    'transactions.id',
                    'transactions.invoice_no',
                    'suppliers.name as name',
                    'transactions.transaction_date',
                    'transactions.final_total',
                    'transactions.payment_status',
                );

            return Datatables::of($transactions)
                ->addColumn(
                    'action',
                    '
                    @can("purchase.create")
                    <a href="{{action(\'TransactionController@edit\', [$id])}}" class="btn btn-info btn-sm"  ><i class="fa fa-edit" aria-hidden="true"></i></a>
                    @endcan
                    @can("purchase.view")
                    <a href="#" data-href="{{action(\'TransactionController@show\', [$id])}}" class="btn btn-info btn-sm btn-modal" data-container=".view_modal"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    @endcan
                    @can("purchase.delete")
                    <a href="{{action(\'TransactionController@destroy\', [$id])}}" class="btn btn-danger btn-sm delete-customer"><i class="fa fa-trash"></i></a>
                    @endcan
                    '
                )
                ->editColumn(
                    'final_total',
                    '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
                )
                ->editColumn('transaction_date', '{{@format_date($transaction_date)}}')
                ->editColumn(
                    'payment_status',
                    '<span class="label @payment_status($payment_status)">{{$payment_status}}
                        </span>'
                )
                ->rawColumns(['final_total','payment_status','transaction_date', 'action'])
                ->make(true);
        }
        return view('transaction.index');

    }

    public function create()
    {
        if (!auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }
        $suppliers = Supplier::forDropdown();
        return view('transaction.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }
        if( !is_null($request->input('transaction_id')) )
        {
            $transaction = Transactions::find($request->input('transaction_id'));
            $msg  = ' Updated';
        }
        else
        {
            $transaction = new Transactions();
            $msg  = 'Created';
        }
        
        $transaction->type = 'purchase';
        $transaction->status = 'received';
        $transaction->payment_status = 'paid';
        $transaction->contact_id = $request->contact_id ?? null;
        $transaction->invoice_no = $request->invoice_no;
        $transaction->transaction_date = $request->transaction_date;
        $transaction->expiry_date = $request->expiry_date;
        $transaction->details = $request->details;
        $transaction->final_total = $this->num_uf($request->final_total, null);
        $transaction->location_id = $request->location_id ?? null;
        $transaction->created_by = auth()->user()->id;
        $transaction->save();

        $items = $request->items;
        foreach($items ?? [] as $key => $item)
        {
                $assign = new TransactionLine();
                $assign->transaction_id = $transaction->id;
                $assign->item = $item['item'];
                $assign->quantity = $item['quantity'];
                $assign->unit_price = $this->num_uf($item['unit_price'], null);
                $assign->sub_total = $this->num_uf($item['sub_total'], null);
                $assign->save();
        }

        return redirect("transactions")->with("msg",$msg);
    }


    private function num_uf($input_number, $currency_details = [])
    {
        $thousand_separator  = '';
        $decimal_separator  = '';

        if (!empty($currency_details)) {
            $thousand_separator = $currency_details->thousand_separator;
            $decimal_separator = $currency_details->decimal_separator;
        } else {
            $thousand_separator = ',';
            $decimal_separator = '.';
        }

        $num = str_replace($thousand_separator, '', $input_number);
        $num = str_replace($decimal_separator, '.', $num);

        return (float)$num;
    }

    public function edit()
    {
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('purchase.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                foreach($ids as $id)
                {
                    $transaction = Transactions::findOrFail($id);
                    $transaction->purchase_lines()->delete();
                    $transaction->delete();
                }
                
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
        if (!auth()->user()->can('purchase.view')) {
            abort(403, 'Unauthorized action.');
        }
        $purchase = Transactions::where('id', $id)
        ->with(['purchase_lines','supplier'])
        ->first();
        return view('transaction.show', compact('purchase'));
    }
}
