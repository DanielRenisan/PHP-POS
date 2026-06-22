<!-- start header section -->
<header :class="{'dark' : $store.app.semidark && $store.app.menu === 'horizontal'}">
    <div class="shadow-sm">
        @php 
            $bussiness = App\Models\Business::first();
        @endphp
        <div class="relative flex w-full items-center bg-white px-5 dark:bg-[#0e1726]">
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
                </ul>
                
                
            </div>
            <div class="flex items-center space-x-1.5 ltr:ml-auto rtl:mr-auto rtl:space-x-reverse dark:text-[#d0d2d6] sm:flex-1 ltr:sm:ml-0 sm:rtl:mr-0 lg:space-x-2">
                <div class="sm:ltr:mr-auto sm:rtl:ml-auto" @click.outside="search = false">
                    
                </div>
            </div>
        </div>
    </div>
</header>
<!-- end header section -->