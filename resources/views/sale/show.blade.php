@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6 no-print no-print" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li><a class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"  href="{{action('SaleController@index')}}">
                <span>Invoice</span>
            </a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Invoice View</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            
            <div>
                @php 
                    $bussiness = App\Models\Business::first();
                @endphp
                <div>
                    <div style="padding: 15px;">
                    <div class="mb-6 flex flex-wrap items-center justify-center gap-4 lg:justify-end">
                            @can('sale.cancel')
                            @if($transaction->status != 'canceled')
                                <a href="{{action('OrderController@cancelInvoice', [$transaction->id])}}" class="btn btn-danger gap-2">
                                <i class="fa fa-times"></i>
                                    Cancel
                                </a>
                            @endif
                            @endcan
                            <button type="button" class="btn btn-primary gap-2 print-invoice" data-href="{{action('SaleController@printInvoice', [$transaction->id])}}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                    <path d="M6 17.9827C4.44655 17.9359 3.51998 17.7626 2.87868 17.1213C2 16.2426 2 14.8284 2 12C2 9.17157 2 7.75736 2.87868 6.87868C3.75736 6 5.17157 6 8 6H16C18.8284 6 20.2426 6 21.1213 6.87868C22 7.75736 22 9.17157 22 12C22 14.8284 22 16.2426 21.1213 17.1213C20.48 17.7626 19.5535 17.9359 18 17.9827" stroke="currentColor" stroke-width="1.5"></path>
                                    <path opacity="0.5" d="M9 10H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                    <path d="M19 14L5 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                    <path d="M18 14V16C18 18.8284 18 20.2426 17.1213 21.1213C16.2426 22 14.8284 22 12 22C9.17157 22 7.75736 22 6.87868 21.1213C6 20.2426 6 18.8284 6 16V14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                    <path opacity="0.5" d="M17.9827 6C17.9359 4.44655 17.7626 3.51998 17.1213 2.87868C16.2427 2 14.8284 2 12 2C9.17158 2 7.75737 2 6.87869 2.87868C6.23739 3.51998 6.06414 4.44655 6.01733 6" stroke="currentColor" stroke-width="1.5"></path>
                                    <circle opacity="0.5" cx="17" cy="10" r="1" fill="currentColor"></circle>
                                    <path opacity="0.5" d="M15 16.5H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                    <path opacity="0.5" d="M13 19H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                </svg>
                                Print
                            </button>

                            
                        </div>
                        <div class="mb-5 grid grid-cols-1 gap-5">
                        @php 
                                    $bussiness = App\Models\Business::first();
                                @endphp
                                <div class="p-5">
                                    <div class="panel">
                                        <div class="flex flex-wrap justify-between gap-4 px-4">
                                            <div class="text-2xl font-semibold uppercase"><span>Invoice</span></div>
                                            <div class="shrink-0">
                                                <img src="{{ isset($bussiness) && isset($bussiness->logo) ? url( 'storage/business_logos/' . $bussiness->logo)  : asset('img/logo.png')}}" alt="image" class="w-14 ltr:ml-auto rtl:mr-auto">
                                            </div>
                                        </div>
                                        <div class="px-4 ltr:text-right rtl:text-left">
                                            <div class="mt-6 space-y-1 text-white-dark">
                                            <p>
                                                    @if(isset($bussiness))
                                                    @if(isset($bussiness->address))
                                                    {{$bussiness->address}}
                                                    @endif
                                                    @if(isset($bussiness->address_two)) , {{$bussiness->address_two}} <br>@endif
                                                    @if(isset($bussiness->city))
                                                    {{$bussiness->city}}<br>
                                                    @endif
                                                    @if(isset($bussiness->country))
                                                    {{$bussiness->country}}<br>
                                                    @endif
                                                    @if(isset($bussiness->mobile))
                                                    {{$bussiness->mobile}}
                                                    @endif
                                                    @if(isset($bussiness->phone))
                                                    ,{{$bussiness->phone}}
                                                    @endif
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <hr class="my-6 border-[#e0e6ed] dark:border-[#1b2e4b]">
                                        <div class="flex flex-col flex-wrap justify-between gap-6 lg:flex-row">
                                            <div class="flex-1">
                                                <div class="space-y-1 text-white-dark">
                                                    <div>Issue For:</div>
                                                    <div class="font-semibold text-black dark:text-white"><span>{{ $transaction->customer }}</span></div>
                                                    <div><span>{{$transaction->address_one}}</span></div>
                                                    <div><span>{{$transaction->address_two}}</span></div>
                                                    <div><span>{{$transaction->email}}</span></div>
                                                    <div><span>{{$transaction->mobile_no}}</span></div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col justify-between gap-6 sm:flex-row lg:w-2/3">
                                                <div class="xl:1/3 sm:w-1/2 lg:w-2/5">
                                                    <div class="mb-2 flex w-full items-center justify-between">
                                                        <div class="text-white-dark">Invoice No :</div>
                                                        <div><span>{{$transaction->invoice_no}}</span></div>
                                                    </div>
                                                    <div class="mb-2 flex w-full items-center justify-between">
                                                        <div class="text-white-dark">Order type :</div>
                                                        <div><span>{{$order_type}}</span></div>
                                                    </div>
                                                    <div class="mb-2 flex w-full items-center justify-between">
                                                        <div class="text-white-dark">Issue Date :</div>
                                                        <div><span>{{$transaction->updated_at}}</span></div>
                                                    </div>
                                                    <div class="mb-2 flex w-full items-center justify-between">
                                                        <div class="text-white-dark">Total Qty :</div>
                                                        <div><span>{{collect($sell_lines)->sum('qty')}}</span></div>
                                                    </div>
                                                    <div class="flex w-full items-center justify-between">
                                                        <div class="text-white-dark">Status :</div>
                                                        <div>
                                                        @if($transaction->status == 'final')
                                                            <span class="badge bg-success shadow-md dark:group-hover:bg-transparent">{{ $transaction->status }}</span>
                                                        @else
                                                            <span class="badge bg-danger shadow-md dark:group-hover:bg-transparent">{{ $transaction->status }}</span>
                                                        @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="xl:1/3 sm:w-1/2 lg:w-2/5">
                                                    <div class="mb-2 flex w-full items-center justify-between">
                                                        <div class="text-white-dark">Total Amount :</div>
                                                        <div><span>{{$total > 0 ? number_format($total, 2) : '0.00'}}</span></div>
                                                    </div>
                                                    <div class="mb-2 flex w-full items-center justify-between">
                                                        <div class="text-white-dark">Paid Amount :</div>
                                                        <div><span>{{$paid_amount > 0 ? number_format($paid_amount, 2) : '0.00'}}</span></div>
                                                    </div>
                                                    <div class="mb-2 flex w-full items-center justify-between">
                                                        <div class="text-white-dark">Remaining Amount :</div>
                                                        <div><span>{{$due_amount > 0 ? number_format($due_amount, 2) : '0.00'}}</span></div>
                                                    </div>
                                                    <div class="flex w-full items-center justify-between">
                                                        <div class="text-white-dark">Payment Status :</div>
                                                        <div>
                                                        @if($transaction->payment_status == 'paid')
                                                            <span class="badge bg-success shadow-md dark:group-hover:bg-transparent">{{ $transaction->payment_status }}</span>
                                                        @elseif($transaction->payment_status == 'patial')
                                                            <span class="badge bg--primary shadow-md dark:group-hover:bg-transparent">{{ $transaction->payment_status }}</span>
                                                        @else
                                                            <span class="badge bg-danger shadow-md dark:group-hover:bg-transparent">{{ $transaction->payment_status }}</span>
                                                        @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive mt-6">
                                            <table class="table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="">S.NO</th>
                                                        <th class="">Product</th>
                                                        <th class="">QTY</th>
                                                        <th class="ltr:text-right rtl:text-left">PRICE</th>
                                                        <th class="ltr:text-right rtl:text-left">Discount</th>
                                                        <th class="ltr:text-right rtl:text-left">AMOUNT</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sell_lines as $line)
                                                        <tr>
                                                            <td >{{ $line['sr'] }}</td>
                                                            <td>{{ $line['description'] }}</td>
                                                            <td>{{ $line['qty'] }}</td>
                                                            <td class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{ $line['unit_cost'] }}</td>
                                                            <td class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{ $line['dis'] }}</td>
                                                            <td class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{ $line['line_total'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-6 grid grid-cols-1 px-4 sm:grid-cols-2">
                                            <div>
                                            <table class="table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="">Method</th>
                                                        <th class="">Amount</th>
                                                        <th class="">Refence No</th>
                                                        <th class="">Note</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($payments as $payment)
                                                        <tr>
                                                            <td>{{ $payment->method }}</td>
                                                            <td >{{ number_format($payment->amount, 2) }}</td>
                                                            <td>{{ $payment->payment_ref_no }}</td>
                                                            <td>{{ $payment->note }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <br>
                                            <table class="table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="">Sell Note</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <tr>
                                                            <td>{!! $transaction->details !!}</td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                            @php
                                                $tax = $transaction->tax_amount > 0 ? ($transaction->tax_amount /100) * $transaction->lines_of_sell->sum('sub_total') : 0;
                                            @endphp

                                            </div>
                                            <div class="space-y-2 ltr:text-right rtl:text-left">
                                                <div class="flex items-center">
                                                    <div class="flex-1">Subtotal</div>
                                                    <div class="w-[37%]"><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{$total > 0 ? number_format($total, 2) : '0.00'}}</span></div>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="flex-1">Tax</div>
                                                    <div class="w-[37%]"><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{$tax}}</span></div>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="flex-1">Discount</div>
                                                    <div class="w-[37%]"><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{$transaction->discount_amount}}</span></div>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="flex-1">Sevice Charge</div>
                                                    <div class="w-[37%]"><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{$transaction->service_charge}}</span></div>
                                                </div>
                                                <div class="flex items-center text-lg font-semibold">
                                                    <div class="flex-1">Grand Total</div>
                                                    <div class="w-[37%]" ><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{$total > 0 ? number_format($total, 2) : '0.00'}}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
$(document).on('click', 'button.print-invoice', function (e) {
		e.preventDefault();
		var href = $(this).data('href');

		$.ajax({
			method: "GET",
			url: href,
			dataType: "json",
			success: function (result) {

				if (result.html_content != '') {
					$('#receipt_section').html(result.html_content);
					setTimeout(function () { window.print(); }, 1000);
				} else {
					toastr.error(result.msg);
				}
			}
		});
	});
    </script>
@endsection