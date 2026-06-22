@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div>
    <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <a href="{{action('BookingController@index')}}" class="text-primary hover:underline">Bookings</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Edit Booking</span>
            </li>
        </ul>
        {!! Form::open(['url' => action('BookingController@update', $transaction->id), 'method' => 'PUT', 
'id' => 'booking_edit_form','class' => 'booking_form', 'files' => true ]) !!}
            <input type="hidden" name="booking_id" value="{{$booking->id ?? '' }}">
            <input type="hidden" name="transaction_id" value="{{$transaction->id ?? '' }}">
            
            <div class="grid grid-cols-1 gap-4 pt-5">
                <!-- Basic -->
                <!-- type=text -->
                <div class="panel">
                    <div class="mb-5 flex items-center justify-between">
                    </div>
                    <div class="mb-5">
                        <div class="grid grid-cols-4 gap-5" style="margin-bottom: 20px;">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ctnSelect1">Check In <span class="text-danger">*</span></label>
                                    <input id="dateTime" class="form-input" name="check_in_at" type="datetime-local" value="{{$booking->check_in_at}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ctnSelect1">Check Out <span class="text-danger">*</span></label>
                                    <input id="dateTimeout" class="form-input" name="check_out_at" type="datetime-local" value="{{$booking->check_out_at}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ctnSelect1">Arival From</label>
                                    <input type="text" placeholder="Arival From" class="form-input" name="arival_from" value="{{$booking->arival_from}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ctnSelect1">Booking Type <span class="text-danger"></span></label>
                                    {!! Form::select('booking_type_id', $booking_types , $booking->booking_type_id, ['class' => 'form-input', 'id' => 'seachable-select',
                                    'placeholder' => __('Please Select')]); !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ctnSelect1">Choose Booking Reference</label>
                                    {!! Form::select('booking_source_id', $sources , $booking->booking_source_id, ['class' => 'form-input', 'id' => 'seachable-sources',
                                'placeholder' => __('Choose Booking Reference')]); !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ctnSelect1">Booking Reference No</label>
                                    <input type="text" placeholder="Booking Reference No" class="form-input" name="ref_no" value="{{$booking->ref_no}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ctnSelect1">Purpose of Visit</label>
                                    <input type="text" placeholder="Purpose of Visit" class="form-input" name="purpose" value="{{$booking->purpose}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ctnSelect1">Remarks</label>
                                    <input type="text" placeholder="Remarks" class="form-input" name="remarks" value="{{$booking->remarks}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <h4 class="title">Customer Info</h4>
                    <div class="grid grid-cols-3 gap-5" style="margin-bottom: 20px;">
                        <div style="align-items: center; gap: 20px;">
                            <div class="form-group">
                                {!! Form::select('contact_id', 
                                        $customers, $booking->contact_id, ['class' => 'form-input mousetrap', 'id' => 'customer_id', 'placeholder' => 'Enter Customer name', 'required']); !!}
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="final_total" id="final_total_input">
                </div>
                <div class="panel">
                    <div class="grid grid-cols-4 gap-5" style="margin-bottom: 20px;">
                        <div style="align-items: center; gap: 20px;">
                            <h4 class="title">Room Details</h4>
                        </div>
                        <div style="align-items: center; gap: 20px;">
                        </div>
                        <div style="align-items: center; gap: 20px;">
                        </div>
                        <div style="align-items: center; gap: 20px;text-align:right;">
                            <button type="button" class="btn btn-primary" id="add-more-btn">ADD MORE</button>
                        </div>
                    </div>
                    <br>
                    <div class="mb-5 fetch-div-0">
                        @if($room->count() > 0)
                        @foreach($room as $kry => $single_room)
                        
                        <input type="hidden" name="room_detail[{{$kry}}][room_id]" value="{{$single_room->id ?? '' }}">
                        @php 
                        $rooms = App\Models\RoomAssign::where('room_type', $single_room->room_type)
                            ->pluck('room_id')
                            ->toArray();
                        @endphp
                        <div class="tr row_set">
                            <div class="grid grid-cols-4 gap-5">
                                <div style="align-items: center; gap: 20px;">
                                    <div class="form-group">
                                        <label for="ctnSelect1">Room Type <span class="text-danger">*</span></label>
                                        <select class="form-input" name="room_detail[{{$kry}}][room_type]" required id="seachable-room_type">
                                            <option readonly>Room Type</option>
                                            @foreach($room_types as $key => $room_type)
                                                <option value="{{$room_type}}" {{$room_type == $single_room->room_type ? 'selected' : ''}}>{{$room_type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div style="align-items: center; gap: 20px;">
                                    <div class="form-group">
                                        <label for="ctnSelect1">Room No. <span class="text-danger">*</span></label>
                                        <select class="form-input" name="room_detail[{{$kry}}][room_no]" required id="room-no">
                                            <option readonly>Room Type</option>
                                            @foreach($rooms as $key => $room_no)
                                                <option value="{{$room_no}}" {{$room_no == $single_room->room_no ? 'selected' : ''}}>{{$room_no}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div style="align-items: center; gap: 20px;">
                                    <div class="form-group">
                                        <label for="ctnSelect1">#Adults</label>
                                        {!! Form::number('room_detail['.$kry.'][adults]',  $single_room->adults, ['class' => 'form-input', 'id' => 'adult-number',
                                            'placeholder' => __('Adults'), 'required']); !!}
                                    </div>
                                </div>
                                <div style="align-items: center; gap: 20px;">
                                    <div class="form-group">
                                        <label for="ctnSelect1">#Children</label>
                                        {!! Form::number('room_detail['.$kry.'][children]',  $single_room->children, ['class' => 'form-input',
                                        'placeholder' => __('Children')]); !!}
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                            <br>
                            <div class="grid grid-cols-3 gap-5">
                                <div style="align-items: center; gap: 20px;">
                                    <div class="form-group">
                                        <label for="ctnSelect1">Check In <span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-input" name="room_detail[{{$kry}}][check_in_at]" value="{{$single_room->check_in_at}}" required>
                                    </div>
                                </div>
                                <div style="align-items: center; gap: 20px;">
                                    <div class="form-group">
                                        <label for="ctnSelect1">Check Out <span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-input" name="room_detail[{{$kry}}][check_out_at]" value="{{$single_room->check_out_at}}" required>
                                    </div>
                                </div>
                                <div style="align-items: center; gap: 20px;">
                                    <div class="form-group">
                                        <label for="ctnSelect1">Rent</label>
                                        <input type="number" placeholder="Rent" class="form-input" name="room_detail[{{$kry}}][rent]" id="rent-input" value="{{$single_room->rent}}">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                            <br>
                            <div class="flex flex-col gap-2.5 xl:flex-row">
                                <div class="col-md-2">
                                    <input type="number" placeholder="Bed" class="form-input" name="room_detail[{{$kry}}][bed_count]" id="bed-count" value="{{$single_room->bed_count}}">
                                </div>
                                <div class="col-md-1">                   
                                    <input type="number" placeholder="Amount" class="form-input" name="room_detail[{{$kry}}][bed_amount]"  id="bed-amount" value="{{$single_room->bed_amount}}">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" placeholder="Person" class="form-input" name="room_detail[{{$kry}}][person_count]" id="person-count" value="{{$single_room->person_count}}">
                                </div>
                                <div class="col-md-1">
                                    <input type="number" placeholder="Amount" class="form-input" name="room_detail[{{$kry}}][person_amount]" id="person-amount" value="{{$single_room->person_amount}}">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" placeholder="Child" class="form-input" name="room_detail[{{$kry}}][childs_count]" id="childs-count" value="{{$single_room->childs_count}}">
                                </div>
                                <div class="col-md-1"  style="border-right: 1px solid black;">
                                    <input type="number" placeholder="Amount" class="form-input" name="room_detail[{{$kry}}][child_amount]" id="child_amount" value="{{$single_room->child_amount}}">    
                                </div>
                                <div  style="align-items: center; gap: 20px;width:40%;">
                                    @php 
                                    $complementary =  App\Models\Complementary::where('id',  $single_room->complementry_id)->sum('rate');
                                    $complementary_amount = $complementary * $single_room->number;
                                    @endphp

                                <input type="hidden" id="complementry_amount" value="{{$complementary_amount}}">
                                    <div class="form-group">
                                        {!! Form::select('room_detail['.$kry.'][complementry_id]', $complementaries , $single_room->complementry_id, ['class' => 'form-input', 'id' => 'complementry',
                                'placeholder' => __('Choose Complementry')]); !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="number"  class="form-input" name="room_detail[{{$kry}}][number]" value="{{$single_room->number}}" readonly id="complementry-no">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="grid grid-cols-1 gap-5">
                                <div style="text-align:right; gap: 20px;"><b>@lang('Total Complementry'): </b>
                                        <span id="complementry_amount">{{$complementary_amount}}</span>
                                </div>
                            </div>
                            @if($kry > 0)
                            <br>
                            <div class="flex flex-col gap-2.5 xl:flex-row">
                                <div class="col-md-12" style="text-align:right;">
                                <button type="button" class="btn btn-danger remove_field">X</button>
                                </div>
                            </div>
                            @endif
                            <br>
                                <div style="--tw-border-opacity: 1;border-color: black;border-bottom-width:3px;height:2px;"></div>
                            <br>
                        </div>
                        @endforeach
                        @else
                        <div class="tr row_set">
                            <div class="grid grid-cols-4 gap-5">
                                <div style="align-items: center; gap: 20px;">
                                    <div>
                                        <label for="ctnSelect1">Room Type <span class="text-danger">*</span></label>
                                        <select class="form-input" name="room_detail[0][room_type]" required id="seachable-room_type">
                                            <option readonly>Room Type</option>
                                            @foreach($room_types as $key => $room_type)
                                                <option value="{{$room_type}}" {{request()->get('room_type') && request()->get('room_type') == $room_type ? 'selected' : '' }}>{{$room_type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div style="align-items: center; gap: 20px;">
                                    <div>
                                        <label for="ctnSelect1">Room No. <span class="text-danger">*</span></label>
                                        {!! Form::select('room_detail[0][room_no]', [ request()->get('room_no') ?  request()->get('room_no') : '' => request()->get('room_no') ?  request()->get('room_no') : ''] , request()->get('room_no') ?? '', ['class' => 'form-input', 'id' => 'room-no',
                                        'required']); !!}
                                    </div>
                                </div>
                                <div style="align-items: center; gap: 20px;">
                                    <div>
                                        <label for="ctnSelect1">#Adults</label>
                                        {!! Form::number('room_detail[0][adults]',  null, ['class' => 'form-input', 'id' => 'adult-number',
                                            'placeholder' => __('Adults'), 'required']); !!}
                                    </div>
                                </div>
                                <div style="align-items: center; gap: 20px;">
                                    <div >
                                        <label for="ctnSelect1">#Children</label>
                                        {!! Form::number('room_detail[0][children]',  null, ['class' => 'form-input',
                                        'placeholder' => __('Children')]); !!}
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                            <br>
                            <div class="grid grid-cols-3 gap-5">
                                <div style="align-items: center; gap: 20px;">
                                    <div>
                                        <label for="ctnSelect1">Check In <span class="text-danger">*</span></label>
                                        <input id="dateTime_date" class="form-input" name="room_detail[0][check_in_at]" type="datetime-local" value="{{date('Y-m-d H:i')}}">
                                    </div>
                                </div>
                                <div style="align-items: center; gap: 20px;">
                                    <div>
                                        <label for="ctnSelect1">Check Out <span class="text-danger">*</span></label>
                                        <input id="dateTime_in" class="form-input" name="room_detail[0][check_out_at]" type="datetime-local" value="{{date('Y-m-d H:i')}}">
                                    </div>
                                </div>
                                <div style="align-items: center; gap: 20px;">
                                    <div>
                                        <label for="ctnSelect1">Rent</label>
                                        <input type="number" placeholder="Rent" class="form-input" name="room_detail[0][rent]" id="rent-input" value="0.00" readonly>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                            <br>
                            <div class="flex flex-col gap-2.5 xl:flex-row">
                                <div class="col-md-2">
                                    <input type="number" placeholder="Bed" class="form-input" name="room_detail[0][bed_count]" id="bed-count" value="" readonly>
                                </div>
                                <div class="col-md-1">                   
                                    <input type="number" placeholder="Amount" class="form-input" name="room_detail[0][bed_amount]"  id="bed-amount" value="" readonly>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" placeholder="Person" class="form-input" name="room_detail[0][person_count]" id="person-count" value="" readonly>
                                </div>
                                <div class="col-md-1">
                                    <input type="number" placeholder="Amount" class="form-input" name="room_detail[0][person_amount]" id="person-amount" value="" readonly>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" placeholder="Child" class="form-input" name="room_detail[0][childs_count]" id="childs-count" value="" readonly>
                                </div>
                                <div class="col-md-1"  style="border-right: 1px solid black;">
                                    <input type="number" placeholder="Amount" class="form-input" name="room_detail[0][child_amount]" id="child_amount" value="" readonly>    
                                </div>
                                <div  style="align-items: center; gap: 20px;width:40%;">
                                    <input type="hidden" id="complementry_amount" value="">
                                    <div class="form-group">
                                        {!! Form::select('room_detail[0][complementry_id]', $complementaries , null, ['class' => 'form-input', 'id' => 'complementry',
                                'placeholder' => __('Choose Complementry')]); !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="number"  class="form-input" name="room_detail[0][number]" value="1" readonly id="complementry-no">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="grid grid-cols-1 gap-5">
                                <div style="text-align:right; gap: 20px;"><b>@lang('Total Complementry'): </b>
                                        <span id="complementry_amount">0</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        <br>
                        <div class="input_fields_wrap">
                        </div> 
                    </div>
                    
                </div>
                <div class="panel">
                    <div class="mb-5">
                        <div class="grid grid-cols-1 gap-5">
                            <div style="text-align:right; gap: 20px;">
                                <div style="font-size:18px"><b>@lang('Total'): </b>
                                    <input type="hidden" name="final_total" id="final_total_input" value="{{$transaction->final_total}}">
                                    <span id="total_payable">Rs {{number_format($transaction->final_total, 2)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="grid grid-cols-1 gap-5">
                        <div style="align-self:flex-end; gap: 20px;">
                            <button type="submit" class="btn btn-info mt-6">UPDATE</button>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
@section('javascript')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/nice-select2.css?v='.$asset_v) }}">
<script src="{{ asset('assets/js/nice-select2.js?v=' . $asset_v) }}"></script>
<script>
    
    document.addEventListener("DOMContentLoaded", function(e) {
        // seachable 
        var options = {
            searchable: true
        };
        NiceSelect.bind(document.getElementById("seachable-select"), options);
        NiceSelect.bind(document.getElementById("seachable-sources"), options);
        NiceSelect.bind(document.getElementById("customer_id"), options);
        NiceSelect.bind(document.getElementById("seachable-room_type"), options);
        // NiceSelect.bind(document.getElementById("seachable-complementry"), options);
        // NiceSelect.bind(document.getElementById("seachable-room-no"), options);
    });
    
    $(document).on('change','#seachable-room_type' ,function () {
        var tr = $(this).parents().closest('.tr');
        var room = $(this).val();
        get_rooms(tr,room);
        get_rooms_details(tr,room);
        get_complementry(tr,room);
    });
    $(document).on('change','#dateTime' ,function () {
        get_room_info();
        
    });
    $(document).on('change','#dateTimeout' ,function () {
        get_room_info();
        
    });
    function get_room_info()
    {
        $('div.tr').each(function(i,v){
            var tr = $(this).closest('.tr');
            
            var room = tr.find('#seachable-room_type option:selected').val();
            console.log(room)
            $.ajax({
                method: "POST",
                url: '/bookings/get-room-details',
                data: { 'room_type': room,
                    'check_in' : $('#dateTime').val(),
                    'check_out' : $('#dateTimeout').val()  
                },
                success: function (result) {
                    if (result != false) {
                        tr.find('input#rent-input').val(result.rent);
                        tr.find('#adult-number').val(result.adults);
                        tr.find('input#rent-input').trigger('change');
                    }
                }
            });
        });
    }
    function get_rooms(tr, room) {
        
        $.ajax({
            method: "POST",
            url: '/bookings/get-rooms',
            dataType: "html",
            data: { 'room_type': room,
                'check_in' : $('#dateTime').val(),
                'check_out' : $('#dateTimeout').val() },
            success: function (result) {
                if (result) {
                    tr.find('#room-no').html(result);
                    tr.find('#complementry-no').attr("readonly", false);
                    tr.find('#rent-input').attr("readonly", false);
                    tr.find('#childs-count').attr("readonly", false);
                    tr.find('#bed-count').attr("readonly", false);
                    tr.find('#person-count').attr("readonly", false);
                }
            }
        });
    }
    function get_rooms_details(tr, room) {
        $.ajax({
            method: "POST",
            url: '/bookings/get-room-details',
            data: { 'room_type': room,
                'check_in' : $('#dateTime').val(),
                'check_out' : $('#dateTimeout').val()  },
            success: function (result) {
                if (result != false) {
                    // __write_number($('input#rent-input'), result.rent, false, 2);
                    tr.find('input#rent-input').val(result.rent);
                    tr.find('#adult-number').val(result.adults);
                    tr.find('input#rent-input').trigger('change');
                    tr.find('input#final_total_input').val(result.rent);
                    tr.find('span#total_payable').text(__currency_trans_from_en(result.rent, true));
                }
            }
        });
    }

    function get_complementry(tr, room) {
        $.ajax({
            method: "POST",
            url: '/bookings/get-complementry',
            dataType: "html",
            data: { 'room_type': room },
            success: function (result) {
                if (result) {
                    tr.find('#complementry').html(result);
                   
                }
            }
        });
    }

    $(document).on('change', 'input#bed-count', function () {
        var count = $(this).val();
        var tr = $(this).parents().closest('.tr');
        cal_bed_total(count, tr);
    });
    $(document).on('change', 'input#person-count', function () {
        var count = $(this).val();
        var tr = $(this).parents().closest('.tr');
        cal_person_total(count, tr);
    });
    $(document).on('change', 'input#childs-count', function () {
        var count = $(this).val();
        var tr = $(this).parents().closest('.tr');
        cal_child_total(count, tr);
    });

    function cal_bed_total(count, tr) {
        var room = $('#seachable-room_type').val();
        $.ajax({
            method: "POST",
            url: '/bookings/get-room-details',
            data: { 'room_type': room },
            success: function (result) {
                if (result != false) {
                    var bed_rate = result.bed_charge * count;
                    tr.find('input#bed-amount').val(bed_rate);
                    get_row_total();
                }
            }
        });
    }
    function cal_person_total(count, tr) {
        var room = $('#seachable-room_type').val();
        $.ajax({
            method: "POST",
            url: '/bookings/get-room-details',
            data: { 'room_type': room },
            success: function (result) {
                if (result != false) {
                    var bed_rate = result.person_charge * count;
                    tr.find('input#person-amount').val(bed_rate);
                    get_row_total();
                }
            }
        });
    }

    function cal_child_total(count, tr) {
        var room = $('#seachable-room_type').val();
        $.ajax({
            method: "POST",
            url: '/bookings/get-room-details',
            data: { 'room_type': room },
            success: function (result) {
                if (result != false) {
                    var bed_rate = (result.person_charge / 2) * count;
                    tr.find('input#child_amount').val(bed_rate);
                    get_row_total();
                }
            }
        });
    }

    function cal_total_payable(value)
    {
        var discounted_unit_price = __read_number($('input#final_total_input'));
        var total = discounted_unit_price + value;
        __write_number($('input#final_total_input'), total, false, 2);
        $('span#total_payable').text(__currency_trans_from_en(total, true));
    }

    $("form#guest_add_form").submit(function (e) {
		e.preventDefault();
	}).validate({
		submitHandler: function (form) {
			var data = $(form).serialize();
			$.ajax({
				method: "POST",
				url: $(form).attr("action"),
				dataType: "json",
				data: data,
				success: function (result) {
					if (result.success == true) {
						$("select#customer_id").append($('<option>', {
							value: result.data.id,
							text: result.data.first_name
						}));
						$('select#customer_id option:selected').val(result.data.id).trigger("change");
						$('div.guest_modal').modal('hide');
						toastr.success(result.msg);
					} else {
						toastr.error(result.msg);
					}
				}
			});
            return false;
        }
	});
	$('.guest_modal').on('hidden.bs.modal', function () {
		$('form#guest_add_form')[0].reset();
	});

    $(document).on('change', 'input#discount_amount', function(){
        var discount_type = $('select#discount_type').val();
	    var discount_amount = __read_number($(this));
        var discounted_unit_price = __read_number($('input#final_total_input'));
        var discount = 0;
	    if (discount_amount) {
            if (discount_type == 'fixed' && discount_amount !== 0) {
                discount = discount_amount;
                discounted_unit_price = discounted_unit_price - discount_amount;
            }
            if (discount_type == 'percentage' && discount_amount !== 0) {
                discount = discount_amount/100 * discounted_unit_price;
                discounted_unit_price = discounted_unit_price - discount;
            }
	    }
        $('span#total_payable').text(__currency_trans_from_en(discounted_unit_price, true));
        $('span#total_discount').text(__currency_trans_from_en(discount, true));
        // __write_number($('input.payment-amount'), discounted_unit_price, false, 2);
    });
    $(document).on('change', 'select#discount_type', function(){
        var discount_type = $(this).find('option:selected').val();
	    var discount_amount = __read_number($('input#discount_amount'));
        console.log(discount_type,discount_amount)
        var discounted_unit_price = __read_number($('input#final_total_input'));
        var discount = 0;
	    if (discount_amount) {
            if (discount_type == 'fixed' && discount_amount !== 0) {
                discount = discount_amount;
                discounted_unit_price = discounted_unit_price - discount_amount;
            }
            if (discount_type == 'percentage' && discount_amount !== 0) {
                discount = discount_amount/100 * discounted_unit_price;
                discounted_unit_price = discounted_unit_price - discount;
            }
	    }
        $('span#total_payable').text(__currency_trans_from_en(discounted_unit_price, true));
        $('span#total_discount').text(__currency_trans_from_en(discount, true));
        // __write_number($('input.payment-amount'), discounted_unit_price, false, 2);
    });
    $(document).on('change', 'input#rent-input', function(){
        var amount = __read_number($(this));
        get_row_total();
    });

    $(document).on('change', 'input#complementry_amount', function(){
        get_row_total();
    });
    $(document).on('change', 'select#complementry', function(){
        var complementry = $(this).find('option:selected').val();
        var tr = $(this).parents().closest('.tr');
	    var number = __read_number(tr.find('input#complementry-no'));
        $.ajax({
            method: "POST",
            url: '/complementaries/get-details',
            data: { 'complementry': complementry },
            success: function (result) {
                var rate = parseFloat(result.rate) * number;
                __write_number(tr.find('input#complementry_amount'), rate, false, 2);
                tr.find('input#complementry_amount').trigger('change');
                tr.find('span#complementry_amount').text(__currency_trans_from_en(rate, true));
                get_row_total();
            }
        });
    });

    $(document).on('change', 'input#complementry-no', function(){
        var complementry = $('select#complementry').find('option:selected').val();
        var tr = $(this).parents().closest('.tr');
	    var number = __read_number($(this));
        $.ajax({
            method: "POST",
            url: '/complementaries/get-details',
            data: { 'complementry': complementry },
            success: function (result) {
                var rate = parseFloat(result.rate) * number;
                __write_number(tr.find('input#complementry_amount'), rate, false, 2);
                tr.find('span#complementry_amount').text(__currency_trans_from_en(rate, true));
                get_row_total();
            }
        });
    });
    var wrapper = $(".input_fields_wrap");
    var index = $(".row_set").length;
    $('#add-more-btn').on('click', function(){
        wrapper.append(
            '<div class="tr">'+
                '<div class="border" style="--tw-border-opacity: 1;border-color: black;border-bottom-width:3px;height:2px;"></div>'+
                '<br>'+
                '<div class="grid grid-cols-4 gap-5">'+
                    '<div style="align-items: center; gap: 20px;">'+
                        '<div>'+
                            '<label for="ctnSelect1">Room Type <span class="text-danger">*</span></label>'+
                            '<select class="form-input" name="room_detail['+ index +'][room_type]" required id="seachable-room_type">'+
                                '<option readonly>Room Type</option>'+
                                '@foreach($room_types as $key => $room_type)'+
                                    '<option value="{{$room_type}}" {{request()->get("room_type") && request()->get("room_type") == $room_type ? "selected" : " " }}>{{$room_type}}</option>'+
                                '@endforeach'+
                            '</select>'+
                        '</div>'+
                    '</div>'+
                    '<div style="align-items: center; gap: 20px;">'+
                        '<div>'+
                            '<label for="ctnSelect1">Room No. <span class="text-danger">*</span></label>'+
                            '{!! Form::select("room_detail[INDEX][room_no]", [ request()->get("room_no") ?  request()->get("room_no") : " " => request()->get("room_no") ?  request()->get("room_no") : " "] , request()->get("room_no") ?? " ", ["class" => "form-input", "id" => "room-no","required"]); !!}'.replace("INDEX", index)+
                        '</div>'+
                    '</div>'+
                    '<div style="align-items: center; gap: 20px;">'+
                        '<div>'+
                            '<label for="ctnSelect1">#Adults</label>'+
                            '{!! Form::number("room_detail[INDEX][adults]",  null, ["class" => "form-input", "id" => "adult-number","placeholder" => __("Adults"), "required"]); !!}'.replace('INDEX', index)+
                        '</div>'+
                    '</div>'+
                    '<div style="align-items: center; gap: 20px;">'+
                        '<div >'+
                            '<label for="ctnSelect1">#Children</label>'+
                            '{!! Form::number("room_detail[INDEX][children]",  null, ["class" => "form-input","placeholder" => __("Children")]); !!}'.replace('INDEX', index)+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<br>'+
                '<div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>'+
                '<br>'+
                '<div class="grid grid-cols-3 gap-5">'+
                    '<div style="align-items: center; gap: 20px;">'+
                        '<div>'+
                            '<label for="ctnSelect1">Check In <span class="text-danger">*</span></label>'+
                            '<input id="dateTime_date" class="form-input" name="room_detail['+ index +'][check_in_at]" type="datetime-local" value="{{date("Y-m-d H:i")}}">'+
                        '</div>'+
                    '</div>'+
                    '<div style="align-items: center; gap: 20px;">'+
                        '<div>'+
                            '<label for="ctnSelect1">Check Out <span class="text-danger">*</span></label>'+
                            '<input id="dateTime_in" class="form-input" name="room_detail['+ index +'][check_out_at]" type="datetime-local" value="{{date("Y-m-d H:i")}}">'+
                        '</div>'+
                    '</div>'+
                    '<div style="align-items: center; gap: 20px;">'+
                        '<div>'+
                            '<label for="ctnSelect1">Rent</label>'+
                            '<input type="number" placeholder="Rent" class="form-input" name="room_detail['+ index +'][rent]" id="rent-input" value="0.00" readonly>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<br>'+
                '<div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>'+
                '<br>'+
                '<div class="flex flex-col gap-2.5 xl:flex-row">'+
                    '<div class="col-md-2">'+
                        '<input type="number" placeholder="Bed" class="form-input" name="room_detail['+ index +'][bed_count]" id="bed-count" value="" readonly>'+
                    '</div>'+
                    '<div class="col-md-1">'+                
                        '<input type="number" placeholder="Amount" class="form-input" name="room_detail['+ index +'][bed_amount]"  id="bed-amount" value="" readonly>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<input type="number" placeholder="Person" class="form-input" name="room_detail['+ index +'][person_count]" id="person-count" value="" readonly>'+
                    '</div>'+
                    '<div class="col-md-1">'+
                        '<input type="number" placeholder="Amount" class="form-input" name="room_detail['+ index +'][person_amount]" id="person-amount" value="" readonly>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<input type="number" placeholder="Child" class="form-input" name="room_detail['+ index +'][childs_count]" id="childs-count" value="" readonly>'+
                    '</div>'+
                    '<div class="col-md-1"  style="border-right: 1px solid black;">'+
                        '<input type="number" placeholder="Amount" class="form-input" name="room_detail['+ index +'][child_amount]" id="child_amount" value="" readonly>'+
                    '</div>'+
                    '<div  style="align-items: center; gap: 20px;width:40%;">'+
                        '<input type="hidden" id="complementry_amount" value="">'+
                        '<div class="form-group">'+
                            '{!! Form::select("room_detail[INDEX][complementry_id]", $complementaries , null, ["class" => "form-input", "id" => "complementry","placeholder" => __("Choose Complementry")]); !!}'.replace('INDEX', index)+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<input type="number"  class="form-input" name="room_detail['+ index +'][number]" value="1" readonly id="complementry-no">'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<br>'+
                '<div class="grid grid-cols-1 gap-5">'+
                    '<div style="text-align:right; gap: 20px;"><b>@lang("Total Complementry"): </b>'+
                        '<span id="complementry_amount">0</span>'+
                    '</div>'+
                '</div>'+
                '<br>'+
                '<div class="grid grid-cols-1">'+
                    '<div style="align-items:right; gap: 20px;">'+
                        '<button type="button" class="btn btn-danger remove_field">X</button>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<br>'
        );
        index ++;
    });

    $(wrapper).on("click", ".remove_field", function(e) { 
        e.preventDefault();
        $(this).parents().closest('.tr').remove();
        get_row_total();
       
    })
    $(document).on("click", ".remove_field", function(e) { 
        e.preventDefault();
        $(this).parents().closest('.tr').remove();
        get_row_total();
       
    })
    function get_row_total()
    {
        var total = 0;
        $('div.tr').each(function(i,v){
            var tr = $(this).closest('.tr');
            var rent = __read_number(tr.find('input#rent-input'));
            var complementry = __read_number(tr.find('input#complementry_amount'));
            var bed_amount = __read_number(tr.find('input#bed-amount'));
            var person_amount = __read_number(tr.find('input#person-amount'));
            var child_amount = __read_number(tr.find('input#child_amount'));
            var row_total = rent + complementry + bed_amount + person_amount + child_amount;
            total += row_total;
        });

        __write_number($('input#final_total_input'), total, false, 2);
        $('span#total_payable').text(__currency_trans_from_en(total, true));
    }
</script>
@endsection