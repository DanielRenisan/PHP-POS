<script src="{{asset('asset/js/perfect-scrollbar.min.js')}}"></script>
    <script defer src="{{asset('asset/js/popper.min.js')}}"></script>
    <script defer src="{{asset('asset/js/tippy-bundle.umd.min.js')}}"></script>

    <script src="{{asset('asset/js/alpine-collaspe.min.js')}}"></script>
    <script src="{{asset('asset/js/alpine-persist.min.js')}}"></script>
    <script defer src="{{asset('asset/js/alpine-ui.min.js')}}"></script>
    <script defer src="{{asset('asset/js/alpine-focus.min.js')}}"></script>
    <script defer src="{{asset('asset/js/alpine.min.js')}}"></script>
    <script src="{{asset('asset/js/custom.js')}}"></script>
    <script defer src="{{asset('asset/js/apexcharts.js')}}"></script>
    <script type="text/javascript">
    base_path = "{{url('/')}}";
</script>

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js?v=$asset_v"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js?v=$asset_v"></script>
<![endif]-->
<!-- jQuery 2.2.3 -->
<script src="{{ asset('AdminLTE/plugins/jQuery/jquery-2.2.3.min.js') }}" crossorigin="anonymous"></script>

<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('AdminLTE/plugins/iCheck/icheck.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('AdminLTE/plugins/select2/select2.full.min.js') }}"></script>
<!-- Add language file for select2 -->
<script src="{{ asset('AdminLTE/plugins/select2/lang/' . session()->get('user.language', config('app.locale') ) . '.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('AdminLTE/plugins/datepicker/bootstrap-datepicker.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('AdminLTE/plugins/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/DataTables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/DataTables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>

<!-- jQuery Validator -->
<script src="{{ asset('js/jquery-validation-1.16.0/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/jquery-validation-1.16.0/dist/additional-methods.min.js') }}"></script>
@php
    $validation_lang_file = 'messages_' . session()->get('user.language', config('app.locale') ) . '.js';
@endphp
@if(file_exists(public_path() . '/js/jquery-validation-1.16.0/src/localization/' . $validation_lang_file))
    <script src="{{ asset('js/jquery-validation-1.16.0/src/localization/' . $validation_lang_file . '') }}"></script>
@endif

<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<!-- Bootstrap file input -->
<script src="{{ asset('plugins/bootstrap-fileinput/fileinput.min.js') }}"></script>
<!--accounting js-->
<script src="{{ asset('plugins/accounting.min.js') }}"></script>

<script src="{{ asset('AdminLTE/plugins/daterangepicker/moment.min.js') }}"></script>

<script src="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>

<script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>

<script src="{{ asset('plugins/bootstrap-tour/bootstrap-tour.min.js') }}"></script>
<script src="{{ asset('plugins/chart/highchart/highcharts.js') }}"></script>
<script src="{{ asset('plugins/chart/highchart/data.js') }}"></script>
<script src="{{ asset('plugins/chart/highchart/map.js') }}"></script>
<script src="{{ asset('plugins/chart/highchart/offline-exporting.js') }}"></script>
<script src="{{ asset('plugins/chart/highchart/world.js') }}"></script>

<script src="{{ asset('plugins/printThis.js') }}"></script>

<script src="{{ asset('plugins/keyboard/jquery.keyboard.js') }}"></script>

<script src="{{ asset('plugins/screenfull.min.js') }}"></script>
<script src="{{asset('asset/js/dashboard.js')}}"></script>


@php
    $business_date_format = 'Y-m-d';
    $datepicker_date_format = str_replace('d', 'dd', $business_date_format);
    $datepicker_date_format = str_replace('m', 'mm', $datepicker_date_format);
    $datepicker_date_format = str_replace('Y', 'yyyy', $datepicker_date_format);

    $moment_date_format = str_replace('d', 'DD', $business_date_format);
    $moment_date_format = str_replace('m', 'MM', $moment_date_format);
    $moment_date_format = str_replace('Y', 'YYYY', $moment_date_format);

    $business_time_format = session('business.time_format');
    $moment_time_format = 'HH:mm';
    if($business_time_format == 12){
        $moment_time_format = 'hh:mm A';
    }

@endphp
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    var financial_year = {
    	start: moment('{{ date("Y")."-01-01" }}'),
    	end: moment('{{ date("Y")."-12-31" }}'),
    }
    //Default setting for select2
    $.fn.select2.defaults.set("language", "{{session()->get('user.language', config('app.locale'))}}");

    var datepicker_date_format = "{{$datepicker_date_format}}";
    var moment_date_format = "{{$moment_date_format}}";
    var moment_time_format = "{{$moment_time_format}}";
</script>

<!-- Scripts -->
<script src="{{ asset('js/AdminLTE-app.js') }}"></script>

@if(file_exists(public_path('js/lang/' . session()->get('user.language', config('app.locale')) . '.js')))
    <script src="{{ asset('js/lang/' . session()->get('user.language', config('app.locale') ) . '.js') }}"></script>
@else
    <script src="{{ asset('js/lang/en.js') }}"></script>
@endif

<script src="{{ asset('js/functions.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/help-tour.js') }}"></script>
<script src="{{ asset('plugins/calculator/calculator.js') }}"></script>

@yield('javascript')