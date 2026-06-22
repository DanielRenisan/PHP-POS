<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Transactions;
use App\Models\TransactionPayment;
use App\Models\BookingRoom;
use App\Models\BookingType;
use App\Models\BookingSource;
use App\Models\Complementary;
use App\Models\RoomAssign;
use App\Models\Room;
use App\Models\Booking;
use App\Models\RoomType;
use DB;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->dummyPaymentLine = ['method' => 'cash', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '', 'cheque_due_date' => '', 'cheque_issued_date' => '',
        'is_return' => 0, 'transaction_no' => ''];
    }
    public function getChk()
    {
        $customers = Customer::forDropdown();
        $payment_line = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        $booking_types = BookingType::pluck('name', 'id');
        $sources = BookingSource::pluck('name', 'id');
        $complementaries = Complementary::pluck('name', 'id');
        $room_types = RoomType::pluck('name')->toArray();

        return view('booking', compact('customers', 'payment_line', 
        'payment_types', 'booking_types','complementaries','sources','room_types'));
    }

    public function payment_types()
    {
        $payment_types = ['cash' => __('Cash'), 'credit' => 'Credit', 'card' => __('Card'), 'cheque' => __('Cheque'), 'bank_transfer' => __('Bank Transfer'), 'other' => __('Other')];

        return $payment_types;
    }

    public function quickChk()
    {
        $customers = Customer::forDropdown();
        $payment_line = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        $booking_types = BookingType::pluck('name', 'id');
        $sources = BookingSource::pluck('name', 'id');
        $complementaries = Complementary::pluck('name', 'id');
        $room_types = RoomType::pluck('name')->toArray();

        return view('checkin', compact('customers', 'payment_line', 
        'payment_types', 'booking_types','complementaries','sources','room_types'));
    }

    public function quickOut(Request $request)
    {
        $room_id = $request->get('room_no');  
        $room = BookingRoom::where('transaction_id', $room_id)->get();
        $room_NOS = BookingRoom::where('transaction_id', $room_id)->pluck('room_no')->toArray();

        $booking = Booking::where('transaction_id',$room_id)->first();
        $transaction = isset($room) ? Transactions::find($room_id) : null;
        $advance = isset($room) ? TransactionPayment::where('transaction_id', $room_id)->sum('amount') : 0;
        $expenses = Transactions::where('type', 'expense')
        ->where('expense_status', 0)
        ->where('expense_type', 'Customer')
        ->whereIn('room_no', $room_NOS)->get();
   
        $payment_lines[] = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        return view('checkout', compact( 
            'booking', 
            'room', 
            'advance', 
            'payment_lines', 
            'payment_types',
            'transaction',
            'expenses'
        ));
    }

    public function editBook(Request $request, $id)
    {
        $transaction = Transactions::findOrFail($id);
        $booking = Booking::where('transaction_id', $transaction->id)->first();
        $room = BookingRoom::where('transaction_id', $transaction->id)->get();
        $customers = Customer::forDropdown();
        $payment_line = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        $booking_types = BookingType::pluck('name', 'id');
        $sources = BookingSource::pluck('name', 'id');
        $complementaries = Complementary::pluck('name', 'id');
        
        $room_types = RoomType::pluck('name')->toArray();        
        return view('edit', compact('transaction', 'booking', 
        'customers', 'room_types',
        'payment_line', 
        'payment_types', 
        'booking_types',
        'complementaries',
        'sources', 'room'));
    }
}
