@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6 no-print no-print" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1"  href="{{action('Rest\ProductController@index')}}">
                <span>Product</span>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Product View</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div>
                @php 
                    $bussiness = App\Models\Business::first();
                @endphp
                <div>
                    <div style="padding: 15px;">
                        <div class="mb-5 grid grid-cols-1 gap-5 lg:grid-cols-3 xl:grid-cols-4">
                            <div class="panel">
                                <div class="mb-5">
                                    <div class="flex flex-col items-center justify-center">
                                        <img src="{{$image_url}}" alt="image"
                                            class="mb-5 object-cover" width="400px" height="150px"/>
                                        <p class="text-xl font-semibold text-primary">{{$product->name ?? '' }}</p>
                                    </div>
                                    <ul
                                        class="m-auto mt-5 flex max-w-[400px] flex-col space-y-4 font-semibold text-white-dark">
                                        <li class="flex items-center gap-2">
                                            <strong>Product Type : </strong>
                                            <span>{{$type ?? ''}}</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <strong>Product Attributes : </strong>
                                            <span>{{$result}}</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <strong>Sku Code : </strong>
                                            <span>{{$product->sku_code ?? '' }}</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <strong>Barcode : </strong>
                                            <span>{{$product->barcode ?? '' }}</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <strong>Is KOT : </strong>
                                            <span>{{$kot ?? '' }}</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <strong>Is BOT : </strong>
                                            <span>{{$bot ?? '' }}</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <strong>Is Enable Stock : </strong>
                                            <span>{{$enable_stock ?? '' }}</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <strong>Status : </strong>
                                            <span>{{$status ?? '' }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- summary -->
                            <div class="panel lg:col-span-2 xl:col-span-3 ">
                                <div class="mb-5 grid grid-cols-1">
                                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                        <table>
                                            <tbody>
                                                <tr style="line-height:3;">
                                                    <th style="text-align: left;">Category :</th>
                                                    <td><span class="rounded-md bg-black/10 p-4 py-2 dark:bg-gray-800 ltr:rounded-bl-none rtl:rounded-br-none">{{$product->category ?? '' }}</span></td>
                                                    <th style="text-align: left;">Sub Category</th>
                                                    <td><span class="rounded-md bg-black/10 p-4 py-2 dark:bg-gray-800 ltr:rounded-bl-none rtl:rounded-br-none">{{$product->sub_category ?? '' }}</span></td>
                                                    <th style="text-align: left;">Brand</th>
                                                    <td><span class="rounded-md bg-black/10 p-4 py-2 dark:bg-gray-800 ltr:rounded-bl-none rtl:rounded-br-none">{{$product->brand ?? '' }}</span></td>
                                                    <th style="text-align: left;">Cuisine</th>
                                                    <td><span class="rounded-md bg-black/10 p-4 py-2 dark:bg-gray-800 ltr:rounded-bl-none rtl:rounded-br-none">{{$product->cousin ?? '' }}</span></td>
                                                </tr>
                                                <tr style="line-height:3;">
                                                    <th style="text-align: left;">Menu</th>
                                                    <td><span class="rounded-md bg-black/10 p-4 py-2 dark:bg-gray-800 ltr:rounded-bl-none rtl:rounded-br-none">{{$product->menu ?? '' }}</span></td>
                                                    <th style="text-align: left;">Type</th>
                                                    <td><span class="rounded-md bg-black/10 p-4 py-2 dark:bg-gray-800 ltr:rounded-bl-none rtl:rounded-br-none">{{$product->type ?? '' }}</span></td>
                                                    <th style="text-align: left;"></th>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                        <table>
                                            <thead>
                                                <tr style="line-height:3;">
                                                    <th style="text-align: center;">Sale price</th>
                                                    <th style="text-align: center;">Purchase price</th>
                                                    <th style="text-align: center;">MRP</th>
                                                    <th style="text-align: center;">Discount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="line-height:3;">
                                                    <td style="text-align: center;">{{$product->sale_price ?? '0.00' }}</td>
                                                    <td style="text-align: center;">{{$product->last_purchase_price ?? '0.00' }}</td>
                                                    <td style="text-align: center;">{{$product->discount ?? '0.00' }}</td>
                                                    <td style="text-align: center;">{{$product->mrp ?? '0.00' }}</td>
                                                </tr>
                                            <tbody>
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