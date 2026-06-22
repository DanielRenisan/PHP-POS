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
                <a href="{{action('FoodCalculationController@index')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"><span>Food Calculation</span></a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Create Food Calculation</span>
                </li>
            </ul>
            <div class="grid grid-cols-1 gap-4 pt-5">
                <div x-data="facilityList">
                    <div class="panel">
                        <div>
                            <div class="p-5" x-data="{ selectedCusType: 'default', selectedHotelOption: '',  newCustomer: {}, imagePreview: null,  }">
                                <form enctype="multipart/form-data" id="food_calc_form" class="needs-validation" method="POST" action="{{ route('food-calculation.store') }}">
                                    @csrf
                                    <div class="grid gap-5" style="margin-bottom: 20px;">
                                        <div>
                                            <span class="inline-block w-1/4" style="margin-right: -135px;">
                                                <label for="menuItem" class="inline-block mb-2 text-sm font-medium text-gray-900 dark:text-white">Menu : </label>
                                                <select id="menuItem" name="menuItem" required style="width: 90%; height: 5%" class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option value="">Select Menu</option>
                                                    @foreach($catelogProductDetails as $catelog)
                                                        <option value="{{ $catelog->id }}" >{{ $catelog->name }} - {{ $catelog->menu }}</option>
                                                    @endforeach
                                                </select>
                                                <p x-text="'Selected product: ' + selectedProduct"></p>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- CRUD Table -->
                                    <div class="relative overflow-x-auto sm:rounded-lg" x-data="tableData()">
                                        <table class="mt-5 w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    No
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Product
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Qty
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Unit
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Ingrediant Amount
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Wastage Qty
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Wastage Unit
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Wastage Amount
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Total
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    <span class="sr-only">Delete</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <template x-for="(row, index) in rows" :key="index">
                                                <tr>
                                                    <td class="py-2 px-4 border-b">
                                                        <p x-text="row.no"></p>
                                                    </td>
                                                    <td class="py-2 px-4 border-b">
                                                        <select required :id="'selectedUnitProduct' + index" x-bind:name="`rows[${index}][name]`" x-model="row.selectedUnitProduct" @change="setProductRelatedData(index)" style="width: 100%; height: 5%" class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            <option  value="">Select Item</option>
                                                            <template x-for="unitProduct in unitProducts">
                                                                <option :value="unitProduct.id" x-text="unitProduct.name"></option>
                                                            </template>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input :id="'qty_' + index" x-bind:name="`rows[${index}][qty]`" x-model="row.qty" @change="changeIngredientAmount(index)" type="number" style="width: 100%; height: 5%" class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                    </td>
                                                    <td>
                                                        <select  required :id="'unit_' + index" x-bind:name="`rows[${index}][selectedUnit]`" x-model="row.selectedUnit" @change="updateUnitsAndWastageUnit(index); changeProductUnit(index)" @click="setWastageUnit(index)" style="width: 100%; height: 5%" class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            <option value="">Select Unit</option>
                                                            <template x-for="unit in row.productUnits">
                                                                <option :value="unit.id" x-bind:selected="relatedProductUnit === unit.short_code" x-text="unit.short_code"></option>
                                                            </template>
                                                        </select>

                                                        <p x-text="'Selected product: ' + selectedProduct"></p>
                                                    </td>
                                                    <td>
                                                    <span :id="'int_amount_' + index"  x-text="row.intAmount" style="width: 100%; height: 5%" class="inline-block bg-gray-50 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">0</span>
                                                        <input :id="'int_amount_' + index" x-bind:name="`rows[${index}][intAmount]`" type="hidden" x-model="row.intAmount" @input="calculateTotal(index); calculateOverallIngredientAmount()" style="width: 100%; height: 5%" class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                    </td>
                                                    <td>
                                                        <input :id="'wast_qty_' + index" x-bind:name="`rows[${index}][wastQty]`" type="number" x-model="row.wastQty" @input="setWastageUnit(index)" @change="getWastageAmount(index)" style="width: 100%; height: 5%" class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                    </td>
                                                    <td>
                                                        <select  required :id="'wast_unit_' + index" x-bind:name="`rows[${index}][wastUnit]`" x-model="row.wastUnit" @change="updateUnitsAndWastageUnit(index)" @click="getWastageAmount(index)" style="width: 100%; height: 5%" class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            <option value="">Select Unit</option>
                                                            <template x-for="unit in row.wastageUnit">
                                                                <option :value="unit.id" x-text="unit.short_code"></option>
                                                            </template>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <span x-text="row.wastAmount"  class="inline-block bg-gray-50 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">0</span>
                                                        <input :id="'wast_amount_' + index" x-bind:name="`rows[${index}][wastAmount]`" x-model="row.wastAmount" type="hidden" @change="calculateOverallWastageAmount()" @input="calculateTotal(index)" style="width: 100%; height: 5%" class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                    </td>
                                                    <td>
                                                    <span x-text="row.total"  class="inline-block bg-gray-50 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">0</span>
                                                        <input :id="'total_' + index" x-bind:name="`rows[${index}][total]`" x-model="row.total" type="hidden" style="width: 100%; height: 5%" class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                    </td>
                                                    <td>
                                                        <button type="button" x-show="index === rows.length - 1" @click="addRow">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                                            </svg>
                                                        </button>
                                                        <button type="button" x-show="index !== rows.length - 1" @click="removeRow(index)"> -
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                            </tbody>
                                        </table>
                                    </div>
                                    <br>
                                    <div class="mt-10">
                                    <span class="inline-block flex justify-start">
                                        <label for="labour_hour" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;">Labour Hour</label>
                                        <input id="labour_hour" type="number" style="width: 30%" class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="labour_hour" />
                                    </span>
                                        <span class="inline-block flex justify-end" style="margin-top: -41px;">
                                        <label for="ingredient_cost" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;">Ingredient Cost</label>
                                        <input id="ingredient_cost" name="ingredient_cost" type="hidden" style="width: 30%" @input="calculateGrandTotal()" class="inline-block ml-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

                                        <span id="ingredient_cost_text" style="width: 30%" class="inline-block ml-10 bg-gray-50 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">0</span>
                                    </span>
                                    </div>
                                    <div class="mt-5">
                                    <span class="inline-block flex justify-start">
                                        <label for="prepare_time" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;">Prepare Time</label>
                                        <input type="text" id="prepare_time" style="width: 30%" name="prepare_time" pattern="[0-9]{2}:[0-9]{2}" placeholder="HH:MM" maxlength="5" @change="calculateTotalTime" class="inline-block ml-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    </span>
                                    <span class="inline-block flex justify-end" style="margin-top: -41px;">
                                        <label for="waste_cost" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;">Waste Cost</label>
                                        <input id="waste_cost" name="waste_cost" type="hidden" style="width: 30%" @input="calculateGrandTotal()" class="inline-block ml-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                        <span id="waste_cost_text" style="width: 30%" class="inline-block ml-10 bg-gray-50 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">0</span>
                                    </span>
                                    </div>
                                    <div class="mt-5">
                                    <span class="inline-block flex justify-start">
                                        <label for="service_time" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;">Service Time</label>
                                        <input type="text" id="service_time" style="width: 30%" name="service_time" pattern="[0-9]{2}:[0-9]{2}" placeholder="HH:MM" maxlength="5" @change="calculateTotalTime" class="inline-block ml-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    </span>
                                        <span class="inline-block flex justify-end" style="margin-top: 1px;">
                                        <label for="extra_cost" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;">Extra Cost</label>
                                        <input id="extra_cost" name="extra_cost" type="number" style="width: 30%" @input="calculateGrandTotal()" class="inline-block ml-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" /><br>
                                    </span>
                                        <span class="inline-block flex justify-end" style="margin-top: -94px;">
                                        <label for="tax_cost" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;">Tax Cost</label>
                                        <select x-model="row.tax" style="width: 30%" class="inline-block ml-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option>Select Tax</option>
                                            <template x-for="tax in taxes">
                                                <option :value="tax.id" x-text="tax.name"></option>
                                            </template>
                                        </select>
                                    </span>
                                    </div>
                                    <div class="mt-5">
                                    <span class="inline-block flex justify-start">
                                        <label for="total_time" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;">Total Time</label>
                                        <input id="total_time" type="text" style="width: 30%" class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="total_time" />
                                    </span>
                                        <span class="inline-block flex justify-end" style="margin-top: 5px">
                                        <label for="service_cost" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;">Service Cost</label>
                                        <input id="service_cost" type="number" style="width: 30%" @input="calculateGrandTotal()" class="inline-block ml-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="service_cost" />
                                    </span>
                                    </div>
                                    <div class="mt-5">
                                    <span class="inline-block flex justify-start">
                                        <label for="cook_instruction" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 93px;">Cook <br> Instruction</label>
                                        <textarea id="cook_instruction" name="cook_instruction" rows="4" style="width: 40%" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                    </span>
                                        <span class="inline-block flex justify-end" style="margin-top: -101px;">
                                        <label for="profit_margin" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;">Profit Margin</label>
                                        <input id="profit_margin" name="profit_margin" type="number" style="width: 30%" @input="calculateGrandTotal()" class="inline-block ml-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                    </span>
                                    </div>
                                    <div class="mt-5">
                                    <span class="inline-block flex justify-start">
                                        <label for="service_instruction" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 93px;">Service <br> Instruction</label>
                                        <textarea id="service_instruction" name="service_instruction" rows="4" style="width: 40%; margin-top: 58px" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                    </span>

                                        <span class="inline-block flex justify-end" style="margin-top: -110px;">
                                        <label for="grand_total" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;"><b>GRAND TOTAL</b></label>
                                        <span id="grand_total_text" style="width: 30%" class="inline-block ml-10 bg-gray-50 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">0</span>
                                        <input id="grand_total" name="grand_total" type="hidden" style="width: 30%" class="inline-block ml-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                    </span>

                                        <span class="inline-block flex justify-end" style="margin-top: -101px;">
                                        <label for="labour_cost" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;">Labour Cost</label>
                                        <input id="labour_cost" name="labour_cost" type="number" style="width: 30%" @input="calculateGrandTotal()" class="inline-block ml-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                    </span>
                                    </div>
                                    <div style="margin-top: 80px">
                                    <span class="inline-block flex justify-end">
                                        <label for="selling_price" class="inline-block mb-2 mr-5 text-sm font-medium text-gray-900 dark:text-white" style="margin-right: 6px;margin-top: 11px;"><b>Selling Price</b></label>
                                        <span id="selling_price_text" style="width: 30%" class="inline-block ml-10 bg-gray-50 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">0</span>
                                        <input id="selling_price" name="selling_price" type="hidden" style="width: 30%" class="inline-block ml-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                    </span>
                                    </div>
                            </div>
                            <div>
                                <div class="flex justify-end items-center mt-3">
                                    <button type="button" class="btn btn-outline-danger" @click="openModal = false">Discard
                                    </button>
                                    <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4" @click="collectAndSendData">Create
                                    </button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            Alpine.data('facilityList', () => ({
                selectedRows: [],
                items: <?php echo $facilities; ?>,
                products: <?php echo $products; ?>,
                catelogProductDetails: <?php echo $catelogProductDetails; ?>,
                relatedProductUnit: '',
                relatedProductUnits: [],
                productSalePrice: 0,
                units: <?php echo $units; ?>,
                wastageUnit: [],
                taxes: <?php echo $taxes; ?>,
                unitProducts: <?php echo $unitProducts; ?>,
                rows: [{
                    no: 1,
                    selectedUnitProduct: null,
                    qty: null,
                    selectedUnit: null,
                    intAmount: 0,
                    wastQty: null,
                    wastUnit: null,
                    wastAmount: 0,
                    total: 0
                }],
                searchText: '',
                openModal: false,
                editModal: false,
                viewModal: false,
                viewItem: {},
                itemToEdit: {},
                costCalculationProducts: [],
                pageSize: 5, // Number of items per page
                currentPage: 1, // Current page number

                showViewModal(item) {
                    this.viewItem = item; // Set the item to view
                    this.viewModal = true; // Show the view modal
                },

                get filteredItems() {
                    return this.items.filter(item => {
                        return item.name.toLowerCase().includes(this.searchText.toLowerCase());
                    });
                },

                get paginatedFilteredItems() {
                    const filtered = this.filteredItems;
                    return filtered.slice(this.startIndex, this.endIndex);
                },

                get totalPages() {
                    return Math.ceil(this.items.length / this.pageSize);
                },

                get startIndex() {
                    return (this.currentPage - 1) * this.pageSize;
                },

                get endIndex() {
                    return this.currentPage * this.pageSize;
                },

                get paginatedItems() {
                    return this.items.slice(this.startIndex, this.endIndex);
                },

                changePage(pageNumber) {
                    this.currentPage = pageNumber;
                },

                previousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                    }
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                    }
                },

                handleIconChange(event) {
                    const file = event.target.files[0];

                    if (file) {
                        const allowedFormats = ['image/jpeg', 'image/png', 'image/gif'];

                        if (!allowedFormats.includes(file.type)) {
                            document.getElementById('iconError').classList.remove('hidden');
                            this.iconFile = null;
                        } else {
                            document.getElementById('iconError').classList.add('hidden');

                            const reader = new FileReader();
                            reader.readAsDataURL(file);
                            reader.onload = () => {
                                this.itemToEdit.icon = reader.result; // Update the icon with the new image data
                            };
                        }
                    }
                },

                setWastageUnit(index) {
                    const ingredientSelectedUnit = this.rows[index].selectedUnit;

                    const relatedUnits = JSON.parse(JSON.stringify(this.units)).filter((unit) => {
                        return unit.unit_parent_id === parseInt(ingredientSelectedUnit) || unit.id === parseInt(ingredientSelectedUnit)
                    });

                    this.wastageUnit = JSON.parse(JSON.stringify(relatedUnits));
                },

                getWastageAmount(index) {
                    const row = this.rows[index];

                    const ingredientSelectedUnit = row.selectedUnit;
                    const ingredientAmount = row.intAmount;
                    const unitAmount  = row.unitAmount;
                    const wastageSelectedUnit = row.wastUnit;
                    const wastageQty = row.wastQty;

                    const ingredientUnit = JSON.parse(JSON.stringify(this.units)).filter((unit) => {
                        return unit.id === parseInt(ingredientSelectedUnit)
                    })[0].add_shortcode_for_otherunit;
                    const parUnit = JSON.parse(JSON.stringify(this.units)).filter((unit) => {
                        return unit.short_code === row.relatedProductUnit
                    })[0].unit_parent_id;

                    const wastageUnit = JSON.parse(JSON.stringify(this.units)).filter((unit) => {
                        return unit.id === parseInt(wastageSelectedUnit)
                    })[0].add_shortcode_for_otherunit;
                    var wastageAmountPerUnit = 0;
                    
                    if(parUnit !== null)
                    {
                        wastageAmountPerUnit = unitAmount;
                    }
                    else
                    {
                        wastageAmountPerUnit = unitAmount/ parseInt(wastageUnit) ;
                    }
                    const wholeWastageAmount = parseInt(wastageQty) * wastageAmountPerUnit;

                    this.rows[index].wastAmount = parseInt(wastageQty) * wastageAmountPerUnit;
                    this.rows[index].total = wholeWastageAmount + parseFloat(ingredientAmount);
                    this.calculateOverallIngredientAmount();
                    this.calculateOverallWastageAmount();
                },

                setProductRelatedData(index) {
                    const row = this.rows[index];
console.log(this.rows)
                    const products = JSON.parse(JSON.stringify(this.products));

                    const relatedProductDetails = products.find((product) => {
                        return product.id === parseInt(row.selectedUnitProduct);
                    });
                    this.productSalePrice = relatedProductDetails.sale_price;

                    row.qty = 1;
                    row.unitAmount = relatedProductDetails.sale_price ? relatedProductDetails.sale_price : relatedProductDetails.last_purchase_price;
                    row.intAmount = relatedProductDetails.sale_price ? relatedProductDetails.sale_price : relatedProductDetails.last_purchase_price;
                    const unitId = relatedProductDetails.sale_unit_id ? relatedProductDetails.sale_unit_id : relatedProductDetails.purchase_unit_id;
                    this.setRelatedProductUnits(unitId,index);
                    row.selectedUnit = unitId;

                    row.relatedProductUnit = this.units.find((unit) => unit.id === parseInt(unitId)).short_code;

                    const relatedUnits = this.units.filter((unit) => {
                        return unit.unit_parent_id === parseInt(relatedProductDetails.sale_unit_id)  || unit.id === parseInt(relatedProductDetails.sale_unit_id) || unit.unit_parent_id === parseInt(relatedProductDetails.purchase_unit_id)  || unit.id === parseInt(relatedProductDetails.purchase_unit_id);
                    });

                    row.wastageUnit = JSON.parse(JSON.stringify(relatedUnits));

                    const total_int = this.rows.reduce((sum, row) => {
                        return sum + (parseFloat(row.intAmount) || 0);
                    }, 0).toFixed(2);
                    document.getElementById("ingredient_cost").value =  total_int;
                    $('span#ingredient_cost_text').html(__currency_trans_from_en(total_int, false));

                    const ingrediantAmount = parseFloat(row.intAmount) || 0;
                    const wastageAmount = parseFloat(row.wastAmount) || 0;

                    const total = ingrediantAmount + wastageAmount;

                    this.rows[index].total = total.toFixed(2);
                    this.calculateGrandTotal();
                },

                setRelatedProductUnits(unitId,index) {
                    const row = this.rows[index];
                    this.relatedProductUnits = JSON.parse(JSON.stringify(this.units)).filter((unit) => {
                        return unit.id === unitId || unit.unit_parent_id === unitId
                    })
                    row.productUnits = this.relatedProductUnits;
                },

                changeIngredientAmount(index) {
                    const row = this.rows[index];
                    console.log(row)
                    const ingredientSelectedUnit = row.selectedUnit;
                    const parUnit = JSON.parse(JSON.stringify(this.units)).filter((unit) => {
                        return unit.short_code === row.relatedProductUnit
                    })[0].unit_parent_id;

                    const ingredientUnit = JSON.parse(JSON.stringify(this.units)).filter((unit) => {
                        return unit.id === parseInt(ingredientSelectedUnit)
                    })[0].add_shortcode_for_otherunit;

                    if(parUnit !== null)
                    {
                        row.intAmount = row.qty * row.unitAmount;
                    }
                    else
                    {
                        row.intAmount = row.qty * row.unitAmount/ parseInt(ingredientUnit) ;
                    }

                    const ingrediantAmount = parseFloat(row.intAmount) || 0;
                    const wastageAmount = parseFloat(row.wastAmount) || 0;

                    const total = ingrediantAmount + wastageAmount;

                    this.rows[index].total = total.toFixed(2);
                    this.calculateOverallIngredientAmount();
                },

                changeProductUnit(index) {
                    const row = this.rows[index];
                    elementId = 'unit_'+index;
                    const rowUnit = document.getElementById(elementId).value;

                    const productPrice = JSON.parse(JSON.stringify(this.products)).find((product) => {
                        return  product.id === parseInt(row.selectedUnitProduct)
                    }).sale_price;

                    const productPurchasePrice = JSON.parse(JSON.stringify(this.products)).find((product) => {
                        return  product.id === parseInt(row.selectedUnitProduct)
                    }).last_purchase_price;

                    const changedUnit = JSON.parse(JSON.stringify(this.units)).find((unit) => {
                        return unit.id === parseInt(rowUnit);
                    });
                    console.log(row);
                    row.intAmount = productPrice ? row.qty * (productPrice / parseInt(changedUnit.add_shortcode_for_otherunit)) : row.qty * ( productPurchasePrice / parseInt(changedUnit.add_shortcode_for_otherunit));
                    this.productSalePrice = row.intAmount;
                    this.updateUnitsAndWastageUnit(index);
                    this.calculateTotal(index);
                    this.calculateOverallIngredientAmount();
                },

                updateUnitsAndWastageUnit(index) {
                    const row = this.rows[index];
                    const selectedUnitId = row.selectedUnit;


                    row.units1 = JSON.parse(JSON.stringify(this.units)).filter((unit) => {
                        return unit.unit_parent_id === parseInt(selectedUnitId) || unit.id === parseInt(selectedUnitId);
                    });

                    row.wastageUnit = JSON.parse(JSON.stringify(row.units1));
                },

                editItem(itemId) {
                    const itemToEdit = JSON.parse(JSON.stringify(this.items.find(item => item.id === itemId)));

                    this.costCalculationProducts = itemToEdit.costCalculationProducts;

                    this.itemToEdit = {
                        ...itemToEdit
                    };
                    this.editModal = true;
                },

                editCategory() {
                    var data = $('form#call_edit_form').serialize();
                    var id = $('form#call_edit_form').find('#edit-id').val();
                    var url = $('form#call_edit_form').attr("action").replace('ID', id)
                    $.ajax({
                        method: "POST",
                        url: url,
                        dataType: "json",
                        data: data,
                        success: function(result) {
                            if (result.success == true) {
                                window.location.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                },


                checkAllCheckbox() {
                    if (this.items.length && this.selectedRows.length === this.items.length) {
                        return true;
                    } else {
                        return false;
                    }
                },

                checkAll(isChecked) {
                    if (isChecked) {
                        this.selectedRows = this.items.map((d) => {
                            return d.id;
                        });
                    } else {
                        this.selectedRows = [];
                    }
                },

                calculateTotal(index) {
                    const row = this.rows[index];

                    const ingrediantAmount = parseFloat(row.intAmount) || 0;
                    const wastageAmount = parseFloat(row.wastAmount) || 0;

                    const total = ingrediantAmount + wastageAmount;

                    this.rows[index].total = total.toFixed(2);
                },

                calculateOverallIngredientAmount() {
                    const total_int = this.rows.reduce((sum, row) => {
                        return sum + (parseFloat(row.intAmount) || 0);
                    }, 0).toFixed(2);
                    document.getElementById("ingredient_cost").value =  total_int;
                    $('span#ingredient_cost_text').html(__currency_trans_from_en(total_int, false));
                    this.calculateGrandTotal();
                },

                calculateOverallWastageAmount() {
                    const total_waste_cost = this.rows.reduce((sum, row) => {
                        return sum + (parseFloat(row.wastAmount) || 0);
                    }, 0).toFixed(2);
                    document.getElementById("waste_cost").value = total_waste_cost;
                    $('span#waste_cost_text').html(__currency_trans_from_en(total_waste_cost, false));
                    this.calculateGrandTotal();
                },

                calculateGrandTotal() {
                    console.log(11);
                    const ingreditentCost = parseFloat(document.getElementById("ingredient_cost").value) || 0;
                    const wastageCost = parseFloat(document.getElementById("waste_cost").value) || 0;
                    const extraCost = parseFloat(document.getElementById("extra_cost").value) || 0;
                    const serviceCost = parseFloat(document.getElementById("service_cost").value) || 0;
                    const profitMargin = parseFloat(document.getElementById("profit_margin").value) || 0;
                    const labourCost = parseFloat(document.getElementById("labour_cost").value) || 0;

                    const grandTotal = ingreditentCost + wastageCost + extraCost + serviceCost + profitMargin + labourCost;;

                    document.getElementById("grand_total").value = grandTotal;
                    document.getElementById("selling_price").value = grandTotal;
                    $('span#selling_price_text').html(__currency_trans_from_en(grandTotal, false));
                    $('span#grand_total_text').html(__currency_trans_from_en(grandTotal, false));
                },

                calculateTotalTime() {
                    const designTimeInput = document.getElementById('prepare_time');
                    const devTimeInput = document.getElementById('service_time');
                    const totalTimeInput = document.getElementById('total_time');

                    const designTime = designTimeInput.value;
                    const devTime = devTimeInput.value;

                    const designTimeInSeconds = this.convertTimeToSeconds(designTime);
                    const devTimeInSeconds = this.convertTimeToSeconds(devTime);

                    let totalSeconds = designTimeInSeconds + devTimeInSeconds;

                    let totalHours = Math.floor(totalSeconds / 3600);
                    let totalMinutes = Math.floor((totalSeconds % 3600) / 60);
                    let remainingSeconds = totalSeconds % 60;

                    totalTimeInput.value = `${String(totalHours).padStart(2, '0')}:${String(totalMinutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
                },
                convertTimeToSeconds(time) {
                    const [hours, minutes] = time.split(':').map(Number);
                    return hours * 3600 + minutes * 60;
                },

                collectAndSendData() {
                    $('form#food_calc_form').validate({
                        rules: {
                            menuItem: "required",
                            productQty: "required",
                            productUnit: "required",

                        },
                        messages: {
                            menuItem: "Required Field",
                            productQty: "Required Field",
                            productUnit: "Required Field",
                        }
                    });
                    if ($('form#food_calc_form').valid()) {
                        // this.openModal = false;
                        $('form#food_calc_form').submit();
                    }
                },

                tableData() {
                    return {
                        products: <?php echo $products; ?>,
                        units: <?php echo $units; ?>,

                        addRow() {
                            this.rows.push({
                                no: this.rows.length + 1,
                                selectedUnitProduct: null,
                                qty: null,
                                selectedUnit: null,
                                intAmount: null,
                                wastQty: null,
                                wastUnit: null,
                                wastAmount: null,
                                total: null
                            });
                        },

                        removeRow(index) {
                            this.rows.splice(index, 1);
                        },
                    }
                },



                deleteRow(item) {
                    if (confirm('Are you sure want to delete selected row ?')) {
                        var href = $('.delete-button').attr('data-href');
                        $.ajax({
                            method: "GET",
                            url: href,
                            dataType: "json",
                            data: {
                                ids: this.selectedRows
                            },
                            success: function(result) {
                                if (result.success == true) {
                                    window.location.reload();

                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                },
            }));
        });
    </script>
@endsection
@section('javascript')
    <script type="text/javascript">

    </script>
@endsection
