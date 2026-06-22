@extends('layouts.app_rest')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.dataTables.css">
<style>
    .dt-search label {
        display: none;
    }

    /* Assuming the input has a fixed width */
    div.dt-container .dt-search input {
        background-image: url('https://cdn3.iconfinder.com/data/icons/feather-5/24/search-512.png');
        background-size: 18px;
        background-repeat: no-repeat;
        background-position: left 10px center;
        /* box-shadow: 0 0 5px rgba(0, 0, 0, 0.3); */
        padding: 10px 10px 3px 30px !important;
        border-radius: 20px !important;
        height:80%;
    }

    div.dt-container .dt-paging .dt-paging-button.current {
        color: white !important;
    }

    .dt-buttons .buttons-html5,
    .buttons-collection,
    .buttons-print {
        border-radius: 5px !important;
        background-color: transparent !important;
        border: 1px solid skyblue !important;
        background: none !important;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

    .dt-buttons button:hover {
        background-color: skyblue !important;
        color: white !important;
    }

    button.dt-paging-button.current {
        border-radius: 30px !important;
        background-color: skyblue !important;
        border: none !important;
        color: white !important;
    }

    span.dt-paging-button {
        border-radius: 30px !important;
        background-color: skyblue !important;
        border: none !important;
        color: white !important;
    }

    a.dt-paging-button {
        border-radius: 30px !important;
        background-color: transparent !important;
        border: none !important;
        color: black !important;
    }

    .custom-table thead tr {
        /* background-color: rgb(235, 235, 235); */
        /* border: 1px solid lightgray; */
        font-family: Arial, Helvetica, sans-serif;

    }
    #example_wrapper table.dataTable tbody td {
        border: none !important;
        /* background-color:white  !important; */
    }
    .custom-table tbody tr {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    .dt-info {
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
    }
    .total-details .bottom{
        display: flex;
        align-items: center !important;
        margin: auto;
        gap: 10px;
        height: 40px;
        font-weight: bold;
        }

        .total-details .bottom h2{
        margin: 0;
        font-size: 40px;
        }

        .total-details p{
        font-size: 12px;
        }
        div.dt-container .dt-paging .dt-paging-button.last {
            font-size: 24px;
            font-weight:bold;
        }
        div.dt-container .dt-paging .dt-paging-button.next {
            font-size: 24px;
            font-weight:bold;
        }

        div.dt-container .dt-paging .dt-paging-button.previous {
            font-size: 24px;
            font-weight:bold;
        }

        div.dt-container .dt-paging .dt-paging-button.first {
            font-size: 24px;
            font-weight:bold;
        }
        table.dataTable tbody tr:hover {
            background-color: #87ceeb !important;
            color:white;
}
</style>

{{-- body content start--------------------------------- --}}

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Employees</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div x-data="employee">
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]"  style="">
                    <div class="px-5" x:data="employee">
                        <div style="display:flex;justify-content: space-between; align-items: center;">
                            <div class="mb-5 flex items-center gap-2">
                            @can('user.create')
                                <button class="btn btn-success gap-2" @click="openModal = true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-5 w-5">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Add New
                                </button>
                                @endcan
                                @can('user.update')
                                <button class="btn btn-primary gap-2 edit-btn" @click="editItem()" style="display: none;">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </button>
                                @endcan
                                @can('user.view')
                                <button class="btn btn-info gap-2 view-btn" style="display: none;">
                                    <i class="fa fa-edit"></i>
                                    View
                                </button>
                                @endcan
                                @can('user.delete')
                                <button type="button" href="#" class="btn btn-danger delete-button"  style="display: none;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                        <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                        <path
                                            d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                        <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                        <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                        <path opacity="0.5"
                                            d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                    </svg>
                                    Delete
                                </button>
                                @endcan
                            </div>
                        </div>

                        <div x-data="{ iconFile: null }" x-show="openModal" class="mb-5">
                            <!-- modal -->
                            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
                                <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                                    <div x-transition x-transition.duration.300
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full cardView" style="max-width:70%">
                                        <div class="heading">
                                            <h2 class="m-0">Add Employee</h2>
                                        </div>
                                        <div class="p-5">
                                            <form id="signupForm" class="needs-validation" method="POST"
                                                action="{{ route('employee.store') }}">
                                                @csrf
                                                <div class="grid grid-cols-3 gap-4">
                                                    <div>
                                                        <label for="firstName">First Name</label>
                                                        <input id="firstName" type="text" class="form-input" name="first_name"
                                                            required />
                                                    </div>
                                                    <div>
                                                        <label for="lastName">LastName</label>
                                                        <input id="lastName" type="text" class="form-input" name="last_name"
                                                            required />
                                                    </div>
                                                    <div>
                                                        <label for="mobile">Mobile No</label>
                                                        <input type="text" id="mobile" class="form-input" maxlength="10"
                                                            pattern="\d{10}" name="mobile"
                                                            title="Please enter exactly 10 digits" required />
                                                    </div>
                                                    <div>
                                                        <label for="phone">Phone</label>
                                                        <input type="text" id="phone" class="form-input" maxlength="10"
                                                            pattern="\d{10}" name="phone"
                                                            title="Please enter exactly 10 digits" />

                                                    </div>
                                                    <div>
                                                        <label for="email">E-mail</label>
                                                        <input id="email" type="email" class="form-input" name="email" />
                                                    </div>
                                                    <div>
                                                        <label for="sex">Sex</label>
                                                        <input id="sex" type="text" class="form-input" name="sex" />
                                                    </div>
                                                    <div>
                                                        <label for="marriedStatus">Married Status</label>
                                                        <input id="marriedStatus" type="text" class="form-input"
                                                            name="married_status" />
                                                    </div>
                                                    <div>
                                                        <label for="addressOne">Address</label>
                                                        <input id="addressOne" type="text" class="form-input"
                                                            name="address_one" />
                                                    </div>


                                                    <div>
                                                        <label for="addressTwo">Address 2</label>
                                                        <input id="addressTwo" type="text" class="form-input"
                                                            name="address_two" />
                                                    </div>
                                                    <div>
                                                        <label for="city">City</label>
                                                        <input id="city" type="text" class="form-input" name="city" />
                                                    </div>
                                                    <div>
                                                        <label for="state">State</label>
                                                        <input id="state" type="text" class="form-input" name="state" />
                                                    </div>
                                                    <div>
                                                        <label for="country">Country</label>
                                                        <input id="country" type="text" class="form-input" name="country" />
                                                    </div>
                                                    <div>
                                                        <label for="zipCode">Zip Code</label>
                                                        <input id="zipCode" type="text" class="form-input" name="zip_code" />
                                                    </div>

                                                    <div>
                                                        <label for="tinNo">Tin No</label>
                                                        <input id="tinNo" type="text" class="form-input" name="tin_no" />
                                                    </div>
                                                    <div>
                                                        <label for="salary">Salary</label>
                                                        <input id="salary" type="text" class="form-input" name="salary" />
                                                    </div>
                                                    <div>
                                                        <label for="joinDate">Join Date</label>
                                                        <input id="joinDate" type="date" class="form-input" name="join_date" />
                                                    </div>
                                                    <div>
                                                        <label for="emergencyContactNo">Emergency Contact No</label>
                                                        <input id="emergencyContactNo" type="text" class="form-input"
                                                            name="emergency_contact_no" />
                                                    </div>
                                                    <div>
                                                        <label for="emergencyEmail">Emergency Email</label>
                                                        <input id="emergencyEmail" type="text" class="form-input"
                                                            name="emergency_email" />
                                                    </div>


                                                    <div>
                                                        <label for="employeeTypeId">Employee Type </label>
                                                        <select class="form-select text-white-dark" name="employee_type_id"
                                                            id="employeeTypeId">
                                                            <option value="" >Select Type</option>
                                                            @foreach ($employee_type as $et)
                                                            <option name="employee_type_id" class="pro-type"
                                                                value="{{ $et->id }}" {{ $et->
                                                                id == old('employee_type_id') ? 'selected' : '' }}>
                                                                {{ $et->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('employee_type_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="attendanceTimeId">Attendance Time </label>
                                                        <select class="form-select text-white-dark" name="attendance_time_id"
                                                            id="attendanceTimeId">
                                                            <option value="" disabled selected>Select Time</option>
                                                            @foreach ($attedance_time as $et)
                                                            <option name="attendance_time_id" class="pro-type"
                                                                value="{{ $et->id }}" {{ $et->
                                                                id == old('attendance_time_id') ? 'selected' : '' }}>
                                                                {{ $et->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('attendance_time_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>


                                                    <div>
                                                        <label for="positionId">Position </label>
                                                        <select class="form-select text-white-dark" name="position_id"
                                                            id="positionId" required>
                                                            <option value="" disabled selected>Select Position</option>
                                                            @foreach ($position as $et)
                                                            <option name="position_id" class="pro-type" value="{{ $et->id }}" {{
                                                                $et->
                                                                id == old('position_id') ? 'selected' : '' }}>
                                                                {{ $et->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('position_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>


                                                    <div>
                                                        <label for="departmentId">Department Name </label>
                                                        <select class="form-select text-white-dark" name="department_id"
                                                            id="departmentId">
                                                            <option value="" disabled selected>Select Department</option>
                                                            @foreach ($department as $et)
                                                            <option name="department_id" class="pro-type" value="{{ $et->id }}"
                                                                {{ $et->
                                                                id == old('department_id') ? 'selected' : '' }}>
                                                                {{ $et->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('department_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="username">Username</label>
                                                        <input id="username" type="text" class="form-input" name="username" required />
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="password">Password</label>
                                                        <input id="password" type="password" class="form-input" name="password" oninput="validatePasswords()" required />
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="confirmPassword">Confirm Password</label>
                                                        <input id="passwordConfirmation" type="password" class="form-input" name="confirm_password"
                                                            oninput="validatePasswords()" required />
                                                        <p id="passwordError" class="error"></p>
                                                    </div>

                                                    {{-- <div>
                                                        <label for="username">Username</label>
                                                        <input id="username" type="text" class="form-input" name="username"
                                                            required />
                                                    </div>

                                                    <div>
                                                        <label for="password">Password</label>
                                                        <input id="password" type="password" class="form-input" name="password"
                                                            oninput="validatePasswords()" required />
                                                    </div>

                                                    <div>
                                                        <label for="confirmPassword">Confirm Password</label>
                                                        <input id="passwordConfirmation" type="password" class="form-input"
                                                            name="confirm_password" oninput="validatePasswords()" required />
                                                        <p id="passwordError" class="error"></p>
                                                    </div> --}}


                                                    <!-- Error message container -->
                                                    <div id="iconError" class="text-red-500 hidden" style="color: red;">
                                                        Please select a valid image for the icon (image format only).
                                                    </div>
                                                    <div style="margin-top: 35px">
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="checkbox" class="form-checkbox" name="status"
                                                                checked />
                                                            <span class=" text-white-dark">Active</span>
                                                                </label>
                                                            </div>
                                                            <div class=" flex justify-end items-center mt-3">
                                                                <button type="button" class="btn btn-outline-danger discard-btn"
                                                                    @click="openModal = false">Discard</button>
                                                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4 discard-btn"
                                                                    id="createButton"
                                                                    onclick="return saveFunction()">Create</button>
                                                    </div>
                                                </div>
                                            </form>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="editModal" class="mb-5">
                            <!-- modal -->
                            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
                                <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                                    <div x-transition x-transition.duration.300
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full cardView"  style="max-width:70%">
                                        <div class="heading">
                                            <h2 class="m-0">Edit Employee</h2>
                                        </div>
                                        <div class="p-5">

                                            <form id="employee_edit_form" class="needs-validation" method="POST"
                                                action="{{ route('employee.update') }}">
                                                @csrf
                                                <input type="hidden" name="id" id="employee_id" x-model="itemToEdit.id">
                                                <div
                                                    class="grid grid-cols-3 gap-4">

                                                    <div>
                                                        <label for="firstName">First Name</label>
                                                        <input id="firstName" type="text" class="form-input"
                                                            x-model="itemToEdit.first_name" name="first_name" />
                                                    </div>
                                                    <div>
                                                        <label for="lastName">LastName</label>
                                                        <input id="lastName" type="text" class="form-input"
                                                            x-model="itemToEdit.last_name" name="last_name" />
                                                    </div>
                                                    <div>
                                                        <label for="mobile">Mobile No</label>
                                                        <input type="text" id="mobile" class="form-input" maxlength="10"
                                                            pattern="\d{10}" name="mobile" x-model="itemToEdit.mobile"
                                                            title="Please enter exactly 10 digits" required />

                                                    </div>
                                                    <div>
                                                        <label for="phone">Phone</label>
                                                        <input type="text" id="phone" class="form-input" maxlength="10"
                                                            pattern="\d{10}" name="phone" x-model="itemToEdit.phone"
                                                            title="Please enter exactly 10 digits" />

                                                    </div>
                                                    <div>
                                                        <label for="email">E-mail</label>
                                                        <input id="email" type="email" class="form-input"
                                                            x-model="itemToEdit.email" name="email" />
                                                    </div>
                                                    <div>
                                                        <label for="sex">Sex</label>
                                                        <input id="sex" type="text" class="form-input" x-model="itemToEdit.sex"
                                                            name="sex" />
                                                    </div>
                                                    <div>
                                                        <label for="marriedStatus">Married Status</label>
                                                        <input id="marriedStatus" type="text" class="form-input"
                                                            x-model="itemToEdit.married_status" name="married_status" />
                                                    </div>
                                                    <div>
                                                        <label for="addressOne">Address</label>
                                                        <input id="addressOne" type="text" class="form-input"
                                                            x-model="itemToEdit.address_one" name="address_one" />
                                                    </div>


                                                    <div>
                                                        <label for="addressTwo">Address 2</label>
                                                        <input id="addressTwo" type="text" class="form-input"
                                                            x-model="itemToEdit.address_two" name="address_two" />
                                                    </div>
                                                    <div>
                                                        <label for="city">City</label>
                                                        <input id="city" type="text" class="form-input"
                                                            x-model="itemToEdit.city" name="city" />
                                                    </div>
                                                    <div>
                                                        <label for="state">State</label>
                                                        <input id="state" type="text" class="form-input"
                                                            x-model="itemToEdit.state" name="state" />
                                                    </div>
                                                    <div>
                                                        <label for="country">Country</label>
                                                        <input id="country" type="text" class="form-input"
                                                            x-model="itemToEdit.country" name="country" />
                                                    </div>
                                                    <div>
                                                        <label for="zipCode">Zip Code</label>
                                                        <input id="zipCode" type="text" class="form-input"
                                                            x-model="itemToEdit.zip_code" name="zip_code" />
                                                    </div>

                                                    <div>
                                                        <label for="tinNo">Tin No</label>
                                                        <input id="tinNo" type="text" class="form-input"
                                                            x-model="itemToEdit.tin_no" name="tin_no" />
                                                    </div>
                                                    <div>
                                                        <label for="salary">Salary</label>
                                                        <input id="salary" type="text" class="form-input"
                                                            x-model="itemToEdit.salary" name="salary" />
                                                    </div>
                                                    <div>
                                                        <label for="joinDate">Join Date</label>
                                                        <input id="joinDate" type="date" class="form-input"
                                                            x-model="itemToEdit.join_date" name="join_date" />
                                                    </div>
                                                    <div>
                                                        <label for="emergencyContactNo">Emergency Contact No</label>
                                                        <input id="emergencyContactNo" type="text" class="form-input"
                                                            x-model="itemToEdit.emergency_contact_no"
                                                            name="emergency_contact_no" />
                                                    </div>
                                                    <div>
                                                        <label for="emergencyEmail">Emergency Email</label>
                                                        <input id="emergencyEmail" type="text" class="form-input"
                                                            x-model="itemToEdit.emergency_email" name="emergency_email" />
                                                    </div>


                                                    <div>
                                                        <label for="employeeTypeId">Employee Type </label>
                                                        <select class="form-select text-white-dark" name="employee_type_id"
                                                            x-model="itemToEdit.employee_type_id" id="employeeTypeId">
                                                            <option value="" disabled selected>Select Type</option>
                                                            @foreach ($employee_type as $et)
                                                            <option name="employee_type_id" class="pro-type"
                                                                value="{{ $et->id }}" {{ $et->
                                                                id == old('employee_type_id') ? 'selected' : '' }}>
                                                                {{ $et->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('employee_type_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="attendanceTimeId">Attendance Time </label>
                                                        <select class="form-select text-white-dark" name="attendance_time_id"
                                                            x-model="itemToEdit.attendance_time_id" id="attendanceTimeId">
                                                            <option value="" disabled selected>Select Time</option>
                                                            @foreach ($attedance_time as $et)
                                                            <option name="attendance_time_id" class="pro-type"
                                                                value="{{ $et->id }}" {{ $et->
                                                                id == old('attendance_time_id') ? 'selected' : '' }}>
                                                                {{ $et->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('attendance_time_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>


                                                    <div>
                                                        <label for="positionId">Position </label>
                                                        <select class="form-select text-white-dark" name="position_id"
                                                            x-model="itemToEdit.position_id" id="positionId" required>
                                                            <option value="" disabled selected>Select Position</option>
                                                            @foreach ($position as $et)
                                                            <option name="position_id" class="pro-type" value="{{ $et->id }}" {{
                                                                $et->
                                                                id == old('position_id') ? 'selected' : '' }}>
                                                                {{ $et->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('position_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>


                                                    <div>
                                                        <label for="departmentId">Department Name </label>
                                                        <select class="form-select text-white-dark" name="department_id"
                                                            x-model="itemToEdit.department_id" id="departmentId">
                                                            <option value="" disabled selected>Select Department</option>
                                                            @foreach ($department as $et)
                                                            <option name="department_id" class="pro-type" value="{{ $et->id }}"
                                                                {{ $et->
                                                                id == old('department_id') ? 'selected' : '' }}>
                                                                {{ $et->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('department_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>

                                                    {{-- <div>
                                                        <label for="username">Username</label>
                                                        <input id="username" type="text" class="form-input" name="username" required />
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="password">Password</label>
                                                        <input id="password" type="password" class="form-input" name="password" oninput="validatePasswords()" required />
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="passwordConfirmation">Confirm Password</label>
                                                        <input id="passwordConfirmation" type="password" class="form-input" name="confirm_password"
                                                            oninput="validatePasswords()" required />
                                                        <p id="passwordError" class="error"></p>
                                                    </div> --}}

                                                
                                                    <div>
                                                        <label for="username">Username</label>
                                                        <input id="username" type="text" class="form-input"
                                                            x-model="itemToEdit.username" name="username" />
                                                    </div>

                                                    <div>
                                                        <label for="password">Password</label>
                                                        <input id="password" type="password" class="form-input"
                                                            x-model="itemToEdit.password" name="password" />
                                                    </div>

                                                    <div>
                                                        <label for="confirmPassword">Confirm Password</label>
                                                        <input id="confirmPassword" type="password" class="form-input"
                                                            x-model="itemToEdit.confirm_password" name="confirm_password" />
                                                        <p x-show="itemToEdit.password !== itemToEdit.confirm_password"
                                                            style="color: red;">Passwords do not match.</p>
                                                    </div>




                                                    <!-- Error message container -->
                                                    <div id="iconError" class="text-red-500 hidden" style="color: red;">
                                                        Please select a valid image for the icon (image format only).
                                                    </div>
                                                    <div style="margin-top: 35px">
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="checkbox" class="form-checkbox" name="status"
                                                                x-model="itemToEdit.checked" />
                                                            <span class="text-white-dark">Active</span>
                                                        </label>
                                                    </div>
                                                    <div class=" flex justify-end items-center mt-3">
                                                        <button type="button" class="btn btn-outline-danger discard-btn"
                                                            @click="editModal = false">Discard</button>
                                                        <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4 discard-btn" 
                                                            
                                                            id="updateButton" onclick="return updateFunction()">
                                                            Update</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div x-show="viewModal" class="mb-5">
                            <!-- modal -->
                            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
                                <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                                    <div x-transition x-transition.duration.300
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg" style="min-width:80%">
                                        <div class="heading">
                                            <h2 class="m-0">View Employee</h2>
                                        </div>
                                        <div class="p-5">
                                            <div class="row view-model" style="display: flex; gap: 30px;">
                                                <div class="left col-4">
                                                    <img style="width: 200px; height: 200px; object-fit: cover; border-radius: 5px;"
                                                        src="{{asset('asset/images/user-profile.jpeg')}}" />
                                                </div>
                  
                       
                                                <div class="right col-4"
                                                    style="display: flex; flex-direction: column; gap: 6px; width: 50%;">
                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        First Name :
                                                        <span class="form-input" id="firstName"  style="line-height: 1.5rem !important"></span>
                                                    </h2>
                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        Last Name : <span class="form-input"
                                                        id="lastName" style="line-height: 1.5rem !important"></span></h2>
                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        Mobile :<span class="form-input"
                                                        id="mobile"></span></h2>

                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        Phone :<span class="form-input" id="#mobile"></span>
                                                    </h2>
                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        email :<span class="form-input" id="#mobile"></span>
                                                    </h2>

                                                    <h2 style="padding-top: 10px;">Status : <span id="status"></span></h2>
                                                </div>
                                                <div class="right col-4"
                                                    style="display: flex; flex-direction: column; gap: 6px; width: 50%;">
                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        Sex :
                                                        <span class="form-input" id="sex"  style="line-height: 1.5rem !important"></span>
                                                    </h2>
                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        Address : <span class="form-input"
                                                            id="addressOne" style="line-height: 1.5rem !important"></span></h2>
                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        City :<span class="form-input"
                                                            id="city"></span></h2>

                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        State :<span class="form-input" id="state"></span>
                                                    </h2>
                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        Country :<span class="form-input" id="country"></span>
                                                    </h2>
                                                </div>
                                            </div>
                                            <div class=" flex justify-end items-center mt-3">
                                                <button type="button" class="btn btn-outline-danger discard-btn"
                                                    @click="viewModal = false">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    @can('user.view')
                    <div class="category-table"   style="position: relative;padding: 15px;">
                        <lable class="dt-buttons" style="position: absolute;margin-left: 300px;margin-top: 5px;z-index:99;">
                            Show
                            <select class="dt-button buttons-collection buttons-page-length" id="pagination" style="height: 2.4em;">
                                <option value="25" {{request()->get('items') == 25 ? 'selected' : ''}}>25</option>
                                <option value="50" {{request()->get('items') == 50 ? 'selected' : ''}}>50</option>
                                <option value="100" {{request()->get('items') == 100 ? 'selected' : ''}}>100</option>
                            </select>
                            rows
                        </lable>
                        <div class="table-responsive">
                            <table  id="example" class="custom-table display nowrap" style="width:100%">
                                <thead>
                                    <tr   style="border:none;background-color:skyblue !important;color:white;">
                                        <th>
                                        </th>
                                        <th>Full Name</th>
                                        <th>Mobile</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>City</th>
                                        <th>Position </th>
                                        <th>Department</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach($employees as $employee)
                                        <tr id="check-tr" data-id="{{ $employee['id'] }}">
                                            <td>
                                                <input type="checkbox" class="form-checkbox mt-1 check-box" id="check-box"
                                                    value="{{ $employee['id'] }}" />
                                            </td>
                                            <td style="text-align:left">{{ $employee['first_name'] }} {{ $employee['last_name'] }}</td>
                                            <td style="text-align:left">{{ $employee['mobile'] }}</td>
                                            <td style="text-align:left">{{ $employee['phone'] }}</td>
                                            <td style="text-align:center">{{ $employee['email'] }}</td>
                                            <td style="text-align:right">{{ $employee['city'] }}</td>
                                            <td style="text-align:right">{{ $employee['positionName'] }}</td>
                                            <td style="text-align:right">{{ $employee['departmentName'] }}</td>
                                            <td style="text-align:right">
                                            @if($employee['status']=== 'Active')
                                            <span class="badge badge-outline-success">{{ $employee['status'] }}</span>
                                            @else
                                            <span class="badge badge-outline-danger">{{ $employee['status'] }}</span>
                                            @endif
                                            </td>
                                            
                                        </tr>
                                        @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="11">{{$employees->links('vendor.pagination.tailwind')}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>
<script type="text/javascript">
    new DataTable('#example', {
        fixedColumns: true,
        lengthMenu: false,
        bPaginate: false,
        bInfo:false,
        layout: {
            topStart: {
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            }
        }
    });

    document.getElementById('pagination').onchange = function() { 
        window.location = "{!! $employees->url(1) !!}&items=" + this.value; 
    }; 
    $(document).ready(function () {
        $(document).on('click', "table#example tbody tr td", function() {
            var currentRow = $(this).closest("tr");
            $('table#example tbody tr').css('background-color', '');
            $('table#example tbody tr').css('color', '');
            currentRow.css('background-color', '#87ceeb');
            currentRow.css('color', 'white');
            $('button.view-btn').show();
            $('button.edit-btn').show();
            $('button.delete-button').hide();
            var id = currentRow.data('id');
            $(document).on('click', 'button.view-btn', function() {
                $('button.view-btn').hide();
                $('button.edit-btn').hide();
                currentRow.css('background-color', '');
                currentRow.css('color', '');
                const show_url = "{{action('Rest\EmployeeController@view', ['ID'])}}".replace('ID', id);
                window.location.href = show_url;
            });
            
            $(document).on('click', 'button.edit-btn', function() {
                $('button.view-btn').hide();
                $('button.edit-btn').hide();
                currentRow.css('background-color', '');
                currentRow.css('color', '');
                const edit_url = "{{action('Rest\EmployeeController@show', ['ID'])}}".replace('ID', id);
                $.ajax({
                    method: "GET",
                    url: edit_url,
                    dataType: "json",
                    success: function (result) {
                        $('form#employee_edit_form #employee_id').val(result.id);
                        $('form#employee_edit_form').find('#firstName').val(result.first_name);
                        $('form#employee_edit_form #lastName').val(result.last_name);
                        $('form#employee_edit_form #mobile').val(result.mobile);
                        $('form#employee_edit_form #phone').val(result.phone);
                        $('form#employee_edit_form #email').val(result.email);
                        $('form#employee_edit_form #sex').val(result.sex);
                        $('form#employee_edit_form #marriedStatus').val(result.married_status);
                        $('form#employee_edit_form #addressOne').val(result.address_one);
                        $('form#employee_edit_form #addressTwo').val(result.address_two);
                        $('form#employee_edit_form #city').val(result.city);
                        $('form#employee_edit_form #state').val(result.state);
                        $('form#employee_edit_form #country').val(result.country);
                        $('form#employee_edit_form #zipCode').val(result.zip_code);
                        $('form#employee_edit_form #tinNo').val(result.tin_no);
                        $('form#employee_edit_form #salary').val(result.salary);
                        $('form#employee_edit_form #joinDate').val(result.join_date);
                        $('form#employee_edit_form #emergencyContactNo').val(result.emergency_contact_no);
                        $('form#employee_edit_form #emergencyEmail').val(result.emergency_email);
                        $('form#employee_edit_form #employeeTypeId').val(result.employee_type_id);
                        $('form#employee_edit_form #attendanceTimeId').val(result.attendance_time_id);
                        $('form#employee_edit_form #positionId').val(result.position_id);
                        $('form#employee_edit_form #departmentId').val(result.department_id);

                    }
                });
            });
	    });

        var searchIDs = [];
        $(document).on('change', '#check-box', function()
        {
            $('button.view-btn').hide();
            $('button.edit-btn').hide();
            if($(this).is(":checked")) 
            {
                searchIDs.push($(this).val());
            }
            else
            {
                searchIDs.splice(searchIDs.indexOf($(this).val()), 1)
            }
            if(searchIDs.length > 0)
            {   
                $('button.delete-button').show();
                $(document).on('click', 'button.delete-button', function() {
                const delete_url = "{{action('Rest\EmployeeController@delete')}}";
                $.ajax({
                    method: "GET",
                    url: delete_url,
                    dataType: "json",
                    data: {
                        ids : searchIDs
                    },
                    success: function (result) {
                        if (result.success == true) {
                            window.location.reload();

                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            });
            }
        });

        $(document).on('click', '.discard-btn', function(){
            $('button.view-btn').hide();
            $('button.edit-btn').hide();
            $('form#employee_edit_form #employee_id').val(" ");
            $('form#employee_edit_form').find('#firstName').val(" ");
            $('form#employee_edit_form #lastName').val(" ");
            $('form#employee_edit_form #mobile').val(" ");
            $('form#employee_edit_form #phone').val(" ");
            $('form#employee_edit_form #email').val(" ");
            $('form#employee_edit_form #sex').val(" ");
            $('form#employee_edit_form #marriedStatus').val(" ");
            $('form#employee_edit_form #addressOne').val(" ");
            $('form#employee_edit_form #addressTwo').val(" ");
            $('form#employee_edit_form #city').val(" ");
            $('form#employee_edit_form #state').val(" ");
            $('form#employee_edit_form #country').val(" ");
            $('form#employee_edit_form #zipCode').val(" ");
            $('form#employee_edit_form #tinNo').val(" ");
            $('form#employee_edit_form #salary').val(" ");
            $('form#employee_edit_form #joinDate').val(" ");
            $('form#employee_edit_form #emergencyContactNo').val(" ");
            $('form#employee_edit_form #emergencyEmail').val(" ");
            $('form#employee_edit_form #employeeTypeId').val(" ");
            $('form#employee_edit_form #attendanceTimeId').val(" ");
            $('form#employee_edit_form #positionId').val(" ");
            $('form#employee_edit_form #departmentId').val(" ");
            $('div.view-model #firstName').text(" ");
            $('div.view-model #lastName').text(" ");
            $('div.view-model #mobile').text(" ");
            $('div.view-model #phone').text(" ");
            $('div.view-model #email').text(" ");
            $('div.view-model #sex').text(" ");
            $('div.view-model #marriedStatus').text(" ");
            $('div.view-model #addressOne').text(" ");
            $('div.view-model #city').text(" ");
            $('div.view-model #state').text(" ");
            $('div.view-model #country').text(" ");
            $('div.view-model #positionId').text(" ");
            $('div.view-model #departmentId').text(" ");
        })
    });


    document.addEventListener('alpine:init', () => {
    //Category list
        Alpine.data('employee', () => ({
            selectedRows: [],
            searchText: '',
            openModal: false,
            editModal: false,
            viewModal: false,
            viewItem: {},
            itemToEdit: {},
            pageSize: 5, // Number of items per page
            currentPage: 1, // Current page number

            showViewModal() {
                this.viewModal = true; // Show the view modal
            },
            
            addCategory() {
                const firstName = document.getElementById('firstName').value;
                const lastName = document.getElementById('lastName').value;
                const mobile = document.getElementById('mobile').value;
                const phone = document.getElementById('phone').value;
                const email = document.getElementById('email').value;
                const sex = document.getElementById('sex').value;
                const marriedStatus = document.getElementById('marriedStatus').value;
                const addressOne = document.getElementById('addressOne').value;
                const addressTwo = document.getElementById('addressTwo').value;
                const city = document.getElementById('city').value;
                const state = document.getElementById('state').value;
                const country = document.getElementById('country').value;
                const zipCode = document.getElementById('zipCode').value;
                const tinNo = document.getElementById('tinNo').value;
                const salary = document.getElementById('salary').value;
                const joinDate = document.getElementById('joinDate').value;
                const emergencyContactNo = document.getElementById('emergencyContactNo').value;
                const emergencyEmail = document.getElementById('emergencyEmail').value;
                const employeeTypeId = document.getElementById('employeeTypeId').value;
                const attendanceTimeId = document.getElementById('attendanceTimeId').value;
                const positionId = document.getElementById('positionId').value;
                const departmentId = document.getElementById('departmentId').value;
                // const username = document.getElementById('username').value;
                // const password = document.getElementById('password').value;
                // const confirmPassword = document.getElementById('confirmPassword').value;
                const checkboxChecked = document.querySelector('input[type="checkbox"]').checked;
                const iconInput = document.getElementById('Image');
                

                if (!firstName || !lastName || !mobile || !iconInput.files[0]) {
                    console.error('Please fill in all fields and select an icon image.');
                    return;
                }

                const isValidImageType = (file) => {
                    const allowedFormats = ['image/jpeg', 'image/png', 'image/gif'];
                    return allowedFormats.includes(file.type);
                };

                const iconFile = iconInput.files[0];

                if (!isValidImageType(iconFile)) {
                    console.error('Please select a valid image for the icon (jpeg, png, gif).');
                    return;
                }

                const reader = new FileReader();
                reader.readAsDataURL(iconFile);
                reader.onload = () => {
                    const newProduct = {
                        id: this.items.length + 1,
                        name: name,
                        Image: reader.result, // Store the image as a data URL
                        Status: checkboxChecked ? 'Active' : 'Inactive',
                        action: 1,
                    };

                    this.items.push(newProduct);

                    // Close the modal after adding the product
                    this.openModal = false;

                    // Reset the form inputs
                    document.getElementById('firstName').value = '';
                    document.getElementById('lastName').value = '';
                    document.getElementById('mobile').value = '';
                    document.getElementById('phone').value = '';
                    document.getElementById('email').value = '';
                    document.getElementById('sex').value = '';
                    document.getElementById('marriedStatus').value = '';
                    document.getElementById('addressOne').value = '';
                    document.getElementById('addressTwo').value = '';
                    document.getElementById('city').value = '';
                    document.getElementById('state').value = '';
                    document.getElementById('country').value = '';
                    document.getElementById('zipCode').value = '';
                    document.getElementById('tinNo').value = '';
                    document.getElementById('salary').value = '';
                    document.getElementById('joinDate').value = '';
                    document.getElementById('emergencyContactNo').value = '';
                    document.getElementById('emergencyEmail').value = '';
                    document.getElementById('employeeTypeId').value = '';
                    document.getElementById('attendanceTimeId').value = '';
                    document.getElementById('positionId').value = '';
                    document.getElementById('departmentId').value = '';
                    document.querySelector('input[type="checkbox"]').checked = false;
                };

                reader.onerror = () => {
                    console.error('Error reading the image file.');
                };
            },
            editItem() {
                this.editModal = true;
            },
            editCategory() {
                    var data = $('form#call_edit_form').serialize();
                    var id = $('form#call_edit_form').find('#edit-id').val();
                    var url = $('form#call_edit_form').attr("action").replace('ID', id)
                    $.ajax({
                        method: "POST",
                        url: url,
                        dataType: "json",
                        data: data,
                        success: function (result) {
                            if (result.success == true) {
                                conso
                                this.editModal = false;
                                toastr.success(result.msg);
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                },
        }));
    });
</script>
<script>
    function validatePasswords() {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('passwordConfirmation').value;
        const passwordError = document.getElementById('passwordError');
        const createButton = document.getElementById('createButton');
        const updateButton = document.getElementById('updateButton');

        if (password !== passwordConfirmation) {
            // Passwords do not match
            passwordError.innerHTML = 'Passwords do not match. Please check again.';
            document.getElementById('password').classList.add('input-error');
            document.getElementById('passwordConfirmation').classList.add('input-error');
            createButton.disabled = true; // Disable the create button
            // updateButton.disabled = true; // Disable the update button
        } else {
            // Passwords match
            passwordError.innerHTML = '';
            document.getElementById('password').classList.remove('input-error');
            document.getElementById('passwordConfirmation').classList.remove('input-error');
            createButton.disabled = false; // Enable the create button
            updateButton.disabled = false; // Enable the update button
        }
    }

    function saveFunction() {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('passwordConfirmation').value;

        if (password !== passwordConfirmation) {
            // Passwords do not match
            alert('Passwords do not match. Please check again.');
            return false; // Exit the function without executing the save logic
        }

        // Passwords match, proceed with the save function
        // Your save function logic goes here
        console.log('Save function executed successfully!');
        return true; // Allow the button click to proceed
    }

    function updateFunction() {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('passwordConfirmation').value;

        // if (password !== passwordConfirmation) {
        //     // Passwords do not match
        //     alert('Passwords do not match. Please check again.');
        //     return false; // Exit the function without executing the update logic
        // }
        console.log('Update function executed successfully!');
        return true; // Allow the button click to proceed
    }
</script>
@endsection

