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
                <span>Expenses</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div>
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5">
                        <div style="justify-content: center; align-items: center;">
                            <div class="total-details grid grid-cols-3 gap-4 mb-5" style="width: 100%;">
                                <div class="box" style="padding: 10px;">
                                    <div class="top flex items-center gap-2">
                                        <div
                                            class="grid h-9 w-9 place-content-center rounded-full  dark:bg-success dark:text-success-light"   style="background:skyblue;color:#fff;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                                                    transform="rotate(-45 8.60699 8.87891)" stroke="currentColor"
                                                    stroke-width="1.5" />
                                                <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                        <h6>Total Amount</h6>
                                    </div>
                                    <div class="bottom">
                                        <h2><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true" id="total_hand">{{number_format($transactions->sum('final_total'), 2)}}</span></h2>
                                    </div>
                                    <!-- <p class="m-0">Total Sales for this Month</p> -->
                                </div>
                                <div class="box" style="padding: 10px;">
                                    <div class="top flex items-center gap-2">
                                        <div
                                            class="grid h-9 w-9 place-content-center rounded-full  dark:bg-success dark:text-success-light"   style="background:skyblue;color:#fff;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                                                    transform="rotate(-45 8.60699 8.87891)" stroke="currentColor"
                                                    stroke-width="1.5" />
                                                <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                        <h6>Total Paid</h6>
                                    </div>
                                    <div class="bottom">
                                        <h2><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true" id="total_sale">{{number_format($transactions->sum('amount_paid'), 2)}}</span></h2>
                                    </div>
                                    <!-- <p class="m-0">Total Sales for this Month</p> -->
                                </div>
                                <div class="box" style="padding: 10px;">
                                    <div class="top flex items-center gap-2">
                                        <div
                                            class="grid h-9 w-9 place-content-center rounded-full  dark:bg-success dark:text-success-light"   style="background:skyblue;color:#fff;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                                                    transform="rotate(-45 8.60699 8.87891)" stroke="currentColor"
                                                    stroke-width="1.5" />
                                                <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                        <h6>Total Due</h6>
                                    </div>
                                    <div class="bottom">
                                        <h2><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true" id="total_remain">{{number_format($transactions->sum('due'), 2)}}</span></h2>
                                    </div>
                                    <!-- <p class="m-0">Total Sales for this Month</p> -->
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="mb-5 flex items-center gap-2">
                                @can('expense.create')    
                                    <a href="{{action('ExpenseController@create')}}" class="btn btn-success gap-2" @click="openModal = true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Add New
                                </a>
                                @endcan
                                @can("expense.update")
                                <button class="btn btn-primary gap-2 edit-btn" style="display: none;">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </button>
                                @endcan
                                @can('expense.delete')
                                    <button type="button" class="btn btn-danger gap-2 delete-button" style="display: none;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round"></path>
                                            <path
                                                d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                            <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round"></path>
                                            <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round"></path>
                                            <path opacity="0.5"
                                                d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                                stroke="currentColor" stroke-width="1.5"></path>
                                        </svg>
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    @can('expense.view')    
                    <div  class="flex" style="position: absolute;margin-left: 195px;margin-top: 15px;z-index:9;">
                        {!! Form::text('date_range', null, ['placeholder' => __('Select Date Range'), 'class' => 'form-control', 'id' => 'product_sr_date_filter', 'readonly']); !!}
                    </div>    
                    <div class="category-table"  style="position: relative;">
                        <div class="table-responsive">
                            <table id="facility_table" class="custom-table display nowrap" style="width:100%">
                                <thead>
                                    <tr  style="border:none;background-color:skyblue !important;color:white;">
                                    <th>
                                    </th>
                                    <th>Date</th>
                                    <th>Ref No</th>
                                    <th>Expense Type</th>
                                    <th>Expense For</th>
                                    <th>Category</th>
                                    <th  class="whitespace-nowrap">Total Amount</th>
                                    <th  class="whitespace-nowrap">Paid</th>
                                    <th  class="whitespace-nowrap">Due</th>
                                    <th>Payment Status</th>
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
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datepicker/bootstrap-datepicker.min.css') }}">
<script type="text/javascript">
    $(document).ready(function () {
        $('#product_sr_date_filter').daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $('#product_sr_date_filter span').html(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                facility_table.ajax.reload();
                updateSummaryCards();
            }
        );
        $('#product_sr_date_filter').on('cancel.daterangepicker', function(ev, picker) {
            $('#product_sr_date_filter').html('<i class="fa fa-calendar"></i> {{ __("messages.filter_by_date") }}');
            facility_table.ajax.reload();
            updateSummaryCards();
        });
        var ajax_url = "{{action('ExpenseController@index')}}";
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
                "targets": [0,6,7,8,9],
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
                { data: 'expense_type', name: 'expense_type' },
                { data: 'expense_for', name: 'expense_for' },
                { data: 'category', name: 'category' },
                { data: 'final_total', name: 'final_total' },
                { data: 'amount_paid', name: 'amount_paid' },
                { data: 'due', name: 'due' },
                { data: 'payment_status', name: 'payment_status' }
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
            updateSummaryCards();
        });

        function updateSummaryCards() {
            var start = $('#product_sr_date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var end = $('#product_sr_date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD');

            var data = {
                start_date: start,
                end_date: end,
            };

            // Fix: Add the correct URL for the summary endpoint
            var sale_url = "{{action('ExpenseController@getSummaryData')}}";
            $.ajax({
                method: "GET",
                url: sale_url,
                dataType: "json",
                data: data,
                success: function (data) {
                    $('span#total_hand').html(__currency_trans_from_en(data.total_hand, true));
                    $('span#total_sale').html(__currency_trans_from_en(data.total_sale, true));
                    $('span#total_remain').html(__currency_trans_from_en(data.total_remain, true));
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching summary data:', error);
                }
            });
        }

        var searchIDs = [];
        $(document).on('change', '#check-box', function()
        {
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
                $('button.edit-btn').hide();
            }
            var id = $(this).val();
            if(searchIDs.length === 1)
            {   
                $('button.delete-button').show();
                $('button.edit-btn').hide();
                $(document).on('click', 'button.delete-button', function() {
                    const delete_url = "{{action('TransactionController@destroy')}}";
                    $.ajax({
                        method: "GET",
                        url: delete_url,
                        dataType: "json",
                        data: {
                            ids : searchIDs
                        },
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
            $('table#facility_table tbody tr').css('background-color', '');
            $('table#facility_table tbody tr').css('color', '');
            var currentRow = $(this).closest("tr");
            $('button.edit-btn').show();
            $('button.delete-button').hide(); 
            currentRow.css('background-color', '#87ceeb');
            currentRow.css('color', 'white');
            var id = currentRow.data('id');
            $(document).on('click', 'button.edit-btn', function() {
                currentRow.css('background-color', '');
                currentRow.css('color', '');
                $('button.edit-btn').hide();
                const show_url = "{{action('ExpenseController@edit', ['ID'])}}".replace('ID', id);
                window.location.href = show_url;
            });
	    });
    });
</script>
@endsection