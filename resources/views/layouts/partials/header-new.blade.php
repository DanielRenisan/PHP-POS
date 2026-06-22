<header class="z-40 no-print" :class="{'dark' : $store.app.semidark && $store.app.menu === 'horizontal'}">
    <div class="shadow-sm">
        @php 
            $bussiness = App\Models\Business::first();
        @endphp
        <div class="relative flex w-full items-center px-5 py-2.5 dark:bg-[#0e1726]" style="background-color: #a9d8f3ff !important;">
            <div class="horizontal-logo flex items-center justify-between ltr:mr-2 rtl:ml-2 lg:hidden">
                <a href="{{route('home')}}" class="main-logo flex shrink-0 items-center">
                    <img class="inline w-8 ltr:-ml-1 rtl:-mr-1" src="{{ isset($bussiness) && isset($bussiness->logo) ? url( 'storage/business_logos/' . $bussiness->logo)  : asset('assets/images/logo.svg')}}" alt="image" />
                    <!-- <span
                        class="hidden align-middle text-2xl font-semibold transition-all duration-300 ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light md:inline">{{ isset($bussiness) && isset($bussiness->name) ? $bussiness->name : 'Pearl Island' }}</span> -->
                </a>
                <a href="javascript:;"
                    class="collapse-icon flex flex-none rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary ltr:ml-2 rtl:mr-2 dark:bg-dark/40 dark:text-[#d0d2d6] dark:hover:bg-dark/60 dark:hover:text-primary lg:hidden"
                    @click="$store.app.toggleSidebar()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" />
                        <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" />
                        <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" />
                    </svg>
                </a>
            </div>
            <div x-data="header"
                class="flex items-center space-x-1.5 ltr:ml-auto rtl:mr-auto rtl:space-x-reverse dark:text-[#d0d2d6] sm:flex-1 ltr:sm:ml-0 sm:rtl:mr-0 lg:space-x-2">
                <div class="sm:ltr:mr-auto sm:rtl:ml-auto" x-data="{ search: false }"
                    @click.outside="search = false">
                   
                    <button type="button"
                        class="search_btn rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 dark:bg-dark/40 dark:hover:bg-dark/60 sm:hidden"
                        @click="search = ! search">
                        <svg class="mx-auto h-4.5 w-4.5 dark:text-[#d0d2d6]" width="20" height="20"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                @can('pos.dashboard')
                <div class="pos-link">
                    <a href="{{ action('POSController@index') }}"
                        class="block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 text-center font-bold">
                        POS
                    </a>
                </div>
                @endcan
                @can('petty-cash.update')
                @php 
                $count =  App\Models\CashRegister::where('user_id', auth()->user()->id)->where('status','open')
                                ->count();
                @endphp
                @if($count == 0)
                <div class="pos-link">
                    <button type="button" id="register_details" title="Open Balance"
                        @click="openCash()"
                        class="block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 btn-modal">
                        <strong><i class="fa fa-money fa-lg" aria-hidden="true"></i></strong>
                    </button>
                </div>
                @endif
                @if($count > 0)
                <div class="pos-link">
                    <button type="button" id="register_details" title="" data-toggle="tooltip" @click="closeCash()" class="block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 btn-modal"
                    >
                        <strong><i class="fa fa-window-close fa-lg" aria-hidden="true"></i></strong>
                    </button>
                </div>
                @endif
                @endcan
                <div class="dropdown" x-data="dropdown" @click.outside="open = false">
                    <!-- <a href="javascript:;"
                        class="relative block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60"
                        @click="toggle">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.0001 9.7041V9C19.0001 5.13401 15.8661 2 12.0001 2C8.13407 2 5.00006 5.13401 5.00006 9V9.7041C5.00006 10.5491 4.74995 11.3752 4.28123 12.0783L3.13263 13.8012C2.08349 15.3749 2.88442 17.5139 4.70913 18.0116C9.48258 19.3134 14.5175 19.3134 19.291 18.0116C21.1157 17.5139 21.9166 15.3749 20.8675 13.8012L19.7189 12.0783C19.2502 11.3752 19.0001 10.5491 19.0001 9.7041Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M12 6V10" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>

                        <span class="absolute top-0 flex h-3 w-3 ltr:right-0 rtl:left-0">
                            <span
                                class="absolute -top-[3px] inline-flex h-full w-full animate-ping rounded-full bg-success/50 opacity-75 ltr:-left-[3px] rtl:-right-[3px]"></span>
                            <span
                                class="relative inline-flex h-[6px] w-[6px] rounded-full bg-success"></span>
                        </span>
                    </a> -->
                    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                        class="top-11 w-[300px] divide-y !py-0 text-dark ltr:-right-2 rtl:-left-2 dark:divide-white/10 dark:text-white-dark sm:w-[350px]">
                        <li>
                            <div
                                class="flex items-center justify-between px-4 py-2 font-semibold hover:!bg-transparent">
                                <h4 class="text-lg">Notification</h4>
                                <template x-if="notifications.length">
                                    <span class="badge bg-primary/80"
                                        x-text="notifications.length + 'New'"></span>
                                </template>
                            </div>
                        </li>
                        <template x-for="notification in notifications">
                            <li class="dark:text-white-light/90">
                                <div class="group flex items-center px-4 py-2" @click.self="toggle">
                                    <div class="grid place-content-center rounded">
                                        <div class="relative h-12 w-12">
                                            <img class="h-12 w-12 rounded-full object-cover"
                                                :src="`assets/images/${notification.profile}`"
                                                alt="image" />
                                            <span
                                                class="absolute bottom-0 right-[6px] block h-2 w-2 rounded-full bg-success"></span>
                                        </div>
                                    </div>
                                    <div class="flex flex-auto ltr:pl-3 rtl:pr-3">
                                        <div class="ltr:pr-3 rtl:pl-3">
                                            <h6 x-html="notification.message"></h6>
                                            <span class="block text-xs font-normal dark:text-gray-500"
                                                x-text="notification.time"></span>
                                        </div>
                                        <button type="button"
                                            class="text-neutral-300 opacity-0 hover:text-danger group-hover:opacity-100 ltr:ml-auto rtl:mr-auto"
                                            @click="removeNotification(notification.id)">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <circle opacity="0.5" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5"
                                                    stroke="currentColor" stroke-width="1.5"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </template>
                        <template x-if="notifications.length">
                            <li>
                                <div class="p-4">
                                    <button class="btn btn-primary btn-small block w-full"
                                        @click="toggle">Read All Notifications</button>
                                </div>
                            </li>
                        </template>
                        <template x-if="!notifications.length">
                            <li>
                                <div
                                    class="!grid min-h-[200px] place-content-center text-lg hover:!bg-transparent">
                                    <div
                                        class="mx-auto mb-4 rounded-full text-primary ring-4 ring-primary/30">
                                        <svg width="40" height="40" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                d="M20 10C20 4.47715 15.5228 0 10 0C4.47715 0 0 4.47715 0 10C0 15.5228 4.47715 20 10 20C15.5228 20 20 15.5228 20 10Z"
                                                fill="currentColor" />
                                            <path
                                                d="M10 4.25C10.4142 4.25 10.75 4.58579 10.75 5V11C10.75 11.4142 10.4142 11.75 10 11.75C9.58579 11.75 9.25 11.4142 9.25 11V5C9.25 4.58579 9.58579 4.25 10 4.25Z"
                                                fill="currentColor" />
                                            <path
                                                d="M10 15C10.5523 15 11 14.5523 11 14C11 13.4477 10.5523 13 10 13C9.44772 13 9 13.4477 9 14C9 14.5523 9.44772 15 10 15Z"
                                                fill="currentColor" />
                                        </svg>
                                    </div>
                                    No data available.
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
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
                            <a href="{{action('Rest\EmployeeController@view', auth()->user()->staff_id ?? '')}}" class="dark:hover:text-white" @click="toggle">
                                <svg class="h-4.5 w-4.5 shrink-0 ltr:mr-2 rtl:ml-2" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                Profile</a>
                        </li>
                        <li>
                            <a href="{{action('ChangePasswordController@index')}}" class="dark:hover:text-white" @click="toggle">
                                <svg class="h-4.5 w-4.5 shrink-0 ltr:mr-2 rtl:ml-2" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                Change Password</a>
                        </li>
                        <li class="border-t border-white-light dark:border-white-light/10">
                            <a href="{{ route('logout') }}" class="!py-3 text-danger" @click="toggle">
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
                <div x-show="cashPopUp" class="mb-5">
                    <!-- modal -->
                    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
                        <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                            <div x-transition x-transition.duration.300
                                class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg"  style="border-radius:.5rem;border-width: 0;">
                                <div class="heading">
                                    <h2 class="m-0">Open Balance</h2>
                                </div>
                                <div class="p-5">

                                    <form id="attent_edit_form" class="needs-validation" method="POST"
                                        action="{{ action('CashRegisterController@store') }}">
                                        @csrf
                                        <input type="hidden" name="id" id="type_id">
                                        <div class="grid grid-cols-1 gap-4 pt-5">
                                            <div>
                                                <label for="name">Amount</label>
                                                <input id="amount" type="number" class="form-input" name="amount" required />
                                            </div>
                                            
                                        </div>
                                        <div class=" flex justify-end items-center mt-3">
                                            <button type="button" class="btn btn-outline-danger discard-btn"
                                                @click="cashPopUp = false">Discard</button>
                                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4 discard-btn">Update</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="closePopUp" class="mb-5">
                @php 
                $cash =  App\Models\CashRegister::where('user_id', auth()->user()->id)->where('status','open')
                                ->first();
                @endphp
                    <!-- modal -->
                    @if(isset($cash))
                    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
                        <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                            <div x-transition x-transition.duration.300
                                class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg"  style="border-radius:.5rem;border-width: 0;max-width:60%;">
                                <div class="heading">
                                    <h2 class="m-0">Current Register ( {{ \Carbon::createFromFormat('Y-m-d H:i:s', $cash->created_at)->format('jS M, Y h:i A') }} - {{ \Carbon::now()->format('jS M, Y h:i A') }})</h2>
                                </div>
                                <div class="p-5">

                                    <form id="cash_register_form" class="needs-validation" method="POST"
                                        action="{{ action('CashRegisterController@postCloseRegister') }}">
                                        @csrf
                                        <div class="grid grid-cols-1 gap-4 pt-5">
                                            <div class="col-sm-12">
                                            <table class="table">
                                                <tr>
                                                    <th></th>
                                                    <th style="text-align:center;">Credit</th>
                                                    <th style="text-align:center;">Debit</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Opening Balance:
                                                    </td>
                                                    <td  style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="opening_balance"></span>
                                                    </td>
                                                    <td style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true">0.00</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Cash Payment
                                                    </th>
                                                    <td  style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="cre_cash_payment"></span>
                                                    </td>
                                                    <td style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="deb_cash_payment"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Cheque Payment:
                                                    </th>
                                                    <td  style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="cre_cheque_payment"></span>
                                                    </td>
                                                    <td style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="deb_cheque_payment"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Credit Payment
                                                    </th>
                                                    <td  style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="cre_credit_payment"></span>
                                                    </td>
                                                    <td style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="deb_credit_payment"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Card Payment
                                                    </th>
                                                    <td  style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="cre_card_payment"></span>
                                                    </td>
                                                    <td style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="deb_card_payment"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Card Payment
                                                    </th>
                                                    <td  style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="cre_bank_transfer_payment"></span>
                                                    </td>
                                                    <td style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="deb_bank_transfer_payment"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Other Payment
                                                    </th>
                                                    <td  style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="cre_other_payment"></span>
                                                    </td>
                                                    <td style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="deb_other_payment"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>Total Sale</strong>
                                                    </th>
                                                    <td  style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true" id="total_sale"></span>
                                                    </td>
                                                    <td style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true">0.00</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <strong>Total Purchase</strong>
                                                    </th>
                                                    <td  style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true">0.00</span>
                                                    </td>
                                                    <td style="text-align:right;">
                                                        <span class="display_currency" data-currency_symbol="true"  id="total_purchase">0.00</span>
                                                    </td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-4 gap-4 pt-5">
                                            <div>
                                            <div class="form-group">
                                                {!! Form::label('closing_amount', __( 'Total Cash' ) . ':*') !!}
                                                {!! Form::text('closing_amount', null, ['class' => 'form-input input_number closing-amount', 'required', 'placeholder' => __( 'Total Cash' ) ]); !!}
                                            </div>
                                            </div>
                                            <div>
                                            <div class="form-group">
                                                {!! Form::label('total_card_slips', __( 'Total Card Slips' ) . ':*') !!}
                                                {!! Form::number('total_card_slips', null, ['class' => 'form-input', 'required', 'placeholder' => __( 'Total Card Slips' ), 'min' => 0 ]); !!}
                                            </div>
                                            </div> 
                                            <div>
                                            <div class="form-group">
                                                {!! Form::label('total_cheques', __( 'Total cheques' ) . ':*') !!}
                                                {!! Form::number('total_cheques', null, ['class' => 'form-input', 'required', 'placeholder' => __( 'Total cheques' ), 'min' => 0 ]); !!}
                                            </div>
                                            </div>
                                            <div>
                                            <div class="form-group">
                                                {!! Form::label('grand_total', 'Grand Total:') !!}
                                                {!! Form::text('grand_total', null, ['class' => 'form-input input_number', 'id' => 'closing_grand_total' ]); !!}
                                            </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 gap-4 pt-5">
                                            <div>
                                                {!! Form::label('closing_note', __( 'Closing Note:' ) . ':') !!}
                                                {!! Form::textarea('closing_note', null, ['class' => 'form-input', 'placeholder' => __( 'Closing Note' ), 'rows' => 3 ]); !!}
                                            </div>
                                        </div>  
                                        <div class=" flex justify-end items-center mt-3">
                                            <button type="button" class="btn btn-outline-danger discard-btn"
                                                @click="closePopUp = false">Discard</button>
                                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4 discard-btn">Close Register</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>