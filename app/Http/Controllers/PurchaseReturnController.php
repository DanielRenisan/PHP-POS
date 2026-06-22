<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Transactions;
use App\Models\Tax;
use App\Models\ProductStock;
use App\Models\PurchaseLine;
use App\Models\TransactionPayment;
use App\Models\BusinessLocation;
use App\Models\Business;
use App\Models\PurchaseReturnLine;
use DB;
use Yajra\DataTables\Facades\DataTables;
class PurchaseReturnController extends Controller
{
    public function __construct()
    {
        $this->dummyPaymentLine = ['method' => 'cash', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '', 'cheque_due_date' => '', 'cheque_issued_date' => '',
        'is_return' => 0, 'transaction_no' => ''];
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('purchase-return.view') && !auth()->user()->can('purchase-return.create')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $purchases = Transactions::leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
        ->leftJoin(
            'purchase_return_lines AS PL',
            'transactions.id',
            '=',
            'PL.transaction_id'
        )
        ->where('transactions.type', 'purchase_return')
        ->select(
            'transactions.id',
            'transaction_date',
            'transactions.ref_no',
            'transactions.old_ref_no',
            'contacts.first_name',
            'transactions.status',
            'transactions.final_total',
            DB::raw('SUM(PL.quantity) as total_quantity'),
            'transactions.created_at',
            'transactions.created_by',
        )
        ->groupBy('transactions.id');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $purchases->whereIn('transactions.created_by', $permitted_users);
        }
        if(!empty($start_date) && !empty($end_date))
        {
            $purchases->whereDate('transactions.transaction_date', '>=', $start_date)->whereDate('transactions.transaction_date', '<=', $end_date);
        }
        $purchases = $purchases->get();
        $purchases = $purchases->transform(function($item) {
            return [
                'id' => $item->id,
                'transaction_date' => date("Y-m-d", strtotime($item->transaction_date)),
                'ref_no' => $item->ref_no,
                'old_ref_no' => $item->old_ref_no,
                'customer' => $item->first_name,
                'qty' => $item->total_quantity,
                'final_total' => $item->final_total,
                'status' => $item->status,
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($purchases)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->editColumn(
                'status',
                '<span class="label btn-success">{{$status}}
                    </span>'
            )
            ->editColumn(
                'final_total',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
            )
            ->rawColumns(['action', 'status', 'final_total'])
            ->make(true);
        }
        return view('purchase_return.index', compact('purchases'));
    }

    public function create()
    {
        if (!auth()->user()->can('purchase-return.create')) {
            abort(403, 'Unauthorized action.');
        }
        $payment_line = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        $taxes = Tax::all();
        $suppliers = Contact::forSupplierDropdown();
        return view('purchase_return.create', compact(
            'suppliers', 
            'taxes',
            'payment_line', 
            'payment_types'
        ));
    }

    public function payment_types()
    {
        $payment_types = ['cash' => __('Cash'), 'credit' => 'Credit', 'card' => __('Card'), 'cheque' => __('Cheque'), 'bank_transfer' => __('Bank Transfer'), 'other' => __('Other')];


        return $payment_types;
    }

    public function getPurchase()
    {
        if (request()->ajax()) {
            $term = request()->term;
            if (empty($term)) {
                return json_encode([]);
            }
            $q = Transactions::
                where('type', 'purchase')
                ->where('status', 'received')
                ->where(function ($query) use ($term) {
                    $query->where('ref_no', 'like', '%' . $term .'%');;
                })
                
                ->select(
                    'id as product_id',
                    'ref_no',
                    'final_total',
                );

            
            $transactions = $q->get();
            
            $transactions_array = [];
            foreach ($transactions as $transaction) {
                $transactions_array[$transaction->product_id]['name'] = $transaction->ref_no;
                $transactions_array[$transaction->product_id]['sku'] = $transaction->final_total;
            }

            $result = [];
            $i = 1;
            $no_of_records = $transactions->count();
            if (!empty($transactions_array)) {
                foreach ($transactions_array as $key => $value) {
                    $result[] = [ 'id' => $i,
                                    'text' => $value['name'] . ' - ' . $value['sku'],
                                    'product_id' => $key
                                ];
                    $name = $value['name'];
                    $i++;
                }
            }
            return json_encode($result);
        }
    }

    public function getPurchaseEntryRow(Request $request)
    {
        if (request()->ajax()) {
            $transaction_id = $request->input('transaction_id');

            
            if (!empty($transaction_id)) {
                $row_count = $request->input('row_count');
                $transaction = Transactions::where('id', $transaction_id)
                ->with(
                    'customer',
                    'lines_of_purchase',
                    'lines_of_purchase.product',
                    'lines_of_purchase.product.variation',
                    'lines_of_purchase.product.pur_unit',
                    'location'
                )
                ->first();
                $currency_details = $this->purchaseCurrencyDetails();
                $taxes = Tax::all();
                return view('purchase_return.partial.row_data_form')
                    ->with(compact(
                        'transaction',
                        'row_count',
                        'currency_details', 'taxes'
                    ));
                
            }
        }
    }

    public function transactionData(Request $request)
    {
        if (request()->ajax()) {
            $transaction_id = $request->input('transaction_id');

            
            if (!empty($transaction_id)) {
                $transaction = Transactions::where('id', $transaction_id)
                ->first();
                
                return $transaction;
                
            }
        }
    }

    public function purchaseCurrencyDetails()
    {
        $output = [
            'purchase_in_diff_currency' => false,
            'p_exchange_rate' => 1,
        ];

        
        $currency = Currency::find(111);
        $output['thousand_separator'] = $currency->thousand_separator;
        $output['decimal_separator'] = $currency->decimal_separator;
        $output['symbol'] = $currency->symbol;
        $output['code'] = $currency->code;
        $output['name'] = $currency->currency;

        return (object)$output;
    }

    public function show($id)
    {
        if(!isset($id) || $id == 'undefined')
        {
            return redirect()->back();
        }
        $transaction = Transactions::where('id', $id)
                                        ->with(['lines_of_purchase_return', 'supplier'])
                                        ->first();
        return view('purchase_return.show', compact('transaction'));   
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('purchase-return.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $ref = $request->ref_no;
            $old_transaction = Transactions::where('ref_no', $ref)
                                        ->with(['lines_of_purchase'])
                                        ->first();
            $transaction_data = $request->only([ 'ref_no', 'status', 'location_id', 'contact_id',
            'transaction_date', 'total_before_tax', 'discount_type', 'discount_amount','tax_id', 
            'tax_amount', 'final_total', 'details']);

            $exchange_rate = 1;
            $user_id = auth()->user()->id;
            $currency_details = $this->purchaseCurrencyDetails();

            //unformat input values
            $transaction_data['total_before_tax'] = $this->num_uf($transaction_data['total_before_tax'], $currency_details)*$exchange_rate;

            $transaction_data['discount_amount'] = 0;
            $transaction_data['contact_id'] = $old_transaction->contact_id;
            $transaction_data['final_total'] = $this->num_uf($transaction_data['final_total'], $currency_details)*$exchange_rate;

            $transaction_data['created_by'] = $user_id;
            $transaction_data['type'] = 'purchase_return';
            $transaction_data['status'] = 'final';
            $transaction_data['payment_status'] = 'paid';
            $transaction_data['transaction_date'] = date('Y-m-d h:i:s', strtotime($transaction_data['transaction_date']));
            
            $transaction = Transactions::create($transaction_data);
            $ref_no = $this->generateRefNo($transaction->id);
            $transaction->ref_no = $ref_no;
            $transaction->old_ref_no = $request->ref_no;
            $transaction->save();
                            

            $return_ids = $request->return_id;                            
            $edit_purchase_lines = PurchaseLine::whereIn('id', $return_ids)->get();
            DB::beginTransaction();
            $purchases = $request->purchases;
            foreach ($edit_purchase_lines as $purchase_line) {
                foreach ($purchases as $purchase) {
                    
                    if($purchase_line->product_id == $purchase['product_id'])
                    {
                        $purchase_line->is_return = 1;
                        $purchase_line->save();

                        $line = new PurchaseReturnLine();
                        $line->transaction_id = $transaction->id;
                        $line->product_id = $purchase_line->product_id;
                        $line->quantity = $purchase['quantity'];
                        $line->unit_price =  $purchase['purchase_price'];
                        $line->removed_purchase_line = $purchase_line->id;
                        $line->save();
                        $this->decreaseProductQuantity(
                            $purchase_line->product_id,
                            $transaction->location_id,
                            $purchase['quantity']
                        );
                    }
                    
                }
            }


            DB::commit();

            $output = ['success' => true,
                        'msg' => __('lang_v1.purchase_delete_success')
                    ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => $e->getMessage()
                        ];
        }

        return redirect('/purchase-returns');
    }

    private function decreaseProductQuantity($product_id, $location_id, $new_quantity, $old_quantity = 0)
    {
        $qty_difference = $new_quantity - $old_quantity;

        $product = Product::find($product_id);

        ProductStock::where('product_id', $product_id)
        ->where('location_id', $location_id)
        ->decrement('qty_available', $qty_difference);

        return true;
    }

    public function generateRefNo($ref_count)
    {
        $prefix = 'PR';

        $ref_digits =  str_pad($ref_count, 6, 0, STR_PAD_LEFT);

        $ref_number = $prefix . $ref_digits;

        return $ref_number;
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


    public function cancel($id)
    {
        if (!auth()->user()->can('purchase-return.cancel')) {
            abort(403, 'Unauthorized action.');
        }
        $transaction = Transactions::where('id', $id)
                                        ->with(['lines_of_purchase_return'])
                                        ->first();
        $transaction->status = 'canceled';
        $transaction->save();

        foreach($transaction->lines_of_purchase_return as $line)
        {
            $line->status = 0;
            $line->save();
            $this->increaseProductQuantity(
                $line->product_id,
                $transaction->location_id,
                $line->quantity
            );
        }

        $output = ['success' => true,
                        'msg' => __('Success')
                    ];

        return $output;            
    }

    private function increaseProductQuantity($product_id, $location_id, $new_quantity, $old_quantity = 0)
    {
        $qty_difference = $new_quantity;

        $product = Product::find($product_id);

        ProductStock::where('product_id', $product_id)
        ->where('location_id', $location_id)
        ->increment('qty_available', $qty_difference);

        return true;
    }
}
