<div class="modal-dialog modal-xl" role="document">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      <h4 class="modal-title" id="modalTitle">{{$booking->ref_no}}</h4>
	    </div>
	    <div class="modal-body">
        @if(isset($booking))
            <input type="hidden" name="booking_id" value="{{$booking->id ?? '' }}">
            <input type="hidden" name="transaction_id" value="{{$transaction->id ?? '' }}">
                <div class="mb-6 grid gap-6 xl:grid-cols-3">
                    <div class="panel h-full">
                        <div class="mb-5 flex items-center">
                            <h4>Customer Details</h4>
                        </div>
                        <div class="overflow-hidden">
                            <table class="table" style="border-style: none">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $booking->customer->first_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Email ID</th>
                                    <td>{{ $booking->customer->email ?? ''}}</td>
                                </tr>
                                <tr>
                                    <th>Mobile No</th>
                                    <td>{{ $booking->customer->contact_no ?? ''}}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $booking->customer->address ?? ''}}</td>
                                </tr>
                                <tr>
                                    <th>Nationality</th>
                                    <td>{{ $booking->customer->nationality ?? ''}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="panel h-full xl:col-span-2">
                        <div class="mb-5 flex items-center">
                            <h4>Booking Details</h4>
                        </div>
                        
                        <div class="relative overflow-hidden">
                            <table class="table" style="border-style: none">
                                <tr>
                                    <th>Checkin At</th>
                                    <td>{{ $booking->check_in_at}}</td>
                                    <th>Booking No</th>
                                    <td>{{ $booking->ref_no}}</td>
                                </tr>
                                <tr>
                                    <th>Checkout At</th>
                                    <td>{{ $booking->check_out_at }}</td>
                                    <th>Purpose</th>
                                    <td>{{ $booking->purpose}}</td>
                                </tr>
                                <tr>
                                    <th>Arival From</th>
                                    <td>{{ $booking->arival_from }}</td>
                                    <th></th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Booking Type</th>
                                    <td>{{ $booking->type->name  ?? ''}}</td>
                                    <th></th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Booking Source</th>
                                    <td>{{ $booking->source ? $booking->source->name : '' }}</td>
                                    <th></th>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            @if($room->count() > 0 )
                <div class="mb-6 grid gap-6 xl:grid-cols-1">
                    <div class="panel h-full">
                        <div class="mb-5 flex items-center">
                            <h4>Room Details</h4>
                        </div>
                        <div class="overflow-hidden">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="20%">Room No.</th>
                                                <th  width="20%">Date</th>
                                                <th colspan="8"  width="60%">Room Rent Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($room as $sin_room)
                                            <tr>
                                                <td>
                                                    <span style="font-weight:bold;font-size:14px;">{{ $sin_room->room_no ?? '' }}<span><br>
                                                    <span style="font-size:14px;">{{ $sin_room->room_type ?? '' }}<span><br>
                                                </td>
                                                <td>
                                                    <span style="font-weight:bold;font-size:14px;">{{ $sin_room->check_in_at ?? '' }}<span><br>
                                                    <span style="font-size:14px;">{{ $sin_room->check_out_at ?? '' }}<span><br>
                                                    <hr>
                                                    <span style="font-weight:bold;font-size:14px;">Adults : {{ $sin_room->adults ?? '' }}<span><br>
                                                    <hr>
                                                    <span style="font-weight:bold;font-size:14px;">Children : {{ $sin_room->children ?? '' }}<span><br>
                                                </td>
                                                <td colspan="8">
                                                    @php 
                                                    $startTimeStamp = strtotime($booking->check_in_at);
                                                    $endTimeStamp = strtotime($booking->check_out_at);
                                                    $timeDiff = abs($endTimeStamp - $startTimeStamp);
                                                    $hour = request()->session()->get('business.day_duration') ?? 24;            
                                                   
                                                    $numberDays = $timeDiff/($hour * 60 * 60);
                                                    $discount = 0;
                                                    $no_of_days = round($numberDays) < $numberDays ? round($numberDays) + 1 : round($numberDays);
                                                    $total_rent = $sin_room->rent;
                                                    if($booking->discount_type == 'percentage')
                                                    {
                                                        $discount = $booking->discount_amount/100 * $total_rent;
                                                    }
                                                    else
                                                    {
                                                        $discount = $booking->discount_amount;
                                                    }
                                                    @endphp
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>From Date</th>
                                                                <th>To Date</th>
                                                                <th>No of Days</th>
                                                                <th>Rend / Day</th>
                                                                <th>Total Rent</th>
                                                                <th>Rent Discount</th>
                                                                <th>Amt. Aft Dis</th>
                                                                <th>Total Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>{{ $sin_room->check_in_at ?? '' }}</td>
                                                                <td>{{ $sin_room->check_out_at ?? '' }}</td>
                                                                <td>{{ $no_of_days}}</td>
                                                                <td style="text-align:right;">{{ number_format($total_rent / $no_of_days, 2) }}</td>
                                                                <td style="text-align:right;">{{ number_format($total_rent, 2) }}</td>
                                                                <td style="text-align:right;">{{ number_format($discount,2) }}</td>
                                                                <td style="text-align:right;">{{ number_format($total_rent - $discount, 2) }}</td>
                                                                <td style="text-align:right;">{{ number_format($total_rent - $discount, 2) }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($booking))
            @php
                $startTimeStamp = strtotime($booking->check_in_at);
                $endTimeStamp = strtotime($booking->check_out_at);
                $timeDiff = abs($endTimeStamp - $startTimeStamp);
                $hour = request()->session()->get('business.day_duration') ?? 24;            
                $numberDays = $timeDiff/($hour * 60 * 60);
                $discount = 0;
                $no_of_days = round($numberDays) < $numberDays ? round($numberDays) + 1 : round($numberDays);
                $total_rent = $room->sum('rent');
                $total_bed = $room->sum('bed_amount');
                $total_person = $room->sum('person_amount');
                $total_child = $room->sum('child_amount');
                $total_add = $total_bed + $total_person + $total_child;
                if($booking->discount_type == 'percentage')
                {
                    $discount = $booking->discount_amount/100 * $total_rent;
                }
                else
                {
                    $discount = $booking->discount_amount;
                }
                $total_payable_aft_dis = $total_rent - $discount +  $booking->servise_charge;
                $comIds = $room->pluck('complementry_id')->toArray();
                $com_rate =  App\Models\Complementary::whereIn('id', $comIds)->sum('rate');
                $complementary_amount = $room->sum('number');
                $complementary =  $com_rate * $complementary_amount;
            @endphp
            <div class="mb-6 grid gap-6 xl:grid-cols-3">
                <div class="panel h-full">
                    <div class="mb-5 flex items-center">
                        <h4>Billing Details</h4>
                    </div>
                    <div class="overflow-hidden">
                        <div class="row">
                            <div class="col-md-12">
                            <table class="table" style="border-style: none">
                                <tr>
                                    <th>Room Rent Amt.</th>
                                    <th  style="text-align:right;">{{ $total_rent > 0 ? number_format($total_rent, 2) : '0.00' }}</th>
                                </tr>
                                <tr>
                                    <th>Discount Amt.</th>
                                    <th  style="text-align:right;">{{ $discount }}</th>
                                </tr>
                                @if($booking->additional_note)
                                <tr>
                                    <th>{{$booking->additional_note}}</th>
                                    <th  style="text-align:right;">{{ $booking->additional_charge ?? '0.00' }}</th>
                                </tr>
                                @endif
                                <tr>
                                    <th>Total Room Rent Amt.</th>
                                    <th style="text-align:right;">{{ $total_payable_aft_dis }}</th>
                                </tr>
                                <tr>
                                    <th>Complementary Amt.</th>
                                    <th style="text-align:right;">{{ $complementary }}</th>
                                </tr>

                                <tr>
                                    <th>Bed/Person Charges.</th>
                                    <th style="text-align:right;">{{ $total_add > 0 ? number_format($total_add, 2) : '0.00' }}</th>
                                </tr>
                                
                                <tr>
                                    <th>Room Expense.</th>
                                    <th style="text-align:right;">{{ $expenses->sum('final_total') > 0 ? number_format($expenses->sum('final_total'), 2) : '0.00' }}</th>
                                </tr>
                                <tr>
                                    <th>Total Amt.</th>
                                    <input type="hidden" class="rent-final-chage-amount" value="{{ number_format($total_payable_aft_dis +  $complementary_amount - $advance, 2)}}">
                                    <th style="text-align:right;"><span class="rent-final-chage-text">{{ number_format($total_payable_aft_dis +  $complementary + $expenses->sum('final_total') + $total_add, 2)}}<span></th>
                                </tr>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
      	</div>
      	<div class="modal-footer">
      		
	    </div>
	</div>
</div>