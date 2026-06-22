<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionPayment;
use App\Models\Transactions;
use Datatables;
use DB;
class PaymentController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('payment.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $sells = TransactionPayment::whereIn('transactions.payment_status', ['paid', 'partial'])
            ->leftjoin(
                'transactions',
                'transactions.id',
                '=',
                'transaction_payments.transaction_id'
            )
            ->join(
                    'contacts AS co',
                    'transactions.contact_id',
                    '=',
                    'co.id'
                )
                ->leftjoin(
                    'customer_types AS ct',
                    'co.customer_type_id',
                    '=',
                    'ct.id'
                )
                ->select(
                    'transactions.id',
                    'transaction_payments.payment_ref_no as paymentRef',
                    'transactions.invoice_no as invoiceNo',
                    'transactions.ref_no as refNo',
                    'ct.name as customer_type',
                    'co.name as customer_name',
                    'transactions.final_total',
                    'transaction_payments.method',
                    'transaction_payments.amount',
                    'transaction_payments.paid_on',
                    'transactions.payment_status',
                    'transactions.type',
                )->groupBy('transaction_payments.id');

            if(!empty($method)){
                $sells->where('transaction_payments.method', 'like', '%' . $method .'%');
            }
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $sells->whereBetween(DB::raw('date(transaction_payments.paid_on)'), [$start_date, $end_date]);
            }

            return Datatables::of($sells)
            ->removeColumn('id')
            ->editColumn(
                'final_total',
                '<span class="display_currency final-total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
            )
            ->editColumn(
                'amount',
                '<span class="display_currency final-total" data-currency_symbol="true" data-orig-value="{{$amount}}">{{$amount}}</span>'
            )
            ->editColumn(
                'payment_status',
                '<span class="label @payment_status($payment_status)">{{__(\'lang_v1.\' . $payment_status)}}
                    </span>'
            )
            ->addColumn(
                'status', function($row){
                $status = '';
                if($row->type == 'sell')
                {
                    $status = $row->invoiceNo;
                }
                else
                {
                    $status = $row->refNo;
                }           
                return '<span class="label bg-light-green">'.$status.'
                    </span>';
            })
            ->rawColumns(['final_total', 'action', 'payment_status', 'amount', 'status'])
            ->make(true);
        }

        return view('payment.index');
    }
}
