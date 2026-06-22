<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerDetail;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Transactions;
use App\Models\TransactionPayment;

use Yajra\DataTables\Facades\DataTables;
class GuestController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('customer.view') && !auth()->user()->can('customer.create')) {
            abort(403, 'Unauthorized action.');
        }
            
        $customers = Customer::join('customer_details', 'customers.id', '=', 'customer_details.customer_id')
        ->select(
                'customers.id',
                'customers.first_name',
                'customers.last_name',
                'customers.email',
                'customers.contact_no',
                'customers.police_info',
                'customers.nationality',
            )->get();
        $customers = $customers->transform(function($item){
            $nationality = 'Native';
            if($item->nationality == 1)
            {
                $nationality = 'Native';
            }
            else
            {
                $nationality = 'Foreigner';
            }
            $transaction_ids = Booking::where('contact_id', $item->id)->pluck('transaction_id')->toArray();
            $final_total = Transactions::whereIn('id', $transaction_ids)->sum('final_total');
            $paid_amount = TransactionPayment::whereIn('transaction_id', $transaction_ids)->sum('amount');
            $balance = $final_total - $paid_amount;
            return [
                'id' => $item->id,
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'email' => $item->email,
                'phone' => $item->contact_no,
                'nationality' => $nationality,
                'due' => $balance,
                'action' => 1,
            ];
        })->toArray();
        return view('guest.index')
        ->with('customers',json_encode($customers,JSON_NUMERIC_CHECK));
    }
    public function create()
    {
        return view('guest.create');
    }

    public function store(Request $request)
    {
        $rules = [
            "email" => "required|email|unique:customers,email",
        ];
        $request->validate($rules);
        try {
            $customer = new Customer();
            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;
            $customer->email = $request->email;
            $customer->contact_no = $request->contact_no;
            $customer->dob = $request->dob;
            $customer->profession = $request->profession ?? null;
            $customer->nationality = $request->nationality;
            $customer->nic = $request->nationality == 1 ? $request->nic : null;
            $customer->address = $request->address;
            $customer->nationality_country = $request->nationality == 2 ? $request->nationality_country : null;
            $customer->passport_no = $request->nationality == 2 ? $request->passport_no : null;
            $customer->visa_reg_no = $request->nationality == 2 ? $request->visa_reg_no : null;
            $customer->purpose = $request->nationality == 2 ? $request->purpose : null;
            $customer->save();

            $details = new CustomerDetail();
            $details->customer_id = $customer->id;
            $details->father_name = $request->father_name;
            $details->gender = $request->gender ?? 0;
            $details->occupation = $request->occupation;
            $details->anniversary = $request->anniversary && $request->anniversary != 'undefined' ? $request->anniversary : null;
            $details->is_vip = $request->is_vip ?? 0;
            $details->contact_type = $request->contact_type;
            $details->country = $request->country;
            $details->state = $request->state;
            $details->city = $request->city;
            $details->zip_code = $request->zip_code;
            $details->save();
            $output = ['success' => true,
                        'data' => $customer,
                        'msg' => __("Added Success")
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' =>__("messages.something_went_wrong")
                        ];
        }

        return $output;
    }

}
