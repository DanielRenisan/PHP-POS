@extends('layouts.app_rest')
@section('content')
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/DataTables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('bootstrap/css/boot.min.css') }}">
<style>
    #facility_table_filter label {
        font-size: 0
    }
    #facility_table_filter label input::after::placeholder {
    content: "Enter your number" !important;
    }
    .button::before { 
    content: "New Button Title"; 
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
    .custom-table>tbody>tr:nth-of-type(odd){background-color:#f9f9f9}
    .custom-table>tbody>tr:hover{background-color:#f5f5f5}
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
        .pagination {
        font-size: 12px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 15px !important;
    }

    .pagination .paginate_button a {
        border-radius: 50%;
    }

    .pagination .paginate_button .previous {
        border: none;
        background-color: red;
    }

    .pagination>li:first-child>a,
    .pagination>li:first-child>span,
    .pagination>li:last-child>a,
    .pagination>li:last-child>span {
        border: none !important;
        background-color: transparent;
    }
    .dt-buttons .btn-info{
        color:black;
    }
</style>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Table Booking</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div x-data="tablebooking">
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5" x:data="tablebooking">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="mb-5 flex items-center gap-2">
                                @can('table-booking.create')
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
                                @can('table-booking.update')
                                <button class="btn btn-primary gap-2 edit-btn" style="display: none;" @click="editItem()" >
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </button>
                                @endcan
                                @can('table-booking.delete')
                                <button type="button" href="#" class="btn btn-danger delete-button" style="display: none;">
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
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg" style="border-radius:.5rem;border-width: 0;">
                                        <div class="heading">
                                            <h2 class="m-0">Add Table Booking</h2>
                                        </div>
                                        <div class="p-5">
                                            <form id="signupForm" class="needs-validation" method="POST"
                                                action="{{ route('table_booking.store') }}">
                                                @csrf
                                                <div class="grid grid-cols-1 gap-4 pt-5">
                                                    <div>
                                                        <label for="tableName">Table </label>
                                                        <select class="form-select text-white-dark" name="table_id" id="TableID"
                                                            required>
                                                            <option value="" disabled selected>Select Table</option>
                                                            @foreach ($table as $tl)
                                                            <option name="SelectParent" class="pro-type" value="{{ $tl->id }}" {{
                                                                $tl->
                                                                id == old('SelectParent') ? 'selected' : '' }}>
                                                                {{ $tl->table_name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('SelectParent')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="contactName">Customer Name </label>
                                                        <select class="form-select text-white-dark" name="contact_id" id="ContactID"
                                                            required>
                                                            <option value="" disabled selected>Select Customer</option>
                                                            @foreach ($customer_name as $ct)
                                                            <option name="SelectParent" class="pro-type" value="{{ $ct->id }}" >
                                                                {{ $ct->first_name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('SelectParent')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="bookingDate">Booking Date Time</label>
                                                        <input id="BookingDate" type="datetime-local" placeholder="Date&Time"
                                                            name="booking_date_time" class="form-input" required />
                                                    </div>
                                                    <div>
                                                        <label for="reservedDate">Reserved Date Time</label>
                                                        <input id="ReservedDate" type="datetime-local" placeholder="Date&Time"
                                                            name="reserved_book_date_time" class="form-input" required />
                                                    </div>

                                                    <div style="margin-top: 20px">
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="checkbox" class="form-checkbox" name="status" checked />
                                                            <span class=" text-white-dark">Active</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class=" flex justify-end items-center mt-3">
                                                    <button type="button" class="btn btn-outline-danger"
                                                        @click="openModal = false">Discard</button>
                                                    <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                                        >Create</button>
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
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg"  style="border-radius:.5rem;border-width: 0;">
                                        <div class="heading">
                                            <h2 class="m-0">Edit Customer Group</h2>
                                        </div>
                                        <div class="p-5">

                                            <form id="book_edit_form" class="needs-validation" method="POST"
                                                action="{{ route('table_booking.update') }}">
                                                @csrf
                                                <input type="hidden" name="id" id="edit-id">
                                                <div class="grid grid-cols-1 gap-4 pt-5">
                                                    <div>
                                                        <label for="tableLocation">Table </label>
                                                        <select class="form-select text-white-dark" name="table_id"
                                                            id="TableID" required>
                                                            <option value="" selected>Select Table</option>
                                                            @foreach ($table as $tl)
                                                            <option name="SelectParent" class="pro-type" value="{{ $tl->id }}">
                                                                {{ $tl->table_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="tableLocation">Customer Name </label>
                                                        <select class="form-select text-white-dark" name="contact_id"
                                                            id="ContactID" required>
                                                            <option value="" selected>Select Table</option>
                                                            @foreach ($customer_name as $ct)
                                                            <option name="SelectParent" class="pro-type" value="{{ $ct->id }}">
                                                                {{ $ct->first_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>


                                                    <div>
                                                        <label for="name">Booking Date Time</label>
                                                        <input id="BookingDate" type="datetime-local" placeholder="Date&Time"
                                                            name="booking_date_time" class="form-input" required />
                                                    </div>

                                                    <div>
                                                        <label for="name">Reserved Date Time</label>
                                                        <input id="ReservedDate" type="datetime-local" placeholder="Date&Time"
                                                            name="reserved_book_date_time" class="form-input" required />
                                                    </div>

                                                    <div style="margin-top: 20px">
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="checkbox" class="form-checkbox" name="status" id="status-input" />
                                                            <span class="text-white-dark">Active</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class=" flex justify-end items-center mt-3">
                                                    <button type="button" class="btn btn-outline-danger"
                                                        @click="editModal = false">Discard</button>
                                                    <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                                        >Update</button>
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
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg">
                                        <div class="heading">
                                            <h2 class="m-0">View Table Booking</h2>
                                        </div>
                                        <div class="p-5">
                                            <div class="row" style="display: flex; gap: 30px;">
                                                <div class="left col-6">
                                                    <img style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px;"
                                                        :src="viewItem.icon" />
                                                </div>
                                                <div class="right col-6"
                                                    style="display: flex; flex-direction: column; gap: 6px; width: 60%;">

                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        Table Name : <span class="form-input"
                                                            x-text="viewItem.table_name"></span>
                                                    </h2>

                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        Contact Name : <span class="form-input" x-text="viewItem.name"></span>
                                                    </h2>

                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        Booking Date : <span class="form-input"
                                                            x-text="viewItem.booking_date_time"></span>
                                                    </h2>

                                                    <h2 style="display: flex; flex-direction: column; font-size: 12px;">
                                                        Reserved Date : <span class="form-input"
                                                            x-text="viewItem.reserved_book_date_time"></span>
                                                    </h2>

                                                    <h2 style="padding-top: 10px;">Status :<span style="margin-left: 20px;"
                                                            :class="viewItem.status === 'Active' ? 'badge badge-outline-success' : 'badge badge-outline-danger'"
                                                            x-text="viewItem.status"></span></h2>
                                                </div>
                                            </div>
                                            <div class=" flex justify-end items-center mt-3">
                                                <button type="button" class="btn btn-outline-danger"
                                                    @click="viewModal = false">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    @can('table-booking.view')
                    <div class="category-table" style="position: relative;padding: 15px;">  
                        <table id="facility_table" class="custom-table display nowrap" width="100%">
                            <thead>
                                <tr  style="border:none;background-color:skyblue !important;color:white;">
                                    <th>
                                    </th>
                                    <th>Table Name</th>
                                    <th>Customer Name</th>
                                    <th>Booking Date</th>
                                    <th>Reserved Book Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        Alpine.data('tablebooking', () => ({
            sselectedRows: [],
            searchText: '',
            openModal: false,
            editModal: false,
            editItem() {
                this.editModal = true;
            },
        }));
    });
    $(document).ready(function () {
        var ajax_url = "{{action('Rest\TableBookingController@index')}}";
        facility_table = $('#facility_table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [[0, 'desc']],
            ajax: ajax_url,
            columnDefs: [{
                "targets": [0, 5],
                "orderable": false,
                "searchable": false
            }],
            language: {
                    paginate: {
                        previous: '<i class="fa fa-chevron-left"></i>', // Icon for previous page
                        next: '<i class="fa fa-chevron-right"></i>' // Icon for next page
                    },
                },

            columns: [
                { data: 'action', name: 'action' },
                { data: 'table_name', name: 'table_name' },
                { data: 'customerName', name: 'customerName' },
                { data: 'booking_date_time', name: 'booking_date_time' },
                { data: 'reserved_book_date_time', name: 'reserved_book_date_time' },
                { data: 'status', name: 'status' },
            ],
            "fnDrawCallback": function (oSettings) {
                __currency_convert_recursively($('#facility_table'));
            },
            createdRow: function (row, data, dataIndex) {
                $(row).attr('class', 'clickable_td');
                $(row).attr('data-id', data.id);
            }
        });

        $(document).on('click', "table#facility_table tbody tr td", function() {
            var currentRow = $(this).closest("tr");
            $('table#facility_table tbody tr').css('background-color', '');
            $('table#facility_table tbody tr').css('color', '');
            currentRow.css('background-color', '#87ceeb');
            currentRow.css('color', 'white');
            $('button.edit-btn').show();
            $('button.delete-button').hide();
            var id = currentRow.data('id');
            
            $(document).on('click', 'button.edit-btn', function() {
                $('button.edit-btn').hide();
                currentRow.css('background-color', '');
                currentRow.css('color', '');
                const edit_url = "{{action('Rest\TableBookingController@show', ['ID'])}}".replace('ID', id);
                $.ajax({
                    method: "GET",
                    url: edit_url,
                    dataType: "json",
                    success: function (result) {
                        console.log(result)
                        $('form#book_edit_form #edit-id').val(result.id);
                        $('form#book_edit_form').find('#TableID').val(result.table_id);
                        $('form#book_edit_form').find('#ContactID').val(result.contact_id);
                        $('form#book_edit_form').find('#BookingDate').val(result.booking_date_time);
                        $('form#book_edit_form').find('#ReservedDate').val(result.reserved_book_date_time);
                        if(result.status == 'Reserved')
                        {
                            $('form#book_edit_form').find('#status-input').prop('checked', true);
                        }
                        else
                        {
                            $('form#book_edit_form').find('#status-input').prop('checked', false);
                        }
                    }
                });
            });
        });

        var searchIDs = [];
        $(document).on('change', '#check-box', function()
        {
            $('button.edit-btn').hide();
            $('button.delete-button').hide();
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
                    const delete_url = "{{action('Rest\TableBookingController@delete')}}";
                    $.ajax({
                        method: "GET",
                        url: delete_url,
                        dataType: "json",
                        data: {
                            ids : searchIDs
                        },
                        success: function (result) {
                            if (result.success == true) {
                                facility_table.ajax.reload();

                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });
            }
        }); 
    });
</script>
@endsection