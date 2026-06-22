<div x-show.transition.duration.500ms="entryPopup"
    :class="{ 'popup-hidden': !entryPopup, 'popup-visible': entryPopup }" class="popup"
    @click.away="entryPopup = null"
    style="width: 80%; max-height: 90vh; height: 80vh; overflow: auto;">
    <div style="height: 100%">
        <div style="padding: 50px; height: 100%">
            <div class="entry-content" style="height: 100%">
                <div class="wrapper" style="height: 100%">
                    <div class="customer-visible" x-show="customerSectionVisible">
                        <div class="customer-buttons">
                            <div class="item" @click="showNewCustomerForm"
                                :class="{ 'highlighted': isNewCustomerSelected }">
                                <img src="{{ asset('asset/images/add-user.png')}}"
                                    alt="">
                                <button  type="button"
                                    :class="{ 'highlighted': isNewCustomerSelected }">New
                                    Customer</button>
                            </div>
                            <div class="item" @click="showExistingCustomerSearch"
                                :class="{ 'highlighted': isExistingCustomerSelected }">
                                <img src="{{ asset('asset/images/exit-user.png')}}" alt="">
                                <button type="button"
                                    :class="{ 'highlighted': isExistingCustomerSelected }">Existing
                                    Customer</button>
                            </div>
                        </div>

                        <div x-show="isNewCustomerFormVisible"
                            style="width: 70%; height: 100%">
                            <form class="new-customer-form"
                                @submit.prevent="addCustomer">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label for="Name">Name</label>
                                        <input class="form-input" type="text" name="quick_name" id="quick_name" required
                                            placeholder="Customer Name"
                                            x-model="newCustomer.name">
                                    </div>
                                    <div>
                                        <label for="Mobile No">Mobile No</label>
                                        <input class="form-input" type="number" name="quick_mobile" id="quick_mobile" required
                                            placeholder="Customer Mobile No"
                                            x-model="newCustomer.mobileNo">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary">Add
                                        New
                                        Customer</button>
                                </div>
                            </form>
                        </div>

                        <div x-show="isExistingCustomerSearchVisible"
                            style="width: 70%; height: 100%">
                            <!-- Existing Customer Search Bar -->
                            <form style="margin: 0 !important;"
                                class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0"
                                >
                                <div class="relative">
                                    <input type="text" x-model="customerSearchQuery"
                                        class="peer border-colored form-input bg-gray-100 placeholder:tracking-widest ltr:pl-9 ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                                        placeholder="Search Customer"
                                        @input="searchExistingCustomers" />
                                    <button type="button"
                                        class="absolute inset-0 h-9 w-9 appearance-none peer-focus:text-primary ltr:right-auto rtl:left-auto btn-hover">
                                        <svg class="mx-auto" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="11.5" cy="11.5" r="9.5"
                                                stroke="currentColor" stroke-width="1.5"
                                                opacity="0.5" />
                                            <path d="M18.5 18.5L22 22"
                                                stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                            <!-- Display Search Results -->
                            <table x-show="customerSearchResults.length > 0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>mobile</th>
                                        <th>Place</th>
                                        <th>Country</th>
                                        <th>Age</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="customer in customerSearchResults"
                                        :key="customer.id">
                                        <tr @click="selectExistingCustomer(customer)">
                                            <td x-text="customer.name"></td>
                                            <td x-text="customer.mobile"></td>
                                            <td x-text="customer.place"></td>
                                            <td x-text="customer.country"></td>
                                            <td x-text="customer.age"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <p
                                x-show="customerSearchResults.length === 0 && customerSearchQuery !== ''">
                                No results found</p>
                        </div>
                    </div>

                    <div x-show="orderTypeSectionVisible" style="height: 100%">
                        <div style="width: 100%;
                        height: 100%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        position: relative">
                            <i class="fas fa-arrow-left"
                                @click="goBackToCustomer()"></i>
                            <div style="width: 100%"
                                x-show="isNewCustomerSelected || isExistingCustomerSelected">
                                <!-- Order Type Div -->
                                <div class="order-type">
                                    <!-- Display the selection buttons -->
                                    <div class="buttons">
                                        <div class="item"
                                            @click="selectOrderType('Dine in')"
                                            :class="{ 'highlighted': selectedOrderType === 'Dine in' }">
                                            <img src="{{ asset('asset/images/dineInIcon.png')}}"
                                                alt="">
                                            <button @click="selectOrderType('Dine in')" type="button"
                                                :class="{ 'highlighted': selectedOrderType === 'Dine in' }">Dine
                                                In</button>
                                        </div>
                                        <!-- <div class="item"
                                            @click="selectOrderType('Room Order')"
                                            :class="{ 'highlighted': selectedOrderType === 'Room Order' }">
                                            <img src="{{ asset('asset/images/roomsIcon.png')}}"
                                                alt="">
                                            <button @click="selectOrderType('Room Order')" type="button"
                                                :class="{ 'highlighted': selectedOrderType === 'Room Order' }">Hotel</button>
                                        </div> -->
                                        <div class="item"
                                            @click="selectOrderType('Online')"
                                            :class="{ 'highlighted': selectedOrderType === 'Online' }">
                                            <img src="{{ asset('asset/images/online.png')}}"
                                                alt="">
                                            <button type="button"
                                                @click="selectOrderType('Online')"
                                                :class="{ 'highlighted': selectedOrderType === 'Online' }">Online</button>
                                        </div>
                                        <div class="item"
                                            @click="selectOrderType('Take away')"
                                            :class="{ 'highlighted': selectedOrderType === 'Take away' }">
                                            <img src="{{ asset('asset/images/takaway.png')}}"
                                                alt="">
                                            <button type="button"
                                                @click="selectOrderType('Take away')"
                                                :class="{ 'highlighted': selectedOrderType === 'Take away' }">Take
                                                Away</button>
                                        </div>
                                        <!-- <div class="item"
                                            @click="selectOrderType('Third Party')"
                                            :class="{ 'highlighted': selectedOrderType === 'Third Party' }">
                                            <img src="{{ asset('asset/images/thirdpartyIcon.png')}}"
                                                alt="">
                                            <button type="button"
                                                @click="selectOrderType('Third Party')"
                                                :class="{ 'highlighted': selectedOrderType === 'Third Party' }">Third
                                                Party</button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div x-show="dineInSection">
                        <i style="top: 45px; left: 50px;" class="fas fa-arrow-left" @click="goBack()"></i>
                        <!-- Display dine-in buttons only if Dine In is selected -->
                        <div class="mt-3 dine-buttons selecting-buttons"
                            x-show="selectedOrderType === 'Dine in'">
                            <template x-for="(button, index) in filteredDineInButtons"
                                :key="button.id">
                                <button :style="button.style" type="button"
                                    class="dine-in-buttons"
                                    :class="{ 'selected': selectedDineInButtons === button.id }"
                                    @click="handleButtonSelection('table',button.id)" 
                                    >
                                    <span style="font-weight: bold;"
                                        x-text="button.label"></span>
                                    <!-- Display order number for this table if it exists -->
                                    <template x-for="order in kotDisplayOrders" :key="order.no">
                                        <template
                                            x-if="order.table === button.label && order.orderNo">
                                            (<span style="font-size: 12px;"
                                                x-text="order.orderNo"></span>)
                                        </template>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div x-show="hotelSection">
                        <i style="top: 45px; left: 50px;" class="fas fa-arrow-left" @click="goBack()"></i>
                        <div class="mt-3 dine-buttons selecting-buttons"
                            x-show="selectedOrderType === 'Room Order'">
                            <template x-for="(button, index) in roomsButtons"
                                :key="button.id">
                                <button style="background-color: rgb(192, 219, 226);"
                                    type="button" class="dine-in-buttons"
                                    :class="{ 'selected': selectedRoomInButtons === button.id }"
                                    @click="handleButtonSelection('room',button.id)">
                                    <span style="font-weight: bold;"
                                        x-text="button.label"></span>
                                    <!-- Checkbox for including tax -->
                                    <!-- <div>
                                        <label style="margin: 0;
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        font-size: 12px;
                                        gap: 2px;">
                                            <input type="checkbox" value="1" name="is_include" id="is_include">
                                            Include Hotel Bill
                                        </label>
                                    </div> -->
                                    <!-- <div>
                                                                        <label class="checkbox-label">
                                                                            <input class="checkbox-round" type="checkbox" value="1" name="is_include" id="is_include">
                                                                            Include Hotel Bill
                                                                        </label>
                                                                    </div> -->
                                </button>
                            </template>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>