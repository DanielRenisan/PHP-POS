<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\TransactionPayment;
use App\Models\CashRegister;
use App\Models\Table;
use App\Models\RoomAssign;
use App\Models\CashRegisterTransaction;
use Illuminate\Support\Facades\DB;

class TransactionPaymentController extends Controller
{
    public function __construct()
    {
        $this->dummyPaymentLine = ['method' => 'cash', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '', 'cheque_due_date' => '', 'cheque_issued_date' => '',
        'is_return' => 0, 'transaction_no' => ''];
    }

    public function create($id)
    {
        if (!auth()->user()->can('purchase-payment.create')) {
            abort(403, 'Unauthorized action.');
        }
        $payment_line = $this->dummyPaymentLine;
        $payment_types = $this->payment_types();
        $transaction = Transactions::find($id);
        
        return view('add_payment.create', compact('transaction', 'payment_line', 
        'payment_types'));
    }


    public function payment_types()
    {
        $payment_types = ['cash' => __('Cash'), 'credit' => 'Credit', 'card' => __('Card'), 'cheque' => __('Cheque'), 'bank_transfer' => __('Bank Transfer'), 'other' => __('Other')];


        return $payment_types;
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('payment.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $transaction_id = $request->input('transaction_id');
            $transaction = Transactions::findOrFail($transaction_id);
            $payments = $request->input('payment');
            if ($transaction->payment_status != 'paid') {
                if ($transaction->status == 'received' || $transaction->status == 'final') {
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
                    $this->cashRegisterUpdate($transaction, $payments);
                }
            }

            $output = ['success' => true,
                            'msg' => __('Payment Added Success')
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                          'msg' => __('Something Went wrong')
                      ];
        }

        $redirect_url = $request->input('redirect_url', url()->previous());

        return redirect($redirect_url)->with('status', $output);

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

    private function cashRegisterUpdate($transaction, $payments)
    {
        $user_id = auth()->user()->id;
        $register =  CashRegister::where('user_id', $user_id)
                                ->where('status', 'open')
                                ->first();
        if(!isset($register))
        {
            return false;
        }                         
        $payments_formatted = [];
        foreach ($payments as $payment) {
            $payments_formatted[] = new CashRegisterTransaction([
                    'amount' => $this->num_uf($payment['amount']),
                    'pay_method' => $payment['method'],
                    'type' => 'credit',
                    'transaction_type' => 'purchase',
                    'transaction_id' => $transaction->id
                ]);
        }

        if (!empty($payments_formatted)) {
            $register->cash_register_transactions()->saveMany($payments_formatted);
        }

        return true;
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

    public function show($id)
    {
        $transaction = Transactions::
            leftjoin('transaction_sell_lines as tsl', 'transactions.id', '=', 'tsl.transaction_id')
            ->leftjoin('contacts as co', 'transactions.contact_id', '=', 'co.id')
            ->leftjoin('users as user', 'transactions.staff_id', '=', 'user.id')
            ->where('transactions.type', 'order')
            ->where('transactions.id', $id)
            ->select([
                'transactions.id',
                'transactions.invoice_no',
                'transactions.order_type',
                'transactions.updated_at',
                'transactions.table_id',
                'transactions.room_id',
                'co.first_name as customer',
                'co.address_one',
                'co.address_two',
                'co.email',
                'co.mobile_no',
                'co.telephone_no',
                'user.first_name as staff',
                'transactions.final_total',
                'transactions.payment_status',
                'transactions.tax_amount',
                'transactions.discount_amount',
                'transactions.service_charge',
                'transactions.details',
                'transactions.status',
            ])->first();

            $order_type = '';
            if(isset($transaction->room_id) && $transaction->order_type == 'Room Order')
            {
                $room_get =  RoomAssign::find($transaction->room_id);
                $order_type = $transaction->order_type .'(' .$room_get->room_id. ')';
            }
            if(isset($transaction->table_id) && $transaction->order_type == 'Dine in')
            {
                $table =  Table::find($transaction->table_id);
                $order_type = $transaction->order_type .'(' .$table->table_name. ')';
            }
            if($transaction->order_type == 'Take away')
            {
                $order_type = $transaction->order_type;
            }
            $total = $transaction->final_total;
            $paid_amount = TransactionPayment::where('transaction_id', $transaction->id)->sum('amount');
            $due_amount = $total - $paid_amount;
            $sell_lines = [];
            foreach($transaction->sell_lines as $key => $line)
            {
                $product = $line->product;
                $discount = 0;
                if($line->discount_amount > 0)
                {
                    $discount = $line->discount_amount;
                }
                $product_array = [
                    'sr' => $key + 1,
                    'line_id' => $line->id,
                    'id' => $product->id,
                    'description' => $product->name,
                    'unit_price' => $line->unit_price,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => $line->quantity,
                    'dis' => $discount ?? 0,
                    'unit_cost' => $line->unit_price,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'status' => $line->status,
                    'orderNo' => $line->order_no,
                    'line_total' => $line->sub_total,
                ];
                array_push($sell_lines, $product_array);
            }
            $payments = $transaction->payment_lines;

            return view('add_payment.show', compact('transaction','payments', 'order_type', 'total','paid_amount', 'due_amount'));
    }
}
