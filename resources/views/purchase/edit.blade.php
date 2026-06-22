@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li>
                <a href="{{action('PurchaseController@index')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">Purchase</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Edit Purchase</span>
            </li>
        </ul>
        <!-- Page level currency setting -->
        <input type="hidden" id="p_code" value="LKR">
        <input type="hidden" id="p_symbol" value="₨">
        <input type="hidden" id="p_thousand" value=",">
        <input type="hidden" id="p_decimal" value=".">
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                <div class="px-5">
                {!! Form::open(['url' => action('PurchaseController@update', $purchase->id), 'method' => 'put', 'id' => 'add_purchase_form', 'files' => true ]) !!}
                    <input type="hidden" name="location_id" value="1">
                    <input type="hidden" name="status" id="purchase-status">
                        <div class="grid grid-cols-2 gap-5" style="margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <label style="width: 20%;" for="supplier">Supplier <span>*</span></label>
                                {!! Form::select('contact_id', $suppliers , $purchase->contact_id, ['class' => 'form-input', 'id' => 'supplier_id', 'required',
                                'placeholder' => __('Please Select')]); !!}
                            </div>
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <label style="width: 20%;" for="referenceNo">Reference No</label>
                                <input class="form-input" type="text" id="referenceNo" name="ref_no" readonly value="{{$purchase->ref_no}}">
                            </div>
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <label style="width: 20%;" for="document">Document</label>
                                <input class="form-input" type="file" id="document" name="document">
                            </div>
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <label style="width: 20%;" for="datepicker">Purchase Date <span>*</span></label>
                                <input class="form-input" type="date" id="transaction_date" name="transaction_date" value="{{date('Y-m-d', strtotime($purchase->transaction_date))}}">
                            </div>
                        </div>

                        <div class=" sm:ltr:mr-auto sm:rtl:ml-auto" @click.outside="search = false" style="display: flex;
                        align-items: center;
                        justify-content: center;" class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0
                            sm:mx-0 sm:block sm:translate-y-0" @submit.prevent="search = false">
                            <div class="relative" style="width: 50%;">
                                <input type="text"
                                    class="peer border-colored form-input bg-gray-100 placeholder:tracking-widest ltr:pl-9 ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                                    placeholder="Product Name / SKU / Barcode"
                                    id="search_product" />
                                <button type="button"
                                    class="absolute inset-0 h-9 w-9 appearance-none peer-focus:text-primary ltr:right-auto rtl:left-auto btn-hover">
                                    <svg class="mx-auto" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                            opacity="0.5" />
                                        <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            @include('purchase.partial.edit_purchase_entry_row')
                        </div>

                        <div class="grid grid-cols-2" style="margin-top: 20px; gap: 150px;">
                            <div class="left" style="padding: 20px;">
                                <div class="grid grid-cols-2">
                                    <div style="align-items: center; gap: 10px; margin-bottom: 10px;width:90%;">
                                        <label for="discountType">Discount Type</label>
                                        <select class="form-select" name="discount_type" id="discount_type">
                                            <option value="percentage" {{$purchase->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                            <option value="fixed" {{$purchase->discount_type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        </select>
                                    </div>
                                    <div style="align-items: center; gap: 20px; margin-bottom: 10px;width:90%;">
                                        {!! Form::label('discount_amount', __( 'Discount Amount' ) . ':') !!}
							            {!! Form::text('discount_amount', $purchase->discount_amount, ['class' => 'form-input input_number']); !!}
                                    </div>
                                </div>
                                @php 
                                    $tax_data = \App\Models\Tax::find($purchase->tax_id); 
                                @endphp
                                <div class="grid grid-cols-1">    
                                    <div style="align-items: center; gap: 20px; margin-bottom: 10px;width:95%;">
                                        <label style="width: 40%;" for="tax">Tax</label>
                                        <select class="form-select" name="tax_id" id="tax_id">
                                            <option value="" data-tax_amount="0" data-tax_type="fixed" selected>None</option>
                                            @foreach($taxes as $tax)
                                                <option value="{{ $tax->id }}" {{$purchase->tax_id == $tax->id ? 'selected' : ''}} data-tax_amount="{{ $tax->amount }}" data-tax_type="{{ $tax->calculation_type }}">{{ $tax->name }}</option>
                                            @endforeach
                                        </select>
                                        {!! Form::hidden('tax_amount', $tax_data->amount ?? '', ['id' => 'tax_amount']); !!}
                                    </div>
                                </div>
                            </div>
                            <div class="right" style="background-color: #f9f9f9; border-radius: 5px; padding: 20px;">
                                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 10px;">
                                    <label style="width: 30%;" for="returnType">Sub Total</label>
                                    <span id="total_subtotal" class="display_currency">{{$purchase->lines_of_purchase->sum('line_total')}}</span>
									<!-- This is total before purchase tax-->
                                    {!! Form::hidden('final_total', $purchase->final_total , ['id' => 'grand_total_hidden']); !!}
									<input type="hidden" id="total_subtotal_input" value="{{$purchase->lines_of_purchase->sum('line_total')}}"  name="total_before_tax">
                                    <!-- <input type="text" class="form-input" > -->
                                </div>
                                @php
                                    $sub_total = $purchase->lines_of_purchase->sum('line_total');
                                    $discount = 0;
                                    if($purchase->discount_type == 'percentage')
                                    {
                                        $discount = ($purchase->discount_amount/100) * $sub_total;
                                    }
                                    else 
                                    {
                                        $discount = $purchase->discount_amount;
                                    }
                                @endphp
                                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 10px;">
                                    <label style="width: 30%;" for="returnType">Discount</label>
                                    <span id="discount_calculated_amount" class="display_currency">{{$discount}}</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 10px;">
                                    <label style="width: 30%;" for="returnType">Tax</label>
                                    <span id="tax_calculated_amount" class="display_currency">{{ $purchase->tax_amount}}</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <label style="width: 30%;" for="returnType">Grand Total</label>
                                    <span id="grand_total_text" class="display_currency">{{ $purchase->final_total }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2" style="gap: 100px; margin-top: 20px;">
                            <div class="bottm-left">
                                <div>
                                    <label style="width: 40%;" for="returnType">Note</label>
                                    <textarea class="form-input" placeholder="Note..." name="details" id="" cols="30"
                                        rows="8">{{ $purchase->deatils }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="buttons"
                            style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 50px;">
                            <button type="button" data-status="pending"  class="btn btn-warning btn-lg btn-flat  gap-2" id="submit_purchase_form">PO</button>
                            <button type="button" data-status="received" class="btn btn-primary btn-lg btn-flat  gap-2" id="submit_purchase_form">GRN</button>
                            <a href="{{action('PurchaseController@index')}}" class="btn btn-dark btn-lg btn-flat gap-2"
                                        >BACK</a>
                        </div>
                    {!! Form::close() !!}
                </div>
                <input type="hidden" id="row_count" value="0"> 
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')

<script src="{{asset('js/purchase.js')}}"></script>
<script src="{{ asset('js/product.js') }}"></script>
<script type="text/javascript">
 
</script>
@endsection