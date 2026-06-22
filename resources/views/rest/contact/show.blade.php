@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6 no-print no-print" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li>
                <a href="{{action('Rest\ContactController@index')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">Customer</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Customer Dashboard</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
        <div class="animate__animated p-6" :class="[$store.app.animation]">
                <!-- start main content section -->
                <div x-data="cusProfile">
                    <div style="padding: 15px;">
                        <div class="mb-5 grid grid-cols-1 gap-5 lg:grid-cols-3 xl:grid-cols-4">
                            <div class="panel">
                                <div class="mb-5 flex items-center justify-between">
                                    <h5 class="text-lg font-semibold dark:text-white-light">Profile</h5>
                                    <!-- <a href="users-account-settings.html"
                                        class="btn btn-primary rounded-full p-2 ltr:ml-auto rtl:mr-auto">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                            <path opacity="0.5" d="M4 22H20" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path
                                                d="M14.6296 2.92142L13.8881 3.66293L7.07106 10.4799C6.60933 10.9416 6.37846 11.1725 6.17992 11.4271C5.94571 11.7273 5.74491 12.0522 5.58107 12.396C5.44219 12.6874 5.33894 12.9972 5.13245 13.6167L4.25745 16.2417L4.04356 16.8833C3.94194 17.1882 4.02128 17.5243 4.2485 17.7515C4.47573 17.9787 4.81182 18.0581 5.11667 17.9564L5.75834 17.7426L8.38334 16.8675L8.3834 16.8675C9.00284 16.6611 9.31256 16.5578 9.60398 16.4189C9.94775 16.2551 10.2727 16.0543 10.5729 15.8201C10.8275 15.6215 11.0583 15.3907 11.5201 14.929L11.5201 14.9289L18.3371 8.11195L19.0786 7.37044C20.3071 6.14188 20.3071 4.14999 19.0786 2.92142C17.85 1.69286 15.8581 1.69286 14.6296 2.92142Z"
                                                stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5"
                                                d="M13.8879 3.66406C13.8879 3.66406 13.9806 5.23976 15.3709 6.63008C16.7613 8.0204 18.337 8.11308 18.337 8.11308M5.75821 17.7437L4.25732 16.2428"
                                                stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </a> -->
                                </div>
                                <div class="mb-5">
                                    <div class="flex flex-col items-center justify-center">
                                        <img src="{{$image_url}}" alt="image"
                                            class="mb-5 h-24 w-24 rounded-full object-cover" />
                                        <p class="text-xl font-semibold text-primary">{{$customer->first_name ?? ''}} {{$customer->last_name ?? ''}}</p>
                                    </div>
                                    <ul
                                        class="m-auto mt-5 flex max-w-[160px] flex-col space-y-4 font-semibold text-white-dark">
                                        <li class="flex items-center gap-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0">
                                                <path
                                                    d="M2.3153 12.6978C2.26536 12.2706 2.2404 12.057 2.2509 11.8809C2.30599 10.9577 2.98677 10.1928 3.89725 10.0309C4.07094 10 4.286 10 4.71612 10H15.2838C15.7139 10 15.929 10 16.1027 10.0309C17.0132 10.1928 17.694 10.9577 17.749 11.8809C17.7595 12.057 17.7346 12.2706 17.6846 12.6978L17.284 16.1258C17.1031 17.6729 16.2764 19.0714 15.0081 19.9757C14.0736 20.6419 12.9546 21 11.8069 21H8.19303C7.04537 21 5.9263 20.6419 4.99182 19.9757C3.72352 19.0714 2.89681 17.6729 2.71598 16.1258L2.3153 12.6978Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <path opacity="0.5"
                                                    d="M17 17H19C20.6569 17 22 15.6569 22 14C22 12.3431 20.6569 11 19 11H17.5"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <path opacity="0.5"
                                                    d="M10.0002 2C9.44787 2.55228 9.44787 3.44772 10.0002 4C10.5524 4.55228 10.5524 5.44772 10.0002 6"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M4.99994 7.5L5.11605 7.38388C5.62322 6.87671 5.68028 6.0738 5.24994 5.5C4.81959 4.9262 4.87665 4.12329 5.38382 3.61612L5.49994 3.5"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M14.4999 7.5L14.6161 7.38388C15.1232 6.87671 15.1803 6.0738 14.7499 5.5C14.3196 4.9262 14.3767 4.12329 14.8838 3.61612L14.9999 3.5"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            {{$customer->professional ?? ''}}
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0">
                                                <path
                                                    d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V12Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <path opacity="0.5" d="M7 4V2.5" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" />
                                                <path opacity="0.5" d="M17 4V2.5" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" />
                                                <path opacity="0.5" d="M2 9H22" stroke="currentColor" stroke-width="1.5"
                                                    stroke-linecap="round" />
                                            </svg>
                                            {{$customer->customer_grp_name ?? ''}}
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0">
                                                <path opacity="0.5"
                                                    d="M4 10.1433C4 5.64588 7.58172 2 12 2C16.4183 2 20 5.64588 20 10.1433C20 14.6055 17.4467 19.8124 13.4629 21.6744C12.5343 22.1085 11.4657 22.1085 10.5371 21.6744C6.55332 19.8124 4 14.6055 4 10.1433Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <circle cx="12" cy="10" r="3" stroke="currentColor"
                                                    stroke-width="1.5" />
                                            </svg>
                                            {{$customer->address_one ?? ''}}, {{$customer->country ?? ''}}
                                        </li>
                                        <li>
                                            <a href="javascript:;" class="flex items-center gap-2">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0">
                                                    <path opacity="0.5"
                                                        d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                                        stroke="currentColor" stroke-width="1.5" />
                                                    <path
                                                        d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908L18 8"
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                </svg>
                                                <span class="truncate text-primary">{{$customer->email ?? ''}}</span></a>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                                <path
                                                    d="M16.1007 13.359L16.5562 12.9062C17.1858 12.2801 18.1672 12.1515 18.9728 12.5894L20.8833 13.628C22.1102 14.2949 22.3806 15.9295 21.4217 16.883L20.0011 18.2954C19.6399 18.6546 19.1917 18.9171 18.6763 18.9651M4.00289 5.74561C3.96765 5.12559 4.25823 4.56668 4.69185 4.13552L6.26145 2.57483C7.13596 1.70529 8.61028 1.83992 9.37326 2.85908L10.6342 4.54348C11.2507 5.36691 11.1841 6.49484 10.4775 7.19738L10.1907 7.48257"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <path opacity="0.5"
                                                    d="M18.6763 18.9651C17.0469 19.117 13.0622 18.9492 8.8154 14.7266C4.81076 10.7447 4.09308 7.33182 4.00293 5.74561"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <path opacity="0.5"
                                                    d="M16.1007 13.3589C16.1007 13.3589 15.0181 14.4353 12.0631 11.4971C9.10807 8.55886 10.1907 7.48242 10.1907 7.48242"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                            <span class="whitespace-nowrap" dir="ltr">+94 {{$customer->mobile_no ?? ''}}, {{$customer->telephone_no ?? ''}}</span>
                                        </li>
                                    </ul>
                                    <!-- <ul class="mt-7 flex items-center justify-center gap-2">
                                        <li>
                                            <a class="btn btn-info flex h-10 w-10 items-center justify-center rounded-full p-0"
                                                href="javascript:;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="h-5 w-5">
                                                    <path
                                                        d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="btn btn-danger flex h-10 w-10 items-center justify-center rounded-full p-0"
                                                href="javascript:;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                                    <path
                                                        d="M3.33946 16.9997C6.10089 21.7826 12.2168 23.4214 16.9997 20.66C18.9493 19.5344 20.3765 17.8514 21.1962 15.9286C22.3875 13.1341 22.2958 9.83304 20.66 6.99972C19.0242 4.1664 16.2112 2.43642 13.1955 2.07088C11.1204 1.81935 8.94932 2.21386 6.99972 3.33946C2.21679 6.10089 0.578039 12.2168 3.33946 16.9997Z"
                                                        stroke="currentColor" stroke-width="1.5" />
                                                    <path opacity="0.5"
                                                        d="M16.9497 20.5732C16.9497 20.5732 16.0107 13.9821 14.0004 10.5001C11.99 7.01803 7.05018 3.42681 7.05018 3.42681M7.57711 20.8175C9.05874 16.3477 16.4525 11.3931 21.8635 12.5801M16.4139 3.20898C14.926 7.63004 7.67424 12.5123 2.28857 11.4516"
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="btn btn-dark flex h-10 w-10 items-center justify-center rounded-full p-0"
                                                href="javascript:;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="h-5 w-5">
                                                    <path
                                                        d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22">
                                                    </path>
                                                </svg>
                                            </a>
                                        </li>
                                    </ul> -->
                                </div>
                            </div>

                            <!-- summary -->
                            <div class="panel lg:col-span-2 xl:col-span-3 ">
                                <div class="mb-5 grid grid-cols-2">
                                    <h5 class="text-lg font-semibold dark:text-white-light">Summary</h5>
                                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                        <!-- <input style="width: 35%;" class="form-input" type="date" x-model="startDate"
                                            @input="handleDateRangeChange" />
                                        <input style="width: 35%;" class="form-input" type="date" x-model="endDate"
                                            @input="handleDateRangeChange" /> -->
                                    </div>
                                </div>
                                <!-- search  -->
                                <div class="m-0" @click.outside="search = false"
                                    style="width: 100%; margin-bottom: 15px;">
                                    <form style="margin: 0 !important;"
                                        class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0">
                                        <div class="relative">
                                            <input type="text"
                                                class="peer border-colored form-input bg-gray-100 placeholder:tracking-widest ltr:pl-9 ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                                                placeholder="Search Summary" x-model="summarySearchTerm"
                                                @input="handleStockSearchInput" />
                                            <button type="button"
                                                class="absolute inset-0 h-9 w-9 appearance-none peer-focus:text-primary ltr:right-auto rtl:left-auto btn-hover">
                                                <svg class="mx-auto" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor"
                                                        stroke-width="1.5" opacity="0.5" />
                                                    <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <template x-for="item in filteredSummary">
                                    <div class="space-y-4">
                                        <div class="rounded border border-[#ebedf2] dark:border-0 dark:bg-[#1b2e4b]">
                                            <div class="flex items-center justify-between p-4 py-2">
                                                <div class="grid h-9 w-9 place-content-center rounded-md bg-secondary-light text-secondary dark:bg-secondary dark:text-secondary-light"
                                                    x-html="item.icon">
                                                </div>
                                                <div
                                                    class="flex flex-auto items-start justify-between font-semibold ltr:ml-4 rtl:mr-4">
                                                    <h6 class="text-[13px] text-white-dark dark:text-white-dark">
                                                        <span x-text="item.name"></span>
                                                        <span
                                                            class="block text-base text-[#515365] dark:text-white-light"
                                                            x-text="item.price"></span>
                                                    </h6>
                                                    <p class="text-secondary ltr:ml-auto rtl:mr-auto"
                                                        x-text="item.percentage"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-1">
                            <!-- task panel  -->
                            <div class="panel">
                                <div class="mb-5 grid grid-cols-2">
                                    <h5 class="text-lg font-semibold dark:text-white-light">Sales</h5>
                                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                        <input style="width: 40%;" class="form-input" type="date" x-model="taskStartDate" @input="handleTaskDateRangeChange" />
                                        <input style="width: 40%;" class="form-input" type="date" x-model="taskEndDate" @input="handleTaskDateRangeChange" />
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 20px;">
                                    <!-- search  -->
                                    <div class="m-0" @click.outside="search = false" style="width: 100%;">
                                        <form style="margin: 0 !important;"
                                            class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0">
                                            <div class="relative">
                                                <input type="text"
                                                    class="peer border-colored form-input bg-gray-100 placeholder:tracking-widest ltr:pl-9 ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                                                    placeholder="Search Task" x-model="taskSearchTerm"
                                                    @input="handleStockSearchInput" />
                                                <button type="button"
                                                    class="absolute inset-0 h-9 w-9 appearance-none peer-focus:text-primary ltr:right-auto rtl:left-auto btn-hover">
                                                    <svg class="mx-auto" width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor"
                                                            stroke-width="1.5" opacity="0.5" />
                                                        <path d="M18.5 18.5L22 22" stroke="currentColor"
                                                            stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="mb-5">
                                    <div class="table-responsive font-semibold text-[#515365] dark:text-white-light">
                                        <table class="whitespace-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Invoice No</th>
                                                    <th>Order Type</th>
                                                    <th>Payment Status</th>
                                                    <th class="text-right">Total Amount</th>
                                                    <th class="text-right">Paid Amount</th>
                                                    <th class="text-right">Due Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody class="dark:text-white-dark">
                                                <template x-for="(task, taskIndex) in paginatedFilteredItems" :key="taskIndex">
                                                    <tr>
                                                        <td><span x-text="task.date"></span></td>
                                                        <td><span x-text="task.invoice_no"></span></td>
                                                        <td><span x-text="task.order_type"></span></td>
                                                        <td><span x-text="task.payment_status"></span></td>
                                                        <td class="text-right"><span  class="display_currency" data-currency_symbol="true"  x-text="task.final_total"></span>
                                                        </td>
                                                        <td class="text-right"><span  class="display_currency" data-currency_symbol="true"  x-text="task.total_paid"></span>
                                                        </td>
                                                        <td class="text-right"><span  class="display_currency" data-currency_symbol="true"  x-text="task.due"></span></td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                        <div class="pagination">
                                            <button style="margin-right: 20px;" @click="previousPage()"
                                                :disabled="currentPage === 1">
                                                <i class="fas fa-chevron-left"></i>
                                            </button>
                                            <template x-for="page in totalPages" :key="page">
                                                <button style="margin-right: 5px;" @click="changePage(page)"
                                                    :class="{ 'active': currentPage === page }"><span x-text="page"></span></button>
                                            </template>
                                            <button style="margin-left: 20px;" @click="nextPage()"
                                                :disabled="currentPage === totalPages">
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Assets panel  -->
                            <!-- <div class="panel">
                                <div class="mb-5 grid grid-cols-2">
                                    <h5 class="text-lg font-semibold dark:text-white-light">Assets</h5>
                                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                        <input style="width: 40%;" class="form-input" type="date" x-model="assetStartDate" @input="handleAssetDateRangeChange" />
                                        <input style="width: 40%;" class="form-input" type="date" x-model="assetEndDate" @input="handleAssetDateRangeChange" />
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 20px;">
                                    <div class="m-0" @click.outside="search = false" style="width: 100%;">
                                        <form style="margin: 0 !important;"
                                            class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0">
                                            <div class="relative">
                                                <input type="text"
                                                    class="peer border-colored form-input bg-gray-100 placeholder:tracking-widest ltr:pl-9 ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                                                    placeholder="Search Asset" x-model="assetSearchTerm"
                                                    @input="handleStockSearchInput" />
                                                <button type="button"
                                                    class="absolute inset-0 h-9 w-9 appearance-none peer-focus:text-primary ltr:right-auto rtl:left-auto btn-hover">
                                                    <svg class="mx-auto" width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor"
                                                            stroke-width="1.5" opacity="0.5" />
                                                        <path d="M18.5 18.5L22 22" stroke="currentColor"
                                                            stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="mb-5">
                                    <div class="table-responsive font-semibold text-[#515365] dark:text-white-light">
                                        <table class="whitespace-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Sales IncomeList</th>
                                                    <th>Sales ExpensiveList</th>
                                                    <th>Sales Category</th>
                                                    <th>Sales Description</th>
                                                    <th class="text-center">Ammount</th>
                                                </tr>
                                            </thead>
                                            <tbody class="dark:text-white-dark">
                                                <template x-for="(task, taskIndex) in filteredAssets" :key="taskIndex">
                                                    <tr>
                                                        <td><span x-text="task.income"></span></td>
                                                        <td><span x-text="task.expensive"></span></td>
                                                        <td class="text-center"><span x-text="task.category"></span>
                                                        </td>
                                                        <td class="text-center"><span x-text="task.description"></span>
                                                        </td>
                                                        <td class="text-center"><span x-text="task.ammount"></span></td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> -->

                        </div>
                    </div>
                </div>
                <!-- end main content section -->

            </div>
        </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
 document.addEventListener("alpine:init", () => {
    Alpine.data("cusProfile", () => ({
      summary: <?php echo $summary_data; ?>,
  
      tasks: <?php echo $all_sales; ?>,
  
      assets: [
        {
          income: "Income List",
          expensive: "Expensive List",
          description: "Description",
          category: "Category",
          ammount: 18000,
          date: "2024-01-15",
        },
        {
          income: "Income List2",
          expensive: "Expensive List2",
          description: "Description2",
          category: "Category2",
          ammount: 12000,
          date: "2024-01-25",
        },
        {
          income: "Income List3",
          expensive: "Expensive List3",
          description: "Description3",
          category: "Category3",
          ammount: 20000,
          date: "2024-01-3",
        },
      ],
      pageSize: 5, // Number of items per page
      currentPage: 1, // Current page number
      startDate: "",
      endDate: "",
      summarySearchTerm: "",
      get filteredSummary() {
        return this.summary.filter((item) => {
          const searchSummary = new RegExp(this.summarySearchTerm, "i");
          const isWithinDateRange = this.isWithinDateRange(item.date);
          return (
            (searchSummary.test(item.name) ||
              searchSummary.test(item.price) ||
              searchSummary.test(item.percentage)) &&
            isWithinDateRange
          );
        });
      },
  
      handleDateRangeChange() {
        this.filteredSummary;
      },
      isWithinDateRange(itemDate) {
        // Check if the item's date is within the selected date range
        if (!this.startDate || !this.endDate) {
          // If either start or end date is not selected, consider it within the range
          return true;
        }
        const startDate = new Date(this.startDate);
        const endDate = new Date(this.endDate);
        const itemDateTime = new Date(itemDate).getTime();
  
        return itemDateTime >= startDate && itemDateTime <= endDate;
      },
  
      taskSearchTerm: "",
      taskStartDate: "",
      taskEndDate: "",
      get filteredTasks() {
        return this.tasks.filter((task) => {
          const searchTask = new RegExp(this.taskSearchTerm, "i");
          const isWithinDateRange = this.isTaskWithinDateRange(task.date);
          return (
            (searchTask.test(task.invoice_no) ||
              searchTask.test(task.order_type) ||
              searchTask.test(task.final_total.toString())) &&
            isWithinDateRange
          );
        });
      },
  
      handleTaskDateRangeChange() {
        this.filteredTasks;
      },
  
      isTaskWithinDateRange(taskDate) {
        if (!this.taskStartDate || !this.taskEndDate) {
          return true;
        }
        const startDate = new Date(this.taskStartDate);
        const endDate = new Date(this.taskEndDate);
        const taskDateTime = new Date(taskDate).getTime();
  
        return taskDateTime >= startDate && taskDateTime <= endDate;
      },
  
      assetSearchTerm: "",
      assetStartDate: "",
      assetEndDate: "",
      get filteredAssets() {
        return this.assets.filter((asset) => {
          const searchAsset = new RegExp(this.assetSearchTerm, "i");
          const isWithinDateRange = this.isAssetWithinDateRange(asset.date);
          return (
            (searchAsset.test(asset.income) ||
              searchAsset.test(asset.expensive) ||
              searchAsset.test(asset.category) ||
              searchAsset.test(asset.description) ||
              searchAsset.test(asset.ammount.toString())) &&
            isWithinDateRange
          );
        });
      },
  
      handleAssetDateRangeChange() {
        this.filteredAssets;
      },
  
      isAssetWithinDateRange(assetDate) {
        if (!this.assetStartDate || !this.assetEndDate) {
          return true;
        }
        const startDate = new Date(this.assetStartDate);
        const endDate = new Date(this.assetEndDate);
        const assetDateTime = new Date(assetDate).getTime();
  
        return assetDateTime >= startDate && assetDateTime <= endDate;
      },

      get paginatedFilteredItems() {
        const filtered = this.filteredTasks;
        return filtered.slice(this.startIndex, this.endIndex);
    },

    get totalPages() {
        return Math.ceil(this.tasks.length / this.pageSize);
    },

    get startIndex() {
        return (this.currentPage - 1) * this.pageSize;
    },

    get endIndex() {
        return this.currentPage * this.pageSize;
    },

    get paginatedItems() {
        return this.tasks.slice(this.startIndex, this.endIndex);
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
    }));
  });
  
  // Simulated API response (replace this with actual API call)
  const apiResponse = {
    // skinColor: '#87CEEB',
  };
  
  // Access the root element
  const root = document.documentElement;
  
  // Update custom properties with API-provided colors or defaults
  root.style.setProperty(
    "--primary-border-color",
    apiResponse.skinColor ||
      getComputedStyle(document.documentElement).getPropertyValue(
        "--primary-border-color"
      )
  );
  root.style.setProperty(
    "--primary-icon-color",
    apiResponse.skinColor ||
      getComputedStyle(document.documentElement).getPropertyValue(
        "--primary-icon-color"
      )
  );
  
  const fullscreenToggle = document.getElementById("fullscreenToggle");
  function toggleFullscreen() {
    if (!document.fullscreenElement) {
      if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
      } else if (document.documentElement.mozRequestFullScreen) {
        /* Firefox */
        document.documentElement.mozRequestFullScreen();
      } else if (document.documentElement.webkitRequestFullscreen) {
        /* Chrome, Safari and Opera */
        document.documentElement.webkitRequestFullscreen();
      } else if (document.documentElement.msRequestFullscreen) {
        /* IE/Edge */
        document.documentElement.msRequestFullscreen();
      }
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.mozCancelFullScreen) {
        /* Firefox */
        document.mozCancelFullScreen();
      } else if (document.webkitExitFullscreen) {
        /* Chrome, Safari and Opera */
        document.webkitExitFullscreen();
      } else if (document.msExitFullscreen) {
        /* IE/Edge */
        document.msExitFullscreen();
      }
    }
  }
  
//   fullscreenToggle.addEventListener("click", toggleFullscreen);
  
</script>
@endsection