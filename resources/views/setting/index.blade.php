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
                <span>Business Setting</span>
            </li>
        </ul>
        {!! Form::open(['url' => action('BussinessController@store'), 'method' => 'post', 'id' => 'bussiness_edit_form',
           'files' => true ]) !!}
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-semibold dark:text-white-light">Business Setting</h3>
                </div>
                <div class="my-4 h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                    <div class="mb-5">
                        <div class="grid grid-cols-3 gap-4 pt-5">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('name',__('Business Name') . ':*') !!}
                                    {!! Form::text('name', $business->name ?? '', ['class' => 'form-input', 'required',
                                    'placeholder' => __('Business Name')]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    @php
                                        $start_date = null;
                                        if(!empty($business->start_date)){
                                            $start_date = date('m/d/Y', strtotime($business->start_date));
                                        }
                                    @endphp
                                    {!! Form::label('start_date', __('Start Date') . ':') !!}
                                    <input type="date" class="form-input" value="{{$start_date}}"  name="start_date" >
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('default_profit_percent', __('Default Profit Percent') . ':') !!} @show_tooltip(__('Default Profit Percent'))
                                    {!! Form::number('default_profit_percent', $business->default_profit_percent, ['class' => 'form-input', 'min' => 0, 
                                        'step' => 0.01, 'max' => 100]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('currency_id', __('Currency') . ':') !!}
                                    {!! Form::select('currency_id', $currencies, $business->currency_id, ['class' => 'form-input','placeholder' => __('Currency'), 'required']); !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('time_zone', __('Timezone') . ':') !!}
                                    {!! Form::select('time_zone', $timezone_list, $business->time_zone, ['class' => 'form-input', 'required']); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('business_logo', __('Business Logo') . ':') !!}
                                    <input class="form-input" id="file-upload" type="file" name="business_logo" style="object-fit: contain;"accept="image/*" />
                                        <p class="help-block"><i> @lang('Previous logo (if exists) will be replaced')</i></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('fy_start_month', __('Financial year start month') . ':') !!} @show_tooltip(__('Starting month of The Financial Year for your business'))
                                        {!! Form::select('fy_start_month', $months, $business->fy_start_month, ['class' => 'form-input', 'required']); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('accounting_method', __('Accounting Method') . ':*') !!}
                                    @show_tooltip(__('Accounting method'))
                                    {!! Form::select('accounting_method', $accounting_methods, $business->accounting_method, ['class' => 'form-input', 'required']); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('country',__('Country') . ':*') !!}
                                    {!! Form::text('country', $business->country, ['class' => 'form-input', 'required',
                                    'placeholder' => __('Country')]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('reg_doc_no',__('Reg Doc No') . ':*') !!}
                                    {!! Form::text('reg_doc_no', $business->reg_doc_no, ['class' => 'form-input',
                                    'placeholder' => __('Reg Doc No')]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('fax_no',__('Fax No') . ':') !!}
                                    {!! Form::text('fax_no', $business->fax_no, ['class' => 'form-input',
                                    'placeholder' => __('Fax No')]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('website',__('Website') . ':') !!}
                                    {!! Form::text('website', $business->website, ['class' => 'form-input',
                                    'placeholder' => __('website')]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('day_duration', __('Duration') . ':*') !!} @show_tooltip(__('Consider No of Hours'))
                                    {!! Form::number('day_duration', $business->day_duration, ['class' => 'form-input', 'min' => 0, 
                                        'max' => 24]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('address',__('Address Line 1') . ':*') !!}
                                    {!! Form::text('address', $business->address, ['class' => 'form-input',
                                    'placeholder' => __('Address')]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('address_two',__('Address Line 2') . ':') !!}
                                    {!! Form::text('address_two', $business->address_two, ['class' => 'form-input',
                                    'placeholder' => __('Address')]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('city',__('City') . ':') !!}
                                    {!! Form::text('city', $business->city, ['class' => 'form-input',
                                    'placeholder' => __('city')]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('mobile',__('Mobile') . ':') !!}
                                    {!! Form::text('mobile', $business->mobile, ['class' => 'form-input',
                                    'placeholder' => __('mobile')]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('phone',__('Phone') . ':') !!}
                                    {!! Form::text('phone', $business->phone, ['class' => 'form-input',
                                    'placeholder' => __('phone')]); !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('email',__('Email') . ':') !!}
                                    {!! Form::text('email', $business->email, ['class' => 'form-input',
                                    'placeholder' => __('email')]); !!}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {!! Form::label('printer_display', __('Printer Display') . ':') !!}
                                    {!! Form::select('printer_display',['KotDisplay' => 'KotDisplay', 'DirectPrint' => 'Direct Print'], $business->printer_display, ['class' => 'form-input','placeholder' => __('Currency'), 'required']); !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-semibold dark:text-white-light">Restaurant Setting</h3>
                </div>
                <div class="my-4 h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                    <div class="mb-5">
                        <div class="grid grid-cols-4 gap-4 pt-5">
                            <div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="form-checkbox"  name="is_bot"
                                        value="1" {{$business->is_bot == 1 ? 'checked' : ''}}>
                                    <span class="text-white-dark">Enable BOT</span>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="form-checkbox"  name="is_kot" {{$business->is_kot == 1 ? 'checked' : ''}}
                                        value="1" >
                                    <span class="text-white-dark">Enable KOT</span>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="form-checkbox"  name="is_need_food_calculation"
                                        value="1" {{$business->is_need_food_calculation == 1 ? 'checked' : ''}}>
                                    <span class="text-white-dark">Enable Food Calculation</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="row">
                <div class="col-md-12 text-right" style="padding-right:30px">
                    <button class="btn btn-primary pull-right" type="submit">@lang('UPDATE')</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection