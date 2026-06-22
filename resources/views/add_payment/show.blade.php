@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6 no-print no-print" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Payments</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            
            <div>
                @php 
                    $bussiness = App\Models\Business::first();
                @endphp
                <div>
                    <div style="padding: 15px;">
                        <div class="mb-5 grid grid-cols-1 gap-5">
                        @php 
                                    $bussiness = App\Models\Business::first();
                                @endphp
                                <div class="p-5">
                                    <div class="panel">
                                        <div class="flex flex-wrap justify-between gap-4 px-4">
                                            <div class="text-2xl font-semibold uppercase"><span>Payment Details</span></div>
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
                                                        <div><span>{{$transaction->lines_of_sell->sum('quantity')}}</span></div>
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
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive mt-6">
                                            <table class="table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="">Paid On</th>
                                                        <th class="">Method</th>
                                                        <th class="">Amount</th>
                                                        <th class="">Refence No</th>
                                                        <th class="">Note</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($payments as $payment)
                                                        <tr>
                                                        <td>{{ $payment->created_at }}</td>
                                                            <td>{{ $payment->method }}</td>
                                                            <td >{{ number_format($payment->amount, 2) }}</td>
                                                            <td>{{ $payment->payment_ref_no }}</td>
                                                            <td>{{ $payment->note }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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