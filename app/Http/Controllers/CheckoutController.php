<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingRoom;
use App\Models\Booking;
use App\Models\TransactionPayment;
use App\Models\Transactions;
use App\Models\RoomAssign;
use App\Models\Category;

use DB;
use Yajra\DataTables\Facades\DataTables;
class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->dummyPaymentLine = ['method' => '', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '', 'cheque_due_date' => '', 'cheque_issued_date' => '',
        'is_return' => 0, 'transaction_no' => ''];
    }
    public function list()
    {
        if (!auth()->user()->can('checkout.view') && !auth()->user()->can('checkout.create')) {
            abort(403, 'Unauthorized action.');
        }
      
        $transactions = Transactions::orderBy('transactions.id', 'DESC')->join('bookings', 'transactions.id', '=', 'bookings.transaction_id')
        ->leftjoin('contacts', 'bookings.contact_id', '=', 'contacts.id')
        ->join('booking_rooms', 'transactions.id', '=', 'booking_rooms.transaction_id')
        ->where('transactions.type', 'checkout')
            ->select(
                'transactions.id',
                'transactions.final_total',
                'bookings.ref_no as ref_no',
                'booking_rooms.id as room_id',
                'contacts.first_name as customer',
                'bookings.check_in_at',
                'bookings.check_out_at',
                'transactions.status',
                'transactions.payment_status',
                'transactions.created_by',
            )->groupBy('transactions.id');
            $permitted_users = auth()->user()->permitted_users();
            if ($permitted_users != 'all') {
                $transactions->whereIn('transactions.created_by', $permitted_users);
            }
            $transactions = $transactions->get();
            
            $transactions->transform(function($item) {
                $data = [];
                $rooms = BookingRoom::where('transaction_id', $item->id)->pluck('room_type', 'room_no')->toArray();
                foreach($rooms as $k => $v) { $data[] = "$k-$v"; }
                $result = implode(',',$data);
                if($item->status == 'canceled')
                {
                    $status = 'CANCELED';
                }
                else
                {
                    $status = 'CHECKED';
                }
                $room = BookingRoom::where('transaction_id', $item->id)->first();
                return [
                    'id' => $item->id,
                    'booking_id' => $item->booking_id,
                    'ref_no' => $item->ref_no,
                    'room' => $result,
                    'room_no' => $room->room_no,
                    'name' => $item->customer,
                    'check_in' => $item->check_in_at,
                    'check_out' => $item->check_out_at,
                    'total' => $item->final_total,
                    'status' => $status,
                    'new_status' => $item->status,
                    'color' => 'lable @payment_status($item->payment_status)',
                    'payment_status' => $item->payment_status,
                    'action' => 1,
                    'contact_id' => $item->contact_id,
                    'show_url' => action('CheckoutController@show', [$item->id]),
                    'print_url' => action('CheckinController@printInvoice', [$item->id])
                ];
            })->toArray();   
        
        return view('checkout.list')->with('transactions',json_encode($transactions,JSON_NUMERIC_CHECK));
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('checkout.create')) {
            abort(403, 'Unauthorized action.');
        }
        $rooms = BookingRoom::join('transactions', 'booking_rooms.transaction_id', '=', 'transactions.id')
        ->where('transactions.type', 'checkin')
        ->where('transactions.status','!=', 'canceled')
        ->select(
            'booking_rooms.transaction_id as id',
            DB::raw("CONCAT(COALESCE(booking_rooms.room_no, ''),' ',COALESCE(booking_rooms.room_type, '')) as room")
            
            )
            ->groupBy('booking_rooms.transaction_id')
        ->get()->pluck('room', 'id')->toArray();
        $room_id = $request->get('room_no');  
        $room = BookingRoom::where('transaction_id', $room_id)->get();
        $room_NOS = BookingRoom::where('transaction_id', $room_id)->pluck('room_no')->toArray();
        $room_ids = RoomAssign::whereIn('room_id', $room_NOS)->pluck('id')->toArray();

        $booking = Booking::where('transaction_id',$room_id)->first();
        $transaction = isset($room) ? Transactions::find($room_id) : null;
        $advance = isset($room) ? TransactionPayment::where('transaction_id', $room_id)->sum('amount') : 0;
        $expenses = Transactions::where('type', 'expense')
        ->where('expense_status', 0)
        ->where('expense_type', 'Customer')
        ->whereIn('room_no', $room_NOS)->get();
        $orders = isset($booking) ? Transactions::where('type', 'order')->where('status', 'final')
        ->where('contact_id', $booking->contact_id)->whereIn('room_id', $room_ids)->where('is_include', 1) : [];
        $all_orders = isset($booking) ? Transactions::where('type', 'order')->where('status', 'final')
        ->where('contact_id', $booking->contact_id)->whereIn('room_id', $room_ids) : [];
        $payment_lines[] = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        $categories = Category::where('parent_id', 0)
            ->pluck('name', 'id');
        $sin_room = BookingRoom::where('transaction_id', $room_id)->first();
        return view('checkout.index', compact(
            'rooms', 
            'booking', 
            'room', 
            'advance', 
            'payment_lines', 
            'payment_types',
            'transaction',
            'orders',
            'all_orders',
            'expenses', 'categories', 'sin_room'
        ));
    }

    public function getPaymentRow(Request $request)
    {
        $row_index = $request->input('row_index');
        $removable = true;
        $payment_types = $this->payment_types();

        $payment_line = $this->dummyPaymentLine;

        return view('checkout.partials.payment_row')
            ->with(compact('payment_types', 'row_index', 'removable', 'payment_line'));
    }

    public function payment_types()
    {
        $payment_types = ['cash' => __('Cash'), 'credit' => 'Credit', 'card' => __('Card'), 'cheque' => __('Cheque'), 'bank_transfer' => __('Bank Transfer'), 'other' => __('Other')];
        return $payment_types;
    }

    public function store(Request $request)
    {

        if (!auth()->user()->can('checkout.create')) {
            abort(403, 'Unauthorized action.');
        }
        $msg  = 'Created';
        $transaction = Transactions::find($request->transaction_id);
        $additional_charge = $request->additional_charge ? $this->num_uf($request->additional_charge, null) : 0;
        $transaction->final_total += $additional_charge;

        // $transaction->final_total = $this->num_uf($request->final_total, null);
        $transaction->save();
        

        $booking = Booking::find($request->booking_id);
        $booking->additional_charge = $request->additional_charge;
        $booking->additional_note = $request->additional_note;
        $booking->save();
        
        // Decode the due_orders_data JSON to get array of [order_id => final_total]
        $due_orders_data = $request->due_orders_data ? json_decode($request->due_orders_data, true) : [];
        $dueOrderAmount = !empty($due_orders_data) ? array_sum($due_orders_data) : 0;
        
        $payments = $request->input('payment');
        $payment  = [];
        foreach ($payments as $index => $payment) {

            $paymentAmount = $this->num_uf($payment['amount']);
            
            // Only subtract due order from the first payment, or distribute it proportionally
            $adjustedAmount = $paymentAmount;
            if ($index === 0 && $dueOrderAmount > 0) {
                $adjustedAmount = max(0, $paymentAmount - $dueOrderAmount);
            }

            TransactionPayment::create([
                'transaction_id' => $transaction->id,
                'amount' => $payment['method'] !==  'credit' ? $adjustedAmount : 0,
                'credit_amount' => $payment['method'] ==  'credit' ? $adjustedAmount : null,
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
            ]);
        }

        $due_order_ids = $request->input('due_order_ids') ? explode(',', $request->input('due_order_ids')) : [];
        
        if (!empty($due_orders_data)) {
            foreach ($due_orders_data as $dueOrderId => $finalTotal) {
                TransactionPayment::create([
                    'transaction_id' => $dueOrderId,
                    'amount' => $this->num_uf($finalTotal), 
                    'credit_amount' => null,
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
                ]);

                $dueTransaction = Transactions::find($dueOrderId);
                $dueTransaction->payment_status = 'paid';
                $dueTransaction->hotel_transaction_id = $transaction->id;
                $dueTransaction->save();
            }
        }

        if (!empty($due_order_ids)) {
            $this->updateRoomOrderStatus($due_order_ids);
        }
        $this->updatePaymentStatus($transaction->id, $request->net_payable);
        $roomNO = BookingRoom::where('booking_id', $booking->id)
        ->where('transaction_id' , $transaction->id)->pluck('room_no')->toArray();
        $transaction->type = "checkout";
        $transaction->save();
        $roomNos = BookingRoom::where('transaction_id', $transaction->id)
        ->where('booking_id', $booking->id)->get();
        foreach($roomNos as $no)
        {
            RoomAssign::where('room_id', $no->room_no)
            ->where('room_type', $no->room_type)
            ->update([
                'status' => 0,
                'checkin_status' => 0
            ]);
        }
        Transactions::where('type', 'expense')
        ->where('expense_status', 0)
        ->where('expense_type', 'Customer')
        ->whereIn('room_no', $roomNO)->update([
            'expense_status' => $transaction->id,
            'contact_id' => $booking->contact_id
        ]);
        $receipt = $this->receiptContent($transaction->id);
        $output = ['success' => 1, 'msg' => $msg, 'receipt' => $receipt];
        if($request->quick_access && $request->quick_access == 1)
        {
            return redirect()->back()->with("msg",$msg);
        }
        else
        {
            return $output;
        }
    }

    private function receiptContent($id)
    {
        
        $transaction = Transactions::findOrFail($id);
        $booking = Booking::where('transaction_id', $transaction->id)->first();
        $data = [];
        $rooms = BookingRoom::where('transaction_id', $transaction->id)->pluck('room_type', 'room_no')->toArray();
        foreach($rooms as $k => $v) { $data[] = "$k-$v"; }
        $result = implode(',<br>',$data);
        $room = BookingRoom::where('transaction_id', $transaction->id)->get();
        $room_no = BookingRoom::where('transaction_id', $transaction->id)->pluck('room_no')->toArray();
        $advance = isset($room) ? TransactionPayment::where('transaction_id', $transaction->id)->sum('amount') : 0;
        $output = ['success' => 1, 'receipt' => []];
        $expenses = isset($room) ? Transactions::where('type', 'expense')
        ->where('expense_status', $id)
        ->where('expense_type', 'Customer')
        ->whereIn('room_no', $room_no)->get() : [];
        $output['success'] = 1;
        $output['is_enabled'] = true;
        $output['html_content'] = view('checkout.receipt', compact('transaction','booking','room','advance', 'expenses', 'result'))->render();
        
        return $output;
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

    private function updatePaymentStatus($transaction_id, $final_amount = null)
    {
        $status = $this->calculatePaymentStatus($transaction_id, $final_amount);
        Transactions::where('id', $transaction_id)
            ->update(['payment_status' => $status]);

        return $status;
    }

    private function updateRoomOrderStatus($due_order_ids)
{
    // If empty, do nothing
    if (empty($due_order_ids)) {
        return false;
    }

    // Ensure it's an array
    if (!is_array($due_order_ids)) {
        $due_order_ids = explode(',', $due_order_ids);
    }

    // Update payment status for all due orders
    return Transactions::whereIn('id', $due_order_ids)
        ->update(['payment_status' => 'paid']);
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

    public function show($id)
    {
        if (!auth()->user()->can('checkin.view')) {
            abort(403, 'Unauthorized action.');
        }

        $transaction = Transactions::findOrFail($id);
        $booking = Booking::where('transaction_id', $transaction->id)->first();
        $room = BookingRoom::where('transaction_id', $transaction->id)->get();
        $room_NOS = BookingRoom::where('transaction_id', $transaction->id)->pluck('room_no')->toArray();
        $expenses = isset($room) ? Transactions::where('type', 'expense')
        ->where('expense_status', $id)
        ->where('expense_type', 'Customer')
        ->whereIn('room_no', $room_NOS)->get() : [];
        $advance = isset($room) ? TransactionPayment::where('transaction_id', $transaction->id)->sum('amount') : 0;
        return view('checkout.show', compact('transaction', 'booking', 'room','advance', 'expenses'));
    }

    public function printInvoice($id)
    {
        try {
            
            $transaction = Transactions::findOrFail($id);
            $booking = Booking::where('transaction_id', $transaction->id)->first();
            $room = BookingRoom::where('transaction_id', $transaction->id)->first();
            $room_NOS = BookingRoom::where('transaction_id', $transaction->id)->pluck('room_no')->toArray();
            $advance = isset($room) ? TransactionPayment::where('transaction_id', $transaction->id)->sum('amount') : 0;
            $output = ['success' => 1, 'receipt' => []];
            $output['receipt']['html_content'] = view('checkout.receipt', compact('transaction','booking','room','advance'))->render();
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __('messages.something_went_wrong')
                        ];
        }

        return $output;
    }
}
