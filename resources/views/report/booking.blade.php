@extends('layouts.app')

@section('content')
<div class="animate__animated p-6 no-print" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Booking Report</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-semibold dark:text-white-light">Booking Report</h3>
                </div>
                <!-- <form action="#" method="get"> -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('customer_id', __('Customer') . ':') !!}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    {!! Form::select('customer_id', $customers, null, ['class' => 'form-input select2', 'placeholder' => __('Please Select'), 'required']); !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('type', __('Type') . ':') !!}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-th-large"></i>
                                    </span>
                                    {!! Form::select('type', ['booking' => 'Booked', 'checkin' => 'Checked', 'checkout' => 'Checkout'], null, ['class' => 'form-input select2', 'placeholder' => __('Please Select'), 'required']); !!}
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="form-group">
                                <lable>&nbsp;&nbsp;&nbsp;&nbsp; <lable>
                                <button type="submit" class="btn btn-block btn-primary">Export</button>
                            </div>
                        </div> -->
                    </div>
                <!-- </form> -->
                <div class="my-4 h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                <div class="mb-5">
                    <div class="table-responsive">
                        <table class="table-hover whitespace-nowrap dataTable-table" id="booking_table">
                            <thead>
                                <tr>
                                    <th>Booking No</th>
                                    <th>Rooms</th>
                                    <th>Customer</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Payment Status</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Due Amount</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="view_booking_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>
</section>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready( function(){
            booking_table = $('#booking_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [[0, 'desc']],
                "ajax": {
                    "url": "/reports/booking",
                    "data": function ( d ) {
                        d.customer_id = $('#customer_id').val();
                        d.type = $('#type').val();
                    }
                },
                columnDefs: [ {
                    "targets": 5,
                    "orderable": false,
                    "searchable": false
                } ],
                columns: [
                    { data: 'ref_no', name: 'bookings.ref_no'  },
                    { data: 'room', name: 'room'},
                    { data: 'customer', name: 'customers.first_name'},
                    { data: 'check_in_at', name: 'check_in_at'},
                    { data: 'check_out_at', name: 'check_out_at'},
                    { data: 'status', name: 'status'},
                    { data: 'type', name: 'type'},
                    { data: 'payment_status', name: 'payment_status'},
                    { data: 'final_total', name: 'final_total'},
                    { data: 'total_paid', name: 'total_paid'},
                    { data: 'due', name: 'due'}
                ],
                "fnDrawCallback": function (oSettings) {
                    __currency_convert_recursively($('#booking_table'));
                }
            });

            $('#customer_id, select#type').change( function(){
                booking_table.ajax.reload();
            });
        });
    </script>
@endsection