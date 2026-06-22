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

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li>
                <span class="before:content-['/']" >
                    Department
                </span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div x-data="categoryList">
                <script src="assets/js/simple-datatables.js"></script>

                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5" x:data="categoryList">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="mb-5 flex items-center gap-2">
                                @can('department.create')
                                <button class="btn btn-success" @click="openModal = true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="h-5 w-5">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Add New
                                </button>
                                @endcan
                                @can('department.update')
                                <button class="btn btn-primary gap-2 edit-btn" style="display: none;" @click="editItem()" >
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </button>
                                @endcan
                                @can('department.delete')
                                <button type="button" href="#" class="btn btn-danger delete-button" style="display:none;">
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
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg">
                                        <div class="heading">
                                            <h2 class="m-0">Add Department</h2>
                                        </div>
                                        <div class="p-5">
                                            <form enctype="multipart/form-data" id="signupForm" class="needs-validation"
                                                method="POST" action="{{ route('department.store') }}">
                                                @csrf
                                                <div class="grid grid-cols-1 gap-4">
                                                    <div>
                                                        <label for="name">Name</label>
                                                        <input id="Name" type="text" placeholder="Department Name" name="name"
                                                            class="form-input" required />
                                                            @error('name')
                                                            @endif
                                                    </div>
                                                    <div>
                                                        <label style="width: 20%;" for="supplier">Location<span>*</span></label>
                                                        {!! Form::select('location_id', $business_locations , null, ['class' => 'form-input', 'id' => 'location_id', 'required',
                                                        'placeholder' => __('Please Select')]); !!}
                                                    </div>
                                                    <div>
                                                        <!-- Image Preview and Update -->
                                                        <label>Upload Image</label>
                                                        <div style="display: flex; align-items: center; gap: 10px; object-fit: cover;">
                                                            <input class="form-input" id="Image" type="file" name="image"
                                                                accept="image/*" @change="handleIconChange" />
                                                        </div>
                                                    </div>
                                                    <!-- Error message container -->
                                                    <div id="iconError" class="text-red-500 hidden" style="color: red;">
                                                        Please select a valid image for the icon (image format only).
                                                    </div>
                                                    <div>
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
                                                    @click="addCategory">Create</button>
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
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg">
                                        <div class="heading">
                                            <h2 class="m-0">Edit Department</h2>
                                        </div>
                                        <div class="p-5">

                                            <form enctype="multipart/form-data" id="depar_edit_form" class="needs-validation"
                                            method="POST" action="{{ route('department.update') }}">
                                                @csrf
                                                <input type="hidden" name="id" id="type_id" x-model="itemToEdit.id">
                                                <div class="grid grid-cols-1 gap-4">
                                                    <div>
                                                        <label for="name">Name</label>
                                                        <input id="Name" type="text" class="form-input" name="name"
                                                            required />
                                                    </div>
                                                    <div>
                                                        <label style="width: 20%;" for="supplier">Location<span>*</span></label>
                                                        {!! Form::select('location_id', $business_locations , null, ['class' => 'form-input', 'id' => 'location_id', 'required',
                                                        'placeholder' => __('Please Select')]); !!}
                                                    </div>
                                                    <!-- Image Preview and Update -->
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <img style="width: 50px; height: 50px; margin: 0; border-radius: 5px; object-fit: cover;"
                                                            src="" alt="Current Icon" class="w-24 h-24 mb-2">
                                                        <input class="form-input" id="Image" type="file" name="image"
                                                            accept="image/*" @change="handleIconChange" />
                                                    </div>

                                                    <!-- Error message container -->
                                                    <div id="iconError" class="text-red-500 hidden" style="color: red;">
                                                        Please select a valid image for the icon (image format only).
                                                    </div>
                                                    <div>
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="checkbox" class="form-checkbox" name="status" id="status-input"
                                                                />
                                                            <span class="text-white-dark">Active</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class=" flex justify-end items-center mt-3">
                                                    <button type="button" class="btn btn-outline-danger discard-btn"
                                                        @click="discard()">Discard</button>
                                                    <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4 discard-btn"
                                                        >Update</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @can('department.view')
                    <div class="category-table" style="position: relative;padding: 15px;">
                        <lable class="dt-buttons" style="position: absolute;margin-left: 300px;margin-top: 5px;z-index:99;">
                            Show
                            <select class="dt-button buttons-collection buttons-page-length" id="pagination" style="height: 2.4em;">
                                <option value="25" {{request()->get('items') == 25 ? 'selected' : ''}}>25</option>
                                <option value="50" {{request()->get('items') == 50 ? 'selected' : ''}}>50</option>
                                <option value="100" {{request()->get('items') == 100 ? 'selected' : ''}}>100</option>
                            </select>
                            rows
                        </lable>
                        <table  id="example" class="custom-table display nowrap" style="width:100%">
                            <thead>
                                <tr style="border:none;background-color:skyblue !important;color:white;">
                                    <th>
                                        
                                    </th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Icon</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $department)
                                    <tr id="check-tr" data-id="{{ $department['id'] }}">
                                        <td>
                                            <input type="checkbox" class="form-checkbox mt-1 check-box" id="check-box"
                                                value="{{ $department['id'] }}" />
                                        </td>
                                        <td style="text-align:left">{{ $department['name'] }}</td>
                                        <td style="text-align:left">{{ $department['location'] }}</td>
                                        <td><img style="border-radius: 5px; object-fit: cover;" src="{{ $department['icon'] }}"
                                                alt="Product Icon" class="w-8 h-8" /></td>
                                        <td style="text-align:center">
                                            @if($department['status']=== 'Active')
                                            <span class="badge badge-outline-success">{{ $department['status'] }}</span>
                                            @else
                                            <span class="badge badge-outline-danger">{{ $department['status'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="11">{{$departments->links('vendor.pagination.tailwind')}}</td>
                                </tr>
                            </tfoot>
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
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
//Category list
        Alpine.data('categoryList', () => ({
            selectedRows: [],
            searchText: '',
            openModal: false,
            editModal: false,
            viewModal: false,
            viewItem: {},
            itemToEdit: {},
            pageSize: 5, // Number of items per page
            currentPage: 1, // Current page number
            editItem() {
                this.editModal = true;
            },
            discard()
            {
                $('form#depar_edit_form #type_id').val(' ');
                $('form#depar_edit_form').find('#Name').val(' ');
        
                this.editModal = false;
                window.location.reload();
            },
            handleIconChange(event) {
                const file = event.target.files[0];

                if (file) {
                    const allowedFormats = ['image/jpeg', 'image/png', 'image/gif'];

                    if (!allowedFormats.includes(file.type)) {
                        document.getElementById('iconError').classList.remove('hidden');
                        this.iconFile = null;
                    } else {
                        document.getElementById('iconError').classList.add('hidden');

                        const reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = () => {
                            this.itemToEdit.icon = reader.result; // Update the icon with the new image data
                        };
                    }
                }
            },
        }));
    });
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
        window.location = "{!! $departments->url(1) !!}&items=" + this.value; 
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
            
            $(document).on('click', 'button.edit-btn', function() {
                $('button.edit-btn').hide();
                currentRow.css('background-color', '');
                currentRow.css('color', '');
                const edit_url = "{{action('Rest\DepartmentController@show', ['ID'])}}".replace('ID', id);
                $.ajax({
                    method: "GET",
                    url: edit_url,
                    dataType: "json",
                    success: function (result) {
                        $('form#depar_edit_form #type_id').val(result.id);
                        $('form#depar_edit_form').find('#Name').val(result.name);
                        $('form#depar_edit_form').find('#location_id').val(result.location_id);
                        $('form#depar_edit_form').find('img').attr("src", result.icon);
                        if(result.status == 'Active')
                        {
                            $('form#depar_edit_form').find('#status-input').prop('checked', true);
                        }
                        else
                        {
                            $('form#depar_edit_form').find('#status-input').prop('checked', false);
                        }
                    }
                });
            });
        });

        var searchIDs = [];
        $(document).on('change', '#check-box', function()
        {
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
                const delete_url = "{{action('Rest\DepartmentController@delete')}}";
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
    });
    $(document).on('click', 'discard-btn', function() {
        $('form#depar_edit_form #type_id').val(' ');
        $('form#depar_edit_form').find('#Name').val(' ');
        window.location.reload();
    });
</script>
@endsection