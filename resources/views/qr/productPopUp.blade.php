<div x-show.transition.duration.500ms="productPopUp"
                                :class="{ 'popup-hidden': !productPopUp, 'popup-visible': productPopUp }"
                                class="popup" @keydown.enter="addProductWithVariations" style="width: 100%;margin-top: -100px;"
                                @click.away="productPopUp = null">
                                <div>
                                    <h6 style="background: skyblue; color: white; position: absolute; top: 0; left: 0; width: 100%; padding: 10px 20px;"
                                        class="menu-title">Cart</h6>
                                </div>
                                <div style="margin-top: 35px;">
                                    <div style="display: flex;">
                                        <div style="width: 30%;">
                                            <img :src="addedProduct.imageUrl" alt="Product Image"
                                                style="width: 100px; height: 100px; border: 1px solid lightgray; border-radius: 5px; padding: 2px;">
                                        </div>
                                        <div style="width: 100%;">
                                            <span x-text="addedProduct.description"></span><br>
                                            <p style="margin: 0; font-size: 12px; font-weight: bold;" x-text="addedProduct.skuCode"></p>
                                            <span x-text="`Rs${addedProduct.price}`"></span>
                                            <p
                                    style="padding: 5px 0;display: flex;">
                                    <button type="button" @click="decreQuantity(addedProduct)"  x-bind:disabled="buttonDisabled"
                                        style="display: flex;align-items: center; justify-content: center; width: 18px; height: 18px; border: 1px solid var(--primary-border-color); margin-right: 5px; border-radius: 10px;">-</button>
                                    <input type="text" @keydown.enter="performCalculation"
                                        class="peer form-input bg-gray-100 placeholder:tracking-widest ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                                        min="1" x-model="addedProduct.qty" 
                                        @input="updateQuantityFromInput($event.target.value, index)"
                                        style="outline: none; border: none; padding: 2px 5px; width: 50px; text-align: center; " id="pos-quantity" x-init="$el.focus().select()">
                                    <button type="button" @click="increQuantity(addedProduct)"  x-bind:disabled="buttonDisabled"
                                        style="display: flex;align-items: center; justify-content: center; width: 18px; height: 18px; border: 1px solid var(--primary-border-color); margin-left: 5px; border-radius: 10px;">+</button>
</p>
                                        </div>
                                    </div>
                                    <div
                                        style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
                                        <button type="button" class="btn btn-danger"
                                            @click="productPopUp = null">Close</button>
                                            <button x-show="myProducts.length > 0" type="button" class="btn btn-info"
                                            @click="viewCart()">View</button>
                                        <button type="button" class="btn btn-primary"
                                        @click="if (productDisabled === false) addToMyProducts(addedProduct)">ADD TO CART
                                        </button>
                                    </div>
                                </div>
                            </div>