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
use App\Models\CashRegister;
use App\Models\CashRegisterTransaction;
use DB;
use Yajra\DataTables\Facades\DataTables;
class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->dummyPaymentLine = ['method' => 'cash', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '', 'cheque_due_date' => '', 'cheque_issued_date' => '',
        'is_return' => 0, 'transaction_no' => ''];
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('purchase.view') && !auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $purchases = Transactions::orderBy('transactions.id', 'DESC')
        ->leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
        ->leftjoin('business_locations as bl', 'transactions.location_id', '=', 'bl.id')
        ->leftJoin(
            'transaction_payments AS TP',
            'transactions.id',
            '=',
            'TP.transaction_id'
        )
        ->leftJoin(
            'purchase_lines AS PL',
            'transactions.id',
            '=',
            'PL.transaction_id'
        )
        ->where('transactions.type', 'purchase')
        ->select(
            'transactions.id',
            'transaction_date',
            'transactions.ref_no',
            'bl.name as location',
            'contacts.first_name',
            'contacts.address_one',
            'contacts.address_two',
            'contacts.email',
            'contacts.mobile_no',
            'contacts.telephone_no',
            'transactions.status',
            'transactions.payment_status',
            'transactions.final_total',
            'TP.method',
            DB::raw('SUM(PL.quantity) as total_quantity'),
            'transactions.created_at',
            'transactions.tax_amount',
            'transactions.created_by',
            'TP.amount as amount_paid'
        )
        ->groupBy('transactions.id');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $purchases->whereIn('transactions.created_by', $permitted_users);
        }
        if(!empty($start_date) && !empty($end_date))
        {
            $purchases->whereDate('transactions.updated_at', '>=', $start_date)->whereDate('transactions.updated_at', '<=', $end_date);
        }
        $purchases = $purchases->get();
        $purchases = $purchases->transform(function($item) {
            $total_paid =  TransactionPayment::where('transaction_id',$item->id)->sum('amount');
            $due = $item->final_total - $total_paid;
            $status = '';
            if($item->status == 'received')
            {
                $status = 'GRN';
            }
            else
            {
                $status = 'PO';
            }
            $lines = $item->lines_of_purchase;
            $purchase_lines = [];
            foreach($lines as $key => $line)
            {
                $discount = 0;
                if($line->discount > 0)
                {
                    $discount = ($line->discount/100) * $line->purchase_price;
                }
                $purchase_line = [
                    'sr' => $key + 1,
                    'product_name' => $line->product->name,
                    'quantity' => $line->quantity,
                    'unit_cost' => $line->purchase_price,
                    'discount' => $discount,
                    'line_total' => $line->line_total,
                ];
                array_push($purchase_lines, $purchase_line);
            }
            $sub_total = $item->lines_of_purchase->sum('line_total');
            $discount_amount = 0;
            if($item->discount_type == 'percentage')
            {
                $discount_amount = ($item->discount_amount ?? 0/100) * $sub_total;
            }
            else 
            {
                $discount_amount = $item->discount_amount ?? 0;
            }
            
            return [
                'id' => $item->id,
                'transaction_date' => date("Y-m-d", strtotime($item->transaction_date)),
                'ref_no' => $item->ref_no,
                'location' => $item->location,
                'customer' => $item->first_name,
                'qty' => $item->total_quantity,
                'payment_status' => $item->payment_status,
                'final_total' => $item->final_total,
                'amount_paid' => $item->amount_paid,
                'due' => $due,
                'status' => $status,
                'address' => $item->address_one .' ' . $item->address_two,
                'email' => $item->email,
                'phone' => $item->mobile_no .' ' . $item->telephone_no,
                'edit_url' => action('PurchaseController@edit', $item->id),
                'sub_total' => $sub_total,
                'tax' => $item->tax_amount,
                'discount' => $discount_amount,
                'products' => $purchase_lines, 
                'p_status' => $item->payment_status,
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($purchases)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->editColumn(
                'payment_status',
                '<span class="label @payment_status($payment_status)">{{$payment_status}}
                    </span>'
            )
            ->editColumn(
                'final_total',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
            )
            ->editColumn(
                'amount_paid',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$amount_paid}}">{{$amount_paid}}</span>'
            )
            ->editColumn(
                'due',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$due}}">{{$due}}</span>'
            )
            ->rawColumns(['action', 'payment_status', 'final_total', 'amount_paid','due'])
            ->make(true);
        }
        return view('purchase.index', compact('purchases'));
    }

    public function create()
    {
        if (!auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }
        $payment_line = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        $taxes = Tax::all();
        $suppliers = Contact::forSupplierDropdown();
        // $business_locations = BusinessLocation::forDropdown();

        $business_locations = BusinessLocation::pluck('name', 'id');
        

        $default_location = null;
        if (count($business_locations) == 1) {
            foreach ($business_locations as $id => $name) {
                $default_location = $id;
            }
        }
        return view('purchase.create', compact(
            'suppliers', 
            'taxes',
            'payment_line', 
            'payment_types',
            'business_locations',
            'default_location'
        ));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $transaction_data = $request->only([ 'ref_no', 'status', 'location_id', 'contact_id',
            'transaction_date', 'total_before_tax', 'discount_type', 'discount_amount','tax_id', 
            'tax_amount', 'final_total', 'details']);

            $exchange_rate = 1;

            $request->validate([
                'status' => 'required',
                'contact_id' => 'required',
                'transaction_date' => 'required',
                'total_before_tax' => 'required',
                'final_total' => 'required',
                'document' => 'file|max:'. (config('constants.document_size_limit') / 1000)
            ]);

            if($request->warehouse_id == null) {
                $transaction_data['warehouse_id'] = null;
            } 

            $user_id = auth()->user()->id;
            $currency_details = $this->purchaseCurrencyDetails();

            //unformat input values
            $transaction_data['total_before_tax'] = $this->num_uf($transaction_data['total_before_tax'], $currency_details)*$exchange_rate;

            $transaction_data['discount_amount'] = $this->num_uf($transaction_data['discount_amount'], $currency_details)*$exchange_rate;
            $transaction_data['final_total'] = $this->num_uf($transaction_data['final_total'], $currency_details)*$exchange_rate;

            $transaction_data['created_by'] = $user_id;
            $transaction_data['type'] = 'purchase';
            $transaction_data['payment_status'] = 'due';
            $transaction_data['transaction_date'] = date('Y-m-d h:i:s', strtotime($transaction_data['transaction_date']));
            
            //upload document
            if ($request->hasFile('document') && $request->file('document')->isValid()) {
                if ($request->document->getSize() <= config('constants.document_size_limit')) {
                    $new_file_name = time() . '_' . $request->document->getClientOriginalName();
                    $path = $request->document->storeAs('public/documents', $new_file_name);
                    $transaction_data['document'] = str_replace('public/documents/', '', $path);
                }
            }
           
            DB::beginTransaction();
            $transaction = Transactions::create($transaction_data);
            if (empty($transaction_data['ref_no'])) {
                $ref_no = $this->generateRefNo($transaction->id);
            }
            $transaction->ref_no = $ref_no;
            $transaction->save();

            $purchase_lines = [];
            $purchases = $request->input('purchases');
            foreach ($purchases as $purchase) {
                $get_product = Product::find($purchase['product_id']);
                $get_product->last_purchase_price = $purchase['purchase_price'];
                $get_product->sale_price = $purchase['sale_price'];
                $get_product->save();
                $new_purchase_line = [
                'product_id' => $purchase['product_id'],
                'quantity'=> $this->num_uf($purchase['quantity'], $currency_details),
                'purchase_price' => $this->num_uf($purchase['purchase_price'], $currency_details)*$exchange_rate,
                'discount' => $this->num_uf($purchase['discount'], $currency_details),
                'discount_type' => $purchase['discount_type'],
                'line_total' => $this->num_uf($purchase['line_total'], $currency_details)*$exchange_rate,
                
                ];
                $purchase_lines[] = $new_purchase_line;

                if ($transaction->status == 'received') {
                    //if status received update existing quantity
                    $this->updateProductQuantity($transaction->location_id, $purchase['product_id'], $purchase['quantity']);
                }
            }
            $payments = $request->input('payment');
            if ($transaction_data['status'] == 'received') {
                foreach ($payments as $payment) {
                    TransactionPayment::create([
                        'transaction_id' => $transaction->id,
                        'amount' => $payment['method'] !==  'credit' ? $this->num_uf($payment['amount']) : 0,
                        'credit_amount' => $payment['method'] ==  'credit' ? $this->num_uf($payment['amount']) : null,
                        'method' => $payment['method'],
                        'card_transaction_number' => $payment['card_transaction_number'],
                        'card_number' => $payment['card_number'],
                        'card_type' => $payment['card_type'],
                        'card_holder_name' => $payment['card_holder_name'],
                        'card_month' => $payment['card_month'],
                        'card_security' => $payment['card_security'],
                        'cheque_number' => $payment['cheque_number'],
                        'cheque_issued_date' => $payment['cheque_issued_date'],
                        'cheque_due_date' => $payment['cheque_due_date'],
                        'bank_account_number' => $payment['bank_account_number'],
                        'note' => $payment['note'],
                        'payment_ref_no' => $this->generateReferenceNumber(),
                        'payment_status' => 'advance',
                    ]);
                }
                //update payment status
                $this->updatePaymentStatus($transaction->id, $transaction->final_total);
            }
            $this->cashRegisterUpdate($transaction, $payments);
            if (!empty($purchase_lines)) {
                $transaction->lines_of_purchase()->createMany($purchase_lines);
            }
            
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

        return redirect('purchases')->with('status', $output);
    }
    private function cashRegisterUpdate($transaction, $payments)
    {
        $user_id = auth()->user()->id;
        $register =  CashRegister::where('user_id', $user_id)
                                ->where('status', 'open')
                                ->first();
        if(!isset($register))
        {
            return false;
        }                         
        $payments_formatted = [];
        foreach ($payments as $payment) {
            $payments_formatted[] = new CashRegisterTransaction([
                    'amount' => $this->num_uf($payment['amount']),
                    'pay_method' => $payment['method'],
                    'type' => 'credit',
                    'transaction_type' => 'purchase',
                    'transaction_id' => $transaction->id
                ]);
        }

        if (!empty($payments_formatted)) {
            $register->cash_register_transactions()->saveMany($payments_formatted);
        }

        return true;
    }
    public function edit($id)
    {
        if (!auth()->user()->can('purchase.update')) {
            abort(403, 'Unauthorized action.');
        }
        $taxes = Tax::all();
        $suppliers = Contact::forSupplierDropdown();
        $currency_details = $this->purchaseCurrencyDetails();
        $purchase = Transactions::where('id', $id)
                                ->with(
                                    'customer',
                                    'lines_of_purchase',
                                    'purchase_lines.product',
                                    'purchase_lines.product.pur_unit',
                                    'location'
                                )
                                ->first();
        $business_locations = BusinessLocation::forDropdown();
        return view('purchase.edit', compact(
            'suppliers','purchase', 'currency_details','business_locations',
            'taxes'
        ));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('purchase.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $transaction = Transactions::findOrFail($id);
            $before_status = $transaction->status;
            $transaction_data = $request->only(['transaction_date', 'total_before_tax', 'discount_type', 'discount_amount','tax_id', 
            'tax_amount', 'final_total', 'details']);

            $exchange_rate = 1;

            $request->validate([
                'status' => 'required',
                'contact_id' => 'required',
                'transaction_date' => 'required',
                'total_before_tax' => 'required',
                'final_total' => 'required',
                'document' => 'file|max:'. (config('constants.document_size_limit') / 1000)
            ]);

            
            $user_id = auth()->user()->id;
            $currency_details = $this->purchaseCurrencyDetails();

            //unformat input values
            $transaction_data['total_before_tax'] = $this->num_uf($transaction_data['total_before_tax'], $currency_details)*$exchange_rate;

            $transaction_data['discount_amount'] = $this->num_uf($transaction_data['discount_amount'], $currency_details)*$exchange_rate;
            $transaction_data['final_total'] = $this->num_uf($transaction_data['final_total'], $currency_details)*$exchange_rate;

            $transaction_data['transaction_date'] = date('Y-m-d h:i:s', strtotime($transaction_data['transaction_date']));
           
            //upload document
            if ($request->hasFile('document') && $request->file('document')->isValid()) {
                if ($request->document->getSize() <= config('constants.document_size_limit')) {
                    $new_file_name = time() . '_' . $request->document->getClientOriginalName();
                    $path = $request->document->storeAs('public/documents', $new_file_name);
                    $transaction_data['document'] = str_replace('public/documents/', '', $path);
                }
            }
            
            DB::beginTransaction();
            $transaction->update($transaction_data);

            $updated_purchase_lines = [];
            
            $updated_purchase_line_ids = [0];
            //P => R (All items quantity update)
            //R => P (Existing minus)
            //R => R (Exisitng quantity update, New product add, minus deleted products)
            $purchases = $request->input('purchases');
            foreach ($purchases as $purchase) {
                $get_product = Product::find($purchase['product_id']);
                $get_product->last_purchase_price = $purchase['purchase_price'];
                $get_product->sale_price = $purchase['sale_price'];
                $get_product->save();
                //update existing purchase line
                if (isset($purchase['purchase_line_id'])) {
                    $purchase_line = PurchaseLine::findOrFail($purchase['purchase_line_id']);
                    $updated_purchase_line_ids[] = $purchase_line->id;
                    
                    //Update quantity for existing products
                    if ($before_status == 'received' && $transaction->status == 'received') {
                     //if status received update existing quantity
                        $this->updateProductQuantity($transaction->location_id, $purchase['product_id'], $purchase['quantity'], $purchase_line->quantity, $currency_details);
                    } elseif ($before_status == 'received' && $transaction->status != 'received') {
                        //decrease quantity only if status changed from received to not received
                            $this->decreaseProductQuantity(
                                $purchase['product_id'],
                                $transaction->location_id,
                                $purchase_line->quantity
                            );
                    } elseif ($before_status != 'received' && $transaction->status == 'received') {
                        $this->updateProductQuantity($transaction->location_id, $purchase['product_id'], $purchase['quantity'], 0, $currency_details);
                    }
                } else {
                    //create newly added purchase lines
                    $purchase_line = new PurchaseLine();
                    $purchase_line->product_id = $purchase['product_id'];
                    
                    //Increase quantity only if status is received
                    if ($transaction->status == 'received') {
                        $this->updateProductQuantity($transaction->location_id, $purchase['product_id'], $purchase['quantity'], 0, $currency_details);
                    }
                }
                
                $purchase_line->quantity = $this->num_uf($purchase['quantity'], $currency_details);
                $purchase_line->purchase_price = $this->num_uf($purchase['purchase_price'], $currency_details)*$exchange_rate;
                $purchase_line->discount = $this->num_uf($purchase['discount'], $currency_details);
                $purchase_line->discount_type = $purchase['discount_type'];
                $purchase_line->line_total = $this->num_uf($purchase['line_total'], $currency_details)*$exchange_rate;
                
                $updated_purchase_lines[] = $purchase_line;
                
            }
            //unset deleted purchase lines
            $delete_purchase_line_ids = [];
            if (!empty($updated_purchase_line_ids)) {
                $delete_purchase_lines = PurchaseLine::where('transaction_id', $transaction->id)
                            ->whereNotIn('id', $updated_purchase_line_ids)
                            ->get();

                if ($delete_purchase_lines->count()) {
                    foreach ($delete_purchase_lines as $delete_purchase_line) {
                        $delete_purchase_line_ids[] = $delete_purchase_line->id;

                        //decrease deleted only if previous status was received
                        if ($before_status == 'received') {
                            $this->decreaseProductQuantity(
                                $delete_purchase_line->product_id,
                                $transaction->location_id,
                                $delete_purchase_line->quantity
                            );
                        }
                    }
                    //Delete deleted purchase lines
                    PurchaseLine::where('transaction_id', $transaction->id)
                                ->whereIn('id', $delete_purchase_line_ids)
                                ->delete();
                }
            }

            //update purchase lines
            if (!empty($updated_purchase_lines)) {
                $transaction->purchase_lines()->saveMany($updated_purchase_lines);
            }
            
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

        return redirect('purchases')->with('status', $output);
    }

    private function updatePaymentStatus($transaction_id, $final_amount = null)
    {
        $status = $this->calculatePaymentStatus($transaction_id, $final_amount);
        Transactions::where('id', $transaction_id)
            ->update(['payment_status' => $status]);

        return $status;
    }

    private function calculatePaymentStatus($transaction_id, $final_amount = null)
    {
        $total_paid = $this->getTotalPaid($transaction_id);

        if (is_null($final_amount)) {
            $final_amount = Transactions::find($transaction_id)->final_total;
        }
        $transaction = Transactions::find($transaction_id);
        $status = 'due';
        if ($final_amount != 0 && $total_paid == 0 &&  $transaction->credit_note == 0) {
            $status = 'due';
        } elseif ($final_amount > $total_paid) {
            $status = 'partial';
        } else {
            $status = 'paid';
        }

        return $status;
    }

    private function getTotalPaid($transaction_id)
    {
        $total_paid = TransactionPayment::where('transaction_id', $transaction_id)
            ->select(DB::raw('SUM(amount) as total_paid'))
            ->first()
            ->total_paid;

        return $total_paid;
    }

    public function uf_date($date, $time = false)
    {
        $date_format = "d-m-Y";
        $mysql_format = 'Y-m-d';
        if ($time) {
            $date_format = $date_format . ' H:i';
            $mysql_format = 'Y-m-d H:i:s';
        }

        return \Carbon::createFromFormat($date_format, $date)->format($mysql_format);
    }

    public function generateRefNo($ref_count)
    {
        $prefix = 'PU';

        $ref_digits =  str_pad($ref_count, 6, 0, STR_PAD_LEFT);

        $ref_number = $prefix . $ref_digits;

        return $ref_number;
    }

    private function generateReferenceNumber()
    {
        $prefix = 'TP';
        $ref_count = rand(1000,9999);
        $ref_digits =  str_pad($ref_count, 4, 0, STR_PAD_LEFT);

        $ref_year = \Carbon::now()->year;
        $ref_number = $prefix . $ref_year . '/' . $ref_digits;

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

    public function payment_types()
    {
        $payment_types = ['cash' => __('Cash'), 'credit' => 'Credit', 'card' => __('Card'), 'cheque' => __('Cheque'), 'bank_transfer' => __('Bank Transfer'), 'other' => __('Other')];


        return $payment_types;
    }


    public function getProducts()
    {
        if (request()->ajax()) {
            $term = request()->term;

            $check_enable_stock = true;
            if (isset(request()->check_enable_stock)) {
                $check_enable_stock = filter_var(request()->check_enable_stock, FILTER_VALIDATE_BOOLEAN);
            }
            if (empty($term)) {
                return json_encode([]);
            }
            $q = Product::
            join(
                'product_a_t_t_assigns as ata',
                'products.id',
                '=',
                'ata.product_id'
            )->whereIn('ata.product_attr_id', [2])
                ->where(function ($query) use ($term) {
                    $query->where('products.name', 'like', '%' . $term .'%');
                    $query->orWhere('products.sku_code', 'like', '%' . $term .'%');
                })
                
                ->select(
                    'products.id as product_id',
                    'products.name',
                    'products.sku_code',
                );

            // if ($check_enable_stock) {
            //     $q->where('enable_stock', 0);
            // }
            $products = $q->get();
            $products_array = [];
            foreach ($products as $product) {
                $products_array[$product->product_id]['name'] = $product->name;
                $products_array[$product->product_id]['sku'] = $product->sku_code;
            }

            $result = [];
            $i = 1;
            $no_of_records = $products->count();
            if (!empty($products_array)) {
                foreach ($products_array as $key => $value) {
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
            $product_id = $request->input('product_id');

            
            if (!empty($product_id)) {
                $row_count = $request->input('row_count');
                $product = Product::where('id', $product_id)->with(['pur_unit','variation'])
                                    ->first();
                $currency_details = $this->purchaseCurrencyDetails();
                
                return view('purchase.partial.row_data_form')
                    ->with(compact(
                        'product',
                        'row_count',
                        'currency_details'
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

    public function updateProductQuantity($location_id, $product_id, $new_quantity, $old_quantity = 0, $number_format = null)
    {
        $qty_difference = $this->num_uf($new_quantity, $number_format) - $this->num_uf($old_quantity, $number_format);
        $product = Product::find($product_id);

        //Check if stock is enabled or not.
        if ($qty_difference != 0) {

            //Add quantity in Stock
            $variation_location_d = ProductStock::where('product_id', $product_id)
                ->where('location_id', $location_id)
                ->first();


            if (empty($variation_location_d)) {
                $variation_location_d = new ProductStock();

                $variation_location_d->product_id = $product_id;
                $variation_location_d->location_id = $location_id;
                $variation_location_d->qty_available = 0;
            }

            $variation_location_d->qty_available += $qty_difference;
            $variation_location_d->save();

            $product_stock = Product::where('id', $product_id)->first();
            $product_stock->stock += $qty_difference;
            $product_stock->save();

        }

        return true;
    }

    public function checkRefNumber(Request $request)
    {
        $contact_id = $request->input('contact_id');
        $ref_no = $request->input('ref_no');
        $purchase_id = $request->input('purchase_id');

        $count = 0;
        if (!empty($contact_id) && !empty($ref_no)) {
            //check in transactions table
            $query = Transactions::where('ref_no', $ref_no)
                            ->where('contact_id', $contact_id);
            if (!empty($purchase_id)) {
                $query->where('id', '!=', $purchase_id);
            }
            $count = $query->count();
        }
        if ($count == 0) {
            echo "true";
            exit;
        } else {
            echo "false";
            exit;
        }
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

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('purchase.delete')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            if (request()->ajax()) {
                $id = $request->id;
                $transaction = Transactions::where('id', $id)
                                            ->with(['lines_of_purchase'])
                                            ->first();
                $delete_purchase_lines = $transaction->purchase_lines;
                DB::beginTransaction();

                $transaction_status = $transaction->status;
                if ($transaction_status != 'received') {
                    $transaction->delete();
                } else {
                    //Delete purchase lines first
                    $delete_purchase_line_ids = [];
                    foreach ($delete_purchase_lines as $purchase_line) {
                        $delete_purchase_line_ids[] = $purchase_line->id;
                        $this->decreaseProductQuantity(
                            $purchase_line->product_id,
                            $transaction->location_id,
                            $purchase_line->quantity
                        );
                    }
                    PurchaseLine::where('transaction_id', $transaction->id)
                                ->whereIn('id', $delete_purchase_line_ids)
                                ->delete();
                }

                    //Delete Transaction
                    $transaction->delete();

                DB::commit();

                $output = ['success' => true,
                            'msg' => __('lang_v1.purchase_delete_success')
                        ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => $e->getMessage()
                        ];
        }

        return $output;
    }

    public function show($id)
    {
        if(!isset($id) || $id == 'undefined')
        {
            return redirect()->back();
        }
        $transaction = Transactions::leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
        ->leftJoin(
            'transaction_payments AS TP',
            'transactions.id',
            '=',
            'TP.transaction_id'
        )
        ->leftJoin(
            'purchase_lines AS PL',
            'transactions.id',
            '=',
            'PL.transaction_id'
        )
        ->where('transactions.type', 'purchase')
        ->where('transactions.id', $id)
        ->select(
            'transactions.id',
            'transactions.created_at',
            'transactions.ref_no',
            'contacts.first_name',
            'contacts.address_one',
            'contacts.address_two',
            'contacts.email',
            'contacts.mobile_no',
            'contacts.telephone_no',
            'transactions.status',
            'transactions.payment_status',
            'transactions.final_total',
            'TP.method',
            DB::raw('SUM(PL.quantity) as total_quantity'),
            'transactions.created_at',
            'transactions.tax_amount',
            'transactions.discount_amount',
            'transactions.discount_type',
            'transactions.details',
            DB::raw('SUM(TP.amount) as amount_paid')
        )->first();
        $lines = $transaction->lines_of_purchase;
        $purchase_lines = [];
        foreach($lines as $key => $line)
        {
            $discount = 0;
            if($line->discount > 0)
            {
                $discount = ($line->discount/100) * $line->purchase_price;
            }
            $purchase_line = [
                'sr' => $key + 1,
                'product_name' => $line->product->name,
                'quantity' => $line->quantity,
                'unit_cost' => $line->purchase_price,
                'discount' => $discount,
                'line_total' => $line->line_total,
            ];
            array_push($purchase_lines, $purchase_line);
        }
        $total_paid = TransactionPayment::where('transaction_id', $transaction->id)->sum('amount');
        $due_amount = $transaction->final_total - $total_paid;
        $sub_total = $transaction->lines_of_purchase->sum('line_total');
        $discount_amount = 0;
        if($transaction->discount_type == 'percentage')
        {
            $discount_amount = ($transaction->discount_amount ?? 0/100) * $sub_total;
        }
        else 
        {
            $discount_amount = $transaction->discount_amount ?? 0;
        }
        $grand_total =  $sub_total +  $transaction->tax_amount - $discount_amount;
        $payments = $transaction->payment_lines;
        return view('purchase.show', compact(
                'transaction', 'total_paid', 'purchase_lines', 'payments',
            'sub_total', 'discount_amount', 'grand_total', 'due_amount'
        ));
    }

    public function printInvoice($id)
    {
        $output = ['is_enabled' => false,
                    'print_type' => 'browser',
                    'html_content' => null,
                    'printer_config' => [],
                    'data' => []
                ];
        $transaction = Transactions::where('id', $id)
        ->with(['lines_of_purchase', 'lines_of_purchase.product'])->first();
        $location_details = BusinessLocation::find($transaction->location_id);

        $output['is_enabled'] = true;
        $business_details = Business::first();
        $output['html_content'] = view('purchase.receipt', compact('transaction', 'location_details', 'business_details'))->render();    
       
        return $output;
    }
}
