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
class PurchaseWastageController extends Controller
{
    public function __construct()
    {
        $this->dummyPaymentLine = ['method' => 'cash', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '', 'cheque_due_date' => '', 'cheque_issued_date' => '',
        'is_return' => 0, 'transaction_no' => ''];
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('purchase-wastage.view') && !auth()->user()->can('purchase-wastage.create')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $purchases = Transactions::orderBy('transactions.id', 'DESC')->leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
        ->where('transactions.type', 'purchase_wastage')
        ->select(
            'transactions.id',
            'transaction_date',
            'transactions.ref_no',
            'transactions.old_ref_no',
            'contacts.first_name',
            'transactions.status',
            'transactions.final_total',
            'transactions.created_at',
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
        return view('purchase_wastage.index', compact('purchases'));
    }

    public function create()
    {
        if (!auth()->user()->can('purchase-wastage.create')) {
            abort(403, 'Unauthorized action.');
        }
        $taxes = Tax::all();
        $suppliers = Contact::forSupplierDropdown();
        return view('purchase_wastage.create', compact(
            'suppliers', 
            'taxes'
        ));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('purchase-wastage.create')) {
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

            $exchange_rate = 1;
            $user_id = auth()->user()->id;
            $currency_details = $this->purchaseCurrencyDetails();

            //unformat input values
            $transaction_data['total_before_tax'] = 0;
            $transaction_data['final_total'] = 0;
            $transaction_data['discount_amount'] = 0;
            $transaction_data['contact_id'] = $old_transaction->contact_id;
            $transaction_data['location_id'] = $old_transaction->location_id;
            

            $transaction_data['created_by'] = $user_id;
            $transaction_data['type'] = 'purchase_wastage';
            $transaction_data['status'] = 'final';
            $transaction_data['payment_status'] = 'paid';
            $transaction_data['transaction_date'] = date('Y-m-d h:i:s', strtotime($transaction_data['transaction_date']));
            
           
            DB::beginTransaction();
            $transaction = Transactions::create($transaction_data);
            $ref_no = $this->generateRefNo($transaction->id);
            $transaction->ref_no = $ref_no;
            $transaction->old_ref_no = $old_transaction->ref_no;
            $transaction->save();

            $purchase_lines = [];
            $purchases = $request->input('purchases');
            foreach ($purchases as $purchase) {
                $line = new PurchaseLine();
                $line->transaction_id = $transaction->id;
                $line->product_id = $purchase['product_id'];
                $line->quantity = $this->num_uf($purchase['quantity_wastage'], $currency_details);
                $line->purchase_price =  $this->num_uf($purchase['purchase_price'], $currency_details)*$exchange_rate;
                $line->discount = 0;
                $line->discount_type = 'percentage';
                $line->line_total = ($this->num_uf($purchase['purchase_price'], $currency_details)*$exchange_rate) *  ($this->num_uf($purchase['quantity_wastage'], $currency_details));
                $line->parent_id = $purchase['line_id'];
                $line->save();

                if ($transaction->status == 'final') {
                    //if status received update existing quantity
                    $this->updateProductQuantity($transaction->location_id, $purchase['product_id'], $purchase['quantity_wastage']);
                }
            }

            // if (!empty($purchase_lines)) {
            //     $transaction->lines_of_purchase()->createMany($purchase_lines);
            // }
            
            $amount = $transaction->lines_of_purchase->sum('line_total');
            $transaction->final_total = $amount;
            $transaction->save();

            DB::commit();
            $output = ['success' => 1,
                            'msg' => __('purchase.purchase_add_success')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' =>  $e->getMessage()
                        ];
        }

        return redirect('/purchase-wastage')->with('status', $output);
    }

    public function updateProductQuantity($location_id, $product_id, $new_quantity, $old_quantity = 0, $number_format = null)
    {
        $qty_difference = $this->num_uf($new_quantity, $number_format);
        $product = Product::find($product_id);

        //Check if stock is enabled or not.
        if ($qty_difference != 0) {

            //Add quantity in Stock
            $variation_location_d = ProductStock::where('product_id', $product_id)
                ->where('location_id', $location_id)
                ->first();


            $variation_location_d->qty_wastage += $qty_difference;
            $variation_location_d->save();

        }

        return true;
    }

    public function show($id)
    {
        if(!isset($id) || $id == 'undefined')
        {
            return redirect()->back();
        }
        $transaction = Transactions::where('id', $id)
        ->with(['lines_of_purchase', 'supplier'])
        ->first();
        return view('purchase_wastage.show', compact('transaction'));   
    }

    public function getEntryRow(Request $request)
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
                return view('purchase_wastage.partial.row_data_form')
                    ->with(compact(
                        'transaction',
                        'row_count',
                        'currency_details', 'taxes'
                    ));
                
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

    public function generateRefNo($ref_count)
    {
        $prefix = 'PW';

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
}
