@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div x-data="sales">
        <!-- Breadcrumb -->
        <ul class="flex space-x-2 rtl:space-x-reverse mb-6">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Summary</span>
            </li>
        </ul>

        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <!-- Total Sales Card -->
            <div class="panel bg-gradient-to-br from-blue-500 to-blue-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Total Sales</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$total_sales}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.74157 18.5545C4.94119 20 7.17389 20 11.6393 20H12.3605C16.8259 20 19.0586 20 20.2582 18.5545M3.74157 18.5545C2.54194 17.1091 2.9534 14.9146 3.77633 10.5257C4.36155 7.40452 4.65416 5.84393 5.76506 4.92196M3.74157 18.5545C3.74156 18.5545 3.74157 18.5545 3.74157 18.5545ZM20.2582 18.5545C21.4578 17.1091 21.0464 14.9146 20.2235 10.5257C19.6382 7.40452 19.3456 5.84393 18.2347 4.92196M20.2582 18.5545C20.2582 18.5545 20.2582 18.5545 20.2582 18.5545ZM18.2347 4.92196C17.1238 4 15.5361 4 12.3605 4H11.6393C8.46374 4 6.87596 4 5.76506 4.92196M18.2347 4.92196C18.2347 4.92196 18.2347 4.92196 18.2347 4.92196ZM5.76506 4.92196C5.76506 4.92196 5.76506 4.92196 5.76506 4.92196Z" stroke="blue" stroke-width="1.5"/>
                                <path opacity="0.5" d="M9.1709 8C9.58273 9.16519 10.694 10 12.0002 10C13.3064 10 14.4177 9.16519 14.8295 8" stroke="blue" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-11/12 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div>

            <!-- Total Purchase Card -->
            <div class="panel bg-gradient-to-br from-green-500 to-green-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Total Purchase</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$total_purchase}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z" stroke="green" stroke-width="1.5"/>
                                <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2" transform="rotate(-45 8.60699 8.87891)" stroke="grren" stroke-width="1.5"/>
                                <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="green" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-2/3 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div>

            <!-- Total Expenses Card -->
            <div class="panel bg-gradient-to-br from-orange-500 to-orange-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Total Expenses</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$total_expense}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-red/20 flex items-center justify-center backdrop-blur-sm">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z" stroke="orange" stroke-width="1.5"/>
                                <path opacity="0.5" d="M10 16H6" stroke="orange" stroke-width="1.5" stroke-linecap="round"/>
                                <path opacity="0.5" d="M14 16H12.5" stroke="orange" stroke-width="1.5" stroke-linecap="round"/>
                                <path opacity="0.5" d="M2 10L22 10" stroke="orange" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-4/5 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div>

            <!-- Customer Due Card -->
            <div class="panel bg-gradient-to-br from-red-500 to-red-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Customer Due</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$due}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                              <rect x="3" y="4.5" width="18" height="12" rx="2" ry="2"/>
                              <text x="12" y="13.5" text-anchor="middle" font-size="8" fill="currentColor" font-family="sans-serif">$</text>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-4/5 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div>
            <!-- Customer Due Card -->
            <!-- <div class="panel bg-gradient-to-br from-red-500 to-red-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Hotel Sales</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$hotel_sales}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                              <rect x="3" y="4.5" width="18" height="12" rx="2" ry="2"/>
                              <text x="12" y="13.5" text-anchor="middle" font-size="8" fill="currentColor" font-family="sans-serif">$</text>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-4/5 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div> -->
            <!-- Customer Due Card -->
            <!-- <div class="panel bg-gradient-to-br from-red-500 to-red-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Restaurant Sales</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$restaurant_sales}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                              <rect x="3" y="4.5" width="18" height="12" rx="2" ry="2"/>
                              <text x="12" y="13.5" text-anchor="middle" font-size="8" fill="currentColor" font-family="sans-serif">$</text>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-4/5 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <!-- Total Sales Card -->
            <div class="panel bg-gradient-to-br from-blue-500 to-blue-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Today Total Sales</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$today_total_sales}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.74157 18.5545C4.94119 20 7.17389 20 11.6393 20H12.3605C16.8259 20 19.0586 20 20.2582 18.5545M3.74157 18.5545C2.54194 17.1091 2.9534 14.9146 3.77633 10.5257C4.36155 7.40452 4.65416 5.84393 5.76506 4.92196M3.74157 18.5545C3.74156 18.5545 3.74157 18.5545 3.74157 18.5545ZM20.2582 18.5545C21.4578 17.1091 21.0464 14.9146 20.2235 10.5257C19.6382 7.40452 19.3456 5.84393 18.2347 4.92196M20.2582 18.5545C20.2582 18.5545 20.2582 18.5545 20.2582 18.5545ZM18.2347 4.92196C17.1238 4 15.5361 4 12.3605 4H11.6393C8.46374 4 6.87596 4 5.76506 4.92196M18.2347 4.92196C18.2347 4.92196 18.2347 4.92196 18.2347 4.92196ZM5.76506 4.92196C5.76506 4.92196 5.76506 4.92196 5.76506 4.92196Z" stroke="blue" stroke-width="1.5"/>
                                <path opacity="0.5" d="M9.1709 8C9.58273 9.16519 10.694 10 12.0002 10C13.3064 10 14.4177 9.16519 14.8295 8" stroke="blue" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-11/12 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div>
            <div class="panel bg-gradient-to-br from-green-500 to-green-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Today Purchase</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$today_total_purchase}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z" stroke="green" stroke-width="1.5"/>
                                <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2" transform="rotate(-45 8.60699 8.87891)" stroke="grren" stroke-width="1.5"/>
                                <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="green" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-2/3 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div>
            <div class="panel bg-gradient-to-br from-orange-500 to-orange-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Today Expenses</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$today_total_expense}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-red/20 flex items-center justify-center backdrop-blur-sm">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z" stroke="orange" stroke-width="1.5"/>
                                <path opacity="0.5" d="M10 16H6" stroke="orange" stroke-width="1.5" stroke-linecap="round"/>
                                <path opacity="0.5" d="M14 16H12.5" stroke="orange" stroke-width="1.5" stroke-linecap="round"/>
                                <path opacity="0.5" d="M2 10L22 10" stroke="orange" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-4/5 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div>
            <!-- <div class="panel bg-gradient-to-br from-red-500 to-red-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Today Customer Due</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$today_due}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                              <rect x="3" y="4.5" width="18" height="12" rx="2" ry="2"/>
                              <text x="12" y="13.5" text-anchor="middle" font-size="8" fill="currentColor" font-family="sans-serif">$</text>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-4/5 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div> -->
            <!-- <div class="panel bg-gradient-to-br from-red-500 to-red-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Today Hotel Sales</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$today_hotel_sales}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                              <rect x="3" y="4.5" width="18" height="12" rx="2" ry="2"/>
                              <text x="12" y="13.5" text-anchor="middle" font-size="8" fill="currentColor" font-family="sans-serif">$</text>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-4/5 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div> -->
            <!-- <div class="panel bg-gradient-to-br from-red-500 to-red-600 border-0 overflow-hidden">
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h6 class="text-white/80 text-sm font-medium mb-1">Today Restaurant Sales</h6>
                            <p class="text-lg font-bold">
                                <span class="display_currency" data-currency_symbol="true">{{$today_restaurant_sales}}</span>
                            </p>
                        </div>
                        <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                              <rect x="3" y="4.5" width="18" height="12" rx="2" ry="2"/>
                              <text x="12" y="13.5" text-anchor="middle" font-size="8" fill="currentColor" font-family="sans-serif">$</text>
                            </svg>
                        </div>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full w-4/5 rounded-full bg-white shadow-lg"></div>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
            <!-- Profit Chart - Takes 2/3 width -->
            <div class="panel xl:col-span-2">
                <div class="mb-5 flex items-center justify-between">
                    <h5 class="text-lg font-semibold dark:text-white">Revenue & Expenses Overview</h5>
                </div>
                <div class="relative overflow-hidden">
                    <div x-ref="revenueChart" class="rounded-lg bg-white dark:bg-black">
                        <div class="grid min-h-[325px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                            <span class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Sales Chart - Takes 1/3 width -->
            <div class="panel">
                <div class="mb-5">
                    <h5 class="text-lg font-semibold dark:text-white mb-1">Daily Sales</h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Weekly comparison overview</p>
                </div>
                <div class="overflow-hidden">
                    <div x-ref="dailySales" class="rounded-lg bg-white dark:bg-black">
                        <div class="grid min-h-[280px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                            <span class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Panels -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Transactions -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-semibold dark:text-white">Recent Transactions</h5>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Latest 10 transactions</p>
                    </div>
                </div>
                <div class="max-h-[400px] overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                    <template x-for="(item, itemIndex) in transactions" :key="itemIndex">
                        <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors cursor-pointer">
                            <div class="h-10 w-10 shrink-0 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                                <span x-text="item.type.substring(0,2).toUpperCase()"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm truncate dark:text-white" x-text="item.type"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="item.room"></p>
                                <p class="text-xs text-gray-400 dark:text-gray-500" x-text="item.date"></p>
                            </div>
                            <div>
                                <template x-if="item.status === 'paid'">
                                    <span class="badge bg-success text-xs px-2 py-1" x-text="item.status"></span>
                                </template>
                                <template x-if="item.status === 'partial'">
                                    <span class="badge bg-warning text-xs px-2 py-1" x-text="item.status"></span>
                                </template>
                                <template x-if="item.status === 'due'">
                                    <span class="badge bg-danger text-xs px-2 py-1" x-text="item.status"></span>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Stock Alert -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-semibold dark:text-white">Stock Alerts</h5>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Low inventory items</p>
                    </div>
                </div>
                <div class="max-h-[400px] overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                    <template x-for="(item, itemIndex) in stockAlerts" :key="itemIndex">
                        <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors cursor-pointer border-l-4 border-orange-500">
                            <div class="h-10 w-10 shrink-0 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 9V14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M12 17.0002V17.0102" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M7.86 2H16.14L22 7.86V16.14L16.14 22H7.86L2 16.14V7.86L7.86 2Z" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm truncate dark:text-white" x-text="item.name"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="item.sku_code"></p>
                                <p class="text-xs text-orange-600 dark:text-orange-400 font-medium">Qty: <span x-text="item.qty"></span></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Reminders -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-semibold dark:text-white">Reminders</h5>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Today's check-ins & outs</p>
                    </div>
                </div>
                <div class="max-h-[400px] overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                    <template x-for="(item, itemIndex) in reminders" :key="itemIndex">
                        <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors cursor-pointer border-l-4 border-green-500">
                            <div class="h-10 w-10 shrink-0 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 2V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M16 2V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M3 9H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M3 7C3 5.89543 3.89543 5 5 5H19C20.1046 5 21 5.89543 21 7V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V7Z" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm truncate dark:text-white" x-text="item.customer"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="item.ref_no"></p>
                                <p class="text-xs text-gray-400 dark:text-gray-500" x-text="item.date"></p>
                            </div>
                            <span class="badge bg-success text-xs px-2 py-1" x-text="item.status"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #4a5568;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #718096;
}

