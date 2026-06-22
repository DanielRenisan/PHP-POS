@inject('request', 'Illuminate\Http\Request')

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl')) ? 'rtl' : 'ltr'}}">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- CSRF Token -->
    @php 
        $bussiness = App\Models\Business::first();
    @endphp
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($bussiness) && isset($bussiness->name) ? $bussiness->name : 'Restaurant Management' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


        @include('layouts.partials.css_new')
    <style>
    .hide {
        display: none !important;
    }   
    </style>
    </head>

    <body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased"
    :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ?  'dark' : '', $store.app.menu, $store.app.layout,$store.app.rtlClass]">
    <!-- sidebar menu overlay -->
    <div x-cloak class="fixed inset-0 z-50 bg-[black]/60 lg:hidden" :class="{'hidden' : !$store.app.sidebar}"
        @click="$store.app.toggleSidebar()"></div>
        <style>
        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        /* CSS to style the modal */
        .modal {
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #iconError.hidden {
            display: none;
        }

        .heading {
            background-color: #4361ee;
            color: white;
            overflow: hidden;
            padding: 15px 20px;
        }

        .heading h2 {
            font-size: 18px;
            font-weight: bold;
        }

        .pagination {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin: 20px 100px 0 0;

        }

        .pagination button {
            border: 1px solid lightblue;
            padding: 3px 10px;
            border-radius: 50%;
        }

        .pagination button:hover {
            background-color: #4361ee;
            border: 1px solid transparent;
            color: white;
        }

        .pagination button.active {
            background-color: #4361ee;
            border: 1px solid transparent;
            color: white;
        }

        .view-deatils h2 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 60%;
            margin: 0 auto;
            font-size: 18px;
            font-weight: 500;
        }
        @media print {
            .no-print{
                display: none !important;
            }
        }
    </style>
    <div class="main-container min-h-screen text-black dark:text-white-dark navbar-sticky" :class="[$store.app.navbar]">
            @include('layouts.partials.sidebar-rest')
            <div class="main-content flex min-h-screen flex-col">
                <!-- Add currency related field-->
                <input type="hidden" id="__code" value="LKR">
                <input type="hidden" id="__symbol" value="₨">
                <input type="hidden" id="__thousand" value=",">
                <input type="hidden" id="__decimal" value=".">
                <input type="hidden" id="__symbol_placement" value="before">
                @include('layouts.partials.header-new') 
                @yield('content')
                <!-- This will be printed -->
                <section class="invoice print_section" id="receipt_section">
                </section>
                @include('layouts.partials.footer_res')
            </div>
            

        </div>

        @include('layouts.partials.script')
        <script>
        document.addEventListener('alpine:init', () => {
        // main section
        Alpine.data('scrollToTop', () => ({
            showTopButton: false,
            init() {
                window.onscroll = () => {
                    this.scrollFunction();
                };
            },

            scrollFunction() {
                if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                    this.showTopButton = true;
                } else {
                    this.showTopButton = false;
                }
            },

            goToTop() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            },
        }));

        // theme customization
        Alpine.data('customizer', () => ({
            showCustomizer: false,
        }));

        // sidebar section
        Alpine.data('sidebar', () => ({
            init() {
                const selector = document.querySelector('.sidebar ul a[href="' + window.location.pathname + '"]');
                if (selector) {
                    selector.classList.add('active');
                    const ul = selector.closest('ul.sub-menu');
                    if (ul) {
                        let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.click();
                            });
                        }
                    }
                }
            },
        }));

        // header section
        Alpine.data('header', () => ({
            init() {
                const selector = document.querySelector('ul.horizontal-menu a[href="' + window.location.pathname + '"]');
                if (selector) {
                    selector.classList.add('active');
                    const ul = selector.closest('ul.sub-menu');
                    if (ul) {
                        let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.classList.add('active');
                            });
                        }
                    }
                }
            },
            cashPopUp :false,
            closePopUp :false,
            notifications: [
                {
                    id: 1,
                    profile: 'user-profile.jpeg',
                    message: '<strong class="text-sm mr-1">John Doe</strong>invite you to <strong>Prototyping</strong>',
                    time: '45 min ago',
                },
            ],

            messages: [
                {
                    id: 1,
                    image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-success-light dark:bg-success text-success dark:text-success-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></span>',
                    title: 'Congratulations!',
                    message: 'Your OS has been updated.',
                    time: '1hr',
                },
            ],

            languages: [
                {
                    id: 1,
                    key: 'Chinese',
                    value: 'zh',
                },
            ],

            removeNotification(value) {
                this.notifications = this.notifications.filter((d) => d.id !== value);
            },

            removeMessage(value) {
                this.messages = this.messages.filter((d) => d.id !== value);
            },

            openCash()
            {
                this.cashPopUp = true;
            },
            closeCash()
            {
                this.closePopUp = true;
                const url = "{{action('CashRegisterController@getRegisterDetails')}}";
                $.ajax({
                    method: "GET",
                    url: url,
                    dataType: "json",
                    success: function (result) {
                        const total = parseFloat(result.cash_in_hand) + parseFloat(result.total_cash_sale) - parseFloat(result.total_cash_purchase);
                        $('form#cash_register_form span#opening_balance').text(result.cash_in_hand);
                        $('form#cash_register_form span#cre_cash_payment').text(result.total_cash_sale);
                        $('form#cash_register_form span#deb_cash_payment').text(result.total_cash_purchase);
                        $('form#cash_register_form span#cre_cheque_payment').text(result.total_cheque_sale);
                        $('form#cash_register_form span#deb_cheque_payment').text(result.total_cheque_purchase);
                        $('form#cash_register_form span#cre_card_payment').text(result.total_card_sale);
                        $('form#cash_register_form span#deb_card_payment').text(result.total_card_purchase);
                        $('form#cash_register_form span#cre_credit_payment').text(result.total_credit_sale);
                        $('form#cash_register_form span#deb_credit_payment').text(result.total_credit_purchase);
                        $('form#cash_register_form span#cre_bank_transfer_payment').text(result.total_bank_transfer_sale);
                        $('form#cash_register_form span#deb_bank_transfer_payment').text(result.total_bank_transfer_purchase);
                        $('form#cash_register_form span#cre_other_payment').text(result.total_other_sale);
                        $('form#cash_register_form span#deb_other_payment').text(result.total_other_purchase);
                        $('form#cash_register_form span#total_sale').text(result.total_sale);
                        $('form#cash_register_form span#total_purchase').text(result.total_purchase);
                        $('form#cash_register_form input#closing_amount').val(total)
                        $('form#cash_register_form input#closing_grand_total').val(total)
                    }
                });
            }
        }));
    });
        </script>
    </body>

</html>