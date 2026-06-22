<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Booking;
use App\Models\TransactionPayment;
use App\Models\BookingRoom;
use App\Models\Customer;

use Yajra\DataTables\Facades\DataTables;
use DB;
class ReportController extends Controller
{
    public function bookingReport(Request $request)
    {
        if (request()->ajax()) {
            $transactions = Booking::orderBy('t.id', 'DESC')
            ->join('transactions as t', 't.id', '=', 'bookings.transaction_id')
            ->join('customers as c', 'bookings.contact_id', '=', 'c.id')
                ->select(
                    't.id',
                    't.final_total',
                    'bookings.ref_no as ref_no',
                    'c.first_name as customer',
                    'bookings.contact_id',
                    'bookings.check_in_at',
                    'bookings.check_out_at',
                    't.status',
                    't.type',
                    't.payment_status',
                );

                $customer = $request->customer_id;
                if(isset($customer))
                {
                    $transactions->where('bookings.contact_id', $customer);
                }

                $type = $request->type;
                if(isset($type))
                {
                    $transactions->where('t.type', $type);
                }

            return Datatables::of($transactions)
                ->editColumn(
                    'final_total',
                    '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
                )
                ->editColumn('ref_no', function ($row) {
                    return  '<a href="#" data-href="' . action('CheckoutController@show', [$row->id]) . '"
                                    class="btn-modal" data-container=".view_modal">' . $row->ref_no . '</a>';
                })
                ->editColumn('type', function($row) {
                    $status = '';
                    if($row->type == 'booking')
                    {
                        $status = 'BOOKED';
                    }
                    elseif($row->type == 'checkin')
                    {
                        $status = 'CHECKIN';
                    }
                    elseif($row->type == 'checkout')
                    {
                        $status = 'CHECKOUT';
                    }
                    return $status;
                })
                ->editColumn('status', function($row) {
                    $status = '';
                    if($row->status == 'pending')
                    {
                        $status = '<span class="label bg-light-green">final</span>';
                    }
                    else
                    {
                        $status = '<span class="label bg-red">canceled</span>';
                    }
                    return $status;
                })
                ->addColumn('room', function($row) {
                    $data = [];
                    $rooms = BookingRoom::where('transaction_id', $row->id)->pluck('room_type', 'room_no')->toArray();
                    foreach($rooms as $k => $v) { $data[] = "$k-$v"; }
                    $result = implode(',<br>',$data);
                    return $result;
                })
                ->addColumn('total_paid', function($row) {
                    $total_paid = TransactionPayment::where('transaction_id', $row->id)->sum('amount');
                    return '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="'.$total_paid.'">'.$total_paid.'</span>';
                })
                ->addColumn('due', function($row) {
                    $total_paid = TransactionPayment::where('transaction_id', $row->id)->sum('amount');
                    $due = $row->final_total - $total_paid;
                    return '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="'.$due.'">'.$due.'</span>';
                })
                ->editColumn(
                    'payment_status',
                    '<span class="label @payment_status($payment_status)">{{$payment_status}}
                        </span>'
                )
                ->rawColumns(['final_total','payment_status','action','room','total_paid','due','status','ref_no'])
                ->make(true);
        }
        $customers = Customer::forDropdown();
        return view('report.booking', compact('customers'));
    }

    public function purchaseReport(Request $request)
    {
        if (request()->ajax()) {
            $transactions = Transactions::join('suppliers', 'transactions.contact_id','=', 'suppliers.id')
               ->where('transactions.type', 'purchase')
                ->select(
                    'transactions.id',
                    'suppliers.name as name',
                    'transactions.transaction_date',
                    'transactions.invoice_no',
                    'transactions.final_total',
                    'transactions.payment_status',
                )->groupBy('transactions.id');

            return Datatables::of($transactions)
                ->editColumn(
                    'final_total',
                    '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
                )
                ->editColumn('transaction_date', '{{@format_date($transaction_date)}}')
                ->editColumn(
                    'payment_status',
                    '<span class="label @payment_status($payment_status)">{{__(\'lang_v1.\' . $payment_status)}}
                        </span>'
                )
                ->addColumn('total_paid', function($row) {
                    $total_paid = TransactionPayment::where('transaction_id', $row->id)->sum('amount');
                    return '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="'.$total_paid.'">'.$total_paid.'</span>';
                })->editColumn('invoice_no', function ($row) {
                    return  '<a href="#" data-href="' . action('TransactionController@show', [$row->id]) . '"
                                    class="btn-modal" data-container=".view_modal">' . $row->ref_no . '</a>';
                })
                ->addColumn('due', function($row) {
                    $total_paid = TransactionPayment::where('transaction_id', $row->id)->sum('amount');
                    $due = $row->final_total - $total_paid;
                    return '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="'.$due.'">'.$due.'</span>';
                })
                ->rawColumns(['final_total','payment_status','transaction_date'])
                ->make(true);
        }
        return view('report.purchase_report');

    }
}
