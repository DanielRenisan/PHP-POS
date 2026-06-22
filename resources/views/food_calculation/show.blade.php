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
                <a href="{{action('FoodCalculationController@index')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"><span>Food Calculation</span></a>
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
                        </div>
                            <div class="p-5">
                                <div class="panel">
                                    <div class="flex flex-wrap justify-between gap-4 px-4">
                                        <div class="text-2xl font-semibold uppercase"><span>Food Calculation</span></div>
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
                                                <div>Calculation For:</div>
                                                <div class="font-semibold text-black dark:text-white"><span>{{$facility['name'] ?? ''}}</span></div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive mt-6">
                                        <table class="table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="">S.NO</th>
                                                    <th class="">PRODUCT</th>
                                                    <th class="">INGREDIANT QTY</th>
                                                    <th class="">INGREDIANT Amt.</th>
                                                    <th class="">WASTAGE QTY.</th>
                                                    <th class="">WASTAGE Amt.</th>
                                                    <th class="ltr:text-right rtl:text-left">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($costCalculationProducts as $key => $cost)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $cost['product_name'] }}</td>
                                                        <td>{{ $cost['qty'] }} ({{ $cost['relatedProductUnit'] }})</td>
                                                        <td class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{ $cost['intAmount'] }}</td>
                                                        <td>{{ $cost['wastQty'] }} ({{ $cost['wastUnit'] }})</td>
                                                        <td class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{ $cost['wastAmount'] }}</td>
                                                        <td class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true">{{ $cost['total'] }}</td>
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
                                                        <th class="">Cook Instruction</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <tr>
                                                            <td>{!! $facility['cooking_instruction'] !!}</td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                            <br>
                                            <table class="table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="">Service Instruction</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <tr>
                                                            <td>{!! $facility['service_instruction'] !!}</td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="space-y-2 ltr:text-right rtl:text-left">
                                            <div class="flex items-center">
                                                <div class="flex-1">Ingredient Cost</div>
                                                <div class="w-[37%]"><span>Rs. {{ number_format($facility['ingredients_cost'], 2) }}</span></div>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="flex-1">Waste Cost</div>
                                                <div class="w-[37%]"><span>Rs. {{ number_format($facility['wastage_cost'], 2) }}</span></div>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="flex-1">Extra Cost</div>
                                                <div class="w-[37%]"><span>Rs. {{ number_format($facility['extra_cost'], 2) }}</span></div>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="flex-1">Service Cost</div>
                                                <div class="w-[37%]"><span>Rs. {{ number_format($facility['service_cost'], 2) }}</span></div>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="flex-1">Profit Margin</div>
                                                <div class="w-[37%]"><span>Rs. {{ number_format($facility['gross_profit'], 2) }}</span></div>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="flex-1">Labour Cost</div>
                                                <div class="w-[37%]"><span>Rs. {{ number_format($facility['labour_cost'], 2) }}</span></div>
                                            </div>
                                            <div class="flex items-center text-lg font-semibold">
                                                <div class="flex-1">Grand Total</div>
                                                <div class="w-[37%]"><span>Rs. {{ number_format($facility['selling_price'], 2) }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" flex justify-end items-center mt-3">
                                    <button type="button" class="btn btn-outline-danger"
                                        @click="viewModal = false">Close</button>
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