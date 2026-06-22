<!-- start main content section -->
{!! Form::open(['url' => action('QRController@store'), 'method' => 'post', 'id' => 'customer_add_form' , 'files' => true]) !!}
<div class="container-fluid no-print">
    <div class="row" @keydown.window="keydownHandler">
        
            {!! Form::hidden('location_id', $default_location, ['id' => 'location_id', 'required']) !!} 
            {!! Form::hidden('contact_id', null, ['id' => 'customer_id', 'x-model'=>"selectedCustomer"], 'required') !!}
            {!! Form::hidden('table_id', request()->get('table'), ['id' => 'table_id']) !!}
            {!! Form::hidden('order_type', request()->get('order_type'), ['id' => 'order_type']) !!}
            
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

                        <template x-for="(category, index) in categories" :key="index">
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
                            <div class="border border-colored p-2" @click="if (productDisabled === false) addNewProduct(product)"
                                style="cursor: pointer;">
                                <div class="details">
                                    <span style="font-size: 10px; margin-top: -100px;"
                                        x-text="product.skuCode"></span>
                                    <h6 style="font-size: 12px; font-weight: bold;"
                                        x-text="product.description.length > 20 ? `${product.description.slice(0, 25)}...` : product.description">
                                    </h6>
                                    <p style="margin: 0; font-size: 12px; font-weight: bold;"
                                        x-text="`Rs${product.price}`"></p>
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
    </div>
</div>
<!-- end main content section -->
 @include('qr.productPopUp')
 @include('qr.viewCardPopUp')
{!! Form::close() !!}