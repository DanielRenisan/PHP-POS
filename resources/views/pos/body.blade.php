<!-- start main content section -->
{!! Form::open(['url' => action('POSController@draft'), 'method' => 'post', 'id' => 'pos_add_form' , 'files' => true]) !!}
<div class="container-fluid no-print">
    <div class="row" @keydown.window="keydownHandler">
        <a @click="toggleThirdColumn"
            style="position: absolute; top: 15px; right: 132px; width: auto; cursor: pointer;"
            class="relative block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-double-sw-ne"
                width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round" id="IconChangeColor">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" id="mainIconPathAttribute"></path>
                <path d="M14 3l-11 11" id="mainIconPathAttribute"></path>
                <path d="M3 10v4h4" id="mainIconPathAttribute"></path>
                <path d="M17 10h4v4" id="mainIconPathAttribute"></path>
                <path d="M10 21l11 -11" id="mainIconPathAttribute"></path>
            </svg>
        </a>
        <div class="col-3 third p-0" x-show="showThirdColumn" style="position: relative;">
            <div class="buttons btns" :class="{ 'cal-hidden': calculatorHidden, 'cal-visible': !calculatorHidden }" style="height:120vh !important;">
                <div class="buttons btns">
                    <div class="grid grid-cols-2 buttons cal-visible-btns cal-hidden-btns" style="grid-template-columns: repeat(2, 1fr)">
                        <template x-for="(button, index) in buttons" :key="index">
                            <button type="button" :id="button.id" style="background-color:#bef3a9ff;border:1px #fff solid !important;"
                                @click="performAction(button.name, button.shortcut)"
                                class="block w-full rounded-md border border-gray-300 hover:bg-gray-100 border-colored btn-hover">
                                <i style="font-size: 25px;" :class="button.icon"></i>
                                <span style="line-height: 15px;" x-text="button.name"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5 first" :style="{ flex: showThirdColumn ? '1' : '.6' }">
            <!-- search  -->
            {!! Form::hidden('location_id', $default_location, ['id' => 'location_id', 'required']) !!}
            {!! Form::hidden('department_id', null, ['id' => 'department_id', 'x-model'=>"selectedDepartment"], 'required') !!}
            {!! Form::hidden('staff_id', null, ['id' => 'staff_id', 'x-model'=>"selectedEmployee"], 'required') !!}
            {!! Form::hidden('contact_id', null, ['id' => 'customer_id', 'x-model'=>"selectedCustomer"], 'required') !!}
            {!! Form::hidden('table_id', null, ['id' => 'table_id', 'x-model'=>"selectedTable"]) !!}
            {!! Form::hidden('room_id', null, ['id' => 'room_id', 'x-model'=>"selectedRoom"]) !!}
            {!! Form::hidden('order_type', null, ['id' => 'order_type', 'x-model'=>"selectedOrderType"]) !!}
            {!! Form::hidden('is_include', null, ['id' => 'is_include', 'x-model'=>"selectedIsInclude"]) !!}
            <div class=" sm:ltr:mr-auto sm:rtl:ml-auto" @click.outside="search = false">
                <form style="margin: 0 !important;"
                    class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0"
                    @submit.prevent="search = false">
                    <div class="relative">
                        <input type="text"
                            class="peer border-colored form-input bg-gray-100 placeholder:tracking-widest ltr:pl-9 ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                            placeholder="Product Name / SKU / Barcode" x-model="searchTerm"
                            @input="handleSearchInput" />
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
                        <div class=" sm:ltr:mr-auto sm:rtl:ml-auto" @click.outside="search = false" style="position: relative">

                            <!-- Dropdown for search results -->
                            <div class="search-results" x-show="searchResults.length > 1">
                                <ul class="p-0 form-input">
                                    <template x-for="(product, index) in searchResults" :key="index">
                                        <li @click="addProduct(product)" style="cursor: pointer;">
                                            <span x-text="product.description"></span>
                                            (<span style="font-size: 10px;" x-text="product.skuCode"></span>)
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Your table structure -->
            <div class="table-responsive border-colored mt-1">
                <table class="fixed-header">
                {!! Form::hidden('transaction_id', null, ['id' => 'transaction_id']) !!}
                    <thead>
                        <tr>
                            <th style="padding-left: 0 !important;">Description</th>
                            <th>Qty</th>
                            <th>Disc</th>
                            <th>Price</th>
                            <th style="text-align: right !important;">Total</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(data, index) in myProducts" :key="index">
                            <tr @click="selectProduct(index)" @dblclick="openEditPopup(index)"
                                style="cursor: pointer;"
                                :class="{ 'highlighted-row': selectedProduct === index }">
                                <input type="hidden" x-bind:name="`products[${index}][product_id]`" x-model="data.id">
                                <input type="hidden" x-bind:name="`products[${index}][line_id]`" x-model="data.line_id">
                                <td style="padding: 5px 10px !important">
                                    <span x-text="data.description.length > 10 ? `${data.description.slice(0, 10)}...` : data.description"></span>
                                    <div style="display: flex; flex-wrap: wrap;">
                                        <template x-for="(variation, key) in data.variations"
                                            :key="key">
                                            <div style="margin-left: 5px;">
                                            <input type="hidden" x-bind:name="`products[${index}][variants][${key}][value]`" x-model="variation.name">
                                            <input type="hidden" x-bind:name="`products[${index}][variants][${key}][amount]`" x-model="variation.price">
                                                <span style="font-size: 12px;"
                                                    x-text="`${variation.name},`"></span>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                                <td class="text-center"
                                    style="padding: 5px 0;display: flex;align-items: center; justify-content: center;">
                                    <button type="button" @click="decrementQuantity(index)"  x-bind:disabled="buttonDisabled"
                                        style="display: flex;align-items: center; justify-content: center; width: 18px; height: 18px; border: 1px solid var(--primary-border-color); margin-right: 5px; border-radius: 10px;">-</button>
                                    <input type="text" @keydown.enter="performCalculation"
                                        class="peer form-input bg-gray-100 placeholder:tracking-widest ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                                        min="1" x-model="data.qty" x-bind:name="`products[${index}][quantity]`" 
                                        @input="updateQuantityFromInput($event.target.value, index)"
                                        style="outline: none; border: none; padding: 2px 5px; width: 50px; text-align: center; " id="pos-quantity" x-init="$el.focus().select()">
                                    <button type="button" @click="incrementQuantity(index)"  x-bind:disabled="buttonDisabled"
                                        style="display: flex;align-items: center; justify-content: center; width: 18px; height: 18px; border: 1px solid var(--primary-border-color); margin-left: 5px; border-radius: 10px;">+</button>
                                </td>
                                <input type="hidden" x-bind:name="`products[${index}][discount_type]`" value="fixed">
                                <input type="hidden" x-bind:name="`products[${index}][discount_amount]`" x-model="data.dis">
                                <input type="hidden" x-bind:name="`products[${index}][transaction_sell_lines_id]`" x-model="data.line_id">
                                <td class="text-center" style="padding: 5px 0" x-text="`${data.dis}`">
                                </td>
                                <input type="hidden" x-bind:name="`products[${index}][unit_price]`" x-model="`${data.price}`">
                                <td class="text-center" style="padding: 5px 0" x-text="`${data.price}`">
                                </td>
                                <input type="hidden" x-bind:name="`products[${index}][sub_total]`" x-model="`${calculateSubTotal(data)}`">
                                <td class="text-right"
                                    style="padding: 5px 0; text-align: right; padding-right: 10px;"
                                    x-text="`${calculateSubTotal(data)}`"></td>
                                <td class="text-center" style="padding: 5px 0; width: 5%;">
                                    <button type="button" x-tooltip="Delete" @click="deleteRow(index)" x-bind:disabled="buttonDisabled">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" class="m-auto">
                                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path
                                                d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                                stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" />
                                            <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" />
                                            <path opacity="0.5"
                                                d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                                stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="p-0 mt-3">
                <div class="price-details">
                    <div class="price-details-left p-0" style="width: 50%;">
                        <p>
                            Total Quantity: <span x-text="calculateTotalQuantity()"></span>
                        </p>
                        <input type="hidden" name="tax_amount" x-model="tax">
                        <!-- <p>
                        
                            Tax: <span x-text="tax"></span>
                        </p> -->
                        <input type="hidden" name="discount_amount" x-model="totalDiscountAmount()">
                        <!-- <p>
                            Discount: <span x-text="totalDiscountAmount()"></span>
                            
                        </p> -->
                        <p>
                            Coupon: <span x-text="coupon"></span>
                            <input type="hidden" name="coupon" x-model="coupon">
                        </p>
                        <p>
                            Loyality Points: <span x-text="loyaltyPoints"></span>
                            <input type="hidden" name="loyalty_points" x-model="loyaltyPoints">
                        </p>
                        <p>
                            Gift Card: <span x-text="giftCard"></span>
                            <input type="hidden" name="gift_card" x-model="giftCard">
                        </p>
                    </div>
                    <div class="price-details-right" style="width: 50%;">
                        <div
                            style="border: 1px solid #fff; padding: 10px; border-radius: 5px;">
                            <p style=" font-size: 16px; font-weight: bold">Sub Total: <span
                                    x-text="totalSubtotal"></span></p>
                            <p style="font-size: 14px; font-weight: bold;">Discount: <span
                                    x-text="totalDiscount"></span></p>
                            <p style="font-size: 14px; font-weight: bold;">Tax: <span
                                    x-text="`${calculateTaxCharge()}`"></span></p>
                            <div class="grand-total"
                                style="border: 1px solid #fff; border-radius: 5px; font-size: 18px; font-weight: bold; padding: 10px;">
                                <p class="m-0">Grand Total: <span
                                        x-text="formatCurrency(calculateGrandTotal())"></span></p>
                                        <input type="hidden" name="final_total" x-model="`${calculateGrandTotal()}.00`">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="buttons detail-btns mt-3" style="display: flex;">
                    <button type="button" @click="openCashPop()"
                    style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px; height: 60px; font-size: 20px; font-weight: bold;"
                                        class="block w-full py-2 px-4 mb-2 rounded-md border border-gray-300 hover:bg-gray-100 border-colored cash-btn">
                                        QUICK PAY</button>
                    <button type="button" @click="openMultyPaymentPop()"
                    style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px; height: 60px; font-size: 20px; font-weight: bold;"
                                        class="block w-full py-2 px-4 mb-2 rounded-md border border-gray-300 hover:bg-gray-100 border-colored card-btn">
                        MULTY PAYMENT</button>
                </div>
            </div>
        </div>
        <div class="col second">
            <div class="flex" style="gap: 2px;">
                <!-- Left column for categories -->
                <div class="w-1/3 second-left" :class="{ 'w-1/3': showThirdColumn, 'w-1/5': !showThirdColumn }">
                    <div class="cat-buttons">
                        <button type="button" x-on:click="selectedCategory = null" :class="{
                                        'active': selectedCategory === null,
                                        'block w-full py-2 px-2 mb-2 rounded-md border border-gray-300 hover:bg-gray-100 border-colored btn-hover': true
                                    }">
                            All Products
                        </button>

                        <template x-for="(category, index) in categories.sort((a, b) => a.name.localeCompare(b.name))" :key="index">
                            <div>
                                <button type="button" class="p-2"
                                    x-on:click="selectedCategory === category.name ? resetSelected() : selectCategory(category.name); showSubcategories = true; showCuisine = !showCuisine; showBrand = !showBrand"
                                    :class="{
                                        'active': selectedCategory === category.name,
                                        'block w-full mb-1 rounded-md border border-gray-300 border-colored btn-hover': true
                                    }">
                                    <i :class="category.icon"></i>
                                    <span x-text="category.name"></span>
                                </button>

                                <!-- Subcategories -->
                                <template
                                    x-if="showSubcategories && selectedCategory === category.name && category.subcategories && category.subcategories.length">
                                    <template
                                        x-for="(subcategory, subIndex) in category.subcategories"
                                        :key="subIndex">
                                        <button type="button" class="p-2 ml-4"
                                            x-on:click="selectedSubcategory = subcategory.name"
                                            :class="{
                                                'active': selectedSubcategory === subcategory.name,
                                                'block w-full mb-1 rounded-md border border-gray-300 border-colored btn-hover': true
                                            }">
                                            <i :class="subcategory.icon"></i>
                                            <span x-text="subcategory.name"></span>
                                        </button>
                                    </template>
                                </template>
                            </div>
                        </template>

                        <!-- Cuisine -->
                        <template x-if="cuisines && cuisines.length">
                            <div>
                                <template x-for="(cuisine, index) in cuisines.sort((a, b) => a.name.localeCompare(b.name))" :key="index">
                                    <button type="button" class="p-2"
                                        x-on:click="selectCuisine(cuisine.name)" :class="{
                                            'active': selectedCuisine === cuisine.name,
                                            'block w-full mb-1 rounded-md border border-gray-300 border-colored btn-hover': true
                                            }">
                                        <i :class="cuisine.icon"></i>
                                        <span x-text="cuisine.name"></span>
                                    </button>
                                </template>
                            </div>
                        </template>

                        <!-- Brands -->
                        <template x-if="menus && menus.length">
                            <div>
                                <template x-for="(brand, index) in menus.sort((a, b) => a.name.localeCompare(b.name))" :key="index">
                                    <button type="button" class="p-2"
                                        x-on:click="selectBrand(brand.name)" :class="{
                                            'active': selectedBrand === brand.name,
                                            'block w-full mb-1 rounded-md border border-gray-300 border-colored btn-hover': true
                                            }">
                                        <i :class="brand.icon"></i>
                                        <span x-text="brand.name"></span>
                                    </button>
                                </template>
                            </div>
                        </template>

                    </div>
                </div>
                <!-- Right column for products -->
                <div class="w-2/3 second-right" :class="{ 'w-1/3': showThirdColumn, 'w-2/3': !showThirdColumn }"
                    :style="{ width: showThirdColumn ? '100%' : '100%' }">
                    <div class="grid grid-cols-2" style="gap: 3px;"
                        :class="{ 'grid-cols-2': showThirdColumn, 'products-width': !showThirdColumn }">
                        <template x-for="(product, index) in filteredProducts" :key="index">
                            <div class="border border-colored p-2" @click="if (productDisabled === false) addToMyProducts(product)"
                                style="cursor: pointer;">
                                <div class="details">
                                    <span style="font-size: 10px; margin-top: -100px;"
                                        x-text="product.skuCode"></span>
                                    <h6 style="font-size: 12px; font-weight: bold;"
                                        x-text="product.description.length > 20 ? `${product.description.slice(0, 25)}...` : product.description">
                                    </h6>
                                    <p style="margin: 0; font-size: 12px; font-weight: bold;"
                                        x-text="`Rs.${product.price}`"></p>
                                    <span style="color: red;"
                                        x-text="product.availability === 'out-of-stock' ? product.availability : ''"></span>
                                </div>
                                <div class="image">
                                    <img :src="product.imageUrl" alt="Product Image"
                                        style="max-width: 50px; max-height: 50px; object-fit: contain;" />
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="anyPopupOpen" :class="{ 'overlay-hidden': !anyPopupOpen, 'overlay-visible': anyPopupOpen }"
            class="overlay">
        </div>

        <!-- Popups -->
        <!-- Edit Products Popup -->
        @include('pos.edit_product')

        <!-- Product variation-popupopup -->
        @include('pos.variation_pop')

        <!-- Popup for Quantity -->
        @include('pos.quantity_pop')

        <!-- Popup for Remove -->
        @include('pos.remove_pop')

        <!-- Popup for Stock -->
        @include('pos.stock_pop')

        <!-- Popup for Cancel Invoice -->
        @include('pos.cancel_invoice')

        <!-- Popup for Pick Held Invoice -->
        @include('pos.held_invoice')

        <!-- Popup for Shipment -->
        @include('pos.shipment_pop')

        <!-- Popup for Discount Percentage -->
        @include('pos.discount_percentage')

        <!-- Popup for Discount Amount -->
        @include('pos.discount_amount')

        <!-- Popup for Tax -->
        @include('pos.tax_pop')

         <!-- Popup for Gift Card -->
         @include('pos.gift_pop')

        <!-- Popup for cash -->
        @include('pos.cash_pop')

        <!-- Popup for multy Payment -->
        @include('pos.milty_payment')

        <!-- Popup for Entry -->
        @include('pos.entry_pop')

        <!-- Popup for new Customer -->
        @include('pos.addCustomerPop')
    </div>
</div>
<!-- end main content section -->
{!! Form::close() !!}