<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashRegister;
use App\Models\CashRegisterTransaction;
use App\Models\User;
use App\Models\Contact;
use App\Models\Transactions;
use App\Models\TransactionPayment;
use App\Models\BusinessLocation;
use DB;

use Yajra\DataTables\Facades\DataTables;
class RegisterController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('register-report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $employee = $request->get('employee');
        $registers = CashRegister::join(
            'users as u',
            'u.id',
            '=',
            'cash_registers.user_id'
        )
                    ->select(
                        'cash_registers.*',
                        DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, ''), '-', COALESCE(email, '')) as user_name")
                    )->groupBy('cash_registers.id');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $registers->whereIn('cash_registers.user_id', $permitted_users);
        }
        if(!empty($start_date) && !empty($end_date))
        {
            $registers->whereDate('cash_registers.created_at', '>=', $start_date)->whereDate('cash_registers.created_at', '<=', $end_date);
        }
        if(!empty($employee))
        {
            $registers->where('cash_registers.user_id', $employee);
        }
        $registers = $registers->get();
    
        $registers->transform(function($item) {
            $initial_amount = CashRegisterTransaction::where('cash_register_id', $item->id)->where('transaction_type', 'initial')->first();
            $sell_amount = CashRegisterTransaction::where('cash_register_id', $item->id)->where('transaction_type', 'sell')->sum('amount');
            $purchase_amount = CashRegisterTransaction::where('cash_register_id', $item->id)->where('transaction_type', 'purchase')->sum('amount');
            $purchase_cash = CashRegisterTransaction::where('cash_register_id', $item->id)->where('transaction_type', 'purchase')->where('pay_method', 'cash')->sum('amount');
            $total_cash = CashRegisterTransaction::where('cash_register_id', $item->id)
            ->where('transaction_type', 'sell')->where('pay_method', 'cash')
            ->sum('amount');
            $balance = isset($initial_amount) ? ($initial_amount->amount + $total_cash) - $purchase_cash : 0;
            return [
                'id' => $item->id,
                'open_time' => date('Y-m-d h:i A', strtotime($item->created_at)),
                'close_time' => $item->closed_at,
                'user' => $item->user_name,
                'cash_in_hand' => $initial_amount ? $initial_amount->amount : '0.00',
                'total_sell' => $sell_amount,
                'total_purchase' => $purchase_amount,
                'total_cash' => $total_cash - $purchase_cash,
                'card_slips' => $item->total_card_slips,
                'total_cheques' => $item->total_cheques,
                'grand_total' => $balance
            ];
        });

        if (request()->ajax()) {
            return Datatables::of($registers)
            ->editColumn(
                'cash_in_hand',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$cash_in_hand}}">{{$cash_in_hand}}</span>'
            )
            ->editColumn(
                'total_sell',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$total_sell}}">{{$total_sell}}</span>'
            )
            ->editColumn(
                'total_purchase',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$total_purchase}}">{{$total_purchase}}</span>'
            )
            ->editColumn(
                'total_cash',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$total_cash}}">{{$total_cash}}</span>'
            )
            ->editColumn(
                'grand_total',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$grand_total}}">{{$grand_total}}</span>'
            )
            ->rawColumns(['cash_in_hand', 'total_sell','total_purchase','total_cash', 'grand_total'])
            ->make(true);
        }
        $employees =  User::join('employees as em', 'users.staff_id', '=', 'em.id')
        ->where('em.status', 'Active')->select('users.id', 'users.first_name')->get();
        return view('report.register_report', compact('employees', 'registers'));

    }

    public function purchaseReport(Request $request)
    {
        if (!auth()->user()->can('purchase-report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $supplier = $request->get('supplier');
        $transactions = Transactions::orderBy('transactions.id', 'DESC')
        ->leftjoin('contacts as co', 'transactions.contact_id', '=', 'co.id')
        ->where('transactions.type', 'purchase')
        ->select([
            'transactions.id',
            'transactions.updated_at',
            'transactions.ref_no',
            'transactions.created_by',
            'co.first_name as supplier',
            'transactions.status',
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
        if(!empty($supplier))
        {
            $transactions->where('transactions.contact_id', $supplier);
        }

        $transactions = $transactions->get();
        $transactions->transform(function($item) {
            $total = $item->final_total;
            $paid_amount = TransactionPayment::where('transaction_id', $item->id)->sum('amount');
            $due_amount = $total - $paid_amount;
            $status = '<span class="badge badge-success">GRN</span>';
            if($item->status !== 'received')
            {
                $status = '<span class="badge badge-warning">PO</span>';
            }
            return [
                'id' => $item->id,
                'transaction_date' => date("Y-m-d H:i:s", strtotime($item->updated_at)),
                'incoice_no' => $item->ref_no,
                'customer' => $item->supplier,
                'total_qty' => $item->lines_of_purchase->sum('quantity'),
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
            ->editColumn('status',function($row) {
                $status = '<span class="btn-success">GRN</span>';
                if($row['status'] !== 'received')
                {
                    $status = '<span class="btn-warning">PO</span>';
                }
                return $status;
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
            ->rawColumns(['payment_status', 'grand_total','paid_amount', 'due_amount','status'])
            ->make(true);
        }
        $suppliers =  Contact::forSupplierDropdown();
        return view('report.purchase_report', compact('transactions', 'suppliers'));

    }

    public function locationReport(Request $request)
    {
        if (!auth()->user()->can('loaction-report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start') ?? date('Y-m-d');
        $end_date = $request->get('end') && $request->get('end') != 'undefined' ? $request->get('end') : $start_date;
        $location = $request->get('location');

        $sales = TransactionPayment::
        leftjoin('transactions as t', 'transaction_payments.transaction_id', '=', 't.id')
        ->where('t.type', 'order')
        ->where('transaction_payments.method', 'cash')
        ->select('transaction_payments.created_at', 't.location_id', 'transaction_payments.amount', 't.created_at as date')
        ->groupBy('transaction_payments.id');
        $sale_return = TransactionPayment::
        leftjoin('transactions as t', 'transaction_payments.transaction_id', '=', 't.id')
        ->where('t.type', 'sell_return')
        ->where('transaction_payments.method', 'cash')
        ->select('transaction_payments.created_at', 't.location_id', 'transaction_payments.amount', 't.created_at as date')
        ->groupBy('transaction_payments.id');
        $credit = TransactionPayment::
        leftjoin('transactions as t', 'transaction_payments.transaction_id', '=', 't.id')
        ->where('t.type', 'order')
        ->where('transaction_payments.method', 'cash')
        ->select('transaction_payments.created_at', 't.location_id', 'transaction_payments.amount', 't.created_at as date')
        ->groupBy('transaction_payments.id');

        $expense = TransactionPayment::
        leftjoin('transactions as t', 'transaction_payments.transaction_id', '=', 't.id')
        ->where('t.type', 'expense')
        ->where('transaction_payments.method', 'cash')
        ->select('transaction_payments.created_at', 't.location_id', 'transaction_payments.amount', 't.created_at as date')
        ->groupBy('transaction_payments.id');
        $purchase = TransactionPayment::
        leftjoin('transactions as t', 'transaction_payments.transaction_id', '=', 't.id')
        ->where('t.type', 'purchase')
        ->where('transaction_payments.method', 'cash')
        ->select('transaction_payments.created_at', 't.location_id', 'transaction_payments.amount', 't.created_at as date')
        ->groupBy('transaction_payments.id');
        
        if(!empty($start_date) && !empty($end_date))
        {
            $sales->whereDate('t.created_at', '>=', $start_date)->whereDate('t.created_at', '<=', $end_date);
            $sale_return->whereDate('t.created_at', '>=', $start_date)->whereDate('t.created_at', '<=', $end_date);
            $credit->whereDate('transaction_payments.created_at', '>=', $start_date)->whereDate('transaction_payments.created_at', '<=', $end_date);
            $expense->whereDate('t.created_at', '>=', $start_date)->whereDate('t.created_at', '<=', $end_date);
            $purchase->whereDate('t.created_at', '>=', $start_date)->whereDate('t.created_at', '<=', $end_date);
        }

        if(!empty($location))
        {
            $sales->where('t.location_id', $location);
            $sale_return->where('t.location_id', $location);
            $credit->where('t.location_id', $location);
            $expense->where('t.location_id', $location);
            $purchase->where('t.location_id', $location);
        }
        $credit = $credit->get();
        $credit->transform(function($item) {

            if(date('Y-m-d', strtotime($item->created_at)) != date('Y-m-d', strtotime($item->date)))
            {
                return $item;
            }

        });
        $sales = $sales->get();
        $sale_return = $sale_return->get();
        $expense = $expense->get();
        $purchase = $purchase->get();
        $output['total_sale'] = $sales->sum('amount');
        $output['sale_return'] = $sale_return->sum('amount');
        $output['net_sale'] = $output['total_sale'] - $output['sale_return'];
        $output['credit_return'] = $credit->sum('amount');
        $output['total_cash'] = $output['net_sale'] + $output['credit_return'];

        $output['expense'] = $expense->sum('amount');
        $output['purchase'] = $purchase->sum('amount');
        $output['balance'] = $output['total_cash'] - $output['expense'] - $output['purchase'];
        $departments = BusinessLocation::get();
        return view('report.location_report', compact('output', 'departments'));
    }

    public function getTotalPurchase(Request $request)
    {
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            $sup = $request->get('supplier');
            $details = $this->getPurchaseTotals($start_date, $end_date, $sup);
           
            return $details;
        }
    }


    public function getPurchaseTotals($start_date = null, $end_date = null, $sup = null)
    {
        $query = Transactions::where('transactions.type', 'purchase')
            ->where('transactions.status', 'received')
            ->select(
                'transactions.id',
                'final_total',
                DB::raw('(SELECT SUM(tp.amount) FROM transaction_payments as tp WHERE tp.transaction_id = transactions.id) as total_paid'),
                DB::raw('(SELECT SUM(tsl.quantity) FROM transaction_sell_lines as tsl WHERE tsl.transaction_id = transactions.id) as total_quantity'),
              
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
        if(!empty($sup))
        {
            $query->where('transactions.contact_id', $sup);
        }
        $sell_details = $query->get();
        $output['total_sell_inc_tax'] = $sell_details->sum('final_total');
        $output['total_received'] = $sell_details->sum('total_paid');
        $output['invoice_due'] = $sell_details->sum('final_total') - $sell_details->sum('total_paid');

        return $output;
    }
}
