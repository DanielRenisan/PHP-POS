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
            <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Business Locations</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div x-data="sizeList">
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5" x:data="sizeList">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="mb-5 flex items-center gap-2">
                                @can('business-location.create')    
                                    <button class="btn btn-success gap-2" @click="openModal = true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Add New
                                    </button>
                                @endcan
                                @can('business-location.update')
                                <button class="btn btn-primary gap-2 edit-btn" style="display: none;" @click="editItem()" >
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </button>
                                @endcan
                            </div>
                        </div>

                        <div x-data="{ iconFile: null }" x-show="openModal" class="mb-5">
                            <!-- modal -->
                            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
                                :class="open && '!block'">
                                <div class="flex items-start justify-center min-h-screen px-4"
                                    @click.self="open = false">
                                    
                                    <div x-transition x-transition.duration.300
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg" style="border-radius:.5rem;border-width: 0;max-width:80%;">
                                        <div class="heading">
                                            <h2 class="m-0">Add Location</h2>
                                        </div>
                                        <div class="p-5">
                                            {!! Form::open(['url' => action('LocationController@store'), 'method' => 'post', 'class' => 'space-y-5', 'id' => 'facilityi6_add_form' ]) !!}
                                                <div class="grid grid-cols-3 gap-4">
                                                    <div>
                                                        {!! Form::label('name', __( 'Location Name' ) . ':*') !!}
                                                        {!! Form::text('name', null, ['class' => 'form-input', 'required', 'placeholder' => __( 'Location Name' ) ]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('landmark', __( 'Landmark' ) . ':') !!}
                                                        {!! Form::text('landmark', null, ['class' => 'form-input', 'placeholder' => __( 'Landmark' ) ]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('city', __( 'City' ) . ':*') !!}
                                                        {!! Form::text('city', null, ['class' => 'form-input', 'placeholder' => __( 'business.city'), 'required' ]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('zip_code', __( 'Zip Code' ) . ':') !!}
                                                        {!! Form::text('zip_code', null, ['class' => 'form-input', 'placeholder' => __( 'Zip Code') ]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('state', __( 'State' ) . ':*') !!}
                                                        {!! Form::text('state', null, ['class' => 'form-input', 'placeholder' => __( 'State'), 'required']); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('country', __( 'Country' ) . ':*') !!}
                                                        {!! Form::text('country', null, ['class' => 'form-input', 'placeholder' => __( 'Country'), 'required' ]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('mobile', __( 'Mobile' ) . ':') !!}
                                                        {!! Form::text('mobile', null, ['class' => 'form-input', 'placeholder' => __( 'Mobile')]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('tin_number','Tin Number' . ':') !!}
                                                        {!! Form::text('tin_number', null, ['class' => 'form-input', 'required',
                                                        'placeholder' => 'Tin Number']); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('reg_doc_no','Registration Document Number' . ':*') !!}
                                                        {!! Form::text('reg_doc_no', null, ['class' => 'form-input', 'required',
                                                        'placeholder' => 'Registration Document Number']); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('fax_no','Fax Number' . ':') !!}
                                                        {!! Form::text('fax_no', null, ['class' => 'form-input', 
                                                        'placeholder' => 'Fax Number']); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('alternate_number', __( 'Alternate Number' ) . ':') !!}
                                                        {!! Form::text('alternate_number', null, ['class' => 'form-input', 'placeholder' => __( 'Alternate_number')]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('email', __( 'Email' ) . ':') !!}
                                                        {!! Form::email('email', null, ['class' => 'form-input', 'placeholder' => __( 'Email')]); !!}
                                                    </div>
                                                </div>    
                                                <div class=" flex justify-end items-right mt-3">
                                                            <button type="button" class="btn btn-outline-danger"
                                                                @click="openModal = false">Discard</button>
                                                            <button
                                                                class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                                                type="submit">Create</button>
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div x-show="editModal" class="mb-5">
                            <!-- modal -->
                            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
                                :class="open && '!block'">
                                <div class="flex items-start justify-center min-h-screen px-4"
                                    @click.self="open = false">
                                    <div x-transition x-transition.duration.300
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg"  style="border-radius:.5rem;border-width: 0;max-width:80%;">
                                        <div class="heading">
                                            <h2 class="m-0">Edit Location</h2>
                                        </div>
                                        <div class="p-5">
                                            {!! Form::open(['url' => action('LocationController@update'),'class'=>'space-y-5', 'method' => 'PUT', 'id' => 'location_edit_form' ]) !!}
                                            <div class="grid grid-cols-3 gap-4">
                                                    <input type="hidden" name="id" id="edit_id">
                                                    <div>
                                                        {!! Form::label('name', __( 'Location Name' ) . ':*') !!}
                                                        {!! Form::text('name', null, ['class' => 'form-input', 'required', 'placeholder' => __( 'Location Name' )]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('landmark', __( 'Landmark' ) . ':') !!}
                                                        {!! Form::text('landmark', null, ['class' => 'form-input', 'placeholder' => __( 'Landmark' ) ]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('city', __( 'City' ) . ':*') !!}
                                                        {!! Form::text('city', null, ['class' => 'form-input', 'placeholder' => __( 'business.city'), 'required' ]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('zip_code', __( 'Zip Code' ) . ':') !!}
                                                        {!! Form::text('zip_code', null, ['class' => 'form-input', 'placeholder' => __( 'Zip Code') ]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('state', __( 'State' ) . ':*') !!}
                                                        {!! Form::text('state', null, ['class' => 'form-input', 'placeholder' => __( 'State'), 'required']); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('country', __( 'Country' ) . ':*') !!}
                                                        {!! Form::text('country', null, ['class' => 'form-input', 'placeholder' => __( 'Country'), 'required' ]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('mobile', __( 'Mobile' ) . ':') !!}
                                                        {!! Form::text('mobile', null, ['class' => 'form-input', 'placeholder' => __( 'Mobile')]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('tin_number','Tin Number' . ':') !!}
                                                        {!! Form::text('tin_number', null, ['class' => 'form-input', 'required',
                                                        'placeholder' => 'Tin Number']); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('reg_doc_no','Registration Document Number' . ':*') !!}
                                                        {!! Form::text('reg_doc_no', null, ['class' => 'form-input', 'required',
                                                        'placeholder' => 'Registration Document Number']); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('fax_no','Fax Number' . ':') !!}
                                                        {!! Form::text('fax_no', null, ['class' => 'form-input', 
                                                        'placeholder' => 'Fax Number']); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('alternate_number', __( 'Alternate Number' ) . ':') !!}
                                                        {!! Form::text('alternate_number', null, ['class' => 'form-input', 'placeholder' => __( 'Alternate_number')]); !!}
                                                    </div>
                                                    <div>
                                                        {!! Form::label('email', __( 'Email' ) . ':') !!}
                                                        {!! Form::email('email', null, ['class' => 'form-input', 'placeholder' => __( 'Email')]); !!}
                                                    </div>
                                                </div>     
                                                <div class=" flex justify-end items-center mt-3">
                                                    <button type="button" class="btn btn-outline-danger"
                                                        @click="editModal = false">Discard</button>
                                                        <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Update</button>
                                                </div>
                                            {!! Form::close() !!}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    @can('business-location.index')    
                    <div class="category-table" style="position: relative;padding: 15px;">
                        <table id="facility_table" class="custom-table display nowrap" width="100%">
                            <thead>
                                <tr style="border:none;background-color:skyblue !important;color:white;">
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Landmark</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Country</th>
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
        Alpine.data('sizeList', () => ({
            selectedRows: [],
            searchText: '',
            openModal: false,
            editModal: false,
            editItem() {
                this.editModal = true;
            },
        }));
    });
    $(document).ready(function () {
        $('.btn.buttons-collection.btn-info span').text('Export')
        facility_table = $('#facility_table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [[0, 'desc']],
            ajax: '/business-locations',
            language: {
                    paginate: {
                        previous: '<i class="fa fa-chevron-left"></i>', // Icon for previous page
                        next: '<i class="fa fa-chevron-right"></i>' // Icon for next page
                    },
                },

            columns: [
                { data: 'name', name: 'name' },
                { data: 'mobile', name: 'mobile' },
                { data: 'landmark', name: 'landmark' },
                { data: 'city', name: 'city' },
                { data: 'state', name: 'state' },
                { data: 'country', name: 'country' }
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
            var id = currentRow.data('id');
            
            $(document).on('click', 'button.edit-btn', function() {
                $('button.edit-btn').hide();
                currentRow.css('background-color', '');
                currentRow.css('color', '');
                const edit_url = "{{action('LocationController@show', ['ID'])}}".replace('ID', id);
                $.ajax({
                    method: "GET",
                    url: edit_url,
                    dataType: "json",
                    success: function (result) {
                        $('form#location_edit_form #edit_id').val(result.id);
                        $('form#location_edit_form').find('#name').val(result.name);
                        $('form#location_edit_form').find('#landmark').val(result.landmark);
                        $('form#location_edit_form').find('#city').val(result.city);
                        $('form#location_edit_form').find('#zip_code').val(result.zip_code);
                        $('form#location_edit_form').find('#state').val(result.state);
                        $('form#location_edit_form').find('#country').val(result.country);
                        $('form#location_edit_form').find('#mobile').val(result.mobile);
                        $('form#location_edit_form').find('#tin_number').val(result.tin_number);
                        $('form#location_edit_form').find('#reg_doc_no').val(result.reg_doc_no);
                        $('form#location_edit_form').find('#fax_no').val(result.fax_no);
                        $('form#location_edit_form').find('#alternate_number').val(result.alternate_number);
                        $('form#location_edit_form').find('#email').val(result.email);
                    }
                });
            });
        });
    });
</script>
@endsection