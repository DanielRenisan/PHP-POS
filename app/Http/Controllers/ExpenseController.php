<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Category;
use App\Models\TransactionPayment;
use App\Models\BookingRoom;
use App\Models\Booking;
use App\Models\BusinessLocation;
use DB;
use Yajra\DataTables\Facades\DataTables;
class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->dummyPaymentLine = ['method' => 'cash', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '', 'cheque_due_date' => '', 'cheque_issued_date' => '',
        'is_return' => 0, 'transaction_no' => ''];
    }
    public function index(Request $request)
    {
        if (!auth()->user()->can('expense.view') && !auth()->user()->can('expense.create')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        $transactions = Transactions::orderBy('transactions.id', 'DESC')->leftjoin('categories', 'transactions.category_id', '=', 'categories.id')
        ->leftjoin('users', 'transactions.staff_id', '=', 'users.id')
        // ->leftjoin('customers', 'transactions.contact_id', '=', 'customers.id')
        ->where('transactions.expense_type','!=', 'Customer')
        ->where('transactions.type', 'expense')
            ->select(
                'transactions.id',
                'transactions.invoice_no',
                'transactions.transaction_date',
                'transactions.expense_type',
                'transactions.room_no',
                'users.first_name',
                'categories.name as category',
                'transactions.final_total',
                'transactions.payment_status',
                'transactions.created_by',
            );
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $transactions->whereIn('transactions.created_by', $permitted_users);
        }
        if(!empty($start_date) && !empty($end_date))
        {
            $transactions->whereDate('transactions.transaction_date', '>=', $start_date)->whereDate('transactions.transaction_date', '<=', $end_date);
        }
        $transactions = $transactions->get();   
        $transactions = $transactions->transform(function($item) {
            $total_paid =  TransactionPayment::where('transaction_id',$item->id)->sum('amount');
            $due = $item->final_total - $total_paid;
            $expense_for = '';
            if($item->expense_type == 'Employee')
            {
                $expense_for = $item->first_name.' (Employee)';
            }
            if($item->expense_type == 'Room')
            {
                $expense_for = $item->room_no.' (Room No)';
            }
            if($item->expense_type == 'Customer')
            {
                $expense_for = $item->room_no.' (Customer)';
            }
            return [
                'id' => $item->id,
                'ref_no' => $item->invoice_no,
                'expense_type' => $item->expense_type,
                'expense_for' => $expense_for,
                'category' => $item->category,
                'transaction_date' => date("Y-m-d", strtotime($item->transaction_date)),
                'final_total' => $item->final_total,
                'amount_paid' => $total_paid,
                'due' => $due,
                'payment_status' => $item->payment_status,
                'action' => 1,
                'edit_url' => action('ExpenseController@edit', [$item->id])
            ];    
        });
        if (request()->ajax()) {
            return Datatables::of($transactions)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->editColumn(
                'payment_status',
                '<span class="label @payment_status($payment_status)">{{$payment_status}}
                    </span>'
            )
            ->editColumn(
                'final_total',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
            )
            ->editColumn(
                'amount_paid',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$amount_paid}}">{{$amount_paid}}</span>'
            )
            ->editColumn(
                'due',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$due}}">{{$due}}</span>'
            )
            ->rawColumns(['action', 'payment_status', 'final_total', 'amount_paid','due'])
            ->make(true);
        }
        return view('expense.index', compact('transactions'));

    }

    public function getSummaryData(Request $request)
    {
        if (!auth()->user()->can('expense.view') && !auth()->user()->can('expense.create')) {
            abort(403, 'Unauthorized action.');
        }
        
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
        
        $transactions = Transactions::orderBy('transactions.id', 'DESC')
            ->leftjoin('categories', 'transactions.category_id', '=', 'categories.id')
            ->leftjoin('users', 'transactions.staff_id', '=', 'users.id')
            ->where('transactions.expense_type','!=', 'Customer')
            ->where('transactions.type', 'expense')
            ->select(
                'transactions.id',
                'transactions.final_total'
            );
        
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $transactions->whereIn('transactions.created_by', $permitted_users);
        }
        
        if(!empty($start_date) && !empty($end_date)) {
            $transactions->whereDate('transactions.transaction_date', '>=', $start_date)
                    ->whereDate('transactions.transaction_date', '<=', $end_date);
        }
        
        $transactions = $transactions->get();
        
        $total_amount = $transactions->sum('final_total');
        $total_paid = 0;
        
        foreach($transactions as $transaction) {
            $paid = TransactionPayment::where('transaction_id', $transaction->id)->sum('amount');
            $total_paid += $paid;
        }
        
        $total_due = $total_amount - $total_paid;
        
        return response()->json([
            'total_hand' => $total_amount,
            'total_sale' => $total_paid,
            'total_remain' => $total_due
        ]);
    }

    public function create()
    {
        if (!auth()->user()->can('expense.create')) {
            abort(403, 'Unauthorized action.');
        }
        $categories = Category::where('parent_id', 0)
                                ->pluck('name', 'id');
        $payment_line = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        $rooms = BookingRoom::join('transactions', 'booking_rooms.transaction_id', '=', 'transactions.id')
        ->where('transactions.type', 'checkin')->pluck('booking_rooms.room_no')->toArray();
        $business_locations = BusinessLocation::forDropdown();
        return view('expense.create', compact('categories','payment_line', 'payment_types', 'rooms', 'business_locations'));
    }


    public function getSubCategories(Request $request)
    {
        if (!empty($request->input('cat_id'))) {
            $category_id = $request->input('cat_id');
            $sub_categories = Category::where('parent_id', $category_id)
                        ->select(['name', 'id'])
                        ->get();
            $html = '<option value="">None</option>';
            if (!empty($sub_categories)) {
                foreach ($sub_categories as $sub_category) {
                    $html .= '<option value="' . $sub_category->id .'">' .$sub_category->name . '</option>';
                }
            }
            echo $html;
            exit;
        }
    }

    public function payment_types()
    {
        $payment_types = ['cash' => __('Cash'), 'credit' => 'Credit', 'card' => __('Card'), 'cheque' => __('Cheque'), 'bank_transfer' => __('Bank Transfer'), 'other' => __('Other')];
        return $payment_types;
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

    public function store(Request $request)
    {
        if (!auth()->user()->can('expense.create')) {
            abort(403, 'Unauthorized action.');
        }
        if( !is_null($request->input('transaction_id')) )
        {
            $transaction = Transactions::find($request->input('transaction_id'));
            $msg  = ' Updated';
        }
        else
        {
            $transaction = new Transactions();
            $msg  = 'Created';
        }
        
        $transaction->type = 'expense';
        $transaction->status = 'received';
        $transaction->location_id = $request->location;
        $transaction->expense_type = $request->expense_type;
        $transaction->staff_id = $request->expense_type == 'Employee' ? $request->staff_id : null;
        if($request->expense_type == 'Customer')
        {
            $transaction->room_no = $request->room_id ?? null; 
        }
        if($request->expense_type == 'Room')
        {
            $transaction->room_no =  $request->room_no ?? null;
        }
        
        $transaction->category_id = $request->category_id;
        $transaction->sub_category_id = $request->sub_category_id ?? null;
        $transaction->contact_id = $request->expense_type == 'Customer' ? $request->contact_id : null;
        
        $transaction->transaction_date = $request->transaction_date;
        $transaction->details = $request->details;
        $transaction->final_total = $this->num_uf($request->final_total, null);
        $transaction->location_id = $request->location_id ?? null;
        $transaction->created_by = auth()->user()->id;
        $transaction->save();

        $transaction->invoice_no = $request->ref_no ?? $this->generateRefNo($transaction->id);
        $transaction->save();
        $payments = $request->input('payment');
        $payment  = [];

        // delete existing payments if updating transaction
        TransactionPayment::where('transaction_id', $transaction->id)->delete();

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
                'note' => $payment['note']
            ]);
        }
         //update payment status
         $this->updatePaymentStatus($transaction->id, $transaction->final_total);

        return redirect("expenses")->with("msg",$msg);
    }
    public function edit($id)
    {
        if (!auth()->user()->can('expense.update')) {
            abort(403, 'Unauthorized action.');
        }

        if(!isset($id) || $id == 'undefined')
        {
            return redirect()->back();
        }
        $expense = Transactions::findOrFail($id);
        $categories = Category::where('parent_id', 0)
                                ->pluck('name', 'id');
        $payment_line = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        $rooms = BookingRoom::join('transactions', 'booking_rooms.transaction_id', '=', 'transactions.id')
        ->pluck('booking_rooms.room_no')->toArray();
        $business_locations = BusinessLocation::forDropdown();
        return view('expense.create', compact('categories','payment_line', 'payment_types', 'expense', 'rooms', 'business_locations'));
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
        $prefix = 'EC';

        $ref_digits =  str_pad($ref_count, 4, 0, STR_PAD_LEFT);

        $ref_number = $prefix . $ref_digits;

        return $ref_number;
    }

    public function printInvoice($id)
    {
        try {
            $transaction = Transactions::findOrFail($id);
            $booking = Booking::where('transaction_id', $transaction->id)->first();
            $rooms = BookingRoom::where('transaction_id', $transaction->id)->pluck('room_type', 'room_no')->toArray();
            foreach($rooms as $k => $v) { $data[] = "$k-$v"; }
            $result = implode(',<br>',$data);
            $room_NOS = BookingRoom::where('transaction_id', $id)->pluck('room_no')->toArray();
            $room = BookingRoom::where('transaction_id', $transaction->id)->get();
            $expenses = Transactions::where('type', 'expense')
            ->where('expense_status', 0)
            ->where('expense_type', 'Customer')
            ->whereIn('room_no', $room_NOS)->get();
            $output = ['success' => 1, 'receipt' => []];
            $output['receipt']['html_content'] = view('transaction.receipt', compact('result', 'expenses', 'booking', 'transaction', 'room'))->render();
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __('messages.something_went_wrong')
                        ];
        }

        return $output;
    }
}
