@extends('layouts.app')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Room Available</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-semibold dark:text-white-light">Room Available</h3>
                </div>
                <div class="my-4 h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                <div class="mb-5">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection