<div x-show.transition.duration.500ms="variationPopupVisible"
                                :class="{ 'popup-hidden': !variationPopupVisible, 'popup-visible': variationPopupVisible }"
                                class="popup" @keydown.enter="addProductWithVariations" style="width: 35%;"
                                @click.away="variationPopupVisible = null">
                                <div>
                                    <h6 style="background: skyblue; color: white; position: absolute; top: 0; left: 0; width: 100%; padding: 10px 20px;"
                                        class="menu-title">Product Variations</h6>
                                </div>
                                <div style="margin-top: 35px;">
                                    <div style="display: flex;">
                                        <div style="width: 30%;">
                                            <img :src="selectedProductWithVariation.imageUrl" alt="Product Image"
                                                style="width: 100px; height: 100px; border: 1px solid lightgray; border-radius: 5px; padding: 2px;">
                                        </div>
                                        <div style="width: 100%;">
                                            <template
                                                x-for="(variation, index) in selectedProductWithVariation.variations"
                                                :key="index">
                                                <div style="margin-bottom: 20px;">
                                                    <div>
                                                        <label
                                                            style="margin-bottom: 15px; font-weight: bold; font-size: 16px;"
                                                            x-text="variation.name"></label>
                                                        <div x-show="variation.type === 'radio'"
                                                            class="radio-button-pill">
                                                            <template x-for="value in variation.values"
                                                                :key="value.name">
                                                                <div class="radio-button">
                                                                    <label class="radio-button-checked"
                                                                        style="font-size: 16px;">
                                                                        <input type="radio"
                                                                            :id="variation.name + '-' + value.name"
                                                                            x-on:change="updateImage(variation.varImg, value.price, value.name);"
                                                                            :value="value.name"
                                                                            x-model="selectedVariations[variation.name]" />
                                                                        <span
                                                                            x-text="value.name + ' - Rs' + value.price"></span>
                                                                    </label>
                                                                </div>
                                                            </template>
                                                        </div>
                                                        <div x-show="variation.type === 'checkbox'"
                                                            class="checkbox-button-pill">
                                                            <template x-for="value in variation.values"
                                                                :key="value.name">
                                                                <div class="checkbox-button">
                                                                    <label class="checkbox-button-checked"
                                                                        style="font-size: 16px;">
                                                                        <input type="checkbox"
                                                                            :id="variation.name + '-' + value.name"
                                                                            x-on:change="updateImage(variation.varImg, value.price, value.name);"
                                                                            :value="value.name"
                                                                            :checked="selectedVariations[variation.name] && selectedVariations[variation.name].includes(value.name)"
                                                                            @change="
                                                                      if (!selectedVariations[variation.name]) {
                                                                        selectedVariations[variation.name] = [];
                                                                      }
                                                                      if ($event.target.checked) {
                                                                        selectedVariations[variation.name].push(value.name);
                                                                      } else {
                                                                        const index = selectedVariations[variation.name].indexOf(value.name);
                                                                        if (index !== -1) {
                                                                          selectedVariations[variation.name].splice(index, 1);
                                                                        }
                                                                      }
                                                                    " />
                                                                        <span
                                                                            x-text="value.name + ' - Rs' + value.price"></span>
                                                                    </label>
                                                                </div>
                                                            </template>
                                                        </div>
                                                        <div x-show="variation.type === 'dropdown'">
                                                            <select class="form-select"
                                                                x-model="selectedVariations[variation.name]"
                                                                x-on:change="updateImageFromDropdown(variation)">
                                                                <option value="">Select a Variation</option>
                                                                <template x-for="value in variation.values"
                                                                    :key="value.name">
                                                                    <option :value="value.name"
                                                                        x-text="value.name + ' - Rs' + value.price">
                                                                    </option>
                                                                </template>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div
                                        style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
                                        <button type="button" class="btn btn-danger"
                                            @click="variationPopupVisible = null">Close</button>
                                        <button type="button" class="btn btn-primary"
                                            @click="addProductWithVariations">Add
                                            Product</button>
                                    </div>
                                </div>
                            </div>