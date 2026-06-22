<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactType;
use App\Models\CustomerGroup;
use App\Models\CustomerType;
use App\Models\EventType;
use App\Models\Transactions;
use App\Models\TransactionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
class ContactController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('contact.view') &&  !auth()->user()->can('contact.create')) {
            abort(403, 'Unauthorized action.');
        }
        $contactType = ContactType::where('status', 'Active')->get();
        $customerType = CustomerType::where('status', 'Active')->get();
        $eventType = EventType::where('status', 'Active')->get();
        $customerGroup = CustomerGroup::where('status', 'Active')->get();


        $customer = Contact::select('contacts.*', 'contacts.id as contact_id', 'contact_types.name as contactname', 'contact_types.code_initial_letter as code_initial_letter', 'customer_types.name as customer_type_name', 'event_types.name as event_type_name','customer_groups.name as customer_grp_name')
            ->leftjoin('contact_types', 'contact_types.id', 'contacts.contact_type_id')
            ->leftjoin('customer_types', 'customer_types.id', 'contacts.customer_type_id')
            ->leftjoin('event_types', 'event_types.id', 'contacts.event_type')
            ->leftjoin('customer_groups', 'customer_groups.id', 'contacts.contact_group')
            ->whereIn('contacts.contact_type_id', [1])
            ->where('contacts.is_default', 0)
            ->get();

        $customer = $customer->transform(function ($item) {
            $item->action = 1;
            if (!empty($item->image)) {
                $image_url = asset(Storage::url(config('constants.contact_img_path') . '/' . $item->image));
            } else {
                $image_url = asset('/img/default.png');
            }
            return [
                'id' => $item->contact_id,
                'code' => $item->code,
                'code_initial_letter' => $item->code_initial_letter,
                'contact_type_id' => $item->contact_type_id,
                'customer_type_id' => $item->customer_type_id,
                'business_name' => $item->business_name,
                'name' => $item->first_name ?? '' .' '. $item->last_name ?? '',
                'first_name' => $item->first_name ? $item->first_name : '',
                'last_name' => $item->last_name ? $item->last_name : '',
                'address_one' => $item->address_one,
                'address_two' => $item->address_two,
                'city' => $item->city,
                'zip_code' => $item->zip_code,
                'state' => $item->state,
                'country' => $item->country,
                'event_type' => $item->event_type,
                'event_date' => $item->event_date,
                'mobile_no' => $item->mobile_no,
                'telephone_no' => $item->telephone_no,
                'email' => $item->email,
                'website' => $item->website,
                'tax_no' => $item->tax_no,
                'contact_group' => $item->contact_group,
                'professional' => $item->professional,
                'nationality_type' => $item->nationality_type,
                'national_id' => $item->national_id,
                'police_info' => $item->police_info,
                'nationality' => $item->nationality,
                'passport_no' => $item->passport_no,
                'purpose_of_visit' => $item->purpose_of_visit,
                'visa_no' => $item->visa_no,
                'payment_settle_days' => $item->payment_settle_days,
                'credit_payment' => $item->credit_payment,
                'open_balance' => $item->open_balance,
                'image' => $image_url,
                'status' => $item->status,
                'event_type_name' => $item->event_type_name,
                'customer_grp_name' => $item->customer_grp_name,
                'action' => 1,
                'show_url' => action('Rest\ContactController@show', $item->id)
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($customer)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->editColumn('status', function ($row) {
                $span = '<span class="btn btn-danger btn-sm" style="width:80px;color:#fff;">InActive</span>';
                if($row['status'] == 'Active')
                {
                    $span = '<span class="btn btn-success btn-sm" style="width:50px;color:#fff;">Active</span>';
                }
                return $span;
            })
            ->editColumn(
                'credit_payment',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$credit_payment}}">{{$credit_payment}}</span>'
            )
            ->editColumn('image', function ($row) {
                return '<img style="border-radius: 5px; object-fit: cover;" class="w-8 h-8" src="'.$row['image'].'">';
            })
            ->rawColumns(['action', 'status', 'credit_payment','image'])
            ->make(true);
        }
        return view('rest.contact.index', compact('contactType', 'customerType', 'eventType', 'customerGroup', 'customer'));
        request()->session()->get('contact_type_id.id');
    }


    public function index_s()
    {
        if (!auth()->user()->can('supplier.view') && !auth()->user()->can('supplier.create')) {
            abort(403, 'Unauthorized action.');
        }

        $contactType_s = ContactType::where('status', 'Active')->get();
        $eventType = EventType::where('status', 'Active')->get();
        $customerGroup = CustomerGroup::where('status', 'Active')->get();


        $supplier = Contact::select('contacts.*','contacts.id as contact_id', 'contact_types.name as contactname', 'contact_types.code_initial_letter as code_initial_letter', 'customer_types.name as customer_type_name', 'event_types.name as event_type_name')
            ->leftjoin('contact_types', 'contact_types.id', 'contacts.contact_type_id')
            ->leftjoin('customer_types', 'customer_types.id', 'contacts.customer_type_id')
            ->leftjoin('event_types', 'event_types.id', 'contacts.event_type')
            ->whereIn('contacts.contact_type_id', [2])
            ->get();
        $supplier = $supplier->transform(function ($item) {
            $item->action = 1;
            if (!empty($item->image)) {
                $image_url = asset(Storage::url(config('constants.contact_img_path') . '/' . $item->image));
            } else {
                $image_url = asset('/img/default.png');
            }
            return [
                'id' => $item->contact_id,
                'code' => $item->code,
                'code_initial_letter' => $item->code_initial_letter,
                'contact_type_id' => 2,
                'customer_type_id' => $item->customer_type_id,
                'business_name' => $item->business_name,
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'name' => $item->first_name ?? '' .' '. $item->last_name ?? '',
                'address_one' => $item->address_one,
                'address_two' => $item->address_two,
                'city' => $item->city,
                'zip_code' => $item->zip_code,
                'state' => $item->state,
                'country' => $item->country,
                'event_type' => $item->event_type,
                'event_date' => $item->event_date,
                'mobile_no' => $item->mobile_no,
                'telephone_no' => $item->telephone_no,
                'email' => $item->email,
                'website' => $item->website,
                'tax_no' => $item->tax_no,
                'contact_group' => $item->contact_group,
                'professional' => $item->professional,
                'nationality_type' => $item->nationality_type,
                'national_id' => $item->national_id,
                'police_info' => $item->police_info,
                'nationality' => $item->nationality,
                'passport_no' => $item->passport_no,
                'purpose_of_visit' => $item->purpose_of_visit,
                'visa_no' => $item->visa_no,
                'payment_settle_days' => $item->payment_settle_days,
                'credit_payment' => $item->credit_payment,
                'open_balance' => $item->open_balance,
                'image' => $image_url,
                'status' => $item->status,
                'action' => 1
            ];
        });

        if (request()->ajax()) {
            return Datatables::of($supplier)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->editColumn('status', function ($row) {
                $span = '<span class="btn btn-danger btn-sm" style="width:80px;color:#fff;">InActive</span>';
                if($row['status'] == 'Active')
                {
                    $span = '<span class="btn btn-success btn-sm" style="width:50px;color:#fff;">Active</span>';
                }
                return $span;
            })
            ->editColumn(
                'open_balance',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$open_balance}}">{{$open_balance}}</span>'
            )
            ->editColumn('image', function ($row) {
                return '<img style="border-radius: 5px; object-fit: cover;" class="w-8 h-8" src="'.$row['image'].'">';
            })
            ->rawColumns(['action', 'status', 'open_balance','image'])
            ->make(true);
        }
        return view('rest.contact.index_s', compact('contactType_s', 'eventType', 'customerGroup', 'supplier'));
    }

    public function create()
    {
        if (!auth()->user()->can('contact.create')) {
            abort(403, 'Unauthorized action.');
        }
        $contactType = ContactType::where('status', 'Active')->get();
        $customerType = CustomerType::where('status', 'Active')->get();
        $eventType = EventType::where('status', 'Active')->get();
        $customerGroup = CustomerGroup::where('status', 'Active')->get();
        $customer = Contact::select('contacts.*', 'contacts.id as contact_id', 'contact_types.name as contactname', 'contact_types.code_initial_letter as code_initial_letter')
        ->leftjoin('contact_types', 'contact_types.id', 'contacts.contact_type_id')->where('contacts.contact_type_id', 1)
            ->where('contacts.is_default', 0)
            ->get();
        return view('rest.contact.create', compact('contactType', 'customerType', 'eventType', 'customerGroup'))
        ->with('customer', json_encode($customer, JSON_NUMERIC_CHECK));
    }

    public function edit($id)
    {
        if (!auth()->user()->can('contact.update')) {
            abort(403, 'Unauthorized action.');
        }
        $contact = Contact::find($id);
        $contactType = ContactType::where('status', 'Active')->get();
        $customerType = CustomerType::where('status', 'Active')->get();
        $eventType = EventType::where('status', 'Active')->get();
        $customerGroup = CustomerGroup::where('status', 'Active')->get();
        $customer = Contact::select('contacts.*', 'contacts.id as contact_id', 'contact_types.name as contactname', 'contact_types.code_initial_letter as code_initial_letter')
        ->leftjoin('contact_types', 'contact_types.id', 'contacts.contact_type_id')->where('contacts.contact_type_id', 1)
            ->where('contacts.is_default', 0)
            ->get();
        return view('rest.contact.edit', compact('contactType', 'customerType', 'eventType', 'customerGroup', 'contact'));
    }

    public function suCreate()
    {
        if (!auth()->user()->can('supplier.create')) {
            abort(403, 'Unauthorized action.');
        }
        $contactType = ContactType::where('status', 'Active')->get();
        $customerType = CustomerType::where('status', 'Active')->get();
        $eventType = EventType::where('status', 'Active')->get();
        $customerGroup = CustomerGroup::where('status', 'Active')->get();
        $customer = Contact::select('contacts.*', 'contacts.id as contact_id', 'contact_types.name as contactname', 'contact_types.code_initial_letter as code_initial_letter')
        ->leftjoin('contact_types', 'contact_types.id', 'contacts.contact_type_id')->where('contacts.contact_type_id', 2)
            ->where('contacts.is_default', 0)
            ->get();
        return view('rest.contact.su_create', compact('contactType', 'customerType', 'eventType', 'customerGroup'))
        ->with('customer', json_encode($customer, JSON_NUMERIC_CHECK));
    }

    public function suEdit($id)
    {
        if (!auth()->user()->can('supplier.update')) {
            abort(403, 'Unauthorized action.');
        }
        $contact = Contact::find($id);
        $contactType = ContactType::where('status', 'Active')->get();
        $customerType = CustomerType::where('status', 'Active')->get();
        $eventType = EventType::where('status', 'Active')->get();
        $customerGroup = CustomerGroup::where('status', 'Active')->get();
        $customer = Contact::select('contacts.*', 'contacts.id as contact_id', 'contact_types.name as contactname', 'contact_types.code_initial_letter as code_initial_letter')
        ->leftjoin('contact_types', 'contact_types.id', 'contacts.contact_type_id')->where('contacts.contact_type_id', 2)
            ->where('contacts.is_default', 0)
            ->get();
        return view('rest.contact.su_edit', compact('contactType', 'customerType', 'eventType', 'customerGroup', 'contact'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('contact.create')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id;

        if (!isset($id)) {

            DB::beginTransaction();

            try {

                // Save data in the first table (Contact)
                $customer = new Contact();
                $customer->code = $request->create_code;
                $customer->contact_type_id = '1';
                $customer->customer_type_id = $request->customer_type_id;
                $customer->business_name = $request->business_name;
                $customer->first_name = $request->first_name;
                $customer->last_name = $request->last_name;
                $customer->address_one = $request->address_one;
                $customer->address_two = $request->address_two;
                $customer->city = $request->city;
                $customer->zip_code = $request->zip_code;
                $customer->state = $request->state;
                $customer->country = $request->country;
                // $customer->event_type = $request->event_type;
                // $customer->event_date = $request->event_date;
                $customer->mobile_no = $request->mobile_no;
                $customer->telephone_no = $request->telephone_no;
                $customer->email = $request->email;
                $customer->website = $request->website;
                $customer->tax_no = $request->tax_no;
                $customer->contact_group = $request->contact_group;
                $customer->professional = $request->professional;
                $customer->nationality_type = $request->nationality_type;
                $customer->national_id = $request->national_id;
                $customer->police_info = $request->police_info;
                $customer->nationality = $request->nationality;
                $customer->passport_no = $request->passport_no;
                $customer->purpose_of_visit = $request->purpose_of_visit;
                $customer->visa_no = $request->visa_no;
                $customer->payment_settle_days = $request->payment_settle_days;
                $customer->credit_payment = $request->credit_payment;
                $customer->open_balance = $request->open_balance;

                if ($request->hasFile('image') && $request->file('image')->isValid()) {
                    $new_file_name = time() . '_' . $request->image->getClientOriginalName();
                    $image_path = config('constants.contact_img_path');
                    $path = $request->image->storeAs($image_path, $new_file_name);
                    if ($path) {
                        $customer->image = $new_file_name;
                    }
                }

                $customer->status = $request->has('status') ? 'Active' : 'Inactive';
                $customer->save();

                // Save data in the second table (customer_grp)
                $customer_grp = new CustomerGroup();
                $customer_grp->name = $request->first_name;
                // ... (other fields for customer_grp)
                $customer_grp->save();

                // Commit the transaction if both saves are successful
                DB::commit();

                return redirect()->route('contact.index')->with('success', 'Data saved successfully in both tables!');
            } catch (\Exception $e) {
                // An error occurred, rollback the transaction
                DB::rollback();

                return redirect()->route('contact.index')->with('error', 'Failed to save data. Please try again.');
            }
        } else {
            if (!auth()->user()->can('contact.update')) {
                abort(403, 'Unauthorized action.');
            }

            $updateCustomer = Contact::find($id);

            $updateCustomer->code = $request->code;
            $updateCustomer->contact_type_id = '1';
            $updateCustomer->customer_type_id = $request->customer_type_id;
            $updateCustomer->business_name = $request->business_name;
            $updateCustomer->first_name = $request->first_name;
            $updateCustomer->last_name = $request->last_name;
            $updateCustomer->address_one = $request->address_one;
            $updateCustomer->address_two = $request->address_two;
            $updateCustomer->city = $request->city;
            $updateCustomer->zip_code = $request->zip_code;
            $updateCustomer->state = $request->state;
            $updateCustomer->country = $request->country;
            // $updateCustomer->event_type = $request->event_type;
            // $updateCustomer->event_date = $request->event_date;
            $updateCustomer->mobile_no = $request->mobile_no;
            $updateCustomer->telephone_no = $request->telephone_no;
            $updateCustomer->email = $request->email;
            $updateCustomer->website = $request->website;
            $updateCustomer->tax_no = $request->tax_no;
            $updateCustomer->contact_group = $request->contact_group;
            $updateCustomer->professional = $request->professional;
            $updateCustomer->nationality_type = $request->nationality_type;
            $updateCustomer->national_id = $request->national_id;
            $updateCustomer->police_info = $request->police_info;
            $updateCustomer->nationality = $request->nationality;
            $updateCustomer->passport_no = $request->passport_no;
            $updateCustomer->purpose_of_visit = $request->purpose_of_visit;
            $updateCustomer->visa_no = $request->visa_no;
            $updateCustomer->payment_settle_days = $request->payment_settle_days;
            $updateCustomer->credit_payment = $request->credit_payment;
            $updateCustomer->open_balance = $request->open_balance;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $new_file_name = time() . '_' . $request->image->getClientOriginalName();
                $image_path = config('constants.contact_img_path');
                $path = $request->image->storeAs($image_path, $new_file_name);
                if ($path) {
                    $updateCustomer->image = $new_file_name;
                }
            }

            $updateCustomer->status = $request->status ? 'Active' : 'Inactive';
            $updateCustomer->save();
            return redirect()->route('contact.index')
                ->with('success', 'contact successfully Updated!!');
        }
    }

    public function store_s(Request $request)
    {
        if (!auth()->user()->can('supplier.create')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id; // id

        if (!isset($id)) {

            $supplier = new Contact();
            $supplier->code = $request->create_code;
            $supplier->contact_type_id = '2';
            $supplier->customer_type_id = $request->customer_type_id;
            $supplier->business_name = $request->business_name;
            $supplier->first_name = $request->first_name;
            $supplier->last_name = $request->last_name;
            $supplier->address_one = $request->address_one;
            $supplier->address_two = $request->address_two;
            $supplier->city = $request->city;
            $supplier->zip_code = $request->zip_code;
            $supplier->state = $request->state;
            $supplier->country = $request->country;
            // $supplier->event_type = $request->event_type;
            // $supplier->event_date = $request->event_date;
            $supplier->mobile_no = $request->mobile_no;
            $supplier->telephone_no = $request->telephone_no;
            $supplier->email = $request->email;
            $supplier->website = $request->website;
            $supplier->tax_no = $request->tax_no;
            $supplier->contact_group = $request->contact_group;
            $supplier->professional = $request->professional;
            $supplier->nationality_type = $request->nationality_type;
            $supplier->national_id = $request->national_id;
            $supplier->police_info = $request->police_info;
            $supplier->nationality = $request->nationality;
            $supplier->passport_no = $request->passport_no;
            $supplier->purpose_of_visit = $request->purpose_of_visit;
            $supplier->visa_no = $request->visa_no;
            $supplier->payment_settle_days = $request->payment_settle_days;
            $supplier->credit_payment = $request->credit_payment;
            $supplier->open_balance = $request->open_balance;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $new_file_name = time() . '_' . $request->image->getClientOriginalName();
                $image_path = config('constants.contact_img_path');
                $path = $request->image->storeAs($image_path, $new_file_name);
                if ($path) {
                    $supplier->image = $new_file_name;
                }
            }

            $supplier->status = $request->has('status') ? 'Active' : 'Inactive';

            $supplier->save();

            return redirect()->route('contact.index_s')
                ->with('success', 'supplier successfully Created!!');
        } else {
            if (!auth()->user()->can('supplier.update')) {
                abort(403, 'Unauthorized action.');
            }

            $supplier = Contact::find($request->id);
            $supplier->code = $request->code;
            $supplier->contact_type_id = '2';
            $supplier->customer_type_id = $request->customer_type_id;
            $supplier->business_name = $request->business_name;
            $supplier->first_name = $request->first_name;
            $supplier->last_name = $request->last_name;
            $supplier->address_one = $request->address_one;
            $supplier->address_two = $request->address_two;
            $supplier->city = $request->city;
            $supplier->zip_code = $request->zip_code;
            $supplier->state = $request->state;
            $supplier->country = $request->country;
            // $supplier->event_type = $request->event_type;
            // $supplier->event_date = $request->event_date;
            $supplier->mobile_no = $request->mobile_no;
            $supplier->telephone_no = $request->telephone_no;
            $supplier->email = $request->email;
            $supplier->website = $request->website;
            $supplier->tax_no = $request->tax_no;
            $supplier->contact_group = $request->contact_group;
            $supplier->professional = $request->professional;
            $supplier->nationality_type = $request->nationality_type;
            $supplier->national_id = $request->national_id;
            $supplier->police_info = $request->police_info;
            $supplier->nationality = $request->nationality;
            $supplier->passport_no = $request->passport_no;
            $supplier->purpose_of_visit = $request->purpose_of_visit;
            $supplier->visa_no = $request->visa_no;
            $supplier->payment_settle_days = $request->payment_settle_days;
            $supplier->credit_payment = $request->credit_payment;
            $supplier->open_balance = $request->open_balance;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $new_file_name = time() . '_' . $request->image->getClientOriginalName();
                $image_path = config('constants.contact_img_path');
                $path = $request->image->storeAs($image_path, $new_file_name);
                if ($path) {
                    $supplier->image = $new_file_name;
                }
            }

            $supplier->status = $request->has('status') ? 'Active' : 'Inactive';
            $supplier->save();
            return redirect()->route('contact.index_s')
                ->with('success', 'supplier successfully Updated!!');
        }
    }



    public function delete(Request $request)
    {
        if (!auth()->user()->can('contact.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $contact =  Contact::whereIn('id', $ids)->delete();;

                $output = [
                    'success' => true,
                    'msg' => __("Deleted Success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }

    public function quickAdd(Request $request)
    {
        if (request()->ajax()) {
            try {
                $customer = new Contact();
                $customer->contact_type_id = '1';
                $customer->first_name = $request->first_name;
                $customer->status = 'Active';  
                $customer->mobile_no = $request->mobile_no;
                $customer->national_id = $request->national_id ?? null;
                if ($request->hasFile('image') && $request->file('image')->isValid()) {
                    $new_file_name = time() . '_' . $request->image->getClientOriginalName();
                    $image_path = config('constants.contact_img_path');
                    $path = $request->image->storeAs($image_path, $new_file_name);
                    if ($path) {
                        $customer->image = $new_file_name;
                    }
                }
                $customer->save();
                $output = [
                    'success' => true,
                    'msg' => __("Addeed"),
                    'data' => $customer
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }

    public function show($id)
    {
        $customer = Contact::select('contacts.*', 'contact_types.name as contactname', 'contact_types.code_initial_letter as code_initial_letter', 'customer_types.name as customer_type_name', 'event_types.name as event_type_name','customer_groups.name as customer_grp_name')
            ->leftjoin('contact_types', 'contact_types.id', 'contacts.contact_type_id')
            ->leftjoin('customer_types', 'customer_types.id', 'contacts.customer_type_id')
            ->leftjoin('event_types', 'event_types.id', 'contacts.event_type')
            ->leftjoin('customer_groups', 'customer_groups.id', 'contacts.contact_group')
            ->where('contacts.id', $id)->first();
        $image_url  = '';   
        if (!empty($customer->image)) {
            $image_url = asset(Storage::url(config('constants.contact_img_path') . '/' . $customer->image));
        } else {
            $image_url = asset('/img/default.png');
        }
        $summary_data = [];
        $total_sale = Transactions::where('type', 'order')->sum('final_total');
        $sales = Transactions::where('contact_id', $id)->where('type', 'order')->sum('final_total');
        $percentage = ($sales/$total_sale) * 100;
        $sale_data = [
            'name' => "Total Orders",
            'price' => 'Rs ' .$sales,
            'percentage' => number_format($percentage, 2). ' %',
            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0">
            <path
                d="M3.79424 12.0291C4.33141 9.34329 4.59999 8.00036 5.48746 7.13543C5.65149 6.97557 5.82894 6.8301 6.01786 6.70061C7.04004 6 8.40956 6 11.1486 6H12.8515C15.5906 6 16.9601 6 17.9823 6.70061C18.1712 6.8301 18.3486 6.97557 18.5127 7.13543C19.4001 8.00036 19.6687 9.34329 20.2059 12.0291C20.9771 15.8851 21.3627 17.8131 20.475 19.1793C20.3143 19.4267 20.1267 19.6555 19.9157 19.8616C18.7501 21 16.7839 21 12.8515 21H11.1486C7.21622 21 5.25004 21 4.08447 19.8616C3.87342 19.6555 3.68582 19.4267 3.5251 19.1793C2.63744 17.8131 3.02304 15.8851 3.79424 12.0291Z"
                stroke="currentColor" stroke-width="1.5" />
            <path opacity="0.5"
                d="M9 6V5C9 3.34315 10.3431 2 12 2C13.6569 2 15 3.34315 15 5V6"
                stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" />
            <path opacity="0.5"
                d="M9.1709 15C9.58273 16.1652 10.694 17 12.0002 17C13.3064 17 14.4177 16.1652 14.8295 15"
                stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" />
        </svg>',
            'date' => "",
        ];
        array_push($summary_data, $sale_data);
        $total_expense = Transactions::where('type', 'expense')->sum('final_total');
        $expense = Transactions::where('contact_id', $id)->where('type', 'expense')->sum('final_total');
        $expense_percentage = $expense > 0 && $total_expense > 0 ? ($expense/$total_expense) * 100 : 0;
        $expense_data = [
            'name' => "Total Expensive",
            'price' => 'Rs ' .$expense,
            'percentage' => number_format($expense_percentage, 2). ' %',
            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0">
            <path
                d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                stroke="currentColor" stroke-width="1.5" />
            <path opacity="0.5" d="M10 16H6" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" />
            <path opacity="0.5" d="M14 16H12.5" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" />
            <path opacity="0.5" d="M2 10L22 10" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" />
        </svg>',
            'date' => "",
        ];
        array_push($summary_data, $expense_data);
        $total_booking = Transactions::where('type', 'booking')->sum('final_total');
        $booking = Transactions::join('bookings as bk', 'transactions.id', '=', 'bk.transaction_id')
        ->where('bk.contact_id', $id)->where('transactions.type', 'booking')->sum('transactions.final_total');
        $booking_percentage = $booking > 0 && $total_booking > 0 ? ($booking/$total_booking) * 100 : 0;
        $booking_data = [
            'name' => "Total Booking",
            'price' => 'Rs ' .$booking,
            'percentage' => number_format($booking_percentage, 2). ' %',
            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0">
            <path
                d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                stroke="currentColor" stroke-width="1.5" />
            <path opacity="0.5" d="M10 16H6" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" />
            <path opacity="0.5" d="M14 16H12.5" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" />
            <path opacity="0.5" d="M2 10L22 10" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" />
        </svg>',
            'date' => "",
        ];
        array_push($summary_data, $booking_data);
        $total_checkin = Transactions::where('type', 'checkin')->sum('final_total');
        $checkin = Transactions::join('bookings as bk', 'transactions.id', '=', 'bk.transaction_id')
        ->where('bk.contact_id', $id)->where('transactions.type', 'checkin')->sum('transactions.final_total');
        $checkin_percentage = $checkin > 0 && $total_checkin > 0 ? ($checkin/$total_checkin) * 100 : 0;
        $checkin_data = [
            'name' => "Total Booking",
            'price' => 'Rs ' .$checkin,
            'percentage' => number_format($checkin_percentage, 2). ' %',
            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0">
            <path
                d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                stroke="currentColor" stroke-width="1.5" />
            <path opacity="0.5" d="M10 16H6" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" />
            <path opacity="0.5" d="M14 16H12.5" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" />
            <path opacity="0.5" d="M2 10L22 10" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" />
        </svg>',
            'date' => "",
        ];
        array_push($summary_data, $checkin_data);

        $all_sales = Transactions::join('contacts as co', 'transactions.contact_id', '=', 'co.id')
        ->where('contact_id', $id)->where('type', 'order')->select([
            'co.first_name as customer',
            'transactions.invoice_no',
            'transactions.payment_status',
            'transactions.final_total',
            'transactions.order_type',
            'transactions.created_at',
        ])
        ->get();
        $all_sales =  $all_sales->transform(function($item) {
            $total_paid = TransactionPayment::where('transaction_id', $item->id)->sum('amount');
            $due = $item->final_total - $total_paid;
            return [
                'id' =>  $item->id,
                'date' => date('Y-m-d', strtotime($item->created_at)),
                'customer' =>  $item->customer,
                'invoice_no' => $item->invoice_no,
                'payment_status' => $item->payment_status,
                'order_type' => $item->order_type,
                'final_total' => $item->final_total,
                'total_paid' => $total_paid,
                'due' => $due,
            ];
        });
        return view('rest.contact.show', compact('customer', 'image_url'))
        ->with('summary_data', json_encode($summary_data, JSON_NUMERIC_CHECK))
        ->with('all_sales', json_encode($all_sales, JSON_NUMERIC_CHECK));
    }
}
