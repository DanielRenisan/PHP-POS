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
                <span>Create Product</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                <div class="px-5">
                    <div class="product" x-data="form">
                        <form enctype="multipart/form-data" id="product_add_form" class="needs-validation" novalidate="" method="POST"
                            action="{{ route('product.store') }}">
                            @csrf
                            <div class="row" style="display: flex;">
                                <div class="col left" style="width: 35%; padding: 0px 10px 10px 10px">
                                    <div class="left-image">
                                        <img src="{{asset('images/no-image.png')}}"
                                            alt="" style="width: 400px; height: 190px; border-radius: 10px;" id="file-preview">
                                        <input class="form-input" id="file-upload" type="file" name="image" style="object-fit: contain;width: 400px;" accept="image/*" />
                                    </div>
                                    <div class="left-details">
                                        <div style="display: flex; align-items: center; gap: 20px; margin-top: 20px;">
                                            <div>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="radio" name="product_type" class="form-radio outline-success"
                                                        x-model="selectedOption" value="0" />
                                                    <span class="text-white-dark">Default</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="radio" name="product_type" class="form-radio outline-success"
                                                        x-model="selectedOption" value="1" />
                                                    <span class="text-white-dark">Specific Department</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div x-show="selectedOption === '1'" style="margin-top: 20px;">
                                            <div class="multibody">
                                                <select class="form-select text-white-dark" name="department[]"
                                                    style="font-size: 14px; font-weight: bold;line-height:1.25rem;" id="choices-multiple-remove-button"
                                                    placeholder="Select Department" multiple required>
                                                    @foreach ($departement as $dp)
                                                    <option class="pro-type" value="{{ $dp->id }}" {{ $dp->
                                                        id == old('department_id') ? 'selected' : '' }}>
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
                                        <div style="display: flex !important; align-items: center; gap: 20px !important;">
                                            <div x-show="showIsForSales || (!showIsPurchased && !showIsForCategory)">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox"  name="enable_stock" x-on:change="enableChange" x-model="check"
                                                        value="1" >
                                                    <span class="text-white-dark">Enable Stock</span>
                                                </label>
                                            </div>
                                            <div x-show="showIsForSales || (!showIsPurchased && !showIsForCategory)">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox" id="open_stock"  name="open_stock" x-bind:disabled="!ableIsChecked" x-model="openStock" x-on:change="openChange"
                                                        value="1" >
                                                    <span class="text-white-dark">Is Open Stock</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 pt-5">
                                            <div x-show="showIsEnable">
                                                <h2 style="font-size: 14px; font-weight: bold;">Alert quantity:*</h2>
                                                <input class="form-input" placeholder="Alert quantity" min="0" name="alert_quantity" type="number" id="alert_quantity" x-bind:disabled="!showIsEnable">
                                            </div>
                                            <div x-show="showIsOpenStock">
                                            <h2 style="font-size: 14px; font-weight: bold;">Open Stock</h2>
                                                <input class="form-input" placeholder="Open Stock" min="0" name="stock" type="number" id="stock_quantity" x-bind:disabled="!showIsOpenStock">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 pt-5">
                                            <div x-show="(!showIsForSales && !showIsPurchased) || showIsForCategory">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox"  name="is_kot"
                                                        value="1" >
                                                    <span class="text-white-dark">Is KOT</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox"  name="is_bot"
                                                        value="1" >
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
                                                        <input type="checkbox" class="form-checkbox" name="station_ids[]" value="{{ $station->id }}">
                                                        <span class="ml-2">{{ $station->name }} <small class="text-gray-500">({{ $station->code }})</small></span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Select one or more stations. Each selected station will print its own ticket when this product is ordered.</p>
                                        </div>
                                        @endisset
                                        <div class="mt-5">
                                            <h2 style="font-size: 14px; font-weight: bold;">Product Name:*</h2>
                                            <input class="form-input" required="required" placeholder="Product Name" name="name" type="text" id="name">
                                        </div>
                                        <div class="mt-5">
                                            <h2 style="font-size: 14px; font-weight: bold;">SKU Code</h2>
                                            <input type="text" class="form-input" name="sku_code">
                                        </div>
                                        <div class="mt-5">
                                            <h2 style="font-size: 14px; font-weight: bold;">Bar Code</h2>
                                            <input type="text" class="form-input" name="barcode">
                                        </div>
                                        <div class="mt-5">
                                            <h2 style="font-size: 14px; font-weight: bold;">Description</h2>
                                            <textarea class="form-input" name="description" placeholder="Description..."></textarea>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 20px; margin-top: 20px;">
                                            <div>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox"  name="status" checked
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
                                                    $function = 'toggleTypeDisable()';
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
                                            <div class="row_set">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="form-checkbox" x-model="{{$attr_value}}" name="product_attry[]"
                                                        x-on:change="{{$function}}" value="{{$attry->id}}" >
                                                    <span class="text-white-dark">{{ $attry->name }}</span>
                                                </label>
                                            </div>
                                            @endforeach
                                            <div>
                                                <label class="flex items-center cursor-pointer">
                                                    <button type="button" class="btn btn-success btn-lg right" id="product-submit-btn">Create</button>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="contents">
                                        <div class="is-for-sales" x-show="showIsForSales || showIsPurchased || showIsForCategory">
                                                <div class="grid grid-cols-3 gap-4"
                                                    style="border: .5px solid lightgray; padding: 20px; margin: 10px 0; border-radius: 5px;">
                                                    <div x-show="!showIsForCategory || showIsForSales || showIsPurchased">
                                                        <label for="category">Product Category</label>
                                                        <select class="form-select text-white-dark" name="category_id"
                                                            id="new_category_id" required>
                                                            <option value="" disabled selected>Select Category</option>
                                                            @foreach ($productCategory as $pc)
                                                            <option class="pro-type" value="{{ $pc->id }}"
                                                                {{ $pc->
                                                                id == old('category_id') ? 'selected' : '' }}>
                                                                {{ $pc->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('product_categeories_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>
                                                    <div  x-show="!showIsForCategory || showIsForSales || showIsPurchased">
                                                        <label for="sub-category">Sub Category</label>
                                                        <select class="form-select text-white-dark" name="sub_category_id"
                                                            id="new_sub_category_id">
                                                            <option value="" disabled selected>Select Category</option>
                                                            <span class="text-danger">
                                                                @error('sub_category_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>
                                                    <div  x-show="!showIsForCategory || showIsForSales || showIsPurchased">
                                                        <label for="brand">Brand</label>
                                                        <select class="form-select text-white-dark" name="brand_id" id="brand">
                                                            <option value="" disabled selected>Select Brand</option>
                                                            @foreach ($brand as $b)
                                                            <option class="pro-type" value="{{ $b->id }}" {{ $b->
                                                                id == old('brand_id') ? 'selected' : '' }}>
                                                                {{ $b->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('brand_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>
                                                    
                                                    <div   x-show="showIsForCategory || (!showIsForSales && !showIsPurchased)">
                                                        <label for="menu">Menu</label>
                                                        <select class="form-select text-white-dark" name="menu_id"  x-bind:disabled="(showIsForSales || showIsPurchased) && !showIsForCategory">
                                                            <option value="" disabled selected>Select Menu</option>
                                                            @foreach ($menu as $mn)
                                                            <option class="pro-type" value="{{ $mn->id }}" {{ $mn->
                                                                id == old('menu_id') ? 'selected' : '' }}>
                                                                {{ $mn->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('menu_id')
                                                                {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </select>
                                                    </div>
                                                    <!-- <div   x-show="showIsForCategory || (!showIsForSales && !showIsPurchased)">
                                                        <label for="drinkType">Drink Type</label>
                                                        <select class="form-select text-white-dark" name="drink_type_id"  x-bind:disabled="(showIsForSales || showIsPurchased) && !showIsForCategory">
                                                            <option value="" disabled selected>Select Type</option>
                                                            @foreach ($drintType as $dt)
                                                            <option class="pro-type" value="{{ $dt->id }}" {{ $dt->
                                                                id == old('menu_id') ? 'selected' : '' }}>
                                                                {{ $dt->name }}</option>
                                                            @endforeach
                                                            <span class="text-danger">
                                                                @error('menu_id')
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
                                                        <div  x-show="showIsPurchased || (!showIsForSales && !showIsForCategory)">
                                                            <label for="purchase-including-tax">Purchase Including Tax</label>
                                                            <select class="form-select text-white-dark"  x-bind:disabled="(showIsForSales || showIsForCategory ) && !showIsPurchased" name="purchase_tax_id">
                                                                <option value="" disabled selected>Select</option>
                                                            </select>
                                                        </div>
                                                        <div style="margin-top: 20px;">
                                                            <label for="sell-including-tax">Sell Including Tax</label>
                                                            <select class="form-select text-white-dark"
                                                                x-bind:disabled="(showIsPurchased || showIsForCategory ) && !showIsForSales">
                                                                <option value="" disabled selected>Select</option>
                                                            </select>
                                                            
                                                        </div>
                                                        <div style="margin-top: 20px;">
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
                                                            <div  x-show="showIsPurchased || (!showIsForSales && !showIsForCategory)">
                                                                <label for="purchased-unit">Purchase Unit</label>
                                                                <select class="form-select text-white-dark" name="purchase_unit_id"
                                                                    x-bind:disabled="(showIsForSales || showIsForCategory ) && !showIsPurchased" id="purchase_unit_id">
                                                                    <option value="" disabled selected>Select</option>
                                                                    @foreach($units as $unit)
                                                                        <option value="{{$unit->id}}" >{{$unit->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div style="margin-top: 20px;">
                                                                <label for="sell-unit">Selling Unit</label>
                                                                <select class="form-select text-white-dark" name="sale_unit_id"
                                                                    x-bind:disabled="(showIsPurchased || showIsForCategory ) && !showIsForSales"  id="sale_unit_id">
                                                                    <option value="" disabled selected>Select</option>
                                                                    @foreach($units as $unit)
                                                                        <option value="{{$unit->id}}" >{{$unit->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div style="margin-top: 20px;">
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
                                                <div class="variations"  x-show="showIsForSales || !showIsPurchased || showIsForCategory "
                                                    style="margin-top: 30px; border: .5px solid lightgray; padding: 20px; border-radius: 5px; position: relative">
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
                                                                    <template x-for="(variation, index) in variations" :key="index">
                                                                        <tr>
                                                                            <td
                                                                                style="display: flex; gap: 10px; align-items: center; padding-right: 0; padding-left: 0; width: 200px;">
                                                                                <label for="variation-type">Type</label>
                                                                                <select class="form-select text-white-dark" x-bind:name="`product_variant[${index}][type_id]`" x-bind:id="`variant-select-dd-${index}`"
                                                                                x-on:change="variantChange(index)">
                                                                                    <option value=" " disabled selected>Select Type</option>  
                                                                                    @foreach($productVariation as $variation)
                                                                                        <option value="{{$variation->id}}" data-value="{{$variation->decimal_value}}">{{$variation->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <template
                                                                                    x-for="(valueObj, valueIndex) in variation.values"
                                                                                    :key="valueIndex">
                                                                                    <div
                                                                                        style="display: flex; align-items: center; gap: 5px;">
                                                                                        <button type="button"
                                                                                            @click="variation.values.push({ value: ''})">
                                                                                            <span
                                                                                                style="border: 1px solid lightgray; border-radius: 5px; padding: 5px 10px; cursor: pointer;">+</span>
                                                                                        </button>
                                                                                        <input type="text" class="form-input" x-bind:name="`product_variant[${index}][variations][${valueIndex}][sku]`"
                                                                                            x-model="valueObj.skuCode"
                                                                                            placeholder="SKU Code">
                                                                                        <input type="text" class="form-input"
                                                                                            x-model="valueObj.name" placeholder="Name" x-bind:name="`product_variant[${index}][variations][${valueIndex}][name]`">
                                                                                        <input type="number" x class="form-input" x-bind:name="`product_variant[${index}][variations][${valueIndex}][amount]`"
                                                                                            x-model="valueObj.amount" x-bind:id="`variant-amount-${index}`"
                                                                                            placeholder="Amount">
                                                                                        <button type="button"
                                                                                            @click="variation.values.splice(valueIndex, 1)">
                                                                                            <span
                                                                                                style="border: 1px solid lightgray; border-radius: 5px; padding: 5px 12px; cursor: pointer;">-</span>
                                                                                        </button>
                                                                                    </div>
                                                                                </template>
                                                                            </td>
                                                                        </tr>
                                                                    </template>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="price"
                                                    style="border: .5px solid lightgray; padding: 20px; margin: 20px 0; border-radius: 5px; position: relative;">
                                                    <h2 style="margin: 0; font-size: 16px; font-weight: bold; position: absolute; top: -12px; left: 5px; background-color: #fafafaeb; padding: 2px 10px; border-radius: 5px;">
                                                        Price</h2>
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div x-show="!showSalesPriceIncludingTax">
                                                                <label for="sales-price">Sales Price</label>
                                                                <input type="number" class="form-input" placeholder="Sales Price" name="sale_price">
                                                            </div>
                                                            <div x-show="showSalesPriceIncludingTax">
                                                                <label for="sales-price-including-tax">Sales Price Including
                                                                    Tax</label>
                                                                <input type="number" class="form-input"
                                                                    placeholder="Sales Price Including Tax" name="sale_price_includ_tax">
                                                            </div>
                                                            <div x-show="showIsPurchased || (!showIsForSales && !showIsForCategory)">
                                                                <label for="last-urchase-unit-price">Last Purchase Unit
                                                                    Price</label>
                                                                <input type="number" class="form-input"
                                                                    placeholder="Last Purchase Unit Price" name="last_purchase_price" id="last_purchase_price" required>
                                                            </div>
                                                            <div>
                                                                <label for="discount">Discount (Rs)</label>
                                                                <input type="number" class="form-input" placeholder="Discount" name="discount">
                                                            </div>
                                                            <div>
                                                                <label for="mrp">MRP</label>
                                                                <input type="number" class="form-input" placeholder="MRP" name="mrp">
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
<script src="{{asset('asset/js/product.js')}}"></script>
@endsection