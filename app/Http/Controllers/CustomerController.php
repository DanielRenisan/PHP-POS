<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Transactions;
use App\Models\TransactionPayment;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use DB;
class CustomerController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('customer.view') && !auth()->user()->can('customer.create')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            
            $customers = Customer::select(
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'contact_no',
                    'police_info',
                    'nationality',
                    'docs'
                );

            return Datatables::of($customers)
                ->addColumn(
                    'action',
                    '@can("customer.update")
                    <a href="{{action(\'CustomerController@edit\', [$id])}}" class="btn btn-info btn-sm"  ><i class="fa fa-edit" aria-hidden="true"></i></a>
                    @endcan
                    @can("customer.delete")
                    <a href="{{action(\'CustomerController@destroy\', [$id])}}" class="btn btn-danger btn-sm delete-customer"><i class="fa fa-trash"></i></a>
                    @endcan
                    @can("customer.view")
                    <a href="{{action(\'CustomerController@show\', [$id])}}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                    @endcan
                    @if(isset($docs))
                    <a href="{{action(\'CustomerController@download\', [$id])}}" class="btn btn-primary btn-sm"><i class="fa fa-download"></i></a>
                    @endif'
                )
                 ->addColumn('balance', function ($row) {
                    $transaction_ids = Booking::where('contact_id', $row->id)->pluck('transaction_id')->toArray();
                    $final_total = Transactions::whereIn('id', $transaction_ids)->sum('final_total');
                    $paid_amount = TransactionPayment::whereIn('transaction_id', $transaction_ids)->sum('amount');
                    $balance = $final_total - $paid_amount;
                    return '<span class="display_currency payment_due" data-currency_symbol="true" data-orig-value="' . $balance . '">' . $balance . '</span>';
                 })
                
                ->rawColumns(['balance', 'action'])
                ->make(true);
        }
        return view('customer.index');
    }

    public function create()
    {
        if (!auth()->user()->can('customer.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('customer.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('customer.create')) {
            abort(403, 'Unauthorized action.');
        }
        if( !is_null($request->input('customer_id')) )
        {
            $customer = Customer::find($request->input('customer_id'));
            $msg  = ' Updated';
        }
        else
        {
            $rules = [
                "email" => "required|email|unique:customers,email",
            ];
            $request->validate($rules);
            $customer = new Customer();
            $msg  = 'Created';
        }
        Storage::delete(config('constants.customer_img_path').'/'.$customer->docs);
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($request->image->getSize() <= config('constants.image_size_limit')) {
                $new_file_name = time() . '_' . $request->image->getClientOriginalName();
                $image_path = config('constants.customer_img_path');
                $path = $request->image->storeAs($image_path, $new_file_name);
                if ($path) {
                    $customer->docs = $new_file_name;
                }
            }
        }
        
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->contact_no = $request->contact_no;
        $customer->dob = $request->dob;
        $customer->profession = $request->profession;
        $customer->nationality = $request->nationality;
        $customer->nic = $request->nationality == 1 ? $request->nic : null;
        $customer->address = $request->address;
        $customer->nationality_country = $request->nationality == 2 ? $request->nationality_country : null;
        $customer->passport_no = $request->nationality == 2 ? $request->passport_no : null;
        $customer->visa_reg_no = $request->nationality == 2 ? $request->visa_reg_no : null;
        $customer->purpose = $request->nationality == 2 ? $request->purpose : null;
        $customer->police_info = $request->police_info ?? null;
        $customer->save();

        return redirect("customers")->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('customer.update')) {
            abort(403, 'Unauthorized action.');
        }
        $customer = Customer::find($id);
        return view('customer.create', compact('customer'));
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('customer.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {

                $Customer = Customer::findOrFail($id);
                $Customer->delete();

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

    public function show($id)
    {
        if (!auth()->user()->can('customer.view')) {
            abort(403, 'Unauthorized action.');
        }
        $customer = Customer::findOrFail($id);
        $transactions = Transactions::orderBy('transactions.id', 'DESC')->join('bookings', 'transactions.id', '=', 'bookings.transaction_id')
                ->leftjoin('customers', 'bookings.contact_id', '=', 'customers.id')
                ->join('booking_rooms', 'transactions.id', '=', 'booking_rooms.transaction_id')
                ->where('bookings.contact_id', $id)
                    ->select(
                        'transactions.id',
                        'transactions.type',
                        'transactions.final_total',
                        'bookings.ref_no as ref_no',
                        'booking_rooms.room_type as type',
                        'booking_rooms.room_no as room',
                        'customers.first_name as customer',
                        'bookings.check_in_at',
                        'bookings.check_out_at',
                        'transactions.status',
                        'transactions.payment_status',
                    );
        if (request()->ajax()) {
            $transactions = Transactions::orderBy('transactions.id', 'DESC')->join('bookings', 'transactions.id', '=', 'bookings.transaction_id')
                ->leftjoin('customers', 'bookings.contact_id', '=', 'customers.id')
                ->join('booking_rooms', 'transactions.id', '=', 'booking_rooms.transaction_id')
                ->where('bookings.contact_id', $id)
                    ->select(
                        'transactions.id',
                        'transactions.type',
                        'transactions.final_total',
                        'bookings.ref_no as ref_no',
                        'booking_rooms.room_type as type',
                        'booking_rooms.room_no as room',
                        'customers.first_name as customer',
                        'bookings.check_in_at',
                        'bookings.check_out_at',
                        'transactions.type',
                        'transactions.payment_status',
                    )->get();
                return Datatables::of($transactions)
                ->editColumn(
                    'final_total',
                    '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
                )
                ->addColumn(
                    'status',
                    '<span class="label @resevation_status($type)">{{$type}}
                        </span>'
                )
                ->addColumn('paid_amount', function($row){
                    $paid_amount = TransactionPayment::where('transaction_id', $row->id)->sum('amount');
                    
                    return '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{ $paid_amount}}">{{ $paid_amount}}</span>';
                })
                ->addColumn('due_amount', function($row){
                    $final_total = Transactions::where('id', $row->id)->sum('final_total');
                    $paid_amount = TransactionPayment::where('transaction_id', $row->id)->sum('amount');
                    $balance = $final_total - $paid_amount;
                   
                    return '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="'.$balance.'">'.$balance.'</span>';
                })
                ->editColumn(
                    'payment_status',
                    '<span class="label @payment_status($payment_status)">{{$payment_status}}
                        </span>'
                )
                ->rawColumns(['final_total','payment_status', 'paid_amount','due_amount','status'])
                ->make(true);        
        } 

        return view('customer.show', compact('customer', 'transactions'));         
    }

    public function download($id)
    {
        $customer = Customer::find($id);
        $path = asset(Storage::url(config('constants.customer_img_path') . '/'.$customer->docs));
        return view('customer.docs', compact('path', 'customer'));
    }
}
