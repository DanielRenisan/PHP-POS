<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('CheckoutController@store'), 'method' => 'post', 'id' => 'add_form' ]) !!}

        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Checkin</h4>
        </div>
        <div class="modal-body">
            <div class="panel">
                <input type="hidden" name="quick_access" value="1">
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
                                        <td>{{ $booking->customer->first_name.' '. $booking->customer->last_name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Email ID</th>
                                        <td>{{ $booking->customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Mobile No</th>
                                        <td>{{ $booking->customer->contact_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $booking->customer->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nationality</th>
                                        <td>{{ $booking->customer->nationality }}</td>
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
                                        <td>{{ $booking->type->name ?? '' }}</td>
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
                @if(isset($room) )
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
                                                        <span style="font-weight:bold;font-size:14px;">{{ $booking->check_in_at ?? '' }}<span><br>
                                                        <span style="font-size:14px;">{{ $booking->check_out_at ?? '' }}<span><br>
                                                        <hr>
                                                        <span style="font-weight:bold;font-size:14px;">Adults : {{ $sin_room->adults ?? '' }}<span><br>
                                                        <hr>
                                                        <span style="font-weight:bold;font-size:14px;">Children : {{ $sin_room->children ?? '' }}<span><br>
                                                    </td>
                                                    <td colspan="8">
                                                        @php 
                                                        $startTimeStamp = strtotime($booking->check_in_at);
                                                        $endTimeStamp = strtotime($booking->check_out_at);
                                                        $hour = request()->session()->get('business.day_duration') ?? 24;            
                                                        $time = $hour * 60 * 60;
                                                        $timeDiff = abs($endTimeStamp - $startTimeStamp);
                                                        $numberDays = $timeDiff/$time;
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
                                                                    <td>{{ $booking->check_in_at ?? '' }}</td>
                                                                    <td>{{ $booking->check_out_at ?? '' }}</td>
                                                                    <td>{{$no_of_days}}</td>
                                                                    <td>{{ $sin_room->rent}}</td>
                                                                    <td>{{ $total_rent }}</td>
                                                                    <td>{{ $discount }}</td>
                                                                    <td>{{ $total_rent - $discount }}</td>
                                                                    <td>{{ $total_rent - $discount }}</td>
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
                    $time = $hour * 60 * 60;
                    $numberDays = $timeDiff/$time;
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
                                        <th style="text-align:right;">{{ $total_rent > 0 ? number_format($total_rent, 2) : '0.00' }}</th>
                                    </tr>
                                    <tr>
                                        <th>Discount Amt.</th>
                                        <th style="text-align:right;">{{ $discount > 0 ? number_format($discount, 2) : '0.00' }}</th>
                                    </tr>
                                    <!-- <tr>
                                        <th>Service Charge Amt.</th>
                                        <th style="text-align:right;">{{ $booking->servise_charge > 0 ? number_format($booking->servise_charge, 2) : '0.00' }}</th>
                                    </tr> -->
                                    <tr>
                                        <th>Total Room Rent Amt.</th>
                                        <th style="text-align:right;">{{ $total_payable_aft_dis > 0 ? number_format($total_payable_aft_dis, 2) : '0.00' }}</th>
                                    </tr>
                                    <tr>
                                        <th>Complementary Amt.</th>
                                        <th style="text-align:right;">{{ $complementary > 0 ? number_format($complementary, 2) : '0.00' }}</th>
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
                                        <th>Advance Amt.</th>
                                        <th style="text-align:right;">{{ $advance > 0  ? number_format($advance, 2) : '0.00'}}</th>
                                    </tr>
                                    <tr>
                                        <th>Payable Rent Amt.</th>
                                        <input type="hidden" class="rent-final-chage-amount" value="{{ number_format($total_payable_aft_dis +  $complementary + $expenses->sum('final_total') + $total_add - $advance, 2)}}">
                                        <th style="text-align:right;"><span class="rent-final-chage-text">{{ number_format($total_payable_aft_dis +  $complementary + $expenses->sum('final_total') + $total_add - $advance, 2)}}<span></th>
                                    </tr>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel h-full">
                        <div class="mb-5 flex items-center">
                            <h4>Additional Charges</h4>
                        </div>
                        <div class="overflow-hidden">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('additional_charge', __('Addititional Charge') . ':*') !!}
                                        {!! Form::number('additional_charge', null, ['class' => 'form-input', 'id' => 'additional_charge_input'
                                        ]); !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('additional_note', __('Addititional Comment') . ':*') !!}
                                        {!! Form::text('additional_note', null, ['class' => 'form-input',
                                        ]); !!}
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12">
                                    <table class="table" style="border-style: none">
                                        <tr>
                                            <th>Payable Amt.</th>
                                            <input type="hidden" name="final_total" id="final_total_input" value="{{$total_payable_aft_dis +  $complementary + $expenses->sum('final_total') + $total_add - $advance}}">
                                            <input type="hidden" name="net_payable" class="net_payable" value="{{ $total_payable_aft_dis +  $complementary + $expenses->sum('final_total') + $total_add - $advance}}">
                                            <th><span class="after-addition-chage-text">{{ number_format($total_payable_aft_dis +  $complementary + $expenses->sum('final_total') + $total_add - $advance, 2)}}</span></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel h-full">
                        <div class="mb-5 flex items-center">
                            <h4>Room Posted Bill</h4>
                        </div>
                        <div class="overflow-hidden">
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="text-align:right;">
                                        @if($expenses->count() > 0)
                                        <a href="#" class="print-invoice" data-href="{{action('ExpenseController@printInvoice', $transaction->id)}}"><i class="fa fa-print" aria-hidden="true"></i></a>
                                        @endif
                                        <a  href="{{action('HomeController@expense',['id' =>  $transaction->id])}}" target="_blank" class="btn btn-info text-right view-expense" title="EXPENSE"><i class="fa fa-money"></i></a>
                                    </div>
                                    
                                    <table class="table">
                                        <tr>
                                            <th>Bill Type</th>
                                            <th>Qty.</th>
                                            <th>Total</th>
                                            <th>Date</th>
                                        </tr>
                                        @foreach($expenses as $expense)
                                        <tr>
                                            <td>{{$expense->category->name ?? ''}}</td>
                                            <td>{{$expense->quantity ?? 0}}</td>
                                            <td>{{$expense->final_total > 0 ? number_format($expense->final_total, 2) : '0.00'}}</td>
                                            <td>{{$expense->created_at}}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="mb-6 grid gap-6 xl:grid-cols-1">
                <div class="panel h-full">
                    <div class="mb-5 flex items-center">
                        <h4>Billing Details</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="text-align:right;">
                            <button type="button" class="btn btn-primary" id="add-payment-row">ADD MORE</button>
                        </div>
                    </div>
                    <br>
                    <div class="overflow-hidden">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="payment_rows_div">
                                    @foreach($payment_lines as $payment_line)

                                        @include('checkout.partials.payment_row', ['removable' => !$loop->first, 'row_index' => $loop->index, 'payment_line' => $payment_line])
                                    @endforeach
                                </div>
                                <input type="hidden" id="payment_row_index" value="{{count($payment_lines)}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
        <button type="submit" class="btn btn-primary">SAVE</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
        </div>

        {!! Form::close() !!}
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/nice-select2.css?v='.$asset_v) }}">
<script src="{{ asset('assets/js/nice-select2.js?v=' . $asset_v) }}"></script>
    
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(e) {
            // seachable 
            var options = {
                searchable: true
            };
            NiceSelect.bind(document.getElementById("room_no"), options);
        });
        $(document).on('change', 'select#room_no', function()
        {
            var room_no = $(this).find('option:selected').val();
            var url = "{{route('checkout.index')}}?room_no="+room_no;
            window.location.replace(url);
        });
        $(document).on('change', 'input#additional_charge_input', function(){
            var charge = __read_number($(this));
            var rent_amount = __read_number($('input.rent-final-chage-amount'));
            var sub_total = rent_amount + charge;
            __write_number($('input.net_payable'), sub_total, false, 2);
		    $('span.after-addition-chage-text').text(__currency_trans_from_en(sub_total, true));
            __write_number($('input.final-payable'), sub_total, false, 2);
            __write_number($('input#credit_input'), sub_total, false, 2);
            __write_number($('input.payment-amount'), sub_total, false, 2);
            $('span.final-payable-text').text(__currency_trans_from_en(sub_total, true));
            
        });
        var rent_amount = $('input.rent-final-chage-amount').val();
        $('input.payment-amount').val(rent_amount);
        $('input#credit_input').val(rent_amount)
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
        $('button#add-payment-row').click(function () {
		var row_index = $('#payment_row_index').val();
		$.ajax({
			method: "POST",
			url: '/get_payment_row',
			data: { row_index: row_index },
			dataType: "html",
			success: function (result) {
				if (result) {
					var appended = $('#payment_rows_div').append(result);

					var total_payable = __read_number($('input#final_total_input'));
					var total_paying = __read_number($('input#total_paying_input'));
					var b_due = total_payable - total_paying;
					$(appended).find('input.payment-amount').focus();
					$(appended).find('input.payment-amount').last().val(__currency_trans_from_en(b_due, false)).change().select();
					__select2($(appended).find('.select2'));
					$('#payment_row_index').val(parseInt(row_index) + 1);
				}
			}
		});
	});

	$(document).on('click', '.remove_payment_row', function () {
		swal({
			title: LANG.sure,
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {
				$(this).closest('.payment_row').remove();
				calculate_balance_due();
			}
		});
	});
    </script>