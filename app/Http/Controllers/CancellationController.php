<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Booking;
use App\Models\TransactionPayment;
use App\Models\RoomAssign;
use App\Models\BookingRoom;
class CancellationController extends Controller
{
    public function index($id)
    {
        if (!auth()->user()->can('booking.cancel')) {
            abort(403, 'Unauthorized action.');
        }
        $transaction = Transactions::findOrFail($id);
        $booking = Booking::where('transaction_id', $transaction->id)->first();

        return view('booking.cancel', compact('transaction', 'booking'));
    }

    public function refund(Request $request)
    {
        $transaction = Transactions::find($request->transaction_id);
        $transaction->status = 'canceled';
        $transaction->save();
        $room_no = BookingRoom::where('transaction_id', $transaction->id)->pluck('room_no')->toArray();
        RoomAssign::whereIn('room_id', $room_no)->update([
            'status' => 0,
            'checkin_status' => 0
        ]);
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

    public function block(Request $request)
    {
        $room_id = $request->input('room_no');

        $roomAssign = RoomAssign::where('room_id', $room_id)->first();
        if($roomAssign->status == 3)
        {
            $roomAssign->status = 0;
        }
        else
        {
            $roomAssign->status = 3;
        }
        
        $roomAssign->save();

        return true;
    }
}
