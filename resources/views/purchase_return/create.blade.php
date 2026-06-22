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
                <a href="{{action('PurchaseReturnController@index')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">Purchase Returns</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Create Purchase Return</span>
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
                    <div class=" sm:ltr:mr-auto sm:rtl:ml-auto" @click.outside="search = false" style="display: flex;
                    align-items: center;
                    justify-content: center;" class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0
                        sm:mx-0 sm:block sm:translate-y-0" @submit.prevent="search = false">
                        <div class="relative" style="width: 50%;">
                            <input type="text"
                                class="peer border-colored form-input bg-gray-100 placeholder:tracking-widest ltr:pl-9 ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                                placeholder="ENTER THE REF NO"
                                id="search_ref" />
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
                    </div><br><br>
                {!! Form::open(['url' => action('PurchaseReturnController@store'), 'method' => 'post', 'id' => 'add_purchase_return_form', 'files' => true ]) !!}
                        <div class="purchase-return-div">
                            @include('purchase_return.partial.row_data_form', ['row_count' => 0])    
                        </div>

                        <div class="buttons"
                            style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 50px;">
                            <button type="button" data-status="received" class="btn btn-primary btn-lg btn-flat  gap-2" id="submit_purchase_return">RETURN</button>
                            <a href="{{action('PurchaseReturnController@index')}}" class="btn btn-dark btn-lg btn-flat gap-2">BACK</a>
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

<script src="{{asset('js/purchase-return.js')}}"></script>
<script src="{{asset('js/purchase.js')}}"></script>
<script src="{{ asset('js/product.js') }}"></script>
<script type="text/javascript">
 
</script>
@endsection
