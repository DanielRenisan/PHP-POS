@extends('layouts.app_rest')

@section('content')
<style>
    table {
        border-collapse: collapse;
        width: 70%;
    }

    td,
    th {
        border: none;
        padding: 8px;
        font-weight: bold;
    }

    .titles {
        width: 800px;
    }

    .bordercell{
        border-bottom: 2px solid black;            
    }

    .bordercell2{
        border-bottom:1px dashed #000;
        border-top: 2px solid black;        
    }

    .secondCol{
        float: right;
        width: 150px;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.dataTables.css">
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Location Balance Report</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div>
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5">
                        <div class="flex" style="width:25%;position: absolute;margin-left: 15px;" x-data="form">
                            <div class="bg-[#f1f2f3] dark:bg-[#1b2e4b] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c]">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            </div>
                            <input type="hidden" id="min">
                            <input type="hidden" id="max">
                            <input id="range-calendar" x-model="date3" class="form-input" />
                        </div>
                        <div  class="flex" style="width:25%;position: absolute;margin-left: 330px;">
                            <select name="location" class="form-input"id="select-department">
                                <option value="">All Locations</option>
                                @foreach($departments as $department)
                                <option value="{{$department->id}}" {{request()->get('location') == $department->id ? 'selected' : ''}}>{{$department->name}}</option>
                                @endforeach
                            </select>    
                        </div>
                        <div style="justify-content: center; align-items: center;">
                            <table>
                                <tr>
                                    <td class="titles">(+)</td>
                                </tr>
                                <tr>
                                    <td>Total Cash Sales</td>
                                    <td class="secondCol"><span class="display_currency final_total" data-currency_symbol="true">{{$output['total_sale']}}</span></td>
                                </tr>
                                <tr>
                                    <td>Total Cash Return</td>
                                    <td class="secondCol"><span class="display_currency final_total" data-currency_symbol="true">{{$output['sale_return']}}</span></td>
                                </tr>
                                <tr>
                                    <td>Net Cash Sales</td>
                                    <td class="secondCol"><span class="display_currency final_total" data-currency_symbol="true">{{$output['net_sale']}}</span></td>
                                </tr>
                                <tr>
                                    <td>Cash Retrived from Credit Customers</td>
                                    <td class="secondCol"><span class="display_currency final_total" data-currency_symbol="true">{{$output['credit_return']}}</span></td>
                                </tr>
                                <tr>
                                    <td>Total Cash Received</td>
                                    <td class="bordercell secondCol"><span class="display_currency final_total" data-currency_symbol="true">{{$output['total_cash']}}</span></td>
                                </tr>
                                <tr >
                                    <td >(-)</td>
                                </tr>
                                <tr>
                                    <td>Total Cash Expenses</td>
                                    <td class="secondCol"><span class="display_currency final_total" data-currency_symbol="true">{{$output['expense']}}</span></td>
                                </tr>
                                <tr>
                                    <td>Supplier Payment By Cash</td>
                                    <td class="secondCol"><span class="display_currency final_total" data-currency_symbol="true">{{$output['purchase']}}</span></td>
                                </tr>
                                <tr>
                                    <td style="color: skyblue;"><strong>Cash Drawer Ending Balance</strong></td>
                                    <td class="secondCol bordercell2"><span class="display_currency final_total" data-currency_symbol="true">{{$output['balance']}}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<link rel="stylesheet" href="{{asset('asset/css/flatpickr.min.css')}}">
<script src="{{asset('asset/js/flatpickr.js')}}"></script>
<script type="text/javascript">
    var fromDate  = "{{request()->get('start')}}";
    if(fromDate === '')
    {
        fromDate  = new Date().toJSON().slice(0, 10)
    }

    var toDate  = "{{request()->get('end')}}";
    if(toDate === '')
    {
        toDate  = new Date().toJSON().slice(0, 10)
    }
   
    document.addEventListener("alpine:init", () => {
        Alpine.data("form", () => ({
            date3: fromDate +' to '+ toDate,
            init() {
                flatpickr(document.getElementById('range-calendar'), {
                    defaultDate: this.date3,
                    dateFormat: 'Y-m-d',
                    mode: 'range'
                })
            }
        }));
    });
    $(document).ready(function () {
        $(document).on('change', '#select-department', function(){
            const emp = $('#select-department').val();
            const dateRange = $('#range-calendar').val();
            var daaa = dateRange.split(" to ");
            const start = daaa[0];
            const end = daaa[1];
            const report_url = "{{action('RegisterController@locationReport', ['start' => 'STA','end' => 'ED','location' => 'LOC'])}}".replace('STA', start).replace('LOC', emp).replace('&amp;','&');
            window.location.href = report_url.toString().replace('ED', end).replace('&amp;','&');
        });
        $(document).on('change', '#range-calendar', function(){
            const dateRange = $(this).val();
            var daaa = dateRange.split(" to ");
            const start = daaa[0];
            const end = daaa[1];
            const emp = $('#select-department').val();
            const report_url = "{{action('RegisterController@locationReport', ['start' => 'STA','end' => 'ED','location' => 'LOC'])}}"
            .replace('STA', start).replace('ED', end).replace('&amp;','&');
            window.location.href = report_url.replace('LOC', emp).replace('&amp;','&');
        });
    });
</script> 
@endsection