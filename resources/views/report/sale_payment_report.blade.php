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
                <span>Payment Receive Report</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div>
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5" x:data="sizeList">
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
                                        <h6>Total Sales</h6>
                                    </div>
                                    <div class="bottom">
                                        <h2><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true" id="total_sales">{{number_format($summaryForHeader->total_sales, 2)}}</span></h2>
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
                                        <h6>Restaurant Sales</h6>
                                    </div>
                                    <div class="bottom">
                                        <h2><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true" id="totalRestSales">{{number_format($summaryForHeader->totalRestSales, 2)}}</span></h2>
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
                                        <h6>Hotel Sales</h6>
                                    </div>
                                    <div class="bottom">
                                        <h2><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true" id="totalHotelSales">{{number_format($summaryForHeader->totalHotelSales, 2)}}</span></h2>
                                    </div>
                                    <!-- <p class="m-0">Total Sales for this Month</p> -->
                                </div>
                            </div>
                        </div>
                        <div  class="flex" style="position: absolute;margin-left: 195px;margin-top: 28px;z-index:9;">
                            {!! Form::text('date_range', null, ['placeholder' => __('Select Date Range'), 'class' => 'form-control', 'id' => 'product_sr_date_filter', 'readonly']); !!}
                        </div>
                        <div  class="flex" style="position: absolute;margin-left: 395px;margin-top: 28px;z-index:9;">
                            <select name="location" class="form-input" width="25%" id="select-department">
                                <option value="">Method</option>
                                <option value="cash" {{request()->get('method') == 'cash' ? 'selected' : ''}}>Cash</option>
                                <option value="card" {{request()->get('method') == 'card' ? 'selected' : ''}}>Card</option>
                                <option value="cheque" {{request()->get('method') == 'cheque' ? 'selected' : ''}}>Cheque</option>
                                <option value="bank_transfer" {{request()->get('method') == 'bank_transfer' ? 'selected' : ''}}>Bank Transfer</option>
                                <option value="credit" {{request()->get('method') == 'credit' ? 'selected' : ''}}>Credit</option>
                            </select>    
                        </div>
                        <!-- <div  class="flex" style="position: absolute;margin-left: 525px;margin-top: 28px;z-index:9;">
                            <select name="location" class="form-input" width="25%" id="select-salestype">
                                <option value="">Sales Type</option>
                                <option value="hotelsale" {{request()->get('salestype') == 'hotelsale' ? 'selected' : ''}}>Hotel Sales</option>
                                <option value="restsale" {{request()->get('salestype') == 'restsale' ? 'selected' : ''}}>Restaurant Sales</option>
                            </select>    
                        </div> -->
                        <div class="category-table" style="position: relative;padding: 15px;">
                            <div class="table-responsive">
                                <table id="facility_table" class="custom-table display nowrap" style="width:100%">
                                    <thead>
                                    <tr style="border:none;background-color:skyblue !important;color:white;">
                                            <th>Date Time</th>
                                            <th>Location</th>
                                            <th>Employee</th>
                                            <th>Reference No</th>
                                            <th>Invoice No</th>
                                            <th>Customer</th>
                                            <th>Payment Method</th>
                                            <th>Amount</th>
                                            <th>Status</th>
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
        var ajax_url = "{{action('SaleReportController@paymentDetailReport')}}";
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
                    d.method = $('select#select-department').val();
                    d.salestype = $('select#select-salestype').val();
                }
            },
            columnDefs: [{
                "targets": [7,8],
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
                { data: 'transaction_date', name: 'transaction_date' },
                { data: 'location', name: 'location' },
                { data: 'employee', name: 'employee' },
                { data: 'ref_no', name: 'ref_no' },
                { data: 'incoice_no', name: 'incoice_no' },
                { data: 'customer', name: 'customer' },
                { data: 'method', name: 'method' },
                { data: 'amount', name: 'amount' },
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
        $('#select-department, #select-employee, #product_sr_date_filter, #select-salestype').change(function () {
            facility_table.ajax.reload();
            salesRepresentativeTotalSales();
        });
        

        function salesRepresentativeTotalSales() {
            var start = $('#product_sr_date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var end = $('#product_sr_date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD');
            var method = $('#select-department').val();
            var salestype = $('#select-salestype').val();
            
            var data_expense = {
                start_date: start,
                end_date: end,
                get_totals: true  // This is the key parameter
            };
            
            // Only add method if it has a value
            if (method && method !== '') {
                data_expense.method = method;
            }
            
            // Add salestype if it has a value
            if (salestype && salestype !== '') {
                data_expense.salestype = salestype;
            }
            
            // Use the existing paymentDetailReport endpoint
            var sale_url = "{{action('SaleReportController@paymentDetailReport')}}";
            
            $.ajax({
                method: "GET",
                url: sale_url,
                dataType: "json",
                data: data_expense,
                success: function (data) {
                    console.log('Response:', data);
                    if (data.totals) {
                        $('span#total_sales').html(__currency_trans_from_en(data.totals.total_sales, true));
                        $('span#totalRestSales').html(__currency_trans_from_en(data.totals.totalRestSales, true));
                        $('span#totalHotelSales').html(__currency_trans_from_en(data.totals.totalHotelSales, true));
                    } else {
                        console.error('No totals found in response');
                        // Show error or default values
                        $('span#total_sales').html('0.00');
                        $('span#totalRestSales').html('0.00');
                        $('span#totalHotelSales').html('0.00');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    console.error('Response:', xhr.responseText);
                    // Show error message or default values
                    $('span#total_sales').html('Error');
                    $('span#totalRestSales').html('Error');
                    $('span#totalHotelSales').html('Error');
                }
            });
        }
    
        $(document).on('click', "table#facility_table tbody tr td", function() {
            $('table#facility_table tbody tr').css('background-color', '');
            $('table#facility_table tbody tr').css('color', '');
            var currentRow = $(this).closest("tr");
            var status = currentRow.data('status');
            $('button.view-btn').show();
            $('button.edit-btn').show();
            currentRow.css('background-color', '#87ceeb');
            currentRow.css('color', 'white');
            var id = currentRow.data('id');
            const view_url = "{{action('TransactionPaymentController@show', ['ID'])}}".replace('ID', id);
            window.location.href = view_url.toString().replace('&amp;','&');
        });
    });
    
</script>
@endsection
