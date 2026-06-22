<div x-show.transition.duration.500ms="viewCardPopUp"
                                :class="{ 'popup-hidden': !viewCardPopUp, 'popup-visible': viewCardPopUp }"
                                class="popup" style="width: 100%;margin-top: -85px;"
                                @click.away="viewCardPopUp = null">
                                <div>
                                    <h6 style="background: skyblue; color: white; position: absolute; top: 0; left: 0; width: 100%; padding: 10px 20px;"
                                        class="menu-title">View Cart</h6>
                                </div>
                                <div style="margin-top: 35px;">
                                <div class=" sm:ltr:mr-auto sm:rtl:ml-auto" @click.outside="search = false">
                <div style="margin: 0 !important;"
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
                </div>
            </div>
            <!-- Your table structure -->
            <div class="table-responsive border-colored mt-1">
                <table class="fixed-header">
                {!! Form::hidden('transaction_id', null, ['id' => 'transaction_id']) !!}
                    <thead>
                        <tr>
                            <th style="padding-left: 0 !important;">Description</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th style="text-align: right !important;">SubTotal</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(data, index) in myProducts" :key="index">
                            <tr @click="selectProduct(index)" @dblclick="openEditPopup(index)"
                                style="cursor: pointer;"
                                :class="{ 'highlighted-row': selectedProduct === index }">
                                <input type="hidden" x-bind:name="`products[${index}][product_id]`" x-model="data.id">
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
                                <!-- <td class="text-center" style="padding: 5px 0" x-text="`${data.dis}`">
                                </td> -->
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
                <!-- <div class="price-details">
                    <div class="price-details-left p-0" style="width: 50%;">
                        <p>
                            Total Quantity: <span x-text="calculateTotalQuantity()"></span>
                        </p>
                        <input type="hidden" name="tax_amount" x-model="tax">
                        <input type="hidden" name="discount_amount" x-model="totalDiscountAmount()">
                    </div>
                    <div class="price-details-right" style="width: 50%;">
                        <div
                            style="border: 1px solid #fff; padding: 10px; border-radius: 5px;">
                            <p style=" font-size: 16px; font-weight: bold">Sub Total: <span
                                    x-text="totalSubtotal"></span></p>
                            <div class="grand-total"
                                style="border: 1px solid #fff; border-radius: 5px; font-size: 18px; font-weight: bold; padding: 10px;">
                                <p class="m-0">Grand Total: <span
                                        x-text="formatCurrency(calculateGrandTotal())"></span></p>
                                        <input type="hidden" name="final_total" x-model="`${calculateGrandTotal()}.00`">
                            </div>
                        </div>
                    </div>
                </div> -->
                                    <div
                                        style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
                                        <button type="button" class="btn btn-danger"
                                            @click="viewCardPopUp = null">Close</button>
                                        <button type="submit" class="btn btn-primary"
                                        >ORDER</button>
                                    </div>
                                </div>
                            </div>