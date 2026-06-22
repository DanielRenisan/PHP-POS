<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Transactions;
use App\Models\BookingRoom;

use DB;
use Yajra\DataTables\Facades\DataTables;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getCalander(Request $request)
    {
        if ($request->ajax()) {
            $data = Booking::join('transactions', 'bookings.transaction_id', '=', 'transactions.id')
            ->leftjoin('booking_rooms', 'bookings.id', '=', 'booking_rooms.booking_id')
            ->whereIn('transactions.type', ['booking', 'checkin', 'checkout'])->select(
                'bookings.id', 
                DB::raw("CONCAT(COALESCE(bookings.ref_no, ''), '-', COALESCE(transactions.type, ''), '(', COALESCE(booking_rooms.room_no, ''),')') as title"),
                'bookings.check_in_at as start', 
                'bookings.check_out_at as end', 
                'transactions.type')->get();

            return response()->json($data);
        }
    }

    public function expense($id)
    {
        $categories = Category::where('parent_id', 0)
        ->pluck('name', 'id');
        $transaction = Transactions::findOrFail($id);
        $booking = Booking::where('transaction_id', $transaction->id)->first();
        $room = BookingRoom::where('transaction_id', $transaction->id)->first();

        return view('expense', compact('transaction', 'booking', 'room','categories'));
    }

    public function postExpense(Request $request)
    {
        $transaction = new Transactions();
        $msg  = 'Created';
    
        $transaction->type = 'expense';
        $transaction->status = 'received';
        $transaction->expense_type = 'Customer';
        
        
        $transaction->category_id = $request->category_id;
        $transaction->sub_category_id = $request->sub_category_id ?? null;
        $transaction->contact_id = $request->contact_id;
        $transaction->room_no = $request->room_no;
        
        $transaction->transaction_date = $request->transaction_date;
        $transaction->details = $request->details;
        $transaction->final_total = $this->num_uf($request->final_total, null);
        $transaction->location_id = $request->location_id ?? null;
        $transaction->created_by = auth()->user()->id;
        $transaction->quantity = $request->quantity;
        $transaction->save();

        $transaction->invoice_no = $request->ref_no ?? $this->generateExpenseRefNo($transaction->id);
        $transaction->save();
        return redirect()->back();
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
    public function generateExpenseRefNo($ref_count)
    {
        $prefix = 'EC';

        $ref_digits =  str_pad($ref_count, 4, 0, STR_PAD_LEFT);

        $ref_number = $prefix . $ref_digits;

        return $ref_number;
    }

    public function getPaymentDues()
    {
        if (request()->ajax()) {

            $query = Booking::join(
                'customers as c',
                'bookings.contact_id',
                '=',
                'c.id'
            )->join(
                'transactions as t',
                'bookings.transaction_id',
                '=',
                't.id'
            )
                        ->leftJoin(
                            'transaction_payments as tp',
                            't.id',
                            '=',
                            'tp.transaction_id'
                        )
                        ->where('t.payment_status', '!=', 'paid');

            //Check for permitted locations of a user
            // $permitted_locations = auth()->user()->permitted_locations();
            // if ($permitted_locations != 'all') {
            //     $query->whereIn('transactions.location_id', $permitted_locations);
            // }

            $dues =  $query->select(
                't.id as id',
                'c.first_name as supplier',
                'final_total',
                DB::raw('SUM(tp.amount) as total_paid')
            )
                        ->groupBy('bookings.contact_id');

            return Datatables::of($dues)
                        ->addColumn('due', function ($row) {
                            $total_paid = !empty($row->total_paid) ? $row->total_paid : 0;
                            $due = $row->final_total - $total_paid;
                            return '<span class="display_currency" data-currency_symbol="true">' .
                            $due . '</span>';
                        })
                        ->removeColumn('id')
                        ->removeColumn('final_total')
                        ->removeColumn('total_paid')
                        ->rawColumns([1])
                        ->make(false);
        }
    }

    public function todayCheckouts()
    {
        if (request()->ajax()) {
            $transactions = Transactions::orderBy('transactions.id', 'DESC')
            ->join('bookings', 'transactions.id', '=', 'bookings.transaction_id')
            ->leftjoin('customers', 'bookings.contact_id', '=', 'customers.id')
            ->whereDate('bookings.check_out_at', date('Y-m-d'))
            ->whereIn('transactions.type', ['checkin', 'booking'])
                ->select(
                    'transactions.id',
                    'transactions.final_total',
                    'bookings.ref_no as ref_no',
                    'customers.first_name as customer',
                    'bookings.check_in_at',
                    'bookings.check_out_at',
                    'transactions.type',
                    'transactions.payment_status',
                );

            return Datatables::of($transactions)
                ->editColumn(
                    'final_total',
                    '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
                )
                ->editColumn('type',
                '<span class="label @resevation_status($type)">{{$type}}
                    </span>'
                    )
                ->addColumn('room', function($row) {
                    $data = [];
                    $rooms = BookingRoom::where('transaction_id', $row->id)->pluck('room_no')->toArray();
                    $result = implode(',',$rooms);
                    return $result;
                })
                ->editColumn(
                    'payment_status',
                    '<span class="label @payment_status($payment_status)">{{$payment_status}}
                        </span>'
                )
                ->rawColumns(['final_total','type','room'])
                ->make(true);
        }
    }
}
