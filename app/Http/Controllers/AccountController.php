<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PettyCash;
use App\Models\Transactions;
use DB;
class AccountController extends Controller
{
    public function index()
    {
        return view('account.index');
    }

    public function store(Request $request)
    {
        $cash = new PettyCash();
        $cash->user_id = auth()->user()->id;
        $cash->amount = $request->amount;
        $cash->save();
        return redirect()->back();
    }

    public function cashFlow(Request $request)
    {
        $start_date = $request->get('start_date') ?? date('Y-m-d');
        $end_date = $request->get('end_date') ?? date('Y-m-d');
        $petty_cash = PettyCash::where('user_id', auth()->user()->id)->first();
        $bookings = Transactions::where('transactions.type', 'booking')
        ->join('transaction_payments as tp', 'transactions.id', '=', 'tp.transaction_id')
        ->join('bookings as bg', 'transactions.id', '=', 'bg.transaction_id')
        ->select(
            'transactions.id',
            'transactions.created_at',
            'tp.amount',
            'bg.ref_no',
            'tp.method'
        );
        
        if(!empty($start_date) && !empty($end_date))
        {
            $bookings->whereDate('transactions.created_at', '>=', $start_date)->whereDate('transactions.created_at', '<=', $end_date);
        }
        $bookings = $bookings->get();
        $checkins = Transactions::where('transactions.type', 'checkin')
        ->join('transaction_payments as tp', 'transactions.id', '=', 'tp.transaction_id')
        ->join('bookings as bg', 'transactions.id', '=', 'bg.transaction_id')
        ->select(
            'transactions.id',
            'transactions.created_at',
            'tp.amount',
            'bg.ref_no',
            'tp.method'
        );

        if(!empty($start_date) && !empty($end_date))
        {
            $checkins->whereDate('transactions.created_at', '>=', $start_date)->whereDate('transactions.created_at', '<=', $end_date);
        }
        $checkins = $checkins->get();
        $checkouts = Transactions::where('transactions.type', 'checkout')
        ->join('transaction_payments as tp', 'transactions.id', '=', 'tp.transaction_id')
        ->join('bookings as bg', 'transactions.id', '=', 'bg.transaction_id')
        ->select(
            'transactions.id',
            'transactions.created_at',
            'tp.amount',
            'bg.ref_no',
            'tp.method'
        );
        if(!empty($start_date) && !empty($end_date))
        {
            $checkouts->whereDate('transactions.created_at', '>=', $start_date)->whereDate('transactions.created_at', '<=', $end_date);
        }
        $checkouts = $checkouts->get();

        $purchases = Transactions::where('transactions.type', 'purchase')
        ->leftjoin('transaction_payments as tp', 'transactions.id', '=', 'tp.transaction_id')
        ->select(
            'transactions.id',
            'transactions.created_at',
            'tp.amount',
            'transactions.invoice_no',
            'tp.method'
        );
        if(!empty($start_date) && !empty($end_date))
        {
            $purchases->whereDate('transactions.created_at', '>=', $start_date)->whereDate('transactions.created_at', '<=', $end_date);
        }
        $purchases = $purchases->get();

        $expenses = Transactions::where('transactions.type', 'expense')
        ->join('transaction_payments as tp', 'transactions.id', '=', 'tp.transaction_id')
        ->select(
            'transactions.id',
            'tp.amount',
            'transactions.invoice_no',
            'tp.method'
        );

        if(!empty($start_date) && !empty($end_date))
        {
            $expenses->whereDate('transactions.created_at', '>=', $start_date)->whereDate('transactions.created_at', '<=', $end_date);
        }
        $expenses = $expenses->get(); 

        $canceled = Transactions::where('transactions.status', 'canceled')
        ->leftjoin('transaction_payments as tp', function($join)
        {
            $join->on('transactions.id', '=', 'tp.transaction_id');
            $join->on('tp.payment_status', '=', DB::raw("'refund'"));
        })
        ->join('bookings as bg', 'transactions.id', '=', 'bg.transaction_id')
        ->select(
            'transactions.created_at',
            'transactions.id',
            'tp.amount',
            'bg.ref_no',
            'tp.method'
        );
        if(!empty($start_date) && !empty($end_date))
        {
            $canceled->whereDate('tp.created_at', '>=', $start_date)->whereDate('tp.created_at', '<=', $end_date);
        }
        $canceled = $canceled->get(); 
        return view('account.cash', compact(
            'petty_cash',
            'bookings',
            'checkouts',
            'checkins',
            'purchases',
            'expenses',
            'canceled'
        ));
    } 
}
