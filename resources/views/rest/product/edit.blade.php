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
                <span>Edit Product</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                <div class="px-5">
                    <div class="product" x-data="form">
                        <form enctype="multipart/form-data" id="product_add_form" class="needs-validation" novalidate="" method="POST"
                            action="{{ route('product.update', $product->id) }}">
                            @csrf
                            <div class="row" style="display: flex;">
                                <div class="col left" style="width: 35%; padding: 0px 10px 10px 10px">
                                    <div class="left-image">
                                        <img src="{{ $image_url }}"
                                            alt="" style="width: 400px; height: 190px; border-radius: 10px;" id="file-preview">
                                        <input class="form-input" id="file-upload" type="file" name="image" style="object-fit: contain;"accept="image/*" />
                                    </div>
                                    <div class="left-details">
                                        <div style="display: flex; align-items: center; gap: 20px;">
                                            <div>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="radio" name="product_type" class="form-radio outline-success" id="product_type"
                                                        value="0" {{ $product->product_type == 0 ? 'checked' : ''}}/>
                                                    <span class="text-white-dark">Default</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="radio" name="product_type" class="form-radio outline-success"  id="product_type"
                                                        value="1" {{ $product->product_type == 1 ? 'checked' : ''}}/>
                                                    <span class="text-white-dark">Specific Department</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="department-div" style="display:{{$product->product_type == 1 ? '' : 'none'}}">
                                            <div class="multibody">
                                                <select class="form-select text-white-dark" name="department[]"
                                                    style="font-size: 14px; font-weight: bold;line-height:1.25rem;" id="choices-multiple-remove-button"
                                                    placeholder="Select Department" multiple required>
                                                    @foreach ($departement as $dp)
                                                    <option class="pro-type" value="{{ $dp->id }}" {{ in_array($dp->id, $product_departments) ? 'selected' : '' }}>
                                                        {{ $dp->name }}</option>
                                                    @endforeach
                                                    <span class="text-danger">
                                                        @error('department_id')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </select>
                                            </div>
                                        </div>
                                        <div  style="display: flex !important; align-items: center; gap: 20px !important;">
                                            <div class="enable-stock-div" style="display:{{in_array(1,$product_attries) ? '' : 'none'}}">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox" id="enable-check-box" name="enable_stock" value="1"  {{$product->enable_stock == 1 ? 'checked' : ''}}
                                                    >
                                                    <span class="text-white-dark">Enable Stock</span>
                                                </label>
                                            </div>
                                            <div class="sale-stock-div" style="display:{{in_array(1,$product_attries) ? '' : 'none'}}">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox" id="openstock-check-box"  name="open_stock" {{$product->enable_stock == 0 ? 'disabled' : ''}} {{$product->open_stock == 1 ? 'checked' : ''}}
                                                        value="1" >
                                                    <span class="text-white-dark">Is Open Stock</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div style="display: flex !important; align-items: center; gap: 20px !important;">
                                            <div  class="stock-quantity-div" style="display:{{$product->enable_stock == 1 ? '' : 'none'}}">
                                                <h2 style="font-size: 14px; font-weight: bold;">Alert quantity:*</h2>
                                                <input class="form-input" placeholder="Alert quantity" min="0" name="alert_quantity" type="number" id="alert_quantity" value="{{$product->alert_quantity}}">
                                            </div>
                                            <div  class="stock-quantity-div" style="display:{{$product->open_stock == 1 ? '' : 'none'}}">
                                                <h2 style="font-size: 14px; font-weight: bold;">Open Stock</h2>
                                                <input class="form-input" placeholder="Open Stock" min="0" name="stock" type="number" id="stock_quantity"   value="{{$product->stock}}">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 pt-5">
                                            <div  class="catelog-div" style="display:{{in_array(3,$product_attries) ? '' : 'none'}}">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox"  name="is_kot"
                                                        value="1" {{$product->is_kot == 1 ? 'checked' : ''}}>
                                                    <span class="text-white-dark">Is KOT</span>
                                                </label>
                                            </div>
                                            <div  class="sale-div">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox"  name="is_bot"
                                                        value="1" {{$product->is_bot == 1 ? 'checked' : ''}}>
                                                    <span class="text-white-dark">Is BOT</span>
                                                </label>
                                            </div>
                                        </div>
                                        @isset($stations)
                                        <div class="mt-5">
                                            <h2 style="font-size: 14px; font-weight: bold;">Stations (Order Ticket Places)</h2>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 pt-2">
                                                @foreach($stations as $station)
                                                    <label class="flex items-center cursor-pointer">
                                                        <input type="checkbox" class="form-checkbox" name="station_ids[]" value="{{ $station->id }}"
                                                            {{ in_array($station->id, $product_station_ids ?? []) ? 'checked' : '' }}>
                                                        <span class="ml-2">{{ $station->name }} <small class="text-gray-500">({{ $station->code }})</small></span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Select one or more stations. Each selected station will print its own ticket when this product is ordered.</p>
                                        </div>
                                        @endisset
                                        <div class="mt-5">
                                            <h2 style="font-size: 14px; font-weight: bold;">Product Name:*</h2>
                                            <input class="form-input" required="required" placeholder="Product Name" name="name" type="text" id="name" value="{{ $product->name }}">
                                        </div>
                                        <div class="mt-5">
                                            <h2 style="font-size: 14px; font-weight: bold;">SKU Code</h2>
                                            <input type="text" class="form-input" name="sku_code" disabled value="{{ $product->sku_code }}">
                                        </div>
                                        <div class="mt-5">
                                            <h2 style="font-size: 14px; font-weight: bold;">Bar Code</h2>
                                            <input type="text" class="form-input" name="barcode" value="{{ $product->barcode }}">
                                        </div>
                                        <div class="mt-5">
                                            <h2 style="font-size: 14px; font-weight: bold;">Alert quantity:*</h2>
                                            <input class="form-input" required="required" placeholder="Alert quantity" min="0" name="alert_quantity" type="number" id="alert_quantity" value="{{ $product->alert_quantity }}">
                                        </div>
                                        <div class="mt-5">
                                            <h2 style="font-size: 14px; font-weight: bold;">Description</h2>
                                            <textarea class="form-input" name="description" placeholder="Description...">{!! $product->description !!}</textarea>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 20px; margin-top: 20px;">
                                            <div>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox"  name="status"  {{ $product->status == 1 ? "checked" : ''}}
                                                        value="1" >
                                                    <span class="text-white-dark">Status</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col right" style="width: 75%;">
                                    <div class="right-top-panel"
                                        style="border: .5px solid lightgray; padding: 20px; margin: 10px 0; border-radius: 5px; position: relative">
                                        <h2 style="margin: 0; font-size: 16px; font-weight: bold; position: absolute; top: -12px; left: 5px; background-color: #fafafaeb; padding: 2px 10px; border-radius: 5px;">
                                            Item Attributes</h2>
                                        <div style="display: flex; align-items: center; gap: 50px; margin-top: 15px;">
                                            @foreach($product_attres as $attry)
                                            @php
                                                $attr_value = '';
                                                $function = "";
                                                if($attry->name == 'Is For Sales')
                                                {
                                                    $attr_value = 'showIsForSales';
                                                    $function = '';
                                                }
                                                if($attry->name == 'Is Purchased')
                                                {
                                                    $attr_value = 'showIsPurchased';
                                                    $function = "";
                                                }
                                                if($attry->name == 'Digital Menu')
                                                {
                                                    $attr_value = 'showIsForCategory';
                                                    $function = "";
                                                }
                                            @endphp
                                            <div>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox" name="product_attry[]" id="product_attry"
                                                        x-on:change="{{$function}}"  {{in_array($attry->id, $product_attries) ? 'checked' : ''}} value="{{$attry->id}}" >
                                                    <span class="text-white-dark">{{ $attry->name }}</span>
                                                </label>
                                            </div>
                                            @endforeach
                                            <div>
                                                <label class="flex items-center cursor-pointer">
                                                    <button type="button" class="btn btn-primary btn-lg right" id="product-submit-btn">UPDATE</button>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="contents">
                                        <div class="is-for-sales">
                                                <div class="grid grid-cols-3 gap-4"
                                                    style="border: .5px solid lightgray; padding: 20px; margin: 10px 0; border-radius: 5px;">
                                                    <div class="category-div"  style="display:{{in_array(1, $product_attries) || in_array(2, $product_attries) ? '' : 'none'}}">
                                                        <label for="category">Product Category</label>
                                                        <select class="form-select text-white-dark" name="category_id"
                                                            id="new_category_id" required>
                                                            <option value="" disabled selected>Select Category</option>
                                                            @foreach ($productCategory as $pc)
                                                            <option class="pro-type" value="{{ $pc->id }}"
                                                                {{ $pc->id == $product->category_id ? 'selected' : '' }}>
                                                                {{ $pc->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('product_categeories_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>
                                                    <div  class="sub-category-div" style="display:{{in_array(1, $product_attries) || in_array(2, $product_attries) ? '' : 'none'}}">
                                                        <label for="sub-category">Sub Category</label>
                                                        <select class="form-select text-white-dark" name="sub_category_id"
                                                            id="new_sub_category_id">
                                                            <option value="" disabled selected>Select Category</option>
                                                            @foreach ($subCategory as $sc)
                                                            <option class="pro-type" value="{{ $sc->id }}"
                                                                {{ $sc->id == $product->sub_category_id ? 'selected' : '' }}>
                                                                {{ $sc->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('sub_category_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>
                                                    <div class="brand-div" style="display:{{in_array(1, $product_attries) || in_array(2, $product_attries) ? '' : 'none'}}">
                                                        <label for="brand">Brand</label>
                                                        <select class="form-select text-white-dark" name="brand_id" id="brand">
                                                            <option value="" disabled selected>Select Brand</option>
                                                            @foreach ($brand as $b)
                                                            <option class="pro-type" value="{{ $b->id }}" {{ $b->
                                                                id == $product->brand_id ? 'selected' : '' }}>
                                                                {{ $b->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('brand_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="menu-div"  style="display:{{in_array(3, $product_attries) ? '' : 'none'}}">
                                                        <label for="menu">Menu</label>
                                                        <select class="form-select text-white-dark" name="menu_id"  x-bind:disabled="(showIsForSales === false || showIsPurchased  === false ) && {{in_array(3,$product_attries) ? 'showIsForCategory === true': '!showIsForCategory'}}">
                                                            <option value="" disabled selected>Select Menu</option>
                                                            @foreach ($menu as $mn)
                                                            <option class="pro-type" value="{{ $mn->id }}" {{ $mn->
                                                                id == $product->menu_id ? 'selected' : '' }}>
                                                                {{ $mn->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('menu_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>
                                                    <!-- <div class="drink-type-menu"  style="display:{{in_array(3, $product_attries) ? '' : 'none'}}">
                                                        <label for="drinkType">Drink Type</label>
                                                        <select class="form-select text-white-dark" name="drink_type_id">
                                                            <option value="" disabled selected>Select Type</option>
                                                            @foreach ($drintType as $dt)
                                                            <option class="pro-type" value="{{ $dt->id }}" {{ $dt->
                                                                id == $product->drink_type_id ? 'selected' : '' }}>
                                                                {{ $dt->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('drink_type_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div> -->
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div class="tax" class="grid grid-cols-3 gap-4"
                                                        style="border: .5px solid lightgray; padding: 20px; margin: 10px 0; border-radius: 5px; position: relative;">
                                                        <h2
                                                            style="margin: 0; font-size: 16px; font-weight: bold; position: absolute; top: -12px; left: 5px; background-color: #fafafaeb; padding: 2px 10px; border-radius: 5px;">
                                                            Tax</h2>
                                                        <div   class="purchase-tax-div" style="display:{{in_array(2, $product_attries) ? '' : 'none'}}">
                                                            <label for="purchase-including-tax">Purchase Including Tax</label>
                                                            <select class="form-select text-white-dark" name="purchase_tax_id">
                                                                <option value="" disabled selected>Select</option>
                                                                @foreach($taxes as $tax_pur)
                                                                    <option value="{{$tax_pur->id}}" {{$tax_pur->id == $product->purchase_tax_id ? 'selected' : ''}}>{{$tax_pur->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div  class="sale-tax-div" style="margin-top: 20px;display:{{in_array(1, $product_attries) ? '' : 'none'}}" >
                                                            <label for="sell-including-tax">Sell Including Tax</label>
                                                            <select class="form-select text-white-dark" name="sale_tax_id">
                                                                <option value="" disabled selected>Select</option>
                                                                @foreach($taxes as $tax_pur)
                                                                    <option value="{{$tax_pur->id}}" {{$tax_pur->id == $product->sale_tax_id ? 'selected' : ''}}>{{$tax_pur->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            
                                                        </div>
                                                        <div class="sale-inclu-div" style="margin-top: 20px;display:{{in_array(1, $product_attries) ? '' : 'none'}}">
                                                            <label class="flex items-center cursor-pointer">
                                                                <input x-model="showSalesPriceIncludingTax" type="checkbox" value="1" name="sale_tax_status"
                                                                    class="form-checkbox" />
                                                                <span class="text-white-dark">Sales Price Including Tax</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="unit" class="grid grid-cols-3 gap-4"
                                                        style="border: .5px solid lightgray; padding: 20px; margin: 10px 0; border-radius: 5px; position: relative;">
                                                        <h2
                                                            style="margin: 0; font-size: 16px; font-weight: bold; position: absolute; top: -12px; left: 5px; background-color: #fafafaeb; padding: 2px 10px; border-radius: 5px;">
                                                            Unit</h2>
                                                        <div>
                                                            <div class="purchase-unit-div" style="display:{{in_array(2, $product_attries) ? '' : 'none'}}">
                                                                <label for="purchased-unit">Purchase Unit</label>
                                                                <select class="form-select text-white-dark" name="purchase_unit_id" id="purchase_unit_id">
                                                                    <option value="" disabled selected>Select</option>
                                                                    @foreach($units as $unit)
                                                                        <option value="{{$unit->id}}" {{$unit->id == $product->purchase_unit_id ? 'selected' : ''}}>{{$unit->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="sale-unit-div" style="margin-top: 20px;display:{{in_array(1, $product_attries) ? '' : 'none'}}">
                                                                <label for="sell-unit">Selling Unit</label>
                                                                <select class="form-select text-white-dark" name="sale_unit_id" id="sale_unit_id">
                                                                    <option value="" disabled selected>Select</option>
                                                                    @foreach($units as $unit)
                                                                        <option value="{{$unit->id}}" {{$unit->id == $product->sale_unit_id ? 'selected' : ''}}>{{$unit->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="purchase-div" style="margin-top: 20px;display:{{in_array(2, $product_attries) ? '' : 'none'}}">
                                                                <label class="flex items-center cursor-pointer">
                                                                    <input x-model="showOnePurchaseUnit" type="checkbox" value="1" name="is_purchase_equals"
                                                                        class="form-checkbox" />
                                                                    <span class="text-white-dark">One Purchase Unit Equals
                                                                        to</span>
                                                                </label>
                                                                <div x-show="showOnePurchaseUnit">
                                                                    <label for="sales-price-including-tax">Sales Unit</label>
                                                                    <input type="text" class="form-input" placeholder="Sales Unit" name="unit_value">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="variations"
                                                    style="display:{{in_array(1, $product_attries) || in_array(3, $product_attries) ? '' : 'none'}};margin-top: 30px; border: .5px solid lightgray; padding: 20px; border-radius: 5px; position: relative">
                                                    <div x-data="{ variations: [] }">
                                                        <button
                                                            style="margin-bottom: 20px; font-size: 16px; font-weight: bold; position: absolute; top: -12px; left: 5px; background-color: #fafafaeb; padding: 2px 10px; border-radius: 5px;"
                                                            type="button" @click="variations.push({ values: [{ value: '' }] })">
                                                            Products Variations <span
                                                                style="border: 1px solid lightgray; border-radius: 5px; padding: 3px 10px; cursor: pointer;">+</span>
                                                        </button>
                                                        <div x-show="variations.length > 0">
                                                            <table>
                                                                <tbody>
                                                                    @foreach($variations as $index => $itemVarian)
                                                                    <template x-for="(variation, index) in variations" :key="index">
                                                                        <tr>
                                                                            <td
                                                                                style="display: flex; gap: 10px; align-items: center; padding-right: 0; padding-left: 0; width: 200px;">
                                                                                <label for="variation-type">Type</label>
                                                                                <select class="form-select text-white-dark" x-bind:name="`product_variant[${index}][type_id]`"
                                                                                >
                                                                                    <option value=" " disabled>Select Type</option>  
                                                                                    @foreach($productVariation as $variation)
                                                                                        <option value="{{$variation->id}}" {{$variation->id ==  $index ? 'selected' : '' }} data-value="{{$variation->decimal_value}}">{{$variation->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                @foreach($itemVarian as $nextValue)
                                                                                <template
                                                                                    x-for="(valueObj, valueIndex) in variation.values"
                                                                                    :key="valueIndex">
                                                                                    <div
                                                                                        style="display: flex; align-items: center; gap: 5px;">
                                                                                        <button type="button"
                                                                                            @click="variation.values.push({ value[]: ''})">
                                                                                            <span
                                                                                                style="border: 1px solid lightgray; border-radius: 5px; padding: 5px 10px; cursor: pointer;">+</span>
                                                                                        </button>
                                                                                        <input type="text" class="form-input" x-bind:name="`product_variant[${index}][variations][${valueIndex}][sku]`"
                                                                                            placeholder="SKU Code" value="{{$nextValue->sku}}">
                                                                                        <input type="text" class="form-input" value="{{$nextValue->name}}"
                                                                                            placeholder="Name" x-bind:name="`product_variant[${index}][variations][${valueIndex}][name]`">
                                                                                        <input type="number" class="form-input" x-bind:name="`product_variant[${index}][variations][${valueIndex}][amount]`"
                                                                                            placeholder="Amount"  value="{{$nextValue->selling_price}}">
                                                                                        <button type="button"
                                                                                            @click="variation.values.splice(valueIndex, 1)">
                                                                                            <span
                                                                                                style="border: 1px solid lightgray; border-radius: 5px; padding: 5px 12px; cursor: pointer;">-</span>
                                                                                        </button>
                                                                                    </div>
                                                                                </template>
                                                                                @endforeach
                                                                            </td>
                                                                        </tr>
                                                                    </template>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <div class="price"
                                                    style="border: .5px solid lightgray; padding: 20px; margin: 20px 0; border-radius: 5px; position: relative;">
                                                    <h2 style="margin: 0; font-size: 16px; font-weight: bold; position: absolute; top: -12px; left: 5px; background-color: #fafafaeb; padding: 2px 10px; border-radius: 5px;">
                                                        Price</h2>
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div x-show="!showSalesPriceIncludingTax">
                                                                <label for="sales-price">Sales Price</label>
                                                                <input type="number" class="form-input" placeholder="Sales Price" name="sale_price" value="{{$product->sale_price}}">
                                                            </div>
                                                            <div x-show="showSalesPriceIncludingTax">
                                                                <label for="sales-price-including-tax">Sales Price Including
                                                                    Tax</label>
                                                                <input type="number" class="form-input"
                                                                    placeholder="Sales Price Including Tax" name="sale_price_includ_tax" value="{{$product->sale_price_includ_tax}}">
                                                            </div>
                                                            <div class="purchase-price-div" style="display:{{in_array(2, $product_attries) ? '' : 'none'}}">
                                                                <label for="last-urchase-unit-price">Last Purchase Unit
                                                                    Price</label>
                                                                <input type="number" class="form-input" id="last_purchase_price"
                                                                    placeholder="Last Purchase Unit Price" name="last_purchase_price" required value="{{$product->last_purchase_price}}">
                                                            </div>
                                                            <div>
                                                                <label for="discount">Discount (Rs)</label>
                                                                <input type="number" class="form-input" placeholder="Discount" name="discount"  value="{{$product->discount}}">
                                                            </div>
                                                            <div>
                                                                <label for="mrp">MRP</label>
                                                                <input type="number" class="form-input" placeholder="MRP" name="mrp" value="{{$product->mrp}}">
                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"></script>
</div>
@endsection


@section('javascript')
<script type="text/javascript"> 
var check = "{{isset($product) ? $product->enable_stock : 1}}";
var openStock = "{{isset($product) ? $product->open_stock : 1}}";
document.addEventListener("alpine:init", () => {
    Alpine.data("form", () => ({
        showDepartmentDropdown: false,
        showIsForSales: false,
        showIsPurchased: false,
        showIsForCategory: false,
        showSalesBeforeTax: false,
        showSalesPriceIncludingTax: false,
        showOnePurchaseUnit: false,
        ableIsChecked: false,
        showIsEnable:false,
        showIsOpenStock:false,
        selectedOption: "0",
        check: check,
        openStock: openStock,
        disableCuisine: false,
        disableType: false,
        decimal:0,
        variations: [],
        selectedDepartments: [],
        enableChange() {
            console.log(this.check);
            if(this.check == true)
            {
                this.ableIsChecked = true;
                this.showIsEnable = true;
            }
            else
            {
                this.ableIsChecked = false;
                this.showIsEnable = false;
                $('#alert_quantity').val('');
            }
        },
        openChange(){
            if(this.openStock == 1)
            {
                this.showIsOpenStock = true;
                document.getElementById("stock_quantity").required = true;
            }
            else
            {
                this.showIsOpenStock = false;
                $('#stock_quantity').val('');
                document.getElementById("stock_quantity").required = false;
            }
            
        },
        variantChange(index){
            const decimals = $('#variant-select-dd-'+index+' option:selected').data('value');
            if(decimals == 1)
            {
                $('#variant-amount-'+index).prop('disabled', false);
            }
            else {
                $('#variant-amount-'+index).prop('disabled', true);
            }
        },
        
    }));
});
const input = document.getElementById('file-upload');
const previewPhoto = () => {
    const file = input.files;
    if (file) {
        const fileReader = new FileReader();
        const preview = document.getElementById('file-preview');
        fileReader.onload = event => {
            preview.setAttribute('src', event.target.result);
        }
        fileReader.readAsDataURL(file[0]);
    }
}
input.addEventListener('change', previewPhoto);
$(document).ready(function () {
    var searchIDs = [];
    $('#product_add_form input[name="product_attry[]"]:checked').each(function(){
        searchIDs.push($(this).val());
    });
    $(document).on('click', 'button#product-submit-btn', function (e) {  
        e.preventDefault();
        //Check if product attr is present or not.
        
        if ($('#product_add_form input[name="product_attry[]"]:checked').length <= 0) {
            toastr.warning('select any product attry');
            return false;
        }
        if(searchIDs.includes('1'))
        {
            document.getElementById("sale_unit_id").required = true;
            document.getElementById("purchase_unit_id").required = false;
            document.getElementById("alert_quantity").required = true;
            document.getElementById("new_category_id").required = true;
            document.getElementById("brand").required = false;
            document.getElementById("last_purchase_price").required = false;
        }
        if(searchIDs.includes('2'))
        {
            document.getElementById("sale_unit_id").required = false;
            document.getElementById("purchase_unit_id").required = true;
            document.getElementById("alert_quantity").required = false;
            document.getElementById("new_category_id").required = true;
            document.getElementById("brand").required = false;
            document.getElementById("last_purchase_price").required = true;
        }
        if(!searchIDs.includes('1') && !searchIDs.includes('2'))
        {
            
            document.getElementById("sale_unit_id").required = false;
            document.getElementById("purchase_unit_id").required = false;
            document.getElementById("alert_quantity").required = false;
            document.getElementById("new_category_id").required = false;
            document.getElementById("brand").required = false;
            document.getElementById("last_purchase_price").required = false;
        }
        $('form#product_add_form').validate({
            rules: {
                name: "required",
                category_id: "required",
                // brand_id: "required",
                alert_quantity: "required",
            },
            messages: {
                name: "Required Field",
                category_id: "Required Field",
                // brand_id: "Required Field",
                alert_quantity: "Required Field",
            }
        });
        if ($('form#product_add_form').valid()) {
            $('form#product_add_form').submit();
        }
    });
    
    $('#new_category_id').change(function () {
        var val = $(this).val();
        get_sub_categories(val);
    });
    function get_sub_categories(cat) {
        $.ajax({
            method: "POST",
            url: '/product/sub-category',
            dataType: "html",
            data: { 'cat_id': cat },
            success: function (result) {
                if (result) {
                    $('#new_sub_category_id').html(result);
                }
            }
        });
    }

    var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
        removeItemButton: true,
        maxItemCount:5,
        searchResultLimit:5,
        renderChoiceLimit:5
    });

    $(document).on('change', '#enable-check-box', function()
    {
        if($(this).is(":checked")) 
        {
            $('#openstock-check-box').prop('disabled', false);
            $('.stock-quantity-div').show();
            $('.sale-stock-div').show();
            
        }
        else
        {
            $('#openstock-check-box').prop('disabled', true);
            $('#openstock-check-box').prop('checked', false);
            $('#alert_quantity').val('');
            $('#stock_quantity').val('');
            $('.stock-quantity-div').hide();
            $('.sale-stock-div').hide();
        }
        
    });
    $(document).on('change', '#openstock-check-box', function()
    {
        if($(this).is(":checked")) 
        {
        }
        else
        {
            $('#stock_quantity').val('');
        }
        
    });
    $(document).on('change', '#product_type', function(){
        if($(this).val() == 1)
        {
            $('.department-div').show();
        }
        else
        {
            $('.department-div').hide();
        }

    });
    $(document).on('change', '#product_attry', function()
    {
        if($(this).is(":checked")) 
        {
            searchIDs.push($(this).val());
        }
        else
        {
            searchIDs.splice(searchIDs.indexOf($(this).val()), 1)
        }
        
        if(searchIDs.includes('1') && !searchIDs.includes('2') && !searchIDs.includes('3') ) 
        {
            $('.sale-stock-div').show();
            $('.enable-stock-div').show();
            $('.stock-quantity-div').show();
            $('.category-div').show();
            $('.sub-category-div').show();
            $('.brand-div').show();
            $('.sale-tax-div').show();
            $('.sale-inclu-div').show();
            $('.sale-unit-div').show();
            $('.sale-div').show();
            
            $('.purchase-div').hide();
            $('.purchase-tax-div').hide();
            $('.purchase-unit-div').hide();
            $('.purchase-price-div').hide();

            $('.catelog-div').hide();
            $('.cuisine-div').hide();
            $('.type-div').hide();
            $('.menu-div').hide();
            $('.drink-type-menu').hide();
            $('.variations').hide();
        }
        if(searchIDs.includes('1') && searchIDs.includes('2') && !searchIDs.includes('3')) 
        {
            $('.sale-stock-div').show();
            $('.enable-stock-div').show();
            $('.stock-quantity-div').show();
            $('.category-div').show();
            $('.sub-category-div').show();
            $('.brand-div').show();
            $('.sale-tax-div').show();
            $('.sale-inclu-div').show();
            $('.sale-unit-div').show();
            $('.sale-div').show();
            
            $('.purchase-div').show();
            $('.category-div').show();
            $('.sub-category-div').show();
            $('.brand-div').show();
            $('.purchase-tax-div').show();
            $('.purchase-unit-div').show();
            $('.purchase-price-div').show();

            $('.catelog-div').hide();
            $('.cuisine-div').hide();
            $('.type-div').hide();
            $('.menu-div').hide();
            $('.drink-type-menu').hide();
            $('.variations').hide();
        }
        if(!searchIDs.includes('1') && searchIDs.includes('2') && !searchIDs.includes('3')) 
        {
            $('.sale-stock-div').hide();
            $('.enable-stock-div').hide();
            $('.stock-quantity-div').hide();
            $('.sale-tax-div').hide();
            $('.sale-inclu-div').hide();
            $('.sale-unit-div').hide();
            $('.sale-div').hide();
            
            $('.purchase-div').show();
            $('.category-div').show();
            $('.sub-category-div').show();
            $('.brand-div').show();
            $('.purchase-tax-div').show();
            $('.purchase-unit-div').show();
            $('.purchase-price-div').show();

            $('.catelog-div').hide();
            $('.cuisine-div').hide();
            $('.type-div').hide();
            $('.menu-div').hide();
            $('.drink-type-menu').hide();
            $('.variations').hide();
        }
        if(!searchIDs.includes('1') && searchIDs.includes('2') && searchIDs.includes('3')) 
        {
            $('.sale-stock-div').hide();
            $('.enable-stock-div').hide();
            $('.stock-quantity-div').hide();
            $('.sale-tax-div').hide();
            $('.sale-inclu-div').hide();
            $('.sale-unit-div').hide();
            $('.sale-div').hide();
            
            $('.purchase-div').show();
            $('.category-div').show();
            $('.sub-category-div').show();
            $('.brand-div').show();
            $('.purchase-tax-div').show();
            $('.purchase-unit-div').show();
            $('.purchase-price-div').show();

            $('.catelog-div').show();
            $('.cuisine-div').show();
            $('.type-div').show();
            $('.menu-div').show();
            $('.drink-type-menu').show();
            $('.variations').show();
        }
        if(searchIDs.includes('1') && searchIDs.includes('2') && searchIDs.includes('3')) 
        {
            $('.sale-stock-div').show();
            $('.enable-stock-div').show();
            $('.stock-quantity-div').show();
            $('.sale-tax-div').show();
            $('.sale-inclu-div').show();
            $('.sale-unit-div').show();
            $('.sale-div').show();
            
            $('.purchase-div').show();
            $('.category-div').show();
            $('.sub-category-div').show();
            $('.brand-div').show();
            $('.purchase-tax-div').show();
            $('.purchase-unit-div').show();
            $('.purchase-price-div').show();

            $('.catelog-div').show();
            $('.cuisine-div').show();
            $('.type-div').show();
            $('.menu-div').show();
            $('.drink-type-menu').show();
            $('.variations').show();
        }
        if(searchIDs.includes('1') && !searchIDs.includes('2') && searchIDs.includes('3')) 
        {
            $('.sale-stock-div').show();
            $('.enable-stock-div').show();
            $('.stock-quantity-div').show();
            $('.sale-tax-div').show();
            $('.sale-inclu-div').show();
            $('.sale-unit-div').show();
            $('.sale-div').show();
            
            $('.purchase-div').hide();
            $('.category-div').show();
            $('.sub-category-div').show();
            $('.brand-div').show();
            $('.purchase-tax-div').hide();
            $('.purchase-unit-div').hide();
            $('.purchase-price-div').hide();

            $('.catelog-div').show();
            $('.cuisine-div').show();
            $('.type-div').show();
            $('.menu-div').show();
            $('.drink-type-menu').show();
            $('.variations').show();
        }
        if(!searchIDs.includes('1') && !searchIDs.includes('2') && searchIDs.includes('3')) 
        {
            $('.sale-stock-div').hide();
            $('.enable-stock-div').show();
            $('.stock-quantity-div').hide();
            $('.sale-tax-div').hide();
            $('.sale-inclu-div').hide();
            $('.sale-unit-div').hide();
            $('.sale-div').hide();
            
            $('.purchase-div').hide();
            $('.category-div').hide();
            $('.sub-category-div').hide();
            $('.brand-div').hide();
            $('.purchase-tax-div').hide();
            $('.purchase-unit-div').hide();
            $('.purchase-price-div').hide();

            $('.catelog-div').show();
            $('.cuisine-div').show();
            $('.type-div').show();
            $('.menu-div').show();
            $('.drink-type-menu').show();
            $('.variations').show();
        }
    });
});
</script>
@endsection