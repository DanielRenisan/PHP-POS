<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Table;
use App\Models\RoomAssign;
use App\Models\TransactionPayment;
use App\Models\TransactionSellLine;
use App\Models\BusinessLocation;
use App\Models\Business;

use DB;
use Yajra\DataTables\Facades\DataTables;
class SaleController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('sale.view')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $transactions = Transactions::orderBy('transactions.id', 'DESC')->
        leftjoin('transaction_sell_lines as tsl', 'transactions.id', '=', 'tsl.transaction_id')
        ->leftjoin('contacts as co', 'transactions.contact_id', '=', 'co.id')
        ->leftjoin('users as user', 'transactions.staff_id', '=', 'user.id')
        ->where('transactions.type', 'order')
        ->whereIn('transactions.status', ['final'])
        ->select([
            'transactions.id',
            'transactions.invoice_no',
            'transactions.order_type',
            'transactions.updated_at',
            'transactions.table_id',
            'transactions.room_id',
            'co.first_name as customer',
            'co.address_one',
            'co.address_two',
            'co.email',
            'co.mobile_no',
            'co.telephone_no',
            'user.first_name as staff',
            'transactions.final_total',
            'transactions.payment_status',
            'transactions.tax_amount',
            'transactions.discount_amount',
            'transactions.status',
            'transactions.created_by',
        ])->groupBy('transactions.id');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $transactions->whereIn('transactions.created_by', $permitted_users);
        }
        if(!empty($start_date) && !empty($end_date))
        {
            $transactions->whereDate('transactions.updated_at', '>=', $start_date)->whereDate('transactions.updated_at', '<=', $end_date);
        }
        $transactions = $transactions->get();
        $transactions = $transactions->transform(function($item) {
            $order_type = '';
            if(isset($item->room_id) && $item->order_type == 'Room Order')
            {
                // $room_get =  RoomAssign::find($item->room_id);
                // $order_type = $item->order_type .'(' .$room_get->room_id. ')';
                $room = RoomAssign::find($item->room_id);
                $order_type = $item->order_type . ' (' . optional($room)->room_id . ')';

            }
            if(isset($item->table_id) && $item->order_type == 'Dine in')
            {
                $table =  Table::find($item->table_id);
                $order_type = $item->order_type .'(' . optional($table)->table_name. ')';
            }
            if($item->order_type == 'Take away')
            {
                $order_type = $item->order_type;
            }
            if($item->order_type == 'Online')
            {
                $order_type = $item->order_type;
            }
            $total = $item->final_total;
            $paid_amount = TransactionPayment::where('transaction_id', $item->id)->sum('amount');
            $due_amount = $total - $paid_amount;
            $sell_lines = [];
            foreach($item->sell_lines as $key => $line)
            {
                $product = $line->product;
                $discount = 0;
                if($line->discount > 0)
                {
                    $discount = ($line->discount/100) * $line->purchase_price;
                }
                $product_array = [
                    'sr' => $key + 1,
                    'line_id' => $line->id,
                    'id' => $product->id,
                    'description' => $product->name,
                    'unit_price' => $line->unit_price,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => $line->quantity,
                    'dis' => $discount ?? 0,
                    'unit_cost' => $line->unit_price,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'status' => $line->status,
                    'orderNo' => $line->order_no,
                    'line_total' => $line->sub_total,
                ];
                array_push($sell_lines, $product_array);
            }
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
                'transaction_date' => date("Y-m-d H:i:s", strtotime($item->updated_at)),
                'incoice_no' => $item->invoice_no,
                'orderType' => $order_type,
                'customer' => $item->customer .''.$item->mobile_no ?? '',
                'address' => $item->address_one .' ' . $item->address_two,
                'email' => $item->email,
                'phone' => $item->mobile_no .' ' . $item->telephone_no,
                'staff' => $item->staff,
                'total_qty' => $item->lines_of_sell->sum('quantity'),
                'payment_status' => $item->payment_status,
                'sell_lines' => $sell_lines,
                'discount' => $item->discount_amount > 0 ? number_format($item->discount_amount, 2) : '0.00',
                'tax' => $item->tax_amount > 0 ? number_format($item->tax_amount, 2) : '0.00',
                'total_amount' => $item->lines_of_sell->sum('sub_total'),
                'grand_total' =>  $total,
                'status' => $item->payment_status,
                'paid_amount' => $paid_amount,
                'due_amount' => $due_amount > 0 ? $due_amount : 0,
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($transactions)
            ->editColumn(
                'payment_status',
                '<span class="label @payment_status($payment_status)">{{$payment_status}}
                    </span>'
            )
            ->editColumn(
                'grand_total',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$grand_total}}">{{$grand_total}}</span>'
            )
            ->editColumn(
                'paid_amount',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$paid_amount}}">{{$paid_amount}}</span>'
            )
            ->editColumn(
                'due_amount',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$due_amount}}">{{$due_amount}}</span>'
            )
            ->rawColumns(['payment_status', 'due_amount', 'paid_amount','grand_total'])
            ->make(true);
        }
        return view('sale.index', compact('transactions'));
    }

    public function getInvoiceSalesTotal(Request $request)
    {
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            $dep = $request->get('department');
            $emp = $request->get('employee');
            $sell_details = $this->getInvoiceTotals($start_date, $end_date, $dep, $emp);
           
            return $sell_details;
        }
    }

    public function getInvoiceTotals($start_date = null, $end_date = null, $dep = null, $emp = null)
    {
        $query = Transactions::where('transactions.type', '=', 'order')
            ->where('transactions.status', '!=', 'canceled')
            ->select(
                'transactions.id',
                'final_total',
                DB::raw('(SELECT SUM(tp.amount) FROM transaction_payments as tp WHERE tp.transaction_id = transactions.id) as total_paid'),
                DB::raw('(SELECT SUM(tsl.quantity) FROM transaction_sell_lines as tsl WHERE tsl.transaction_id = transactions.id) as total_quantity')
            )
            ->groupBy('transactions.id');
        //Check for permitted locations of a user
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $query->whereIn('transactions.created_by', $permitted_users);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween(DB::raw('date(updated_at)'), [$start_date, $end_date]);
        }
        if(!empty($dep))
        {
            if ($dep == '1') { // restaurant
                $query->where('transactions.type', 'order');
            } elseif ($dep == '2') { // hotel
                $query->whereIn('transactions.type', ['booking', 'checkin', 'checkout']);
            }
        }

        // employee filter
        if(!empty($emp)) {
            $query->where('transactions.created_by', $emp);
        }
        $sell_details = $query->get();

        $output['total_sell_inc_tax'] = $sell_details->sum('final_total');
        $output['total_received'] = $sell_details->sum('total_paid');
        $output['invoice_due'] = $sell_details->sum('final_total') - $sell_details->sum('total_paid');

        return $output;
    }

    public function getSalesTotalSell(Request $request)
    {
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            $dep = $request->get('department');
            $emp = $request->get('employee');
            $sell_details = $this->getSellTotals($start_date, $end_date, $dep, $emp);
           
            return $sell_details;
        }
    }


    public function getSellTotals($start_date = null, $end_date = null, $dep = null, $emp = null)
    {
        $query = Transactions::whereIn('transactions.type', ['order', 'booking', 'checkin', 'checkout'])
            ->where('transactions.status', '!=', 'canceled')
            ->select(
                'transactions.id',
                'final_total',
                DB::raw('(SELECT SUM(tp.amount) FROM transaction_payments as tp WHERE tp.transaction_id = transactions.id) as total_paid'),
                DB::raw('(SELECT SUM(tsl.quantity) FROM transaction_sell_lines as tsl WHERE tsl.transaction_id = transactions.id) as total_quantity')
            )
            ->groupBy('transactions.id');
        //Check for permitted locations of a user
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $query->whereIn('transactions.created_by', $permitted_users);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween(DB::raw('date(updated_at)'), [$start_date, $end_date]);
        }
        if(!empty($dep))
        {
            if ($dep == '1') { // restaurant
                $query->where('transactions.type', 'order');
            } elseif ($dep == '2') { // hotel
                $query->whereIn('transactions.type', ['booking', 'checkin', 'checkout']);
            }
        }

        // employee filter
        if(!empty($emp)) {
            $query->where('transactions.created_by', $emp);
        }
        $sell_details = $query->get();

        $output['total_sell_inc_tax'] = $sell_details->sum('final_total');
        $output['total_received'] = $sell_details->sum('total_paid');
        $output['invoice_due'] = $sell_details->sum('final_total') - $sell_details->sum('total_paid');

        return $output;
    }

    public function cancel(Request $request)
    {
        if (!auth()->user()->can('sale.cancel')) {
            abort(403, 'Unauthorized action.');
        }
        $transaction = Transactions::find($request->transaction_id);
        $transaction->status = 'canceled';
        $transaction->save();
        TransactionPayment::create([
            'transaction_id' => $transaction->id,
            'amount' => $this->num_uf($request->amount) ?? 0,
            'credit_amount' =>  null,
            'method' => 'cash',
            'card_transaction_number' => null,
            'card_number' => null,
            'card_type' => 'visa',
            'card_holder_name' => null,
            'card_month' => null,
            'card_security' => null,
            'cheque_number' => null,
            'cheque_issued_date' => null,
            'cheque_due_date' => null,
            'bank_account_number' => null,
            'note' => $request->note ?? null,
            'payment_status' => 'refund',
            'payment_ref_no' => $this->generateReferenceNumber()
        ]);

        return redirect()->back();
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

    public function order(Request $request)
    {
        if (!auth()->user()->can('order.list')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $transactions = Transactions::
        leftjoin('transaction_sell_lines as tsl', 'transactions.id', '=', 'tsl.transaction_id')
        ->leftjoin('contacts as co', 'transactions.contact_id', '=', 'co.id')
        ->leftjoin('users as user', 'transactions.staff_id', '=', 'user.id')
        ->where('transactions.type', 'order')
        ->where('transactions.payment_status', 'due')
        ->where('tsl.status', '!=', 'canceled')
        ->select([
            'transactions.id',
            'transactions.invoice_no',
            'transactions.order_type',
            'transactions.updated_at',
            'co.first_name as customer',
            'co.address_one',
            'co.address_two',
            'co.email',
            'co.mobile_no',
            'co.telephone_no',
            'user.first_name as staff',
            'transactions.final_total',
            'transactions.payment_status',
            'transactions.tax_amount',
            'transactions.status',
            'transactions.created_by',
        ])->groupBy('transactions.id');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $transactions->whereIn('transactions.created_by', $permitted_users);
        }
        if(!empty($start_date) && !empty($end_date))
        {
            $transactions->whereDate('transactions.updated_at', '>=', $start_date)->whereDate('transactions.updated_at', '<=', $end_date);
        }
        $transactions = $transactions->get();
        $transactions->transform(function($item) {

            $valid_sell_lines = $item->sell_lines->where('status', '!=', 'canceled');
            $sell_lines = [];

            foreach ($valid_sell_lines as $key => $line) {
                $product = $line->product;
                $discount = 0;
                if($line->discount > 0)
                {
                    $discount = ($line->discount/100) * $line->purchase_price;
                }
                $product_array = [
                    'sr' => $key + 1,
                    'line_id' => $line->id,
                    'id' => $product->id,
                    'description' => $product->name,
                    'unit_price' => $line->unit_price,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => $line->quantity,
                    'dis' => $discount ?? 0,
                    'unit_cost' => $line->unit_price,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'status' => $line->status,
                    'orderNo' => $line->order_no,
                    'line_total' => $line->sub_total,
                ];
                array_push($sell_lines, $product_array);
            }
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
                'transaction_date' => date("Y-m-d", strtotime($item->updated_at)),
                'incoice_no' => $item->invoice_no,
                'orderType' => $item->order_type,
                'customer' => $item->customer,
                'address' => $item->address_one .' ' . $item->address_two,
                'email' => $item->email,
                'phone' => $item->mobile_no .' ' . $item->telephone_no,
                'staff' => $item->staff,
                'total_qty' => $valid_sell_lines->sum('quantity'),
                'payment_status' => $item->payment_status,
                'sell_lines' => $sell_lines,
                'discount' => $discount_amount,
                'tax' => $item->tax_amount,
                'total_amount' => $valid_sell_lines->sum('sub_total'),
                'status' => $item->status,
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($transactions)
            ->editColumn(
                'payment_status',
                '<span class="label @payment_status($payment_status)">{{$payment_status}}
                    </span>'
            )
            ->editColumn(
                'total_amount',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$total_amount}}">{{$total_amount}}</span>'
            )
            ->rawColumns(['payment_status', 'total_amount'])
            ->make(true);
        }
        return view('sale.list', compact('transactions'));
    }

    public function show($id)
    {
        if(isset($id))
        {

            $transaction = Transactions::
            leftjoin('transaction_sell_lines as tsl', function ($join) {
                $join->on('transactions.id', '=', 'tsl.transaction_id')
                    ->where('tsl.status', '!=', 'canceled');
            })
            ->leftjoin('contacts as co', 'transactions.contact_id', '=', 'co.id')
            ->leftjoin('users as user', 'transactions.staff_id', '=', 'user.id')
            ->where('transactions.id', $id)
            ->select([
                'transactions.id',
                'transactions.invoice_no',
                'transactions.order_type',
                'transactions.updated_at',
                'transactions.table_id',
                'transactions.room_id',
                'co.first_name as customer',
                'co.address_one',
                'co.address_two',
                'co.email',
                'co.mobile_no',
                'co.telephone_no',
                'user.first_name as staff',
                'transactions.final_total',
                'transactions.payment_status',
                'transactions.tax_amount',
                'transactions.discount_amount',
                'transactions.service_charge',
                'transactions.details',
                'transactions.status',
            ])->first();

            $order_type = '';
            if(isset($transaction->room_id) && $transaction->order_type == 'Room Order')
            {
                $room_get =  RoomAssign::find($transaction->room_id);
                $order_type = $transaction->order_type .'(' .$room_get->room_id. ')';
            }
            if(isset($transaction->table_id) && $transaction->order_type == 'Dine in')
            {
                $table =  Table::find($transaction->table_id);
                $order_type = $transaction->order_type .'(' .$table->table_name. ')';
            }
            if($transaction->order_type == 'Take away')
            {
                $order_type = $transaction->order_type;
            }
            // $total = $transaction->final_total;
            $total = TransactionSellLine::where('transaction_id', $transaction->id)->where('status', '!=', 'canceled')->sum('sub_total');
            $paid_amount = TransactionPayment::where('transaction_id', $transaction->id)->sum('amount');
            $due_amount = $total - $paid_amount;

            $valid_sell_lines = $transaction->sell_lines->where('status', '!=', 'canceled');
            $sell_lines = [];
            foreach($valid_sell_lines as $key => $line)
            {
                $product = $line->product;
                $discount = 0;
                if($line->discount_amount > 0)
                {
                    $discount = $line->discount_amount;
                }
                $product_array = [
                    'sr' => $key + 1,
                    'line_id' => $line->id,
                    'id' => $product->id,
                    'description' => $product->name,
                    'unit_price' => $line->unit_price,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => $line->quantity,
                    'dis' => $discount ?? 0,
                    'unit_cost' => $line->unit_price,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'status' => $line->status,
                    'orderNo' => $line->order_no,
                    'line_total' => $line->sub_total,
                ];
                array_push($sell_lines, $product_array);
            }
            $payments = $transaction->payment_lines;
            return view('sale.show', compact('transaction', 'sell_lines', 'order_type', 'total','paid_amount', 'due_amount', 'payments'));
        }
        else
        {
            return redirect()->back();
        }
        
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
        ->with(['sell_lines', 'sell_lines.product'])->first();
        $location_details = BusinessLocation::find($transaction->location_id);

        $output['is_enabled'] = true;
        $business_details = Business::first();
        $layout = 'receipt';
        $output['check'] = null;
        $type = 'receipt';
        $orderNo = null;
        $output['html_content'] = view('pos.receipt.receipt', compact('transaction', 'location_details', 'business_details', 'type', 'orderNo'))->render();    
       
        return $output;
    }
}
