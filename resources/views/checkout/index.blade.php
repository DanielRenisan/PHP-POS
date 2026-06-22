@extends('layouts.app_rest')

@section('content')
<style>
.col-md {
    width: 66.66666667%;
    float: left;
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}  
.col-sm-offset-2 {
    margin-left: 16.66666667%;
}
</style>
<div class="animate__animated p-6   no-print" :class="[$store.app.animation]">
<div x-data="sizeList">
            <!-- start main content section -->
            {!! Form::open(['url' => action('CheckoutController@store'), 'method' => 'post', 
            'id' => 'transaction_add_form','class' => 'transaction_form', 'files' => true ]) !!}
                <div>
                    <ul class="flex space-x-2 rtl:space-x-reverse">
                        <li>
                            <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
                        </li>
                        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                            <span>Checkout</span>
                        </li>
                    </ul>
                    <div class="grid grid-cols-1 gap-4 pt-5">
                        <!-- Basic -->
                        <!-- type=text -->
                        <div class="panel">
                            <div class="mb-5 flex items-center justify-between">
                                <h3 class="font-semibold dark:text-white-light">Checkout</h3>
                            </div>
                            <div class="my-4 h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                            <div class="mb-5">
                                <div class="grid grid-cols-1 gap-4 pt-5">
                                    <div class="col-md  col-sm-offset-2">
                                        <div class="form-group">
                                            {!! Form::label('room_no', __( 'Room No' ) . ':*') !!}
                                            <select name="room_no" class="form-input" required id="room_no">
                                                <option value=" ">Select Room</option>
                                                @foreach($rooms as $id_key => $sin_room)
                                                <option value="{{$id_key}}" {{request()->get('room_no') == $id_key ? 'selected' : '' }}>{{$sin_room}}</option>
                                                @endforeach
                                            </select>
                                            <!-- {!! Form::select('room_no', $rooms, request()->get('room_no') ?? null, ['class' => 'form-input', 'placeholder' => __( 'select room'), 'required' ]); !!} -->
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                        <div id="checkout-form-div">
                            @if(isset($booking))
                            <input type="hidden" name="booking_id" value="{{$booking->id ?? '' }}">
                            <input type="hidden" name="transaction_id" value="{{$transaction->id ?? '' }}">
                                <div class="mb-6 grid gap-6 xl:grid-cols-3">
                                    <div class="panel h-full">
                                        <div class="mb-5 flex items-center">
                                            <h4>Customer Details</h4>
                                        </div>
                                        <div class="overflow-hidden">
                                            <table>
                                                <tr>
                                                    <th class="text-left">Name</th>
                                                    <td  class="text-left">{{ $booking->customer->first_name.' '. $booking->customer->last_name}}</td>
                                                </tr>
                                                <tr>
                                                    <th  class="text-left">Email ID</th>
                                                    <td  class="text-left">{{ $booking->customer->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th  class="text-left">Mobile No</th>
                                                    <td  class="text-left">{{ $booking->customer->contact_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th  class="text-left">Address</th>
                                                    <td   class="text-left">{{ $booking->customer->address }}</td>
                                                </tr>
                                                <tr>
                                                    <th  class="text-left">Nationality</th>
                                                    <td  class="text-left">{{ $booking->customer->nationality }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel h-full xl:col-span-2">
                                        <div class="mb-5 flex items-center">
                                            <h4>Booking Details</h4>
                                        </div>
                                        
                                        <div class="relative overflow-hidden">
                                            <table>
                                                <tr style="line-height:2;">
                                                    <th class="text-left">Checkin At</th>
                                                    <td class="text-left">{{ $booking->check_in_at}}</td>
                                                    <th class="text-left">Booking No</th>
                                                    <td class="text-left">{{ $booking->ref_no}}</td>
                                                </tr>
                                                <tr style="line-height:2;">
                                                    <th class="text-left">Checkout At</th>
                                                    <td class="text-left">{{ $booking->check_out_at }}</td>
                                                    <th class="text-left">Purpose</th>
                                                    <td class="text-left">{{ $booking->purpose}}</td>
                                                </tr>
                                                <tr style="line-height:2;">
                                                    <th class="text-left">Arival From</th>
                                                    <td class="text-left">{{ $booking->arival_from }}</td>
                                                    <th class="text-left"></th>
                                                    <td></td>
                                                </tr>
                                                <tr style="line-height:2;">
                                                    <th class="text-left">Booking Type</th>
                                                    <td class="text-left">{{ $booking->type->name ?? '' }}</td>
                                                    <th></th>
                                                    <td></td>
                                                </tr>
                                                <tr style="line-height:2;">
                                                    <th class="text-left">Booking Source</th>
                                                    <td class="text-left">{{ $booking->source ? $booking->source->name : '' }}</td>
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
                                            <div class="grid grid-cols-1 gap-4 pt-5">
                                                <div>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th width="20%">Room No.</th>
                                                                <th  width="20%">Date</th>
                                                                <th colspan="8"  width="60%">Room Rent Details</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($room as $sin_room)
                                                            <tr style="line-height:2.0rem;">
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
                                                                    <table>
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
                                        <div class="grid grid-cols-1 gap-4 pt-5">
                                            <div>
                                            <table>
                                                <tr style="line-height:2.0rem;">
                                                    <th  class="text-left">Room Rent Amt.</th>
                                                    <th style="text-align:right;">{{ $total_rent > 0 ? number_format($total_rent, 2) : '0.00' }}</th>
                                                </tr>
                                                <tr style="line-height:2.0rem;">
                                                    <th  class="text-left">Discount Amt.</th>
                                                    <th style="text-align:right;">{{ $discount > 0 ? number_format($discount, 2) : '0.00' }}</th>
                                                </tr>
                                                <!-- <tr>
                                                    <th>Service Charge Amt.</th>
                                                    <th style="text-align:right;">{{ $booking->servise_charge > 0 ? number_format($booking->servise_charge, 2) : '0.00' }}</th>
                                                </tr> -->
                                                <tr style="line-height:2.0rem;">
                                                    <th  class="text-left">Total Room Rent Amt.</th>
                                                    <th style="text-align:right;">{{ $total_payable_aft_dis > 0 ? number_format($total_payable_aft_dis, 2) : '0.00' }}</th>
                                                </tr>
                                                <tr style="line-height:2.0rem;">
                                                    <th  class="text-left">Complementary Amt.</th>
                                                    <th style="text-align:right;">{{ $complementary > 0 ? number_format($complementary, 2) : '0.00' }}</th>
                                                </tr>
                                                <tr style="line-height:2.0rem;">
                                                    <th  class="text-left">Bed/Person Charges.</th>
                                                    <th style="text-align:right;">{{ $total_add > 0 ? number_format($total_add, 2) : '0.00' }}</th>
                                                </tr>
                                                <tr style="line-height:2.0rem;">
                                                    <th  class="text-left">Room Expense.</th>
                                                    <th style="text-align:right;">{{ $expenses->sum('final_total') > 0 ? number_format($expenses->sum('final_total'), 2) : '0.00' }}</th>
                                                </tr>
                                                @php
                                                    $due_order = $orders->where('payment_status', 'due')->sum('final_total');
                                                    $due_order_ids = $orders->where('payment_status', 'due')->pluck('id')->toArray();
                                                    $due_orders_data = $orders->where('payment_status', 'due')->pluck('final_total', 'id')->toArray();
                                                @endphp
                                                <tr style="line-height:2.0rem;">
                                                    <input type="hidden" name="due_order_ids" value="{{ implode(',', $due_order_ids) }}">
                                                    <input type="hidden" name="due_orders_data" value="{{ json_encode($due_orders_data) }}">
                                                    <th  class="text-left">Rest Orders.</th>
                                                    <th style="text-align:right;">{{ $due_order > 0 ? number_format($due_order, 2) : '0.00' }}</th>
                                                </tr>
                                                <tr style="line-height:2.0rem;">
                                                    <th  class="text-left">Advance Amt.</th>
                                                    <th style="text-align:right;">{{ $advance > 0  ? number_format($advance, 2) : '0.00'}}</th>
                                                </tr>
                                                <tr style="line-height:2.0rem;">
                                                    <th  class="text-left">Payable Rent Amt.</th>
                                                    <input type="hidden" class="rent-final-chage-amount" value="{{ number_format($total_payable_aft_dis +  $complementary + $expenses->sum('final_total') + $due_order + $total_add - $advance, 2)}}">
                                                    <th style="text-align:right;"><span class="rent-final-chage-text">{{ number_format($total_payable_aft_dis +  $complementary + $expenses->sum('final_total') + $due_order + $total_add - $advance, 2)}}<span></th>
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
                                        <div class="grid grid-cols-1 gap-4 pt-5">
                                            <div>
                                                <div class="form-group">
                                                    {!! Form::label('additional_charge', __('Addititional Charge') . ':*') !!}
                                                    {!! Form::number('additional_charge', null, ['class' => 'form-input', 'id' => 'additional_charge_input'
                                                    ]); !!}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="form-group">
                                                    {!! Form::label('additional_note', __('Addititional Comment') . ':*') !!}
                                                    {!! Form::text('additional_note', null, ['class' => 'form-input',
                                                    ]); !!}
                                                </div>
                                            </div>
                                            <hr>
                                            <div>
                                                <table>
                                                    <tr  style="line-height:2.0rem;">
                                                        <th class="text-left">Payable Amt.</th>
                                                        <input type="hidden" name="final_total" id="final_total_input" value="{{$total_payable_aft_dis +  $complementary + $expenses->sum('final_total') + $due_order + $total_add - $advance}}">
                                                        <input type="hidden" name="net_payable" class="net_payable" value="{{ $total_payable_aft_dis +  $complementary + $expenses->sum('final_total') + $due_order + $total_add - $advance}}">
                                                        <th><span class="after-addition-chage-text">{{ number_format($total_payable_aft_dis +  $complementary + $expenses->sum('final_total') + $due_order + $total_add - $advance, 2)}}</span></th>
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
                                        <div class="grid grid-cols-1 gap-4 pt-5">
                                            <div>
                                                <div style="padding-right:0px; width:20%;float: right;">
                                                    @if($expenses->count() > 0)
                                                    <a href="#" class="print-invoice" data-href="{{action('ExpenseController@printInvoice', $transaction->id)}}"><i class="fa fa-print" aria-hidden="true"></i></a>
                                                    @endif
                                                    <button type="button" class="hover:text-info" @click="addExpense()" title="cancel">
                                                        <i class="fa fa-money"></i>
                                                    </button>
                                                </div>
                                                
                                                <table>
                                                    <tr>
                                                        <th>Bill Type</th>
                                                        <th>Qty.</th>
                                                        <th>Total</th>
                                                        <th>Date</th>
                                                    </tr>
                                                    @foreach($expenses as $expense)
                                                    <tr>
                                                        <td  class="text-left">{{$expense->category->name ?? ''}}</td>
                                                        <td  class="text-center">{{$expense->quantity ?? 0}}</td>
                                                        <td  class="text-right">{{$expense->final_total > 0 ? number_format($expense->final_total, 2) : '0.00'}}</td>
                                                        <td  class="text-left">{{$expense->created_at}}</td>
                                                    </tr>
                                                    @endforeach
                                                    @php
                                                    $all_orders = $all_orders->get();

                                                    @endphp
                                                    @foreach($all_orders as $order)
                                                    <tr>
                                                        <td  class="text-left">{{$order->invoice_no ?? ''}}</td>
                                                        <td  class="text-center">{{$order->lines_of_sell->sum('quantity') ?? 0}}</td>
                                                        <td  class="text-right">{{$order->final_total > 0 ? number_format($order->final_total, 2) : '0.00'}}</td>
                                                        <td  class="text-left">{{$order->created_at}}</td>
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
                    </div>
                    <div class="mb-6 grid gap-6 xl:grid-cols-1">
                        <div class="panel h-full">
                            <div class="mb-5 flex items-center">
                                <h4>Billing Details</h4>
                            </div>
                            <div class="grid grid-cols-1 gap-4 pt-5">
                                <div class="col-md-12" style="padding-right:0px;float: right;">
                                    <button type="button" class="btn btn-primary" id="add-payment-row"  style="position: absolute; right: 10px;">+ ADD MORE</button>
                                </div>
                            </div>
                            <br>
                            <div class="overflow-hidden">
                                <div class="grid grid-cols-1 gap-4 pt-5">
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
                    <div class="panel">
                        <div class="mb-5 row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right" style="position: absolute; right: 10px;">CHECKOUT</button>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
            <div x-show="expenseModal" class="mb-5">
                <!-- modal -->
                <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
                    :class="open && '!block'">
                    <div class="flex items-start justify-center min-h-screen px-4"
                        @click.self="open = false">
                        <div x-transition x-transition.duration.300
                            class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg">
                            <div class="heading">
                                <h2 class="m-0"><span x-text="itemToExpense.ref_no"></span> Expense</h2>
                            </div>
                            <div class="p-5">
                                    {!! Form::open(['url' => action('HomeController@postExpense'), 'method' => 'post', 'id' => 'guest_add_form' ]) !!} 
                                    <input type="hidden" name="contact_id" value="{{isset($booking) ? $booking->contact_id : '' }}">
                                    <input type="hidden" name="room_no" value="{{isset($sin_room) && isset($sin_room->room_no) ? $sin_room->room_no : '' }}">
                                    <div class="grid grid-cols-3 gap-4 pt-5">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('invoice_no', __('Reference No:').':') !!}
                                                {!! Form::text('invoice_no', isset($expense) && $expense->invoice_no ? $expense->invoice_no : '', ['class' => 'form-input']); !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ctnSelect1">Date </label>
                                                <input class="form-input" name="transaction_date" type="date" value="{{date('Y-m-d')}}">
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ctnSelect1">Category</label>
                                                {!! Form::select('category_id', $categories , isset($expense) && $expense->category_id ? $expense->category_id : null, ['class' => 'form-input', 'id' => 'seachable-category',
                                                'placeholder' => __('Choose Category'), 'required']); !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ctnSelect1">Sub Category</label>
                                                {!! Form::select('sub_category_id', [] , isset($expense) && $expense->sub_category_id ? $expense->sub_category_id : null, ['class' => 'form-input', 'id' => 'seachable-sub-cate',
                                                'placeholder' => __('Sub Category')]); !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('quantity', 'qty *') !!}
                                                {!! Form::number('quantity', isset($expense) && $expense->quantity ? $expense->quantity : null, ['class' => 'form-input', 'id' => 'quantity' , 'required']); !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('final_total', 'Amount *') !!}
                                                {!! Form::number('final_total', isset($expense) && $expense->final_total ? $expense->final_total : null, ['class' => 'form-input', 'id' => 'final_amount' , 'required']); !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('details', 'Note') !!}
                                                        {!! Form::textarea('details', isset($expense) && $expense->details ? $expense->details : null, ['class' => 'form-input', 'rows' => 3, 'id' => 'details']); !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" flex justify-end items-center mt-3">
                                        <button type="button" class="btn btn-outline-danger"
                                            @click="expenseModal = false">Discard</button>
                                            <button type="Submit" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                            >SAVE</button>
                                    </div>
                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>
</div>
<section class="invoice print_section" id="receipt_section">  
@endsection
@section('javascript')
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

    $("form#transaction_add_form").submit(function (e) {
		e.preventDefault();
	}).validate({
		submitHandler: function (form) {
			// if (cnf) {
				var data = $(form).serialize();
				var url = $(form).attr('action');
				$.ajax({
					method: "POST",
					url: url,
					data: data,
					dataType: "json",
					success: function (result) {
						if (result.success == 1) {

							toastr.success(result.msg);

                            reset_form();

							if (result.receipt.is_enabled) {
								pos_print(result.receipt);
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
							}
                            var newURL = location.href.split("?")[0];
                            window.history.pushState('object', document.title, newURL);
						} else {
							toastr.error(result.msg);
						}
					}
				});
			// }
			return false;
        }
	});

    function pos_print(receipt) {
        if (receipt.html_content != '') {
		//If printer type browser then print content
            $('#receipt_section').html(receipt.html_content);
            __currency_convert_recursively($('#receipt_section'));
            setTimeout(function () { window.print(); }, 1000);
	    }
    }
    
    function reset_form() {
        $('#checkout-form-div').hide();
        $('#room_no').val(' ');
        
        if (typeof NiceSelect !== 'undefined') {
            var roomSelect = document.getElementById("room_no");

            if (roomSelect && roomSelect.nextElementSibling && roomSelect.nextElementSibling.classList.contains('nice-select')) {
                var niceSelectInstance = roomSelect.nextElementSibling;
                niceSelectInstance.querySelector('.current').textContent = 'Select Room';
                niceSelectInstance.querySelector('.list .option.selected').classList.remove('selected');
                niceSelectInstance.querySelector('.list .option[data-value=" "]').classList.add('selected');
            }
        }
        $('#transaction_add_form')[0].reset();
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('sizeList', () => ({
            selectedRows: [],
            searchText: '',
            expenseModal: false,
            itemToExpense: {},
            addExpense() {
                this.expenseModal = true;
            },
        }));
    });
    </script>
@endsection