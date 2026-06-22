<!-- start sidebar section -->
<div class="no-print" :class="{'dark text-white-dark' : $store.app.semidark}">
    <nav x-data="sidebar"
        class="sidebar fixed bottom-0 top-0 z-50 h-full min-h-screen w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300">
        <div class="h-full bg-white dark:bg-[#0e1726]" style="background-color: #b2e4ffff !important;">
        @php 
            $bussiness = App\Models\Business::first();
        @endphp
            <div class="flex items-center justify-between px-4 py-3">
                <a href="{{route('home')}}" class="main-logo flex shrink-0 items-center">
                    <img class="ml-[5px] w-8 flex-none" src="{{ isset($bussiness) && isset($bussiness->logo) ? url( 'storage/business_logos/' . $bussiness->logo)  : asset('assets/images/logo.svg')}}" alt="image" />
                    <!-- <span
                        class="align-middle text-2xl font-semibold ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light lg:inline">{{ isset($bussiness) && isset($bussiness->name) ? $bussiness->name : 'Pearl Island' }}</span> -->
                </a>
                <a href="javascript:;"
                    class="collapse-icon flex h-8 w-8 items-center rounded-full transition duration-300 hover:bg-gray-500/10 rtl:rotate-180 dark:text-white-light dark:hover:bg-dark-light/10"
                    @click="$store.app.toggleSidebar()">
                    <svg class="m-auto h-5 w-5" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <ul class="perfect-scrollbar relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold"
                x-data="{ activeDropdown: 'dashboard' }">
                <li class="nav-item">
                    <ul>
                        <li  class="menu nav-item">
                            <a href="{{route('home')}}" class="nav-link group">
                                <div class="flex items-center">
                                         <i class="fa fa-sliders"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Dashboard</span>
                                </div>
                                <div class="rtl:rotate-180">
                                </div>
                            </a>
                        </li>
                        @if(auth()->user()->can('user.view') ||
                        auth()->user()->can('user.create') ||
                        auth()->user()->can('role.view') ||
                        auth()->user()->can('role.create') ||
                        auth()->user()->can('employee-type.view') ||
                        auth()->user()->can('employee-type.create')||
                        auth()->user()->can('attendance-type.view') ||
                        auth()->user()->can('attendance-type.create')||
                        auth()->user()->can('position.view') ||
                        auth()->user()->can('position.create') ||
                        auth()->user()->can('em-destination.view') ||
                        auth()->user()->can('em-destination.create') ||
                        auth()->user()->can('department.view') || auth()->user()->can('department.create'))
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group {{ $request->segment(1) == 'roles' || in_array($request->segment(2), ['employee', 'employee_type','attendance','position', 'destination','department']) ? 'active' : '' }}"
                                
                                @click="activeDropdown === 'user' ? activeDropdown = null : activeDropdown = 'user'">
                                <div class="flex items-center">
                                    <i class="fa fa-users"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Users</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'user'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>    
                            <ul x-cloak x-show="activeDropdown === 'user'" x-collapse class="sub-menu text-gray-500">
                                @if(auth()->user()->can('user.view') || auth()->user()->can('user.create'))   
                                <li class="{{ $request->segment(2) == 'employee'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\EmployeeController@index')}}">Employee</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('role.view') || auth()->user()->can('role.create'))   
                                    <li class="{{ $request->segment(1) == 'roles'  ? 'active' : '' }}"><a href="{{ action('RoleController@index') }}">Role</a></li>
                                @endif
                                @if(auth()->user()->can('employee-type.view') || auth()->user()->can('employee-type.create')) 
                                <li class="{{ $request->segment(2) == 'employee_type'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\EmployeeTypeController@index')}}">Employee Type</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('attendance-type.view') || auth()->user()->can('attendance-type.create'))
                                <li class="{{ $request->segment(2) == 'attendance'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\AttendanceTypeController@index')}}">Attendance</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('position.view') || auth()->user()->can('position.create'))
                                <li class="{{ $request->segment(2) == 'position'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\PositionController@index')}}">Position</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('em-destination.view') || auth()->user()->can('em-destination.create'))
                                <li class="{{ $request->segment(2) == 'destination'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\EMDestinationController@index')}}">Destination</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('department.view') || auth()->user()->can('department.create'))
                                <li class="{{ $request->segment(2) == 'department'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\DepartmentController@index')}}">Department</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(auth()->user()->can('contact.view') ||
                        auth()->user()->can('contact.create') ||
                        auth()->user()->can('supplier.view') ||
                        auth()->user()->can('supplier.create') ||
                        auth()->user()->can('wakeup.view') ||
                        auth()->user()->can('wakeup.create') ||
                        auth()->user()->can('customer-type.view') ||
                        auth()->user()->can('customer-type.create')||
                        auth()->user()->can('customer-group.view') ||
                        auth()->user()->can('customer-group.create'))
                        <li class="menu nav-item">
                            <button type="button" 
                            class="nav-link group {{ in_array($request->segment(1), ['wakeup-call', 'guest']) || $request->segment(3) == 'index_s' || in_array($request->segment(2), ['contact', 'customer_type', 'customer_group']) ? 'active' : '' }}"
                            @click="activeDropdown === 'contact' ? activeDropdown = null : activeDropdown = 'contact'">
                                <div class="flex items-center">
                                    <i class="fa fa-address-book"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Contacts</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'contact'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'contact'" x-collapse class="sub-menu text-gray-500">
                                @if(auth()->user()->can('contact.view') || auth()->user()->can('contact.create'))
                                <li class="{{ $request->segment(2) == 'contact' && $request->segment(3) == ''  ? 'active' : '' }}">
                                    <a href="{{action('Rest\ContactController@index')}}">Customer</a>

                                </li>
                                @endif
                                @if(auth()->user()->can('supplier.view') || auth()->user()->can('supplier.create'))
                                <li class="{{ $request->segment(2) == 'contact' && $request->segment(3) == 'index_s'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\ContactController@index_s')}}">Supplier</a>

                                </li>
                                @endif
                                
                                @if(auth()->user()->can('customer-type.view') || auth()->user()->can('customer-type.create'))
                                <li class="{{ $request->segment(2) == 'customer_type'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\CustomerTypeController@index')}}">Customer Type</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('customer-group.view') || auth()->user()->can('customer-group.create'))
                                <li class="{{ $request->segment(2) == 'customer_group'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\CustomerGroupController@index')}}">Customer Group</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(auth()->user()->can('room-facility.view') ||
                        auth()->user()->can('room-facility.create') ||
                        auth()->user()->can('room-details.view') ||
                        auth()->user()->can('room-details.create') ||
                        auth()->user()->can('room-size.view') ||
                        auth()->user()->can('room-size.create')||
                        auth()->user()->can('room-type.view') ||
                        auth()->user()->can('room-type.create')||
                        auth()->user()->can('bed.view') ||
                        auth()->user()->can('bed.create') ||
                        auth()->user()->can('booking-type.view') ||
                        auth()->user()->can('booking-type.create') ||
                        auth()->user()->can('complementary.view') ||
                        auth()->user()->can('complementary.create')||
                        auth()->user()->can('floor-plan.view') ||
                        auth()->user()->can('floor-plan.create')||
                        auth()->user()->can('booking-source.view') ||
                        auth()->user()->can('booking-source.create')||
                        auth()->user()->can('room.view') ||
                        auth()->user()->can('room.create'))
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group  {{ in_array($request->segment(1), ['room-facilities', 'room-details', 'room-sizes', 'types']) ? 'active' : '' }}"
                                @click="activeDropdown === 'facility' ? activeDropdown = null : activeDropdown = 'facility'">
                                <div class="flex items-center">
                                    <i class="fa fa-th-list"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Room
                                        Facility</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'facility'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'facility'" x-collapse
                                class="sub-menu text-gray-500">
                                @if(auth()->user()->can('room-facility.view') ||
                                auth()->user()->can('room-facility.create'))
                                <li class="{{ $request->segment(1) == 'room-facilities'  ? 'active' : '' }}">
                                    <a href="{{action('RoomFacilityController@index')}}">Facility</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('room-details.view') ||
                                auth()->user()->can('room-details.create'))
                                <li class="{{ $request->segment(1) == 'room-details'  ? 'active' : '' }}">
                                    <a href="{{action('RoomDetailController@index')}}">Facility Details</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('room-size.view') || auth()->user()->can('room-size.create'))
                                <li class="{{ $request->segment(1) == 'room-sizes'  ? 'active' : '' }}"><a href="{{ action('RoomSizeController@index') }}">Room Size</a></li>
                                @endif
                                @if(auth()->user()->can('room-type.view') || auth()->user()->can('room-type.create'))
                                <li class="{{ $request->segment(1) == 'types'  ? 'active' : '' }}"><a href="{{ action('RoomTypeController@index') }}">Room Type</a></li>
                                @endif
                                @if(auth()->user()->can('bed.view') || auth()->user()->can('bed.create'))
                                <li class="{{ $request->segment(1) == 'beds'  ? 'active' : '' }}"><a href="{{ action('BedController@index') }}">Bed List</a></li>
                                @endif
                                @if(auth()->user()->can('booking-type.view') ||
                                auth()->user()->can('booking-type.create'))
                                <li class="{{ $request->segment(1) == 'booking-types'  ? 'active' : '' }}"><a href="{{ action('BookingTypeController@index') }}">Booking Type List</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('complementary.view') ||
                                auth()->user()->can('complementary.create'))
                                <li class="{{ $request->segment(1) == 'complementaries'  ? 'active' : '' }}"><a href="{{ action('ComplementaryController@index') }}">Complementary</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('floor-plan.view') || auth()->user()->can('floor-plan.create'))
                                <li class="{{ $request->segment(1) == 'floor-plans'  ? 'active' : '' }}"><a href="{{ action('FloorPlaneController@index') }}">Floor Plan List</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('booking-source.view') ||
                                auth()->user()->can('booking-source.create'))
                                <li class="{{ $request->segment(1) == 'booking-sources'  ? 'active' : '' }}"><a href="{{ action('BookingSourceController@index') }}"></i>Booking
                                        Source</a></li>
                                @endif
                                @if(auth()->user()->can('room.view') || auth()->user()->can('room.create'))
                                <li class="{{ $request->segment(1) == 'rooms'  ? 'active' : '' }}"><a href="{{ action('RoomController@index') }}">Room Assign</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(auth()->user()->can('booking.view') ||
                        auth()->user()->can('booking.create') ||
                        auth()->user()->can('checkin.view') ||
                        auth()->user()->can('checkin.create') ||
                        auth()->user()->can('checkout.create') ||
                        auth()->user()->can('checkout.view'))
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group  {{ in_array($request->segment(1), ['bookings', 'check-in', 'check-out', 'room-status']) ? 'active' : '' }}"
                                @click="activeDropdown === 'reservation' ? activeDropdown = null : activeDropdown = 'reservation'">
                                <div class="flex items-center">
                                    <i class="fa fa-bed"></i>
                                    <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Room Reservation</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'reservation'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'reservation'" x-collapse class="sub-menu text-gray-500">
                                @if(auth()->user()->can('booking.view') || auth()->user()->can('booking.create')) 
                                    <li class="{{ $request->segment(1) == 'bookings'  ? 'active' : '' }}"><a href="{{ action('BookingController@index') }}">Booking</a></li>
                                @endif
                                @if(auth()->user()->can('checkin.view') || auth()->user()->can('checkin.create'))   
                                    <li class="{{ $request->segment(1) == 'check-in'  ? 'active' : '' }}"><a href="{{ action('CheckinController@index') }}">Checkin</a></li>
                                @endif
                                @if(auth()->user()->can('checkout.create')) 
                                    <li class="{{ $request->segment(1) == 'check-out' &&  $request->segment(2) == '' ? 'active' : '' }}"><a href="{{ action('CheckoutController@index') }}">Checkout</a></li>
                                @endif
                                @if(auth()->user()->can('checkout.view')) 
                                    <li class="{{ $request->segment(1) == 'check-out' &&  $request->segment(2) == 'list'  ? 'active' : '' }}"><a href="{{ action('CheckoutController@list') }}">Checkout List</a></li>
                                @endif
                                <li class="{{ $request->segment(1) == 'room-status'  ? 'active' : '' }}"><a href="{{ action('RoomStatusController@index') }}">Room Status</a></li>
                            </ul>
                        </li>
                        @endif
                        @if(auth()->user()->can('product.view') ||
                            auth()->user()->can('product.create') ||
                            auth()->user()->can('product-variation.view') ||
                            auth()->user()->can('product-variation.create') ||
                            auth()->user()->can('type.view') ||
                            auth()->user()->can('type.create') ||
                            auth()->user()->can('product-category.view') ||
                            auth()->user()->can('product-category.create') ||
                            auth()->user()->can('cousine.view') ||
                            auth()->user()->can('cousine.create') ||
                            auth()->user()->can('menu.view') ||
                            auth()->user()->can('menu.create') ||
                            auth()->user()->can('drink-type.view') ||
                            auth()->user()->can('drink-type.create') ||
                            auth()->user()->can('brand.view') ||
                            auth()->user()->can('brand.create') ||
                            auth()->user()->can('unit.view') ||
                            auth()->user()->can('unit.create') ||
                            auth()->user()->can('food-calculation.view') || auth()->user()->can('food-calculation.create'))
                            <li class="menu nav-item">
                            <button type="button" class="nav-link group   {{ $request->segment(1) == 'food-calculation' || in_array($request->segment(2), ['product', 'variation', 'types', 'categeory', 'cousine', 'menu', 'drinttype', 'brand', 'unit']) ? 'active' : '' }}"
                                @click="activeDropdown === 'products' ? activeDropdown = null : activeDropdown = 'products'">
                                <div class="flex items-center">
                                <i class="fa fa-product-hunt"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Products</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{'!rotate-90' : activeDropdown === 'products'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'products'" x-collapse
                                class="sub-menu text-gray-500">
                                @if(auth()->user()->can('product.view') || auth()->user()->can('product.create')) 
                                <li class="{{ $request->segment(2) == 'product'  ? 'active' : '' }}">
                                    <a href="{{ action('Rest\ProductController@index') }}">List</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('product-variation.view') || auth()->user()->can('product-variation.create')) 
                                <li class="{{ $request->segment(2) == 'variation'  ? 'active' : '' }}">
                                    <a href="{{ action('Rest\ProductVaritationController@index') }}">Variation</a>
                                </li>
                                @endif
                                @if($bussiness->is_need_food_calculation == 1)
                                    @if(auth()->user()->can('food-calculation.view') || auth()->user()->can('food-calculation.create'))
                                        <li class="{{ $request->segment(1) == 'food-calculation'  ? 'active' : '' }}">
                                            <a href="{{action('FoodCalculationController@index')}}">Add Food Cost</a>
                                        </li>
                                    @endif
                                @endif
                                @if(auth()->user()->can('type.view') || auth()->user()->can('type.create'))
                                <li class="{{ $request->segment(2) == 'types'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\TypeController@index')}}">Type</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('product-category.view') || auth()->user()->can('product-category.create'))
                                <li class="{{ $request->segment(2) == 'categeory'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\ProductCategoryController@index')}}">Category</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('cousine.view') || auth()->user()->can('cousine.create'))
                                <li class="{{ $request->segment(2) == 'cousine'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\CousineController@index')}}">Cousine</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('menu.view') || auth()->user()->can('menu.create'))
                                <li class="{{ $request->segment(2) == 'menu'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\MenuController@index')}}">Menu</a>
                                </li>
                                @endif
                               
                                @if(auth()->user()->can('brand.view') || auth()->user()->can('brand.create'))
                                <li class="{{ $request->segment(2) == 'brand'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\BrandController@index')}}">Brand</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('unit.view') || auth()->user()->can('unit.create'))
                                <li class="{{ $request->segment(2) == 'unit'  ? 'active' : '' }}">
                                    <a href="{{action('Rest\UnitController@index')}}">Unit</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(auth()->user()->can('purchase.view') ||
                            auth()->user()->can('purchase.create') ||
                            auth()->user()->can('purchase-return.view') ||
                            auth()->user()->can('purchase-return.create')||
                            auth()->user()->can('purchase-wastage.view') ||
                            auth()->user()->can('purchase-wastage.create'))
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group {{in_array($request->segment(1), ['purchases', 'purchase-returns', 'purchase-wastage']) ? 'active' : '' }}"
                                @click="activeDropdown === 'purshase' ? activeDropdown = null : activeDropdown = 'purshase'">
                                <div class="flex items-center">
                                <i class="fas fa-shopping-cart"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Purchases</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{'!rotate-90' : activeDropdown === 'purshase'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'purshase'" x-collapse
                                class="sub-menu text-gray-500">
                                @if(auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create'))
                                <li class="{{ $request->segment(1) == 'purchases' ? 'active' : '' }}">
                                    <a href="{{action('PurchaseController@index')}}">List</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('purchase-return.view') || auth()->user()->can('purchase-return.create'))
                                <li class="{{ $request->segment(1) == 'purchase-returns' ? 'active' : '' }}">
                                    <a href="{{ action('PurchaseReturnController@index') }}">Purchase Return</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('purchase-wastage.view') || auth()->user()->can('purchase-wastage.create'))
                                <li class="{{ $request->segment(1) == 'purchase-wastage' ? 'active' : '' }}">
                                    <a href="{{ action('PurchaseWastageController@index') }}">Wastage</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if($bussiness->is_kot == 1)
                        @if(auth()->user()->can('kitchen.view'))
                            <li class="menu nav-item">
                                <button type="button" class="nav-link group  {{in_array($request->segment(1), ['kot']) ? 'active' : '' }}"
                                    @click="activeDropdown === 'kit' ? activeDropdown = null : activeDropdown = 'kit'">
                                    <div class="flex items-center">
                                    <i class="fa fa-eercast "></i>
                                        <span
                                            class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Kitchen</span>
                                    </div>
                                    <div class="rtl:rotate-180"
                                        :class="{'!rotate-90' : activeDropdown === 'kit'}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </button>
                                <ul x-cloak x-show="activeDropdown === 'kit'" x-collapse
                                    class="sub-menu text-gray-500">
                                    <li class="{{ $request->segment(1) == 'kot' ? 'active' : '' }}">
                                        <a href="{{action('KitchenController@index')}}">KOT</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @endif
                        @if($bussiness->is_bot == 1)
                        @if(auth()->user()->can('bot.view'))
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group {{in_array($request->segment(1), ['bot']) ? 'active' : '' }}"
                                @click="activeDropdown === 'bar' ? activeDropdown = null : activeDropdown = 'bar'">
                                <div class="flex items-center">
                                <i class="fa fa-glass"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">BAR</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{'!rotate-90' : activeDropdown === 'bar'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'bar'" x-collapse
                                class="sub-menu text-gray-500">
                                <li class="{{ $request->segment(1) == 'bot' ? 'active' : '' }}">
                                    <a href="{{action('BOTController@index')}}">BOT</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @endif
                        @if(auth()->user()->can('expense.view') ||
                            auth()->user()->can('expense.create') ||
                            auth()->user()->can('category.view') ||
                            auth()->user()->can('category.create'))
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group {{in_array($request->segment(1), ['categories', 'expenses']) ? 'active' : '' }}"
                                @click="activeDropdown === 'expense' ? activeDropdown = null : activeDropdown = 'expense'">
                                <div class="flex items-center">
                                <i class="fa fa-money"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Expenses</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{'!rotate-90' : activeDropdown === 'expense'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'expense'" x-collapse
                                class="sub-menu text-gray-500">
                                @if(auth()->user()->can('expense.view') || auth()->user()->can('expense.create')) 
                                <li class="{{ $request->segment(1) == 'expenses' ? 'active' : '' }}">
                                    <a href="{{ action('ExpenseController@index') }}">List</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('category.view') || auth()->user()->can('category.create')) 
                                <li class="{{ $request->segment(1) == 'categories' ? 'active' : '' }}">
                                    <a href="{{ action('CategoryController@index') }}">Category</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(auth()->user()->can('order.list') ||
                            auth()->user()->can('sale.view'))
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group {{in_array($request->segment(1), ['invoices', 'orders']) ? 'active' : '' }}"
                                @click="activeDropdown === 'pos' ? activeDropdown = null : activeDropdown = 'pos'">
                                <div class="flex items-center">
                                <i class="fa fa-cubes"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Sales</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{'!rotate-90' : activeDropdown === 'pos'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'pos'" x-collapse
                                class="sub-menu text-gray-500">
                                @if(auth()->user()->can('sale.view'))
                                <li class="{{ $request->segment(1) == 'invoices' ? 'active' : '' }}">
                                    <a href="{{action('SaleController@index')}}">Invoice List</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('order.list'))
                                <li class="{{ $request->segment(1) == 'orders' ? 'active' : '' }}">
                                    <a href="{{action('SaleController@order')}}">Order List</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(auth()->user()->can('table.view') ||
                            auth()->user()->can('table.create') ||
                            auth()->user()->can('table-booking.view') ||
                            auth()->user()->can('category.create') ||
                            auth()->user()->can('floor.view') ||
                            auth()->user()->can('floor.create') ||
                            auth()->user()->can('tax.view') ||
                            auth()->user()->can('tax.create')
                        )
                        <li class="menu nav-item">
                            <button type="button" 
                                class="nav-link group {{ in_array($request->segment(2), ['table_booking', 'table','tax']) || $request->segment(1) == 'floors' ? 'active' : '' }}"
                                @click="activeDropdown === 'rest' ? activeDropdown = null : activeDropdown = 'rest'">
                                <div class="flex items-center">
                                    <i class="fa fa-cutlery"></i>
                                    <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                        Restaurant
                                    </span>
                                </div>
                                <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'rest'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>

                            <ul x-cloak x-show="activeDropdown === 'rest'" x-collapse class="sub-menu text-gray-500">

                                {{-- Table Booking --}}
                                @if(auth()->user()->can('table-booking.view') || auth()->user()->can('table-booking.create'))
                                <li class="{{ $request->segment(2) == 'table_booking' ? 'active' : '' }}">
                                    <a href="{{ action('Rest\TableBookingController@index') }}">Table Booking</a>
                                </li>
                                @endif

                                {{-- Table --}}
                                @if(auth()->user()->can('table.view') || auth()->user()->can('table.create'))
                                <li class="{{ $request->segment(2) == 'table' ? 'active' : '' }}">
                                    <a href="{{ action('Rest\TableController@index') }}">Table</a>
                                </li>
                                @endif

                                {{-- Table Location (from Backend section) --}}
                                @if(auth()->user()->can('floor.view') || auth()->user()->can('floor.create'))
                                <li class="{{ $request->segment(1) == 'floors' ? 'active' : '' }}">
                                    <a href="{{ action('FloorController@index') }}">Table Location</a>
                                </li>
                                @endif

                                {{-- Tax (from Backend section) --}}
                                @if(auth()->user()->can('tax.view') || auth()->user()->can('tax.create'))
                                <li class="{{ $request->segment(2) == 'tax' ? 'active' : '' }}">
                                    <a href="{{ action('Rest\TaxController@index') }}">Tax</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if(auth()->user()->can('sale-report.view') ||
                        auth()->user()->can('sale-detail-report.view') ||
                        auth()->user()->can('register-report.view') || 
                        auth()->user()->can('payment-detail-report.view') ||
                        auth()->user()->can('purchase-report.view') ||
                        auth()->user()->can('loaction-report.view') ||
                        auth()->user()->can('sale-cancel-report.view') ||
                        auth()->user()->can('sale-profit-report.view') ||
                        auth()->user()->can('stock-report.view') )
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group {{in_array($request->segment(2), ['sale-profit-report','sale-cancel-report','location-balance-report', 'sale-report', 'sale-detail-report', 'register-report', 'sale-payment-report','purchase-report','stock-report']) ? 'active' : '' }}"
                                @click="activeDropdown === 'report' ? activeDropdown = null : activeDropdown = 'report'">
                                <div class="flex items-center">
                                    <i class="fa fa-line-chart"></i>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Reports</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'report'}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'report'" x-collapse
                                class="sub-menu text-gray-500">
                                @if(auth()->user()->can('sale-report.view'))
                                <li class="{{ $request->segment(2) == 'sale-report' ? 'active' : '' }}"><a href="{{ action('SaleReportController@index') }}">Sales Report</a></li>
                                @endif
                                @if(auth()->user()->can('sale-detail-report.view'))
                                <li class="{{ $request->segment(2) == 'sale-detail-report' ? 'active' : '' }}"><a href="{{ action('SaleReportController@detailReport') }}">Sales Detail Report</a></li>
                                @endif
                                <!-- @if(auth()->user()->can('register-report.view'))
                                <li class="{{ $request->segment(2) == 'register-report' ? 'active' : '' }}"><a href="{{ action('RegisterController@index') }}">Register Report</a></li>
                                @endif -->
                                @if(auth()->user()->can('payment-detail-report.view'))
                                <li class="{{ $request->segment(2) == 'sale-payment-report' ? 'active' : '' }}"><a href="{{ action('SaleReportController@paymentDetailReport') }}">Payment Receive Report</a></li>
                                @endif
                                @if(auth()->user()->can('purchase-report.view'))
                                <li class="{{ $request->segment(2) == 'purchase-report' ? 'active' : '' }}"><a href="{{ action('RegisterController@purchaseReport') }}">Purchase Report</a></li>
                                @endif
                                <!-- @if(auth()->user()->can('loaction-report.view'))
                                <li class="{{ $request->segment(2) == 'location-balance-report' ? 'active' : '' }}"><a href="{{ action('RegisterController@locationReport') }}">Location Report</a></li>
                                @endif -->
                                @if(auth()->user()->can('sale-cancel-report.view'))
                                <li class="{{ $request->segment(2) == 'sale-cancel-report' ? 'active' : '' }}"><a href="{{ action('SaleReportController@saleCancelReport') }}">Sale Cancel Report</a></li>
                                @endif
                                @if(auth()->user()->can('sale-profit-report.view'))
                                <li class="{{ $request->segment(2) == 'sale-profit-report' ? 'active' : '' }}"><a href="{{ action('ProfitReportController@profitReport') }}">Sale Profit Report</a></li>
                                @endif
                                @if(auth()->user()->can('stock-report.view'))
                                    <li class="{{ $request->segment(2) == 'stock-report' ? 'active' : '' }}"><a href="{{ action('StockReportController@report') }}">Stock Report</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        
                        @if(auth()->user()->can('setting.update') ||
                        auth()->user()->can('business-location.index') ||
                        auth()->user()->can('business-location.create'))
                            <li class="menu nav-item">
                                <button type="button" class="nav-link group"
                                    :class="{'active' : activeDropdown === 'business'}"
                                    @click="activeDropdown === 'business' ? activeDropdown = null : activeDropdown = 'business'">
                                    <div class="flex items-center">
                                        <i class="fa fa-cogs"></i>
                                        <span
                                            class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Setting</span>
                                    </div>
                                    <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'business'}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </button>
                                <ul x-cloak x-show="activeDropdown === 'business'" x-collapse
                                    class="sub-menu text-gray-500">
                                    @if(auth()->user()->can('setting.update'))
                                        <li><a href="{{ action('BussinessController@index') }}">Business Setting</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!-- end sidebar section -->