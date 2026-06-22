@extends('layouts.app')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li>
                <a href="{{action('CustomerController@index')}}" class="text-primary hover:underline">Customers</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>View Document</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                </div>
                <div class="mb-5">
                    <div class="col-md-12">
                    @if(mime_content_type(storage_path('app/public/customer_img/'.$customer->docs)) == "application/pdf")
                    <iframe src="{{$path }}" title="description"  width="1000" height="600"></iframe>
                    @else
                    <img src="{{$path }}" width="500" height="400">
                    
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection