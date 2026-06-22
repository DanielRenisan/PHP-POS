@extends('layouts.app_rest')
@section('content')
<style>
    ::-webkit-scrollbar {
        display: none;
    }

    .mt-100 {
        margin-top: 100px
    }

    .multibody {
        background: #ffffff;
        background: -webkit-linear-gradient(to right, #ffffff, #ffffff);
        background: linear-gradient(to right, #ffffff, #ffffff);
        color: #514B64;
    }
</style>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li>
                <a href="{{action('PurchaseController@index')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">Purchases</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Add Payment</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                <div class="px-5">
                    <div class="product" x-data="form">
                    <div class="row  grid grid-cols-3 gap-4 mb-5" style="width: 100%;">
                        <div class="box" style="padding: 10px;width:20%">
                            <div class="top flex items-center gap-2">
                                <div
                                    class="grid h-9 w-9 place-content-center rounded-full bg-success-light text-success dark:bg-success dark:text-success-light">
                                    <i class="fa fa-user"></i>
                                </div>
                                <h6>Customer</h6>
                            </div>
                            <div class="bottom">
                                <h2>{{$transaction->customer->first_name ?? ''}}</h2>
                            </div>
                            <!-- <p class="m-0">Total Sales for this Month</p> -->
                        </div>
                        <div class="box" style="padding: 10px;width:20%">
                            <div class="top flex items-center gap-2">
                                <div
                                    class="grid h-9 w-9 place-content-center">
                                    Invoice No : 
                                </div>
                                <h6>{{$transaction->ref_no ?? ''}}</h6>
                            </div>
                            <div class="bottom">
                                <h2>Location : {{$transaction->location->name ?? ''}}</h2>
                            </div>
                            <!-- <p class="m-0">Total Sales for this Month</p> -->
                        </div>
                        @php 
                            $paid = App\Models\TransactionPayment::where('transaction_id', $transaction->id)->sum('amount');
                            $due = $transaction->final_total - $paid;
                        @endphp
                        <div class="box" style="padding: 10px;">
                            <div class="top flex items-center gap-2">
                                <h2>Total Amount : <span class="display_currency" data-currency_symbol="true">{{$transaction->final_total ?? ''}}</span></h2>
                            </div>
                            <div class="top flex items-center gap-2">
                                <h2>Paid Amount : <span class="display_currency" data-currency_symbol="true">{{$paid ?? ''}}</span></h2>
                            </div>
                            <div class="bottom">
                            <h2>Due Amount : <span class="display_currency" data-currency_symbol="true">{{$due ?? ''}}</span></h2>
                            </div>
                            <!-- <p class="m-0">Total Sales for this Month</p> -->
                        </div>
                    </div>
                        <form enctype="multipart/form-data" id="product_add_form" class="needs-validation" method="POST"
                            action="{{ action('TransactionPaymentController@store') }}">
                            @csrf
                            {!! Form::hidden('transaction_id', $transaction->id); !!}
                            <input type="hidden" name="redirect_url" value="{{ url()->previous() }}">
                            <div class="grid grid-cols-2" style="gap: 100px; margin-top: 20px;">
                            @include('add_payment.partials.payment_row_form', ['row_index' => 0])
                            </div>
                            <div class="row" style="display: flex;float:right">
                                <button type="submit" class="btn btn-success pull-right">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection