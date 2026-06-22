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
                <a href="{{action('Rest\ProductController@create')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">Product</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Add Open Stock</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                <div class="px-5">
                    <div class="product">
                        <form enctype="multipart/form-data" id="product_add_form" class="needs-validation" method="POST"
                            action="{{ action('OpenStockController@store') }}">
                            @csrf
                            {!! Form::hidden('product_id', $product->id); !!}
                            @foreach($locations as $key => $value)
                            <div class="row" style="display: flex;">
                                <div class="total-details grid grid-cols-1 gap-4 mb-5" style="width: 100%;">
                                    <p class="box-title">Location : {{$value}}</p>
                                </div>
                            </div>
                            <div class="row" style="display: flex;">
                                <table class="table table-condensed table-bordered text-center table-striped add_opening_stock_table" style="width:100%">
                                    <thead>
                                    <tr style="border:none;background-color:skyblue !important;color:white;">
                                        <th>Product Name</th>
                                        <th width="20%">Quantity</th>
                                        <th>Purchase Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <td>{{ $product->name }} ({{ $product->sku_code }})</td>
                                        <td>
                                        {!! Form::text('stocks[' . $key . '][quantity]', null, ['class' => 'form-input input-sm input_number purchase_quantity', 'required']); !!}
                                        <span class="input-group-addon">
                                            {{ $product->pur_unit->short_code ?? $product->unit->short_code ?? '' }}
                                        </span>
                                        </td>
                                        <td>
                                            {!! Form::text('stocks[' . $key . '][purchase_price]', $product->last_purchase_price , ['class' => 'form-input input-sm input_number unit_price', 'required']); !!}
                                        </td>
                                    </tbody>
                                </table>
                            </div>
                            @endforeach
                            <div class="row" style="display: flex;float:right">
                                <button type="submit" class="btn btn-success pull-right">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</div>
@endsection