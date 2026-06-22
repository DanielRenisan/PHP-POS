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
                <span>Order List</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div x-data="sizeList">
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5">
                        <div style="display:flex;gap: 0.875rem;">
                            <button class="btn btn-info gap-2 view-btn"  style="display:none;color:#fff">
                                <i class="fa fa-eye"></i>
                                View
                            </button>
                        </div>
                        <div  class="flex" style="position: absolute;margin-left: 195px;margin-top: 15px;z-index:9;">
                            {!! Form::text('date_range', null, ['placeholder' => __('Select Date Range'), 'class' => 'form-control', 'id' => 'product_sr_date_filter', 'readonly']); !!}
                        </div>
                        <div class="category-table" style="position: relative;">
                            <div class="table-responsive">
                                <table id="facility_table" class="custom-table display nowrap" style="width:100%">
                                    <thead>
                                        <tr style="border:none;background-color:skyblue !important;color:white;">
                                            <th>Invoice No</th>
                                            <th>Order Type</th>
                                            <th>Customer</th>
                                            <th>Staff</th>
                                            <th>Status</th>
                                            <th>Total Quantity</th>
                                            <th>Total Amount</th>
                                            <th>Payment status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
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
            }
        );
        $('#product_sr_date_filter').on('cancel.daterangepicker', function(ev, picker) {
            $('#product_sr_date_filter').html('<i class="fa fa-calendar"></i> {{ __("messages.filter_by_date") }}');
            facility_table.ajax.reload();
        });
        var ajax_url = "{{action('SaleController@order')}}";
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
                "targets": [6,7],
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
                { data: 'incoice_no', name: 'incoice_no' },
                { data: 'orderType', name: 'orderType' },
                { data: 'customer', name: 'customer' },
                { data: 'staff', name: 'staff' },
                { data: 'status', name: 'status' },
                { data: 'total_qty', name: 'total_qty' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'payment_status', name: 'payment_status' }
            ],
            "fnDrawCallback": function (oSettings) {
                __currency_convert_recursively($('#facility_table'));
            },
            createdRow: function (row, data, dataIndex) {
                $(row).attr('class', 'clickable_td');
                $(row).attr('data-id', data.id);
                $(row).attr('data-status', data.status)
            }
        });
        $('#product_sr_date_filter').change(function () {
            facility_table.ajax.reload();
        });
        $(document).on('dblclick', 'tr.clickable_td', function() {
            const id = $(this).data('id');
            const show_url = "{{action('SaleController@show', ['ID'])}}".replace('ID', id);
            window.location.href = show_url;
        });
        $(document).on('click', "table#facility_table tbody tr td", function() {
            $('table#facility_table tbody tr').css('background-color', '');
            $('table#facility_table tbody tr').css('color', '');
            var currentRow = $(this).closest("tr");
            var status = currentRow.data('status');
            $('button.view-btn').show();
            currentRow.css('background-color', '#87ceeb');
            currentRow.css('color', 'white');
            if(status !== 'paid')
            
            var id = currentRow.data('id');
            $(document).on('click', 'button.view-btn', function() {
                currentRow.css('background-color', '');
                currentRow.css('color', '');
                $('button.view-btn').hide();
                $('button.add-payment-btn').hide();
                const show_url = "{{action('SaleController@show', ['ID'])}}".replace('ID', id);
                    window.location.href = show_url;
            });
	    });
        
    });
</script>
@endsection

