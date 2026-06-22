<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\AttendanceType;
use App\Models\DepartmentPoss;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\Position;
use App\Models\Transactions;
use App\Models\TransactionPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('user.view') && !auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }
        $items = $request->items ?? 25;
        $employee_type = EmployeeType::where('status', 'Active')->get();
        $attedance_time = AttendanceType::where('status', 'Active')->get();
        $position = Role::get();
        $department = DepartmentPoss::where('status', 'Active')->get();
        $employees = Employee::select('employees.*','employees.id as employee_id', 'employee_types.name as employeeName', 'attendance_types.start_time as startTime', 'positions.name as positionName', 'department_posses.name as departmentName')
            ->leftjoin('employee_types', 'employee_types.id', 'employees.employee_type_id')
            ->leftjoin('attendance_types', 'attendance_types.id', 'employees.attendance_time_id')
            ->leftjoin('roles as positions', 'positions.id', 'employees.position_id')
            ->leftjoin('department_posses', 'department_posses.id', 'employees.department_id')->paginate($items);
        $image_url = asset('asset/images/user-profile.jpeg');
        $employees->getCollection()->transform(function ($item) use($image_url){
            $item->action = 1;
            return [
                'id' => $item->employee_id,
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'mobile' => $item->mobile,
                'phone' => $item->phone,
                'email' => $item->email,
                'sex' => $item->sex,
                'married_status' => $item->married_status,
                'address_one' => $item->address_one,
                'address_two' => $item->address_two,
                'city' => $item->city,
                'state' => $item->state,
                'country' => $item->country,
                'zip_code' => $item->zip_code,
                'tin_no' => $item->tin_no,
                'salary' => $item->salary,
                'join_date' => $item->join_date,
                'emergency_contact_no' => $item->emergency_contact_no,
                'emergency_email' => $item->emergency_email,
                'employee_type_id' => $item->employee_type_id,
                'attendance_time_id' => $item->attendance_time_id,
                'position_id' => $item->position_id,
                'department_id' => $item->department_id,
                'username' => $item->username,
                'password' => $item->password,
                'confirm_password' => $item->confirm_password,
                'status' => $item->status,
                'employeeName' => $item->employeeName,
                'startTime' => $item->startTime,
                'positionName' => $item->positionName,
                'departmentName' => $item->departmentName,
                'action' => 1,
                'image_url' => $image_url
            ];
        });
   
        return view('rest.employee.index', compact('employee_type', 'attedance_time', 'position', 'department', 'employees'));
    }

    public function create()
    {
        if (!auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }
        $employee_type = EmployeeType::where('status', 'Active')->get();
        $attedance_time = AttendanceType::where('status', 'Active')->get();
        $position = Role::get();
        $department = DepartmentPoss::where('status', 'Active')->get();

        return view('rest.employee.create', compact('employee_type', 'attedance_time', 'position', 'department'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }

        $Employee = new Employee();
        $Employee->first_name = $request->first_name;
        $Employee->last_name = $request->last_name;
        $Employee->mobile = $request->mobile;
        $Employee->phone = $request->phone;
        $Employee->email = $request->email;
        $Employee->sex = $request->sex;
        $Employee->married_status = $request->married_status;
        $Employee->address_one = $request->address_one;
        $Employee->address_two = $request->address_two;
        $Employee->city = $request->city;
        $Employee->state = $request->state;
        $Employee->country = $request->country;
        $Employee->zip_code = $request->zip_code;
        $Employee->tin_no = $request->tin_no;
        $Employee->salary = $request->salary;
        $Employee->join_date = $request->join_date;
        $Employee->emergency_contact_no = $request->emergency_contact_no;
        $Employee->emergency_email = $request->emergency_email;
        $Employee->employee_type_id = $request->employee_type_id;
        $Employee->attendance_time_id = $request->attendance_time_id;
        $Employee->position_id = $request->position_id;
        $Employee->department_id = $request->department_id;
        $Employee->username = $request->username;
        $Employee->password = $request->password;
        $Employee->confirm_password = $request->confirm_password;
        $Employee->status = $request->has('status') ? 'Active' : 'Inactive';
        $Employee->save();
        
        $user_details = $request->only(['first_name', 'last_name', 'username', 'email', 'password']);
        $user_details['password'] = bcrypt($user_details['password']);
        $user = User::create($user_details);
        $user->staff_id = $Employee->id;
        $user->save(); 

        $role_id = $request->input('position_id');
        $role = Role::findOrFail($role_id);
        $user->assignRole($role->name);

        return redirect()->route('employee.index')
            ->with('success', 'customerGroup successfully Created!!');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('user.update')) {
            abort(403, 'Unauthorized action.');
        }

        $employee = Employee::find($id);
        $employee_type = EmployeeType::where('status', 'Active')->get();
        $attedance_time = AttendanceType::where('status', 'Active')->get();
        $position = Role::get();
        $department = DepartmentPoss::where('status', 'Active')->get();

        return view('rest.employee.edit', compact('employee_type', 'attedance_time', 'position', 'department', 'employee'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('user.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id;
        $Employee = Employee::find($id);
        $Employee->first_name = $request->first_name;
        $Employee->last_name = $request->last_name;
        $Employee->mobile = $request->mobile;
        $Employee->phone = $request->phone;
        $Employee->email = $request->email;
        $Employee->sex = $request->sex;
        $Employee->married_status = $request->married_status;
        $Employee->address_one = $request->address_one;
        $Employee->address_two = $request->address_two;
        $Employee->city = $request->city;
        $Employee->state = $request->state;
        $Employee->country = $request->country;
        $Employee->zip_code = $request->zip_code;
        $Employee->tin_no = $request->tin_no;
        $Employee->salary = $request->salary;
        $Employee->join_date = $request->join_date;
        $Employee->emergency_contact_no = $request->emergency_contact_no;
        $Employee->emergency_email = $request->emergency_email;
        $Employee->employee_type_id = $request->employee_type_id;
        $Employee->attendance_time_id = $request->attendance_time_id;
        $Employee->position_id = $request->position_id;
        $Employee->department_id = $request->department_id;
        $Employee->username = $request->username;
        $Employee->password = $request->password;
        $Employee->confirm_password = $request->confirm_password;
        $Employee->status = $request->has('status') ? 'Active' : 'Inactive';
        $Employee->save();
        $user_data = $request->only(['first_name', 'last_name', 'email']);
        if (!empty($request->input('password'))) {
            $user_data['password'] = bcrypt($request->input('password'));
        }
        if (!empty($request->input('username'))) {
            $user_data['username'] = $request->input('username');
        }
        $update_user = User::where('staff_id', $Employee->id)->first();
        $update_user->update($user_data);
        $role_id = $request->input('position_id');
        $user_role = $update_user->roles->first();
        if ($user_role->id != $role_id) {
            $update_user->removeRole($user_role->name);

            $role = Role::findOrFail($role_id);
            $update_user->assignRole($role->name);
        }
        return redirect()->route('employee.index')
            ->with('success', 'customerGroup successfully Updated!!');
    }


    //delete
    public function delete(Request $request)
    {
        if (!auth()->user()->can('user.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $Employee =  Employee::whereIn('id', $ids)->delete();;

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

    public function show($id)
    {
        if (!auth()->user()->can('user.view')) {
            abort(403, 'Unauthorized action.');
        }
        $employee = Employee::find($id);
        return $employee;
    }

    public function view($id)
    {
        if (!auth()->user()->can('user.view')) {
            abort(403, 'Unauthorized action.');
        }
        $employee = Employee::find($id);
        $image_url = asset('img/pro.png');
        $user = User::where('staff_id', $id)->first();

        $summary_data = [];
        $total_sale = Transactions::where('type', 'order')->sum('final_total');
        $sales = Transactions::where('created_by', $user->id)->where('type', 'order')->sum('final_total');
        $percentage = $total_sale > 0 ? ($sales/$total_sale) * 100 : 0;
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
        $expense = Transactions::where('created_by', $user->id)->where('type', 'expense')->sum('final_total');
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
        ->where('transactions.created_by', $user->id)->where('transactions.type', 'booking')->sum('transactions.final_total');
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
        ->where('transactions.created_by', $user->id)->where('transactions.type', 'checkin')->sum('transactions.final_total');
        $checkin_percentage = $checkin > 0 && $total_checkin > 0 ? ($checkin/$total_checkin) * 100 : 0;
        $checkin_data = [
            'name' => "Total Checkin",
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
        return view('rest.employee.show', compact('employee', 'image_url'))
        ->with('summary_data', json_encode($summary_data, JSON_NUMERIC_CHECK))
        ->with('all_sales', json_encode($all_sales, JSON_NUMERIC_CHECK));
    }
}
