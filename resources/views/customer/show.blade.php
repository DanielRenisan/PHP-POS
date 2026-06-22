@extends('layouts.app')

@section('content')
<div class="animate__animated p-6   no-print" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li>
                <a href="{{action('CustomerController@index')}}" class="text-primary hover:underline">Customer</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Customer Detail</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-semibold dark:text-white-light">Customer Detail</h3>
                </div>
                <div class="my-4 h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                
            </div>
            <div class="mb-6 grid gap-6 xl:grid-cols-3">
                @php 
                    $transaction_ids = $transactions->pluck('id')->toArray();
                    $final_total =  $transactions->sum('final_total');
                    $paid_amount = App\Models\TransactionPayment::whereIn('transaction_id', $transaction_ids)->sum('amount');
                    $balance = $final_total - $paid_amount;
                @endphp
                <div class="panel h-full  xl:col-span-2">
                    <div class="mb-5 flex items-center">
                        <h4>Customer Details</h4>
                    </div>
                    <div class="overflow-hidden">
                        <table class="table" style="border-style: none">
                            <tr>
                                <th>Name</th>
                                <td>{{ $customer->first_name .' '. $customer->last_name }}</td>
                                <th>Date Of Birth</th>
                                <td>{{ $customer->dob }}</td>
                            </tr>
                            <tr>
                                <th>Email ID</th>
                                <td>{{ $customer->email }}</td>
                            </tr>
                            <tr>
                                <th>Mobile No</th>
                                <td>{{ $customer->contact_no }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $customer->address }}</td>
                            </tr>
                            <tr>
                                <th>Nationality</th>
                                <td>{{ $customer->nationality }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="panel h-full">
                    <div class="mb-5 flex items-center">
                        <h4>Payment Details</h4>
                    </div>
                    <div class="overflow-hidden">
                        <table class="table" style="border-style: none">
                            <tr>
                                <th>Total Amount</th>
                                <td>{{ number_format($final_total, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <td>{{ number_format($paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                            <tr>
                                <th>Due Amount</th>
                                <td>{{ number_format($balance, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mb-6 grid gap-6 xl">
                <div class="panel h-full">
                    <div class="mb-5 flex items-center">
                        <h4>Transaction Details</h4>
                    </div>
                    <div class="overflow-hidden">
                        <table class="table-hover whitespace-nowrap dataTable-table" id="transaction_table">
                            <thead>
                                <tr>
                                    <th>Booking No</th>
                                    <th>Room Type</th>
                                    <th>Room No</th>
                                    <th>Name</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Total Amount</th>
                                    <th>Total Paid</th>
                                    <th>Total Due</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready( function(){
            var id = "{{$customer->id}}";
            transaction_table = $('#transaction_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [[0, 'desc']],
                "ajax": {
                    "url": "/customers/show/"+id,
                    "data": function ( d ) {
                    }
                },
                columnDefs: [ {
                    "targets": 5,
                    "orderable": false,
                    "searchable": false
                } ],
                columns: [
                    { data: 'ref_no', name: 'bookings.ref_no'  },
                    { data: 'type', name: 'booking_rooms.room_type'},
                    { data: 'room', name: 'booking_rooms.room_no'},
                    { data: 'customer', name: 'customers.first_name'},
                    { data: 'check_in_at', name: 'check_in_at'},
                    { data: 'check_out_at', name: 'check_out_at'},
                    { data: 'status', name: 'status'},
                    { data: 'payment_status', name: 'payment_status'},
                    { data: 'final_total', name: 'final_total'},
                    { data: 'paid_amount', name: 'paid_amount'},
                    { data: 'due_amount', name: 'due_amount'}
                ],
                "fnDrawCallback": function (oSettings) {
                    __currency_convert_recursively($('#transaction_table'));
                },
                createdRow: function( row, data, dataIndex ) {
                    $( row ).find('td:eq(4)').attr('class', 'clickable_td');
                }
            });
        });
    </script>
@endsection