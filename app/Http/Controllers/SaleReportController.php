<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionPayment;
use App\Models\Transactions;
use App\Models\DepartmentPoss;
use App\Models\Product;
use App\Models\User;
use App\Models\BusinessLocation;
use App\Models\TransactionSellLine;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class SaleReportController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('sale-report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $department = $request->get('department');
        $employee = $request->get('employee');
        
            $transactions = Transactions::orderBy('transactions.id', 'DESC')
            ->leftjoin('bookings as bk', 'transactions.id', '=', 'bk.transaction_id')
            ->leftjoin('contacts as bc', 'bk.contact_id', '=', 'bc.id')
            ->leftjoin('contacts as co', 'transactions.contact_id', '=', 'co.id')
            ->leftjoin('users as user', 'transactions.created_by', '=', 'user.id')
            ->whereIn('transactions.type', ['order', 'booking', 'checkin', 'checkout'])
            ->where('transactions.status', '!=', 'canceled')
            ->select([
                'transactions.id',
                'transactions.updated_at',
                'transactions.invoice_no',
                'transactions.department_id',
                'transactions.created_by',
                'user.first_name as seller',
                'co.first_name as customer',
                'bc.first_name as bk_customer',
                'bk.ref_no',
                'transactions.type',
                'transactions.final_total',
                'transactions.payment_status',
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
            if(!empty($department))
            {
                // $transactions->where('transactions.department_id', $department);

                if ($department == '1') { // restaurant
                    $transactions->where('transactions.type', 'order');
                } elseif ($department == '2') { // hotel
                    $transactions->whereIn('transactions.type', ['booking', 'checkin', 'checkout']);
                }
            }
            if(!empty($employee))
            {
                $transactions->where('transactions.created_by', $employee);
            }
            
            $transactions = $transactions->get();
           
            $transactions->transform(function($item) {
                $total = $item->final_total;
                $paid_amount = TransactionPayment::where('transaction_id', $item->id)->sum('amount');
                $due_amount = $total - $paid_amount;
                $href = '#';
                
                if($item->type == 'order')
                {
                    $href = action('SaleController@show', $item->id);
                }
                if($item->type == 'booking')
                {
                    $href = action('BookingController@show', $item->id);
                }
                if($item->type == 'checkin')
                {
                    $href = action('CheckinController@show', $item->id);
                }
                if($item->type == 'checkout')
                {
                    $href = action('CheckoutController@show', $item->id);
                }
                return [
                    'id' => $item->id,
                    'transaction_date' => date("Y-m-d H:i:s", strtotime($item->updated_at)),
                    'incoice_no' => $item->invoice_no.''.$item->ref_no,
                    'seller' => $item->seller,
                    'customer' => $item->customer .' ' .$item->bk_customer,
                    'type' => $item->type,
                    'total_qty' => $item->lines_of_sell->sum('quantity'),
                    'payment_status' => $item->payment_status,
                    'grand_total' =>  $total,
                    'status' => $item->status,
                    'paid_amount' => $paid_amount,
                    'due_amount' => $due_amount,
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
        $departments = DepartmentPoss::where('status', "Active")->get();
        $employees =  User::join('employees as em', 'users.staff_id', '=', 'em.id')
        ->where('em.status', 'Active')->select(['users.id', 'users.first_name'])->get();
        return view('report.sale_report', compact('transactions', 'employees', 'departments'));

    }

    public function filter(Request $request)
    {
        $start_date = $request->get('start');
        $end_date = $request->get('end') && $request->get('end') != 'undefined' ? $request->get('end') : $request->get('start');
        $items = $request->get('items') ?? 25;
        $department = $request->get('department');
        $employee = $request->get('employee');
        $term = $request->get('term');
        $transactions = Transactions::orderBy('transactions.id', 'DESC')
        ->leftjoin('bookings as bk', 'transactions.id', '=', 'bk.transaction_id')
        ->leftjoin('contacts as bc', 'bk.contact_id', '=', 'bc.id')
        ->leftjoin('contacts as co', 'transactions.contact_id', '=', 'co.id')
        ->leftjoin('users as user', 'transactions.created_by', '=', 'user.id')
        ->whereIn('transactions.type', ['order', 'booking', 'checkin', 'checkout'])
        ->select([
            'transactions.id',
            'transactions.updated_at',
            'transactions.invoice_no',
            'transactions.department_id',
            'transactions.created_by',
            'user.first_name as seller',
            'co.first_name as customer',
            'bc.first_name as bk_customer',
            'bk.ref_no',
            'transactions.type',
            'transactions.final_total',
            'transactions.payment_status',
        ])->groupBy('transactions.id');
        
        if(!empty($start_date) && !empty($end_date))
        {
            $transactions->whereDate('transactions.updated_at', '>=', $start_date)->whereDate('transactions.updated_at', '<=', $end_date);
        }
        if(!empty($department))
        {
            $transactions->where('transactions.department_id',  $department);
        }
        if(!empty($employee))
        {
            $transactions->where('transactions.created_by', $employee);
        }

        if(!empty($term))
        {
            $transactions->where(function($q) use($term){
                $q->where('transactions.invoice_no', 'like', '%'.$term.'%');
                $q->orWhere('user.first_name', 'like', '%'.$term.'%');
                $q->orWhere('co.first_name', 'like', '%'.$term.'%');
                $q->orWhere('bc.first_name', 'like', '%'.$term.'%');
                $q->orWhere('bk.ref_no', 'like', '%'.$term.'%');
                $q->orWhere('bk.payment_status', 'like', '%'.$term.'%');
            });
        }
        $transactions = $transactions->paginate($items);
        $transactions->transform(function($item) {
            $total = $item->final_total;
            $paid_amount = TransactionPayment::where('transaction_id', $item->id)->sum('amount');
            $due_amount = $total - $paid_amount;
            return [
                'id' => $item->id,
                'transaction_date' => date("Y-m-d H:i:s", strtotime($item->updated_at)),
                'incoice_no' => $item->invoice_no.''.$item->ref_no,
                'seller' => $item->seller,
                'customer' => $item->customer .' ' .$item->bk_customer,
                'type' => $item->type,
                'total_qty' => $item->lines_of_sell->sum('quantity'),
                'payment_status' => $item->payment_status,
                'grand_total' =>  $total,
                'status' => $item->status,
                'paid_amount' => $paid_amount,
                'due_amount' => $due_amount,
            ];
        });
        $departments = DepartmentPoss::where('status', "Active")->get();
        $employees =  User::join('employees as em', 'users.staff_id', '=', 'em.id')
        ->where('em.status', 'Active')->get();
        $output['table'] = view('report.partial.sale', compact('transactions', 'employees', 'departments'))->render();
        $output['header'] = view('report.partial.header', compact('transactions'))->render();
        $output['footer'] = view('report.partial.footer', compact('transactions'))->render();
        return $output;
    }

    public function detailReport(Request $request)
    {
        if (!auth()->user()->can('sale-detail-report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $location = $request->get('location');
        $employee = $request->get('employee');
        $product = $request->get('product');
        $transactions = TransactionSellLine::orderBy('transaction_sell_lines.id', 'DESC')
        ->join('transactions', 'transaction_sell_lines.transaction_id', '=', 'transactions.id')
        ->join('products as pr', 'transaction_sell_lines.product_id', '=', 'pr.id')
        ->leftjoin('contacts as co', 'transactions.contact_id', '=', 'co.id')
        ->leftjoin('business_locations as bl', 'transactions.location_id', '=', 'bl.id')
        ->leftjoin('users as user', 'transactions.staff_id', '=', 'user.id')
        ->where('transactions.type', 'order')
        ->select([
            'transactions.id',
            'transactions.updated_at',
            'bl.name as location',
            'user.first_name as employee',
            'co.first_name as customer',
            'transactions.invoice_no',
            'pr.id as product_id',
            'pr.sku_code',
            'pr.name as product_name',
            'transaction_sell_lines.quantity',
            'transaction_sell_lines.unit_price',
            'transaction_sell_lines.discount_amount',
            'transactions.created_by',
        ])->groupBy('transaction_sell_lines.id');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $transactions->whereIn('transactions.created_by', $permitted_users);
        }
        if(!empty($start_date) && !empty($end_date))
        {
            $transactions->whereDate('transactions.updated_at', '>=', $start_date)->whereDate('transactions.updated_at', '<=', $end_date);
        }
        if(!empty($location))
        {
            $transactions->where('transactions.location_id', $location);
        }
        if(!empty($employee))
        {
            $transactions->where('transactions.staff_id',  $employee);
        }
        if(!empty($product))
        {
            $transactions->where('pr.id', $product);
        }
        
        $transactions = $transactions->get();
        $transactions->transform(function($item) {
            $subtotal = ($item->unit_price * $item->quantity) - $item->discount_amount;
            return [
                'id' => $item->id,
                'transaction_date' => date("Y-m-d H:i:s", strtotime($item->updated_at)),
                'location' => $item->location,
                'employee' => $item->employee,
                'customer' => $item->customer,
                'incoice_no' => $item->invoice_no,
                'sku' => $item->sku_code,
                'product_name' => $item->product_name,
                'qty' => $item->quantity,
                'price' => $item->unit_price,
                'discount' => $item->discount_amount,
                'sub_total' => $subtotal,
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($transactions)
            ->editColumn(
                'price',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$price}}">{{$price}}</span>'
            )
            ->editColumn(
                'discount',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$discount}}">{{$discount}}</span>'
            )
            ->editColumn(
                'sub_total',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$sub_total}}">{{$sub_total}}</span>'
            )
            ->rawColumns(['price', 'discount','sub_total'])
            ->make(true);
        }
        $departments = BusinessLocation::get();
        $products = Product::get();
        $employees =  User::join('employees as em', 'users.staff_id', '=', 'em.id')
        ->where('em.status', 'Active')->select(['users.id', 'users.first_name'])->get();
        return view('report.detail_report', compact('transactions', 'employees', 'departments', 'products'));
    }

    public function paymentDetailReport(Request $request)
    {
        if (!auth()->user()->can('payment-detail-report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $method = $request->get('method');
        $salestype = $request->get('salestype');
        $get_totals = $request->get('get_totals');
        
        $transactions = TransactionPayment::orderBy('transaction_payments.id', 'DESC')
        ->join('transactions', 'transaction_payments.transaction_id', '=', 'transactions.id')
        
        ->leftjoin('bookings as bk', 'transactions.id', '=', 'bk.transaction_id')
        ->leftjoin('contacts as bc', 'bk.contact_id', '=', 'bc.id')
        ->leftjoin('contacts as co', 'transactions.contact_id', '=', 'co.id')
        ->leftjoin('business_locations as bl', 'transactions.location_id', '=', 'bl.id')
        ->leftjoin('users as user', 'transactions.created_by', '=', 'user.id')
        ->where('transactions.payment_status', 'paid')
        ->where('transaction_payments.method', '!=', 'credit')
        ->whereIn('transactions.type', ['order', 'checkout', 'checkin', 'booking'])
        ->select([
            'transactions.id',
            'transaction_payments.id as payment_id',
            'transaction_payments.updated_at',
            'bl.name as location',
            'user.first_name as employee',
            'transaction_payments.payment_ref_no',
            'transactions.invoice_no',
            'co.first_name as customer',
            'bc.first_name as bk_customer',
            'transactions.department_id',
            'transactions.type',
            'transaction_payments.method',
            'transaction_payments.amount',
            'transactions.payment_status',
            'transactions.final_total',
            'transactions.created_by',
            'transactions.order_type',
            'user.first_name as seller',
        ])->groupBy('transaction_payments.id');

        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $transactions->whereIn('transactions.created_by', $permitted_users);
        }
        
        if(!empty($start_date) && !empty($end_date))
        {
            $transactions->whereDate('transaction_payments.updated_at', '>=', $start_date)->whereDate('transaction_payments.updated_at', '<=', $end_date);
        }
        if(!empty($method))
        {
            $transactions->where('transaction_payments.method', $method);
        }
        
        // Add sales type filtering
        if(!empty($salestype))
        {
            if($salestype == 'hotelsale') {
                $transactions->whereIn('transactions.type', ['checkout', 'checkin', 'booking']);
            } elseif($salestype == 'restsale') {
                $transactions->where('transactions.type', 'order');
            }
        }
  
        $transactions = $transactions->get();
        
        $totalSales = 0;
        $totalPaid = 0;
        $totalDue = 0;
        $totalRestSales = 0;
        $totalHotelSales = 0;
        
        // Get unique transactions to avoid double counting
        $uniqueTransactions = $transactions->groupBy('id');
        
        foreach($uniqueTransactions as $transactionGroup) {
            $transaction = $transactionGroup->first();
            
            $totalSales += $transaction->final_total;
            
            // Calculate total paid for this transaction
            $paidForTransaction = TransactionPayment::where('transaction_id', $transaction->id)->sum('amount');
            $totalPaid += $paidForTransaction;
            
            // Calculate due for this transaction
            $dueForTransaction = $transaction->final_total - $paidForTransaction;
            $totalDue += $dueForTransaction;

            if($transaction->type == 'order') {
                $totalRestSales += $transaction->final_total;
            }

            if(in_array($transaction->type, ['checkin', 'checkout', 'booking'])) {
                $totalHotelSales += $transaction->final_total;
            }
            
        }
        // Add this block to handle the get_totals request
        if ($get_totals) {
            return response()->json([
                'totals' => [
                    'total_sales' => $totalSales,
                    'totalRestSales' => $totalRestSales,
                    'totalHotelSales' => $totalHotelSales,
                ]
            ]);
        }
        
        // Create summary data for header
        $summaryForHeader = (object)[
            'total_sales' => $totalSales,
            'totalRestSales' => $totalRestSales,
            'totalHotelSales' => $totalHotelSales,
        ];
        
        // Transform transactions for DataTable
        $transactions->transform(function($item) {
            $amount = TransactionPayment::where('id', '<=', $item->payment_id)->where('transaction_id', $item->id)->sum('amount');
            $balance = $item->final_total - $amount;
            return [
                'id' => $item->id,
                'transaction_date' => date("Y-m-d H:i:s", strtotime($item->updated_at)),
                'location' => $item->location,
                'employee' => $item->employee,
                'ref_no' => $item->payment_ref_no,
                'incoice_no' => $item->invoice_no,
                'customer' => $item->customer .' ' .$item->bk_customer,
                'method' => $item->method,
                'amount' => $item->amount,
                'balance' => $balance,
                'payment_status' => $item->payment_status,
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
                'amount',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$amount}}">{{$amount}}</span>'
            )
            ->editColumn(
                'balance',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$balance}}">{{$balance}}</span>'
            )
            ->rawColumns(['amount', 'balance', 'payment_status'])
            ->make(true);
        }
        return view('report.sale_payment_report', compact('transactions', 'summaryForHeader'));
    }

    public function saleCancelReport(Request $request)
    {
        if (!auth()->user()->can('sale-cancel-report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $department = $request->get('department');
        $employee = $request->get('employee');
        $transactions = Transactions::orderBy('transactions.id', 'DESC')
        ->leftjoin('bookings as bk', 'transactions.id', '=', 'bk.transaction_id')
        ->leftjoin('contacts as bc', 'bk.contact_id', '=', 'bc.id')
        ->leftjoin('contacts as co', 'transactions.contact_id', '=', 'co.id')
        ->leftjoin('users as user', 'transactions.created_by', '=', 'user.id')
        ->whereIn('transactions.type', ['order', 'booking', 'checkin', 'checkout'])
        ->where('transactions.status', 'canceled')
        ->select([
            'transactions.id',
            'transactions.updated_at',
            'transactions.invoice_no',
            'transactions.department_id',
            'transactions.created_by',
            'user.first_name as seller',
            'co.first_name as customer',
            'bc.first_name as bk_customer',
            'bk.ref_no',
            'transactions.type',
            'transactions.final_total',
            'transactions.payment_status',
        ])->groupBy('transactions.id');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $transactions->whereIn('transactions.created_by', $permitted_users);
        }
        if(!empty($start_date) && !empty($end_date))
        {
            $transactions->whereDate('transactions.updated_at', '>=' , $start_date)->whereDate('transactions.updated_at', '<=', $end_date);
        }
        $transactions = $transactions->get();
        $transactions->transform(function($item) {
            $total = $item->final_total;
            $paid_amount = TransactionPayment::where('transaction_id', $item->id)->sum('amount');
            $due_amount = $total - $paid_amount;
            $href = '#';
            
            if($item->type == 'order')
            {
                $href = action('SaleController@show', $item->id);
            }
            if($item->type == 'booking')
            {
                $href = action('BookingController@show', $item->id);
            }
            if($item->type == 'checkin')
            {
                $href = action('CheckinController@show', $item->id);
            }
            if($item->type == 'checkout')
            {
                $href = action('CheckoutController@show', $item->id);
            }
            return [
                'id' => $item->id,
                'transaction_date' => date("Y-m-d H:i:s", strtotime($item->updated_at)),
                'invoice_no' => $item->invoice_no,
                'ref_no' => $item->ref_no,
                'href' => $href,
                'seller' => $item->seller,
                'customer' => $item->customer .' ' .$item->bk_customer,
                'type' => $item->type,
                'total_qty' => $item->lines_of_sell->sum('quantity'),
                'payment_status' => $item->payment_status,
                'grand_total' =>  $total,
                'status' => $item->status,
                'paid_amount' => $paid_amount,
                'due_amount' => $due_amount,
            ];
        });
       
        if (request()->ajax()) {
            return Datatables::of($transactions)
           
            ->editColumn(
                'payment_status',
                '<span class="label @payment_status($payment_status)">{{$payment_status}}
                    </span>'
            )
            ->editColumn('invoice_no', function($row) {
                
                return '<a href="'.$row['href'].'" class="text-primary underline font-semibold hover:no-underline">'.$row['invoice_no'].''.$row['ref_no'].'<a>';
            })
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
            ->rawColumns(['payment_status', 'grand_total','paid_amount', 'due_amount', 'invoice_no'])
            ->make(true);
        }
        $departments = DepartmentPoss::where('status', "Active")->get();
        $employees =  User::join('employees as em', 'users.staff_id', '=', 'em.id')
        ->where('em.status', 'Active')->select(['users.id', 'users.first_name'])->get();
        return view('report.sale_cancel_report', compact('employees', 'departments'));

    }
}