.badge {
    display: inline-block;
    font-weight: 600;
    border-radius: 0.375rem;
}
</style>
@endsection

@section('javascript')
<script>
document.addEventListener("alpine:init", () => {
    Alpine.data("sales", () => ({
        init() {
            isDark = this.$store.app.theme === "dark" || this.$store.app.isDarkMode ? true : false;
            isRtl = this.$store.app.rtlClass === "rtl" ? true : false;

            const revenueChart = null;
            const dailySales = null;

            setTimeout(() => {
                this.revenueChart = new ApexCharts(this.$refs.revenueChart, this.revenueChartOptions);
                this.$refs.revenueChart.innerHTML = "";
                this.revenueChart.render();

                this.dailySales = new ApexCharts(this.$refs.dailySales, this.dailySalesOptions);
                this.$refs.dailySales.innerHTML = "";
                this.dailySales.render();
            }, 300);

            this.$watch("$store.app.theme", () => {
                isDark = this.$store.app.theme === "dark" || this.$store.app.isDarkMode ? true : false;
                this.revenueChart.updateOptions(this.revenueChartOptions);
                this.dailySales.updateOptions(this.dailySalesOptions);
            });

            this.$watch("$store.app.rtlClass", () => {
                isRtl = this.$store.app.rtlClass === "rtl" ? true : false;
                this.revenueChart.updateOptions(this.revenueChartOptions);
            });
        },

        get revenueChartOptions() {
            return {
                series: [
                    {
                        name: "Sales",
                        data: <?php echo $sale_array; ?>,
                    },
                    {
                        name: "Expenses",
                        data: <?php echo $expense_array; ?>,
                    },
                ],
                chart: {
                    height: 325,
                    type: "area",
                    fontFamily: "Nunito, sans-serif",
                    zoom: { enabled: false },
                    toolbar: { show: false },
                },
                dataLabels: { enabled: false },
                stroke: {
                    show: true,
                    curve: "smooth",
                    width: 3,
                    lineCap: "round",
                },
                colors: ["#0041aaff", "#e63904ff"],
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 90, 100],
                    },
                },
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                xaxis: {
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            fontSize: "12px",
                            colors: isDark ? "#888ea8" : "#888ea8",
                        },
                    },
                },
                yaxis: {
                    opposite: isRtl ? true : false,
                    labels: {
                        style: {
                            colors: isDark ? "#888ea8" : "#888ea8",
                        },
                    },
                },
                grid: {
                    borderColor: isDark ? "#191e3a" : "#e0e6ed",
                    strokeDashArray: 5,
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: false } },
                    padding: { top: 0, right: 0, bottom: 0, left: 10 },
                },
                legend: {
                    position: "top",
                    horizontalAlign: "right",
                    fontSize: "14px",
                    markers: { width: 10, height: 10 },
                    itemMargin: { horizontal: 10 },
                },
                tooltip: {
                    theme: isDark ? "dark" : "light",
                },
            };
        },

        get dailySalesOptions() {
            return {
                series: [
                    {
                        name: "This Week",
                        data: <?php echo $this_week_count; ?>,
                    },
                    {
                        name: "Last Week",
                        data: <?php echo $last_week_count; ?>,
                    },
                ],
                chart: {
                    height: 280,
                    type: "bar",
                    fontFamily: "Nunito, sans-serif",
                    toolbar: { show: false },
                    stacked: true,
                    stackType: "100%",
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "28%",
                        borderRadius: 8,
                    },
                },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 1 },
                colors: ["#28db05ff", "#98bba1ff"],
                xaxis: {
                    categories: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                    labels: {
                        style: {
                            colors: isDark ? "#888ea8" : "#888ea8",
                            fontSize: "12px",
                        },
                    },
                },
                yaxis: { show: false },
                fill: { opacity: 1 },
                legend: {
                    position: "top",
                    horizontalAlign: "left",
                    fontSize: "12px",
                    markers: { width: 8, height: 8 },
                },
                grid: {
                    show: false,
                    padding: { top: 10, right: 0, bottom: 0, left: 0 },
                },
                tooltip: {
                    theme: isDark ? "dark" : "light",
                },
            };
        },

        reminders: <?php echo $reminders; ?>,
        transactions: <?php echo $transactions; ?>,
        stockAlerts: <?php echo $stocks; ?>,
    }));
});
</script>
@endsection