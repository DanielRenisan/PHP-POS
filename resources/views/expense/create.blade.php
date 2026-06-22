@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li>
                <a href="{{action('ExpenseController@index')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">Expenses</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Add Expense</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            {!! Form::open(['url' => action('ExpenseController@store'), 'method' => 'post', 
'id' => 'transaction_add_form','class' => 'transaction_form', 'files' => true ]) !!}
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                </div>
                <div class="mb-5">
                    <input type="hidden" name="transaction_id" value="{{isset($expense) ? $expense->id : '' }}">

                    <div class="grid grid-cols-3 gap-5">
                            @if(count($business_locations) == 1)
                                @php 
                                    $default_location = current(array_keys($business_locations->toArray())) 
                                @endphp
                            @else
                                @php $default_location = null; @endphp
                            @endif
                            <div>
                                <label for="supplier">Business Location<span>*</span></label>
                                {!! Form::select('location_id', $business_locations, isset($expense) ? $expense->location_id : $default_location, ['class' => 'form-input', 'id' => 'purchase_location_id', 'placeholder' => __('Please Select'), 'required']); !!}
                            </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('expense_type', 'Expense Type: *') !!}
                                <select name="expense_type" class="form-input" id="type"  required> 
                                    <option value="">Select One</option> 
                                    <option value="Employee" {{isset($expense) && $expense->expense_type == 'Employee' ? 'selected' : ''}}>Employee</option> 
                                    <option value="Other" {{isset($expense) && $expense->expense_type == 'Other' ? 'selected' : ''}}>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="customer-div"  style="display:{{ isset($expense) && $expense->expense_type == 'Customer' ? '' : 'none' }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('room_id', 'Room :') !!}
                                    <select name="room_id" class="form-input" id="contact_id"> 
                                        @foreach($rooms as $key => $room) 
                                        <option value="{{$room}}" {{ isset($expense) && $expense->expense_type == 'Customer' &&   $expense->room_no == $room ? 'selected' : '' }}>{{$room}}</option> 
                                        @endforeach
                                    </select>
                                </div>
                            </div>
				        </div>
                        <div class="staff-div"  style="display:{{ isset($expense) && $expense->expense_type == 'Employee' ? '' : 'none' }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('staff_id', 'Staff :') !!}
                                    <select name="staff_id" class="form-input" id="staff_id"> 
                                        @foreach(App\Models\User::get() as $user) 
                                        <option value="{{$user->id}}" {{ isset($expense) && $expense->expense_type == 'Employee' &&  $expense->staff_id == $user->id ? 'selected' : '' }}>{{$user->first_name}}</option> 
                                        @endforeach
                                    </select>
                                </div>
                            </div>
				        </div>
                        <div class="room-div"  style="display:{{ isset($expense) && $expense->expense_type == 'Room' ? '' : 'none' }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('room_no', 'Room No :') !!}
                                    <input type="text" placeholder="Room No" class="form-input" name="room_no" id="room_no" value="{{isset($expense) && $expense->expense_type == 'Room' ? $expense->room_no : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('invoice_no', __('Reference No:').':') !!}
                                {!! Form::text('invoice_no', isset($expense) && $expense->invoice_no ? $expense->invoice_no : '', ['class' => 'form-input']); !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ctnSelect1">Date* </label>
                                <input id="basic" class="form-input" name="transaction_date" type="date" value="{{isset($expense) ? $expense->transaction_date : '' }}" required>
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
                </div>
            </div>
            <div class="panel" style="display:{{ isset($expense) ? 'none' : ' ' }}">
                <h4>Payment Details</h4>
                <div class="mb-5">
                    @include('booking.partials.payment_row_form', ['row_index' => 0])
                </div>
            </div>

            <div class="panel">
                <div class="mb-5 row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pull-right">SAVE</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('assets/js/alpine-collaspe.min.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/alpine-persist.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-ui.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-focus.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine.min.js?v=' . $asset_v) }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/nice-select2.css?v='.$asset_v) }}">
<script src="{{ asset('assets/js/nice-select2.js?v=' . $asset_v) }}"></script>
    
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(e) {
            // seachable 
            var options = {
                searchable: true
            };
            NiceSelect.bind(document.getElementById("staff_id"), options);
            NiceSelect.bind(document.getElementById("contact_id"), options);
            NiceSelect.bind(document.getElementById("seachable-category"), options);
            NiceSelect.bind(document.getElementById("seachable-sub-cate"), options);
        });
        document.addEventListener("alpine:init", () => {
            Alpine.data("form", () => ({
                date1: new Date().toISOString().substr(0, 10),
                init() {
                    flatpickr(document.getElementById('basic'), {
                        dateFormat: 'Y-m-d',
                        defaultDate: this.date1,
                    })
                }
            }));
        });
        $(document).on('change', 'select#type', function () {
            if($(this).val() == 'Employee')
            {
                $('.staff-div').show();
                $('.room-div').hide();
                $('.customer-div').hide();
                document.getElementById('staff_id').required = true;
                document.getElementById('room_no').required = false;
                document.getElementById('contact_id').required = false;
            }
            if($(this).val() == 'Room')
            {
                $('.staff-div').hide();
                $('.room-div').show();
                $('.customer-div').hide();
                document.getElementById('staff_id').required = false;
                document.getElementById('room_no').required = true;
                document.getElementById('contact_id').required = false;
            }
            if($(this).val() == 'Customer')
            {
                $('.staff-div').hide();
                $('.room-div').hide();
                $('.customer-div').show();
                document.getElementById('staff_id').required = false;
                document.getElementById('room_no').required = false;
                document.getElementById('contact_id').required = true;
            }

            if($(this).val() == '')
            {
                $('.staff-div').hide();
                $('.room-div').hide();
                $('.customer-div').hide();
                document.getElementById('staff_id').required = false;
                document.getElementById('room_no').required = false;
                document.getElementById('contact_id').required = false;
            }
        });

        $('#seachable-category').change(function () {
		    get_sub_categories();
        });
        function get_sub_categories() {
            var cat = $('#seachable-category').val();
            $.ajax({
                method: "POST",
                url: '/expenses/get-sub-category',
                dataType: "html",
                data: { 'cat_id': cat },
                success: function (result) {
                    if (result) {
                        $('#seachable-sub-cate').html(result);
                    }
                }
            });
        }
        $(document).on('change', 'input#final_amount', function () {
		var total = $(this).val();
		$('input.payment-amount').val(total);
	});
        </script>
@endsection
