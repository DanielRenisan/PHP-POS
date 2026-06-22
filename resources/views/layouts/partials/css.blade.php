<link rel="preconnect" href="https://fonts.googleapis.com/" />
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/perfect-scrollbar.min.css?v='.$asset_v) }}" />
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/style.css?v='.$asset_v) }}" />
        <link defer rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/animate.css?v='.$asset_v) }}" />
        <script src="{{ asset('assets/js/perfect-scrollbar.min.js?v='.$asset_v) }}"></script>
        <script defer src="{{ asset('assets/js/popper.min.js?v='.$asset_v) }}"></script>
        <script defer src="{{ asset('assets/js/tippy-bundle.umd.min.js?v='.$asset_v) }}"></script>
        <link rel="stylesheet" type="text/css" media="screen"  href="{{ asset('assets/css/flatpickr.min.css?v='.$asset_v) }}">
        
        <style scoped>
            /* range picker */
            input[type='range'] {
                -webkit-appearance: none;
            }

            input[type='range']::-webkit-slider-runnable-track {
                width: 100%;
                height: 8px;
                background: #dee2e6;
                border: none;
                border-radius: 3px;
            }

            input[type='range']::-webkit-slider-thumb {
                -webkit-appearance: none;
                border: none;
                height: 16px;
                width: 16px;
                border-radius: 50%;
                background: #4361ee;
                margin-top: -4px;
            }

            .dark input[type='range']::-webkit-slider-runnable-track {
                background: #1b2e4b;
            }

            .dark input[type='range'] {
                background-color: transparent;
            }

            input[type='range']:focus {
                outline: none;
            }

            input[type='range']:active::-webkit-slider-thumb {
                background: #4361eec2;
                cursor: pointer;
            }
        </style>
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css?v='.$asset_v) }}">

<!-- Styles -->
<link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.min.css?v='.$asset_v) }}">
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css?v='.$asset_v) }}">

@if( in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl')) )
	<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.rtl.min.css?v='.$asset_v) }}">
@endif

<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('plugins/ionicons/css/ionicons.min.css?v='.$asset_v) }}">
 <!-- Select2 -->
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/select2/select2.min.css?v='.$asset_v) }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('AdminLTE/css/AdminLTE.min.css?v='.$asset_v) }}">
<!-- iCheck -->
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/iCheck/square/blue.css?v='.$asset_v) }}">

<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datepicker/bootstrap-datepicker.min.css?v='.$asset_v) }}">

<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/DataTables/datatables.min.css?v='.$asset_v) }}">

<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css?v='.$asset_v) }}">
<!-- Bootstrap file input -->
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-fileinput/fileinput.min.css?v='.$asset_v) }}">

<!-- AdminLTE Skins.-->
<link rel="stylesheet" href="{{ asset('AdminLTE/css/skins/_all-skins.min.css?v='.$asset_v) }}">

@if( in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl')) )
	<link rel="stylesheet" href="{{ asset('AdminLTE/css/AdminLTE.rtl.min.css?v='.$asset_v) }}">
@endif

<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.css?v='.$asset_v) }}">
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-tour/bootstrap-tour.min.css?v='.$asset_v) }}">
<link rel="stylesheet" href="{{ asset('plugins/calculator/calculator.css?v='.$asset_v) }}">
<link rel="stylesheet" href="{{ asset('plugins/keyboard/keyboard.css?v='.$asset_v) }}">
<script src="{{ asset('assets/js/flatpickr.js?v='.$asset_v) }}"></script>
@yield('css')
<!-- app css -->
<link rel="stylesheet" href="{{ asset('css/app.css?v='.$asset_v) }}">

@if(isset($pos_layout) && $pos_layout)
	<style type="text/css">
		.content{
			padding-bottom: 0px !important;
		}
	</style>
@endif