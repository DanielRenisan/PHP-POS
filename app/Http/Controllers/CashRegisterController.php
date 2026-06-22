<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashRegister;
use DB;
class CashRegisterController extends Controller
{
    public function store(Request $request)
    {
        try {
            $initial_amount = 0;
            if (!empty($request->input('amount'))) {
                $initial_amount = $this->num_uf($request->input('amount'));
            }
            $user_id = auth()->user()->id;

            $register = CashRegister::create([
                        'user_id' => $user_id,
                        'location_id' => $request->location_id,
                        'status' => 'open'
                    ]);
            $register->cash_register_transactions()->create([
                            'amount' => $initial_amount,
                            'pay_method' => 'cash',
                            'type' => 'credit',
                            'transaction_type' => 'initial'
                        ]);                
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
        }

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

    public function getRegisterDetails()
    {

        $query = CashRegister::join(
            'cash_register_transactions as ct',
            'ct.cash_register_id',
            '=',
            'cash_registers.id'
        )
                                ->join(
                                    'users as u',
                                    'u.id',
                                    '=',
                                    'cash_registers.user_id'
                                );
        $user_id = auth()->user()->id;
        $query->where('user_id', $user_id)
            ->where('status', 'open');
                              
        $register_details = $query->select(
            'cash_registers.created_at as open_time',
            DB::raw("SUM(IF(transaction_type='initial', amount, 0)) as cash_in_hand"),
            DB::raw("SUM(IF(transaction_type='sell', amount, IF(transaction_type='refund', -1 * amount, 0))) as total_sale"),
            DB::raw("SUM(IF(transaction_type='purchase', amount, IF(transaction_type='refund', -1 * amount, 0))) as total_purchase"),
            DB::raw("SUM(IF(pay_method='cash', IF(transaction_type='sell', amount, 0), 0)) as total_cash_sale"),
            DB::raw("SUM(IF(pay_method='cash', IF(transaction_type='purchase', amount, 0), 0)) as total_cash_purchase"),
            
            DB::raw("SUM(IF(pay_method='credit', IF(transaction_type='sell', amount, 0), 0)) as total_credit_sale"),
            DB::raw("SUM(IF(pay_method='credit', IF(transaction_type='purchase', amount, 0), 0)) as total_credit_purchase"),

            DB::raw("SUM(IF(pay_method='cheque', IF(transaction_type='sell', amount, 0), 0)) as total_cheque_sale"),
            DB::raw("SUM(IF(pay_method='cheque', IF(transaction_type='purchase', amount, 0), 0)) as total_cheque_purchase"),

            DB::raw("SUM(IF(pay_method='card', IF(transaction_type='sell', amount, 0), 0)) as total_card_sale"),
            DB::raw("SUM(IF(pay_method='card', IF(transaction_type='purchase', amount, 0), 0)) as total_card_purchase"),

            DB::raw("SUM(IF(pay_method='bank_transfer', IF(transaction_type='sell', amount, 0), 0)) as total_bank_transfer_sale"),
            DB::raw("SUM(IF(pay_method='bank_transfer', IF(transaction_type='purchase', amount, 0), 0)) as total_bank_transfer_purchase"),

            DB::raw("SUM(IF(pay_method='other', IF(transaction_type='sell', amount, 0), 0)) as total_other_sale"),
            DB::raw("SUM(IF(pay_method='other', IF(transaction_type='purchase', amount, 0), 0)) as total_other_purchase"),
           
            
            DB::raw("SUM(IF(transaction_type='refund', amount, 0)) as total_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='cash', amount, 0), 0)) as total_cash_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='cheque', amount, 0), 0)) as total_cheque_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='card', amount, 0), 0)) as total_card_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='bank_transfer', amount, 0), 0)) as total_bank_transfer_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='other', amount, 0), 0)) as total_other_refund"),
            DB::raw("SUM(IF(pay_method='cheque', 1, 0)) as total_cheques"),
            DB::raw("SUM(IF(pay_method='card', 1, 0)) as total_card_slips"),
            DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as user_name"),
            'email'
        )->first();
        return $register_details;
    }

    public function postCloseRegister(Request $request)
    {
        try {
            
            $input = $request->only(['closing_amount', 'total_card_slips', 'total_cheques',
                                    'closing_note']);
            $input['closing_amount'] = $this->num_uf($request->grand_total);
            $user_id = auth()->user()->id;
            $input['closed_at'] = \Carbon::now()->format('Y-m-d H:i:s');
            $input['status'] = 'close';
            $register = CashRegister::where('user_id', $user_id)
            ->where('status', 'open')->first();
            CashRegister::where('user_id', $user_id)
                                ->where('status', 'open')
                                ->update($input);
            
            $output = ['success' => 1,
                            'msg' => __('Close_success')
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            $output = ['success' => 0,
                            'msg' => __("Something Went Wrong")
                        ];
        }

        return redirect()->back()->with('status', $output);
    }
}
