@extends('layouts.app')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
        </ul>
        <div class="pt-5">
            <div class="mb-6 grid grid-cols-1 gap-6 text-white sm:grid-cols-2 xl:grid-cols-4">
                <!-- Users Visit -->
                <div class="panel bg-gradient-to-r from-cyan-500 to-cyan-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">TODAY BOOKING</div>
                        
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">{{$today_booking}}</div>
                        <div class="badge bg-white/30"></div>
                    </div>
                </div>

                <!-- Sessions -->
                <div class="panel bg-gradient-to-r from-violet-500 to-violet-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">TOTAL AMOUNT</div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">{{$total_booking_amount}}</div>
                        <div class="badge bg-white/30"></div>
                    </div>
                </div>

                <!-- Time On-Site -->
                <div class="panel bg-gradient-to-r from-blue-500 to-blue-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">TOTAL CUSTOMER</div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">{{$total_customer}}</div>
                        <div class="badge bg-white/30"></div>
                    </div>
                </div>

                <!-- Bounce Rate -->
                <div class="panel bg-gradient-to-r from-fuchsia-500 to-fuchsia-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">TOTAL CHECKIN</div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">{{$total_booking}}</div>
                        <div class="badge bg-white/30"></div>
                    </div>
                    <div class="mt-5 flex items-center font-semibold">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                            <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="currentColor" stroke-width="1.5"></path>
                            <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="mb-6 grid gap-6 xl:grid-cols-2">
                <div class="panel h-full">
                    <div class="mb-5 flex items-center dark:text-white-light">
                    </div>
                    
                    <div class="relative overflow-hidden">
                    @foreach($rooms as $room)
                        @php
                            $assign = App\Models\RoomAssign::find($room->id);
                            $back_class = '#478778';
                            $size = 'color:white;font-weight:bold;text-align:center;';
                            $title = 'Avaialable';
                            if($assign->status == 1)
                            {
                                $back_class = '#3b82f6 var(--tw-gradient-from-position)';
                                $title = 'Booked';
                            }
                            if($assign->status == 2)
                            {
                                $back_class = '#22d3ee var(--tw-gradient-to-position)';
                                $title = 'Checkin';
                            }
                            if($assign->status == 3)
                            {
                                $back_class = 'black';
                                $title = 'Blocked';
                            }
                        @endphp
                        <div @if($assign->status == 2) onclick='window.open("{{action('CheckoutController@index')}}", "_blank");' @elseif($assign->status == 0 || $assign->status == 1 ) onclick='window.open("{{action('CheckinController@create', ['room_no' => $room->room_id, 'room_type' => $room->room_type])}}", "_blank");'@endif class="col-sm-1" title="{{$title}}" style="{{$size}}border-style: solid;border-width: thin;background:{{$back_class}}">{{$room->room_id}}</div>
                    @endforeach
                    </div>
                </div>
                <div class="panel h-full xl:col-span-1" style="height:250px;">
                    <h3>Today Checkouts</h3>
                    <div class="mb-5 flex items-center dark:text-white-light">
                    </div>
                    
                    <div class="relative overflow-hidden">
                        <div class="">
                            <table class="table-hover whitespace-nowrap dataTable-table" id="booking_table">
                                <thead>
                                    <tr>
                                        <th>Booking No</th>
                                        <th>Rooms</th>
                                        <th>Name</th>
                                        <th>Check Out</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-6 grid gap-6">
                <div class="panel h-full">
                    <div class="row">
                        <form action="{{action('Auth\LoginController@dashboard')}}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div x-data="form">
                                    <input id="basic" x-model="date1" class="form-input flatpickr-input active" name="start_date" type="text" readonly="readonly" value="">
                                    </div>
                                </div>
                            </div>    
                        </form>
                    </div>
                    
                    <div class="relative overflow-hidden">
                        <div style="height:250px; overflow: auto;">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Rooms</th>
                                        @php
                                            $dt = request()->get('start_date') ? strtotime(request()->get('start_date')) : strtotime(date("Y-m-d"));
                                            $end_time = request()->get('end_date') ? strtotime(request()->get('end_date')) : strtotime("+1 week", $dt);
                                        @endphp
                                        @for($i=$dt; $i<$end_time; $i+=86400)
                                            <th style="white-space:nowrap;text-align:center;font-size:16px;font-weight:bold;">{{date("Y-m-d", $i)}}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rooms as $room)
                                    <tr>
                                        <td style="font-size:16px;font-weight:bold;">{{$room->room_id}}</td>
                                        @for($i=$dt; $i<$end_time; $i+=86400)
                                            @php
                                            $room_booked = App\Models\BookingRoom::join('transactions', 'booking_rooms.transaction_id', '=', 'transactions.id')
                                            ->join('room_assigns', 'room_assigns.room_id', '=', 'booking_rooms.room_no')
                                            ->where('booking_rooms.room_no', $room->room_id)
                                            ->whereIn('room_assigns.status', [1,2])
                                            ->whereIn('transactions.type', ['checkin','booking'])
                                            ->where('transactions.status', '!=', 'canceled')
                                            ->select(
                                                'booking_rooms.id',
                                                'booking_rooms.transaction_id',
                                                'booking_rooms.check_in_at',
                                                'booking_rooms.check_out_at',
                                                'transactions.type',
                                                )
                                            ->first();
                                            @endphp
                                            @if(isset($room_booked) && date('Y-m-d', strtotime($room_booked->check_in_at)) <= date("Y-m-d", $i) && date('Y-m-d', strtotime($room_booked->check_out_at)) >= date("Y-m-d", $i))
                                                @if($room_booked->type == 'booking')
                                                    <td  style="text-align:center;background:#3b82f6 var(--tw-gradient-from-position);color:white;font-weight:bold;text-align:center;">
                                                        <a href="#" class="btn-modal" data-container=".edit_modal"
                                                        data-href="{{action('DashboardController@editBook',[$room_booked->transaction_id])}}"  title="CHECKIN"><i class="fa fa-check-square-o"  style="color:white;font-size:18px;"></i></a>
                                                    </td>
                                                @elseif($room_booked->type == 'checkin')
                                                    <td style="text-align:center;background:#22d3ee var(--tw-gradient-to-position);color:white;font-weight:bold;text-align:center;">
                                                        <a  href="#" class="btn-modal" data-container=".checkout_modal"
                                                        data-href="{{action('DashboardController@quickOut', ['room_no' =>  $room_booked->transaction_id])}}" title="CHECKOUT"><i class="fa fa-sign-out"  style="color:white;font-size:18px;"></i></a>
                                                        &nbsp;&nbsp;
                                                        <a  href="{{action('HomeController@expense',['id' =>  $room_booked->transaction_id])}}" target="_blank" class="view-expense" title="EXPENSE"><i class="fa fa-money"  style="color:white;font-size:18px;"></i></a>
                                                    </td>    
                                                @endif
                                            @else
                                                @if($room->status == 3)
                                                <td style="text-align:center;background:black;color:white;font-weight:bold;text-align:center;">
                                                <i class="fa fa-minus-square-o" style="color:white;font-size:20px;"></i>
                                                </td>
                                                @else
                                                <td style="text-align:center;background:#478778;color:white;font-weight:bold;text-align:center;">
                                                    <div class="btn-group" role="group" aria-label="...">
                                                        <a  href="#" class="btn-modal" title="Booking" 
                                                        data-href="{{action('DashboardController@getChk', ['room_no' => $room->room_id, 'room_type' => $room->room_type])}}" 
                                                        data-container=".booking_modal">
                                                        <i class="fa fa-calendar-check-o"   
                                                        style="color:white;font-size:20px;"></i></a>
                                                        &nbsp;&nbsp;
                                                        <a  href="#" class="btn-modal" data-container=".checkin_modal"
                                                        data-href="{{action('DashboardController@quickChk', ['room_no' => $room->room_id, 'room_type' => $room->room_type])}}" class="btn-modal" title="CHECKIN"><i class="fa fa-check-square-o"   style="color:white;font-size:20px;"></i></a>
                                                    </div>
                                                </td>
                                                @endif
                                            @endif
                                        @endfor
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="mb-6 grid gap-6 xl:grid-cols-1">
                <div class="panel h-full xl:col-span-2">
                    <div class="mb-5 flex items-center dark:text-white-light">
                    </div>
                    
                    <div class="relative overflow-hidden">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div> -->
            <div class="mb-6 grid gap-6 xl:grid-cols-3">
                <div class="panel h-full">
                    <div class="mb-5 flex items-center">
                        <h5 class="text-lg font-semibold dark:text-white-light">Total Booking History</h5>
                    </div>
                    <div class="overflow-hidden">
                      <canvas id="history" height="100px"></canvas>
                    </div>
                </div>
                <div class="panel h-full xl:col-span-2">
                    <div class="mb-5 flex items-center dark:text-white-light">
                    </div>
                    
                    <div class="relative overflow-hidden">
                    <canvas id="myChart" height="100px"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <!-- Recent Transactions -->
                <div class="panel">
                    <h3>Rooms</h3>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="ltr:rounded-l-md rtl:rounded-r-md">Type</th>
                                    <th>Available</th>
                                    <th>Booked</th>
                                    <th>Checkin</th>
                                    <th class="text-center ltr:rounded-r-md rtl:rounded-l-md">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($types as $type)
                                @php 
                                $total = App\Models\RoomAssign::where('room_type', $type->name)->count();
                                $total_checkin = App\Models\RoomAssign::where('room_type', $type->name)->where('status', 2)->count();
                                $total_booked = App\Models\RoomAssign::where('room_type', $type->name)->where('status', 1)->count();
                                $total_avaialbe = App\Models\RoomAssign::where('room_type', $type->name)->where('status', 0)->count();
                                @endphp
                                <tr>
                                    <td class="font-semibold">{{$type->name}}</td>
                                    <td class="whitespace-nowrap">{{ $total_avaialbe }}</td>
                                    <td class="whitespace-nowrap">{{ $total_booked }}</td>
                                    <td>{{ $total_checkin }}</td>
                                    <td class="text-center">
                                    {{ $total }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel h-full xl:col-span-1">
                    <h3>Customer Due</h3>
                    <div class="relative overflow-hidden">
                    <div class="table-responsive">
                        <table class="table-hover whitespace-nowrap dataTable-table" id="payment_dues_table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Due</th>
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
<div class="modal fade" id="view_expense_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade booking_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade checkin_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade checkout_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade edit_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>
@endsection
@section('javascript')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<script src="{{ asset('assets/js/alpine-collaspe.min.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/alpine-persist.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-ui.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-focus.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine.min.js?v=' . $asset_v) }}"></script>  
<script type="text/javascript">
    var start = "{{request()->get('start_date') ? request()->get('start_date') : date('Y-m-d')}}";
    var end = "{{request()->get('end_date') ? request()->get('end_date') : date('Y-m-d')}}";
    document.addEventListener("alpine:init", () => {
        Alpine.data("form", () => ({
            date1: start,
            date2: end,
            init() {
                flatpickr(document.getElementById('basic'), {
                    dateFormat: 'Y-m-d',
                    defaultDate: this.date1,
                })
            }
        }));
    });
    $(document).ready(function () {
        booking_table = $('#booking_table').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
            info: false,
            lengthChange : false,
            buttons:[],
            "ajax": {
                "url": "/today-checkouts",
                "data": function ( d ) {
                }
            },
            columnDefs: [ {
                "targets": 3,
                "orderable": false,
                "searchable": false
            } ],
            columns: [
                { data: 'ref_no', name: 'bookings.ref_no'  },
                { data: 'room', name: 'room'},
                { data: 'customer', name: 'customers.first_name'},
                { data: 'check_out_at', name: 'check_out_at'},
                { data: 'type', name: 'type'},
            ],
            "fnDrawCallback": function (oSettings) {
                __currency_convert_recursively($('#booking_table'));
            }
        });
        var payment_dues_table = $('#payment_dues_table').DataTable({
					processing: true,
					serverSide: true,
					ordering: false,
					searching: false,
					dom: 'tirp',
					buttons:[],
					ajax: '/payment-due',
					"fnDrawCallback": function (oSettings) {
			            __currency_convert_recursively($('#payment_dues_table'));
			        }
			    });
        var calendar = $('#calendar').fullCalendar({
            editable:true,
            header:{
                left:'prev,next today',
                center:'title',
                right:'month,agendaWeek,agendaDay'
            },
            events:'/get-calender',
            selectable:true,
            selectHelper: true,
            eventRender: function(event, element) {
                if(event.type == "booking") {
                    element.css('background-color', '#3b82f6 var(--tw-gradient-from-position)');
                }
                if(event.type == "checkin") {
                    element.css('background-color', '#22d3ee var(--tw-gradient-to-position)');
                }
                if(event.type == "checkout") {
                    element.css('background-color', 'blue');
                }
            }
        });
    });
      var reservation =  <?php echo $reservation; ?>;
      const data = {
        labels: ['JAN','FEB', "MAR", 'APR', 'MAY', 'JUN', 'JULY', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
        datasets: [{
          label: 'Reservations',
          backgroundColor: '#478778',
          borderColor: '#478778',
          data: reservation,
        }]
      };
  
      const config = {
        type: 'line',
        data: data,
        options: {
          scales: {
            y: {
               
            }
        }
        }
      };
  
      const myChart = new Chart(
        document.getElementById('myChart').getContext("2d"),
        config
      );

      var dataas =  <?php echo $data; ?>;
      const data1 = {
        labels: ['Checkin','Checkout','Pending'],
        datasets: [{
          label: 'History',
          backgroundColor: ['#3b82f6 ', '#22d3ee', '#478778'],
          borderColor: ['#3b82f6 ', '#22d3ee', '#478778'],
          data: dataas,
        }]
      };
      const config1 = {
        type: 'doughnut',
        data: data1,
        options: {
          scales: {
            y: {
               
            }
        }
        }
      };
      const history = new Chart(
        document.getElementById('history').getContext("2d"),
        config1
      );
        $(document).on('click', 'a.view-expense', function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr("href"),
                dataType: "html",
                success: function (result) {
                    $('#view_expense_modal').html(result).modal('show');
                }
            });
        });
        $(document).on('change', '#basic', function (e) {
            e.preventDefault();
            var dat = $(this).val();
            window.location.href = "{{action('Auth\LoginController@dashboard', ['start_date' =>'START','end_date' => 'END'])}}".replace('START', dat).replace('END', dat);
        });
  
</script>
@endsection