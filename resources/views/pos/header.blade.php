<!-- start header section -->
<header :class="{'dark' : $store.app.semidark && $store.app.menu === 'horizontal'}">
    <div class="shadow-sm">
        @php 
            $bussiness = App\Models\Business::first();
        @endphp
        <div class="relative flex w-full items-center bg-white px-5 dark:bg-[#0e1726]" style="background-color: #97c585ff !important;">
            <div class="hidden ltr:mr-2 rtl:ml-2 sm:block header-icons">
                <ul class="flex items-center space-x-2 rtl:space-x-reverse dark:text-[#d0d2d6] p-0"
                    style="margin: 0;">
                    <a href="{{ route('home') }}" class="main-logo flex shrink-0 items-center"
                        style="border: none;">
                        <img class="inline w-8 ltr:-ml-1 rtl:-mr-1" src="{{ isset($bussiness) && isset($bussiness->logo) ? url( 'storage/business_logos/' . $bussiness->logo)  : asset('assets/images/logo.svg')}}" style="width:5rem;height:4rem"
                            alt="image" />
                        <!-- <span
                            class="hidden align-middle text-2xl font-semibold transition-all duration-300 ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light md:inline">{{ isset($bussiness) && isset($bussiness->name) ? $bussiness->name : 'Pearl Island' }}</span> -->
                    </a>
                    <!-- <a href="javascript:;"
                                        class="collapse-icon flex flex-none rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary ltr:ml-2 rtl:mr-2 dark:bg-dark/40 dark:text-[#d0d2d6] dark:hover:bg-dark/60 dark:hover:text-primary lg:hidden sidebar-icon"
                                        @click="toggleSidebar">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                        </svg>
                                    </a> -->
                    <li style="cursor: pointer;">
                        <a @click="locationPopup = true" class="block header-icon svg-icn-btn p-2"
                            title="Location">
                            <i style="font-size: 30px;" class="fas fa-map-marker-alt"></i>
                        </a>
                    </li>
                    <li style="cursor: pointer;">
                        <a @click="employeePopup = true" class="block header-icon svg-icn-btn"
                            title="Employee">
                            <i style="font-size: 30px;" class="far fa-user-circle"></i>
                        </a>
                    </li>
                    <li style="cursor: pointer;">
                        <a @click="entryPopup = true" class="block header-icon svg-icn-btn"
                            title="Customer">
                            <i style="font-size: 30px;" class="far fa-user"></i>
                        </a>
                    </li>
                    <!-- @if(in_array('Dine In', $order_types))
                    <li style="cursor: pointer;">
                        <a @click="dineOrderType()" class="block header-icon svg-icn-btn"
                            title="Dine In">
                            <i style="font-size: 30px;" class="fas fa-utensils"></i>
                        </a>
                    </li>
                    @endif -->
                    <!-- @if(in_array('Room Order', $order_types))
                    <li style="cursor: pointer;">
                        <a @click="roomOrderType()" class="block header-icon svg-icn-btn"
                            title="Room Order">
                            <i style="font-size: 30px;" class="fas fa-bed"></i>
                        </a>
                    </li>
                    @endif -->
                    @if(in_array('Take Away', $order_types))
                    <li style="cursor: pointer;" x-data="{ isActive }" x-init="$nextTick(() => toggleAway())">
                        <a @click="toggleAway" class="block header-icon svg-icn-btn take-away-btn" title="Take Away">
                            <i style="font-size: 30px;" class="fas fa-shopping-bag"></i>
                        </a>
                    </li>
                    @endif
                    <!-- <li style="cursor: pointer;">
                        <a @click="toggleOnline"  class="block header-icon svg-icn-btn take-online-btn"
                            title="Online Order">
                            <i style="font-size: 30px;" class="fas fa-shopping-cart"></i>
                        </a>
                    </li> -->
                    <li style="cursor: pointer;">
                        <a @click="orderStatusPopup = true" class="block header-icon svg-icn-btn"
                            title="Orders">
                            <i style="font-size: 30px;" class="fas fa-receipt"></i>
                        </a>
                    </li>
                    <!-- <li style="cursor: pointer;">
                        <a @click="paymentDetailsPopup = true" class="block header-icon svg-icn-btn"
                            title="Payment">
                            <i style="font-size: 30px;" class="fab fa-cc-visa"></i>
                        </a>
                    </li> -->
                </ul>
                <div x-show="anyPopupOpen"
                    :class="{ 'overlay-hidden': !anyPopupOpen, 'overlay-visible': anyPopupOpen }"
                    class="overlay">
                </div>

                <!-- Popups -->
                <!-- location Popup -->
                @include('pos.location_pop')

                <!-- Employees Popup -->
                @include('pos.employee_pop')

                <!-- Customer Popup -->
                @include('pos.customer_pop')

                <!-- dine in popup -->
                @include('pos.dine_pop')

                <!-- Room popup -->
                @include('pos.room_pop')

                <!-- KOT pop up -->
                @include('pos.kot_pop')

                <!-- Orders Status popup -->
                @include('pos.order_status_pop')
                <!-- Payemt Details popup -->
                @include('pos.payment_pop')
            </div>
            <div class="flex items-center space-x-1.5 ltr:ml-auto rtl:mr-auto rtl:space-x-reverse dark:text-[#d0d2d6] sm:flex-1 ltr:sm:ml-0 sm:rtl:mr-0 lg:space-x-2">
                <div class="sm:ltr:mr-auto sm:rtl:ml-auto" @click.outside="search = false">
                    <button type="button"
                        class="search_btn rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 dark:bg-dark/40 dark:hover:bg-dark/60 sm:hidden"
                        @click="search = ! search">
                        <svg class="mx-auto h-4.5 w-4.5 dark:text-[#d0d2d6]" width="30" height="30"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <!-- full screen Icon -->
                <div class="full-screen" id="fullscreenToggle" @click="toggleFullscreen">
                    <a href="javascript:;"
                        class="relative block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-arrows-fullscreen" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M5.828 10.172a.5.5 0 0 0-.707 0l-4.096 4.096V11.5a.5.5 0 0 0-1 0v3.975a.5.5 0 0 0 .5.5H4.5a.5.5 0 0 0 0-1H1.732l4.096-4.096a.5.5 0 0 0 0-.707zm4.344 0a.5.5 0 0 1 .707 0l4.096 4.096V11.5a.5.5 0 1 1 1 0v3.975a.5.5 0 0 1-.5.5H11.5a.5.5 0 0 1 0-1h2.768l-4.096-4.096a.5.5 0 0 1 0-.707zm0-4.344a.5.5 0 0 0 .707 0l4.096-4.096V4.5a.5.5 0 1 0 1 0V.525a.5.5 0 0 0-.5-.5H11.5a.5.5 0 0 0 0 1h2.768l-4.096 4.096a.5.5 0 0 0 0 .707zm-4.344 0a.5.5 0 0 1-.707 0L1.025 1.732V4.5a.5.5 0 0 1-1 0V.525a.5.5 0 0 1 .5-.5H4.5a.5.5 0 0 1 0 1H1.732l4.096 4.096a.5.5 0 0 1 0 .707z" />
                        </svg>
                    </a>
                </div>
                <!-- user Dropdown -->
                <div class="dropdown flex-shrink-0" x-data="dropdown" @click.outside="open = false">
                    <a href="javascript:;" class="group relative" @click="toggle()">
                        <span><img
                                class="h-9 w-9 rounded-full object-cover saturate-50 group-hover:saturate-100"
                                src="{{asset('img/pro.png')}}" alt="image" /></span>
                    </a>
                    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                        class="top-11 w-[230px] !py-0 font-semibold text-dark ltr:right-0 rtl:left-0 dark:text-white-dark dark:text-white-light/90">
                        <li>
                            <div class="flex items-center px-4 py-4">
                                <div class="flex-none">
                                    <img class="h-10 w-10 rounded-md object-cover"
                                        src="{{asset('img/pro.png')}}" alt="image" />
                                </div>
                                <div class="truncate ltr:pl-4 rtl:pr-4">
                                    <h4 class="text-base">
                                        {{auth()->user()->first_name ?? ''}}
                                    </h4>
                                    <a class="text-black/60 hover:text-primary dark:text-dark-light/60 dark:hover:text-white"
                                        href="javascript:;">{{auth()->user()->email ?? ''}}</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="dark:hover:text-white" @click="toggle">
                                <svg class="h-4.5 w-4.5 shrink-0 ltr:mr-2 rtl:ml-2" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                Profile</a>
                        </li>
                        @can('petty-cash.update')
                        <li>
                            
                            @php 
                            $count =  App\Models\CashRegister::where('user_id', auth()->user()->id)->where('status','open')
                                            ->count();
                            @endphp
                            @if($count == 0)
                                <a type="button" id="register_details" title="{{ __('cash_register.register_details') }}" data-toggle="tooltip" @click="openCash()" class=""
                                >
                                Open Petty Cash
                                </a>
                            @endif
                            @if($count > 0)
                                <a type="button" id="register_details" title="{{ __('cash_register.register_details') }}" data-toggle="tooltip" @click="closeCash()" class=""
                                >
                                Close Petty Cash
                                </a>
                            @endif
                        </li>
                        @endcan
                        <li class="border-t border-white-light dark:border-white-light/10">
                            <a href="{{route('logout')}}" class="!py-3 text-danger" @click="toggle">
                                <svg class="h-4.5 w-4.5 shrink-0 rotate-90 ltr:mr-2 rtl:ml-2" width="18"
                                    height="18" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5"
                                        d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Sign Out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('pos.openCash')
    @include('pos.closeCash')
</header>
<!-- end header section -->