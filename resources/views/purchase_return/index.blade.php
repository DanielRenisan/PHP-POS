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
<div class="animate__animated p-6 no-print no-print" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Purchase Return</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div>
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="mb-5 flex items-center gap-2">
                                @can('purchase-return.create')    
                                    <a href="{{action('PurchaseReturnController@create')}}" class="btn btn-success gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Add New
                                </a>
                                @endcan
                                @can('purchase-return.view')
                                <button type="button" class="btn btn-info gap-2 view-btn" style="display:none;color:#fff">
                                    <i class="fa fa-close-o"></i>
                                    View
                                </button>
                                @endcan
                                @can('purchase-return.cancel')
                                <button type="button" class="btn btn-danger gap-2 delete-button" style="display:none">
                                    <i class="fa fa-close-o"></i>
                                    Cancel
                                </button>
                                @endcan
                            </div>
                        </div>
                        @can('purchase-return.view') 
                            <div  class="flex" style="position: absolute;margin-left: 195px;margin-top: 15px;z-index:9;">
                                {!! Form::text('date_range', null, ['placeholder' => __('Select Date Range'), 'class' => 'form-control', 'id' => 'product_sr_date_filter', 'readonly']); !!}
                            </div>   
                            <div class="category-table"  style="position: relative;">
                                <div class="table-responsive">
                                    <table id="facility_table" class="custom-table display nowrap" style="width:100%">
                                        <thead>
                                            <tr  style="border:none;background-color:skyblue !important;color:white;">
                                                <th></th>
                                                <th>Date</th>
                                                <th>Ref No.</th>
                                                <th>Purchase Ref No.</th>
                                                <th>Supplier</th>
                                                <th>Total Qty.</th>
                                                <th>Total Amt.</th>
                                                <th>status</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<section class="invoice print_section" id="receipt_section">
</section>
@endsection
@section('javascript')
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datepicker/bootstrap-datepicker.min.css') }}">
<script type="text/javascript">
    $(document).ready(function () {
        $('#product_sr_date_filter').daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $('#product_sr_date_filter span').html(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                facility_table.ajax.reload();
            }
        );
        $('#product_sr_date_filter').on('cancel.daterangepicker', function(ev, picker) {
            $('#product_sr_date_filter').html('<i class="fa fa-calendar"></i> {{ __("messages.filter_by_date") }}');
            facility_table.ajax.reload();
        });
        var ajax_url = "{{action('PurchaseReturnController@index')}}";
        facility_table = $('#facility_table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [[0, 'desc']],
            "ajax": {
                "url": ajax_url,
                "data": function ( d ) {
                    var start = $('#product_sr_date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    var end = $('#product_sr_date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    d.start_date = start;
                    d.end_date = end;
                }
            },
            columnDefs: [{
                "targets": [0,6,7],
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
                { data: 'transaction_date', name: 'transaction_date' },
                { data: 'ref_no', name: 'ref_no' },
                { data: 'old_ref_no', name: 'old_ref_no' },
                { data: 'customer', name: 'customer' },
                { data: 'qty', name: 'qty' },
                { data: 'final_total', name: 'final_total' },
                { data: 'status', name: 'status' }
            ],
            "fnDrawCallback": function (oSettings) {
                __currency_convert_recursively($('#facility_table'));
            },
            createdRow: function (row, data, dataIndex) {
                $(row).attr('class', 'clickable_td');
                $(row).attr('data-id', data.id);
            }
        });
        $('#product_sr_date_filter').change(function () {
            facility_table.ajax.reload();
        });
        var searchIDs = [];
        $(document).on('change', '#check-box', function()
        {
            $('button.view-btn').hide();
            if($(this).is(":checked")) 
            {
                searchIDs.push($(this).val());
            }
            else
            {
                searchIDs.splice(searchIDs.indexOf($(this).val()), 1)
            }
            if(searchIDs.length > 1 || searchIDs.length == 0)
            {
                $('button.delete-button').hide(); 
            }
            var id = $(this).val();
            if(searchIDs.length === 1)
            {   
                $('button.delete-button').show();
                $(document).on('click', 'button.delete-button', function() {
                    const delete_url = "{{action('PurchaseReturnController@cancel', ['ID'])}}".replace('ID', id);
                    $.ajax({
                        method: "GET",
                        url: delete_url,
                        dataType: "json",
                        success: function (result) {
                            if (result.success == true) {
                                toastr.error(result.msg);
                                window.location.reload();

                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });
            }
        });
        $(document).on('click', "table#facility_table tbody tr td", function() {
            var currentRow = $(this).closest("tr");
            $('button.view-btn').show();
            $('button.delete-button').hide(); 
            
            var id = currentRow.data('id');
            $(document).on('click', 'button.view-btn', function() {
                const edit_url = "{{action('PurchaseReturnController@show', ['ID'])}}".replace('ID', id);
                window.location.href = edit_url;
            });
	    });
    });
</script>
@endsection