@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6 no-print no-print" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"><a  href="{{action('PurchaseReturnController@index')}}">
                <span>Purchase Return</span></a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>View</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            
            <div>
                <div>
                    <div style="padding: 15px;">
                        <div class="mb-5 grid grid-cols-1 gap-5">
                        @php 
                                $bussiness = App\Models\Business::first();
                            @endphp
                            <div class="mb-6 flex flex-wrap items-center justify-center gap-4 lg:justify-end" style="padding-right:15px;">
                                <a href="{{action('PurchaseReturnController@index')}}" class="btn btn-dark">BACK</a>
                            </div>
                        </div>
                            <div class="p-5">
                                <div class="panel">
                                    <div class="flex flex-wrap justify-between gap-4 px-4">
                                        <div class="text-2xl font-semibold uppercase"><span>{{ $transaction->ref_no}}</span></div>
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
                                                <div>Supplier:</div>
                                                <div class="font-semibold text-black dark:text-white"><span>{{$transaction->supplier->first_name}}</span></div>
                                                <div><span>{{$transaction->supplier->address_one}}</span></div>
                                                <div><span>{{$transaction->supplier->email}}</span></div>
                                                <div><span>{{$transaction->supplier->mobile_no}} {{$transaction->supplier->telephone_no ? '/'. $transaction->supplier->telephone_no : ''}}</span></div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col justify-between gap-6 sm:flex-row lg:w-2/3">
                                            <div class="xl:1/3 sm:w-1/2 lg:w-2/5">
                                                <div class="mb-2 flex w-full items-center justify-between">
                                                    <div class="text-white-dark">Ref No :</div>
                                                    <div><span>{{ $transaction->ref_no}}</span></div>
                                                </div>
                                                <div class="mb-2 flex w-full items-center justify-between">
                                                    <div class="text-white-dark">Issue At :</div>
                                                    <div><span>{{ $transaction->created_at}}</span></div>
                                                </div>
                                                <div class="mb-2 flex w-full items-center justify-between">
                                                    <div class="text-white-dark">Total Qty :</div>
                                                    <div><span>{{$transaction->lines_of_purchase_return->sum('quantity')}}</span></div>
                                                </div>
                                            </div>
                                            <div class="xl:1/3 sm:w-1/2 lg:w-2/5">
                                                <div class="mb-2 flex w-full items-center justify-between">
                                                    <div class="text-white-dark">Total Amount :</div>
                                                    <div><span>Rs. {{ number_format($transaction->final_total, 2) }}</span></div>
                                                </div>
                                                <div class="mb-2 flex w-full items-center justify-between">
                                                    <div class="text-white-dark">Purchase Ref :</div>
                                                    <div><span>{{ $transaction->old_ref_no }}</span></div>
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
                                                    <th class="ltr:text-right rtl:text-left">Line Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($transaction->lines_of_purchase_return as $kry => $line)
                                                    <tr>
                                                        <td>{{ $kry + 1 }}</td>
                                                        <td>{{ $line->product->name }}</td>
                                                        <td>{{ $line->quantity }}</td>
                                                        <td class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{ $line->unit_price }}</td>
                                                        <td class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{ $line->quantity * $line->unit_price }}</td>
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
                                                        <th class="">Return Note</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <tr>
                                                            <td>{!! $transaction->details !!}</td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="space-y-2 ltr:text-right rtl:text-left">
                                            <div class="flex items-center">
                                                <div class="flex-1">Total</div>
                                                <div class="w-[37%]"><span>Rs. {{ number_format($transaction->final_total, 2) }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" flex justify-end items-center mt-3">
                                    
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