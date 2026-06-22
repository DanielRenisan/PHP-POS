<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Transactions;
use App\Models\TransactionPayment;
use App\Models\BookingRoom;
use App\Models\BookingType;
use App\Models\BookingSource;
use App\Models\Complementary;
use App\Models\RoomAssign;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Category;
use App\Models\RoomType;
use DB;
use Yajra\DataTables\Facades\DataTables;
class CheckinController extends Controller
{
    public function __construct()
    {
        $this->dummyPaymentLine = ['method' => 'cash', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '', 'cheque_due_date' => '', 'cheque_issued_date' => '',
        'is_return' => 0, 'transaction_no' => ''];
    }
    public function index()
    {
        if (!auth()->user()->can('checkin.view') && !auth()->user()->can('checkin.create')) {
            abort(403, 'Unauthorized action.');
        }
        
        $transactions = Transactions::orderBy('transactions.id', 'DESC')->join('bookings', 'transactions.id', '=', 'bookings.transaction_id')
        ->leftjoin('contacts', 'bookings.contact_id', '=', 'contacts.id')
        ->join('booking_rooms', 'transactions.id', '=', 'booking_rooms.transaction_id')
        ->where('transactions.type', 'checkin')
        ->where('transactions.status','!=', 'canceled')
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
                'bookings.contact_id',
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
                    'edit_url' => action('CheckinController@edit', [$item->id]),
                    'checkin_url' => action('CheckoutController@index', ["room_no" => $item->id]),
                    'show_url' => action('CheckinController@show', [$item->id]),
                    'ex_url' => action('BookingController@viewExchange', [$item->id]),
                    'cancel_url' => action('CancellationController@index', [$item->id]),
                    'print_url' => action('CheckinController@printInvoice', [$item->id])
                ];
            })->toArray();
        $categories = Category::where('parent_id', 0)
            ->pluck('name', 'id');
        return view('checkin.index', compact('categories'))
        ->with('transactions', json_encode($transactions, JSON_NUMERIC_CHECK));
    }

    public function create()
    {
        if (!auth()->user()->can('checkin.create')) {
            abort(403, 'Unauthorized action.');
        }
        $customers = Contact::forCustomerDropdown();
        $payment_line = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        $booking_types = BookingType::pluck('name', 'id');
        $sources = BookingSource::pluck('name', 'id');
        $complementaries = Complementary::pluck('name', 'id');
        $room_types = RoomType::pluck('name')->toArray();
        return view('checkin.create', compact(
            'customers', 'payment_line', 'payment_types', 
            'booking_types','complementaries','sources',
            'room_types'
        ));
    }

    public function payment_types()
    {
        $payment_types = ['cash' => __('Cash'), 'credit' => 'Credit', 'card' => __('Card'), 'cheque' => __('Cheque'), 'bank_transfer' => __('Bank Transfer'), 'other' => __('Other')];


        return $payment_types;
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('checkin.create')) {
            abort(403, 'Unauthorized action.');
        }
        $this->validate($request, [
            'check_in_at' => 'required|before:check_out_at',
        ]); 
        $transaction = new Transactions();
        $msg  = 'Created';
        $transaction->type = 'checkin';
        $transaction->status = 'pending';
        $transaction->final_total = $this->num_uf($request->final_total, null);
        $transaction->created_by = auth()->user()->id;
        $transaction->save();

        $booking = new Booking();
        $booking->transaction_id = $transaction->id;
        $booking->check_in_at = $request->check_in_at;
        $booking->check_out_at = $request->check_out_at;
        $booking->arival_from = $request->arival_from;
        $booking->booking_type_id = $request->booking_type_id;
        $booking->booking_source_id = $request->booking_source_id;
        $booking->purpose = $request->purpose;
        $booking->remarks = $request->remarks;
        $booking->contact_id = $request->contact_id;
        $booking->discount_type = $request->discount_type;
        $booking->discount_amount = $request->discount_amount ?? 0;
        $booking->save();

        $booking->ref_no = $request->ref_no ?? $this->generateRefNo($booking->id);
        $booking->save();

        $room_details = $request->room_detail;

        if($room_details != [])
        {
            foreach ($room_details as $key => $room_detail) 
            {
                $room = new BookingRoom();
                $room->transaction_id = $transaction->id;
                $room->booking_id = $booking->id;
                $room->room_type = $room_detail['room_type'];
                $room->room_no = $room_detail['room_no'];
                $room->adults = $room_detail['adults'] ?? 0;
                $room->children = $room_detail['children'] ?? 0;
                $room->rent = $this->num_uf($room_detail['rent'], null);
                $room->check_in_at = $request->check_in_at;
                $room->check_out_at = $request->check_out_at;
                $room->bed_count = $room_detail['bed_count'] ?? 0;
                $room->bed_amount = $this->num_uf($room_detail['bed_amount'], null) ?? 0;
                $room->person_count = $room_detail['person_count'] ?? 0;
                $room->person_amount = $this->num_uf($room_detail['person_amount'], null) ?? 0;
                $room->childs_count = $room_detail['childs_count'] ?? 0;
                $room->child_amount = $this->num_uf($room_detail['child_amount'], null);
                $room->complementry_id = $room_detail['complementry_id'] ?? 0;
                $room->number = $room_detail['number'] ?? 0;
                $room->save();

                RoomAssign::where('room_id', $room_detail['room_no'])
                ->where('room_type', $room_detail['room_type'])
                ->update([
                    'status' => 2,
                    'checkin_status' => 1

                ]);
            }
        }

        $payments = $request->input('payment');
        $payment  = [];
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
        if($request->quick_access && $request->quick_access == 1)
        {
            return redirect()->back()->with("msg",$msg);
        }
        else
        {
            return redirect("check-in")->with("msg",$msg);
        }
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
    public function generateRefNo($ref_count)
    {
        $prefix = 'CK';

        $ref_digits =  str_pad($ref_count, 6, 0, STR_PAD_LEFT);

        $ref_number = $prefix . $ref_digits;

        return $ref_number;
    }

    public function edit($id)
    {
        if (!auth()->user()->can('checkin.update')) {
            abort(403, 'Unauthorized action.');
        }
        $transaction = Transactions::findOrFail($id);
        $booking = Booking::where('transaction_id', $transaction->id)->first();
        $room = BookingRoom::where('transaction_id', $transaction->id)->get();
        $customers = Contact::forCustomerDropdown();
        $payment_line = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        $booking_types = BookingType::pluck('name', 'id');
        $sources = BookingSource::pluck('name', 'id');
        $complementaries = Complementary::pluck('name', 'id');
        $room_types = RoomType::pluck('name')->toArray();
        return view('checkin.edit', compact('transaction', 'booking', 'room_types',
        'customers', 
        'payment_line', 
        'payment_types', 
        'booking_types',
        'complementaries',
        'sources', 'room', 'room_types'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('checkin.update')) {
            abort(403, 'Unauthorized action.');
        }

        $transaction = Transactions::find($request->transaction_id);
        $msg  = 'Updated';

        $transaction->final_total = $request->final_total;
        $transaction->save();

        $booking = Booking::find($request->booking_id);
        $booking->check_in_at = $request->check_in_at;
        $booking->check_out_at = $request->check_out_at;
        $booking->arival_from = $request->arival_from;
        $booking->booking_type_id = $request->booking_type_id;
        $booking->booking_source_id = $request->booking_source_id;
        $booking->purpose = $request->purpose;
        $booking->remarks = $request->remarks;
        $booking->contact_id = $request->contact_id;
        $booking->save();

        $room_details = $request->room_detail;

        if($room_details != [])
        {
            $roomNos = BookingRoom::where('transaction_id', $transaction->id)
            ->where('booking_id', $booking->id)->get();
            foreach($roomNos as $roomNo)
            {
                RoomAssign::where('room_id', $roomNo->room_no)
                ->where('room_type', $roomNo->room_type)
                ->update([
                    'status' => 0,
                    'checkin_status' => 0
                ]);
            }
            BookingRoom::where('transaction_id', $transaction->id)
            ->where('booking_id', $booking->id)->delete();
            foreach ($room_details as $key => $room_detail) 
            {
                $room = new BookingRoom();
                $room->transaction_id = $transaction->id;
                $room->booking_id = $booking->id;
                $room->room_type = $room_detail['room_type'];
                $room->room_no = $room_detail['room_no'];
                $room->adults = $room_detail['adults'] ?? 0;
                $room->children = $room_detail['children'] ?? 0;
                $room->rent = $this->num_uf($room_detail['rent'], null);
                $room->check_in_at = $request->check_in_at;
                $room->check_out_at = $request->check_out_at;
                $room->bed_count = $room_detail['bed_count'] ?? 0;
                $room->bed_amount = $this->num_uf($room_detail['bed_amount'], null) ?? 0;
                $room->person_count = $room_detail['person_count'] ?? 0;
                $room->person_amount = $this->num_uf($room_detail['person_amount'], null) ?? 0;
                $room->childs_count = $room_detail['childs_count'] ?? 0;
                $room->child_amount = $this->num_uf($room_detail['child_amount'], null);
                $room->complementry_id = $room_detail['complementry_id'] ?? 0;
                $room->number = $room_detail['number'] ?? 0;
                $room->save();

                RoomAssign::where('room_id', $room_detail['room_no'])
                ->where('room_type', $room_detail['room_type'])
                ->update([
                    'status' => 1
                ]);
            }
        }


        return redirect("check-in")->with("msg",$msg);
    }
    public function show($id)
    {
        if (!auth()->user()->can('checkin.view')) {
            abort(403, 'Unauthorized action.');
        }

        $transaction = Transactions::findOrFail($id);
        $booking = Booking::where('transaction_id', $transaction->id)->first();
        $room = BookingRoom::where('transaction_id', $transaction->id)->get();
        $advance = isset($room) ? TransactionPayment::where('transaction_id', $transaction->id)->sum('amount') : 0;
        return view('checkin.show', compact('transaction', 'booking', 'room','advance'));
    }

    public function printInvoice($id)
    {
        try {
            
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
            if($transaction->type == 'checkin')
            {
                $output['receipt']['html_content'] = view('checkin.receipt', compact('transaction','booking','room','advance','result'))->render();
            }
            else
            {
                $output['receipt']['html_content'] = view('checkout.receipt', compact('transaction','booking','room','advance', 'expenses', 'result'))->render();
            }
            
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __('messages.something_went_wrong')
                        ];
        }

        return $output;
    }

    public function expense($id)
    {
        if (!auth()->user()->can('checkin.expense')) {
            abort(403, 'Unauthorized action.');
        }
        $categories = Category::where('parent_id', 0)
        ->pluck('name', 'id');
        $transaction = Transactions::findOrFail($id);
        $booking = Booking::where('transaction_id', $transaction->id)->first();
        $room = BookingRoom::where('transaction_id', $transaction->id)->first();

        return view('checkin.expense', compact('transaction', 'booking', 'room','categories'));
    }

    public function postExpense(Request $request, $id)
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

        $output = ['success' => true];
        return $output;
    }

    public function generateExpenseRefNo($ref_count)
    {
        $prefix = 'EC';

        $ref_digits =  str_pad($ref_count, 4, 0, STR_PAD_LEFT);

        $ref_number = $prefix . $ref_digits;

        return $ref_number;
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('checkin.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $transaction = Transactions::findOrFail($id);
                $roomNos = BookingRoom::where('transaction_id', $transaction->id)
                ->pluck('room_no')->toArray();
                RoomAssign::whereIn('room_id', $roomNos)->update([
                    'status' => 0,
                    'checkin_status' => 0
                ]);
                BookingRoom::where('transaction_id', $transaction->id)->delete();
                Booking::where('transaction_id', $transaction->id)->delete();
                TransactionPayment::where('transaction_id', $transaction->id)->delete();
                $transaction->delete();
                $output = ['success' => true,
                            'msg' => __("Deleted Success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }
}