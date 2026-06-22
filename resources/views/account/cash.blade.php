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
                <span>Cash Flow</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-semibold dark:text-white-light">Cash Flow</h3>
                </div>
                <div class="row">
                    <form action="{{action('AccountController@cashFlow')}}" method="get">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input class="form-input" name="start_date" type="date" value="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input class="form-input" name="end_date" type="date" value="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                           <button class="btn btn-primary" type="submit">FILTER</button>  
                        </div>
                    </form>
                </div>
                <div class="my-4 h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                    <div class="mb-5">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr class="bg-danger/20 border-danger/20">
                                            <th>Date</th>
                                            <th>Ref No</th>
                                            <th>Payment method</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Account Balance</th>
                                        </tr>
                                    </thead>    
                                    <tbody>
                                        <tr class="bg-danger/20 border-danger/20">
                                            <td>Booking</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @foreach($bookings as $booking)
                                        <tr>
                                            <td>{{ date('Y-m-d H:i', strtotime($booking->created_at))}}</td>
                                            <td>{{$booking->ref_no}}</td>
                                            <td>{{$booking->method}}</td>
                                            <td></td>
                                            <td>{{$booking->amount ?? '0.00'}}</td>
                                            <td>{{$booking->amount ?? '0.00'}}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="bg-danger/20 border-danger/20">
                                            <td>Checkin</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @foreach($checkins as $checkin)
                                        <tr>
                                            <td>{{ date('Y-m-d H:i', strtotime($checkin->created_at))}}</td>
                                            <td>{{$checkin->ref_no}}</td>
                                            <td>{{$checkin->method}}</td>
                                            <td></td>
                                            <td>{{$checkin->amount ?? '0.00'}}</td>
                                            <td>{{$checkin->amount ?? '0.00'}}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="bg-danger/20 border-danger/20">
                                            <td>Checkout</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @foreach($checkouts as $checkout)
                                        <tr>
                                            <td>{{ date('Y-m-d H:i', strtotime($checkout->created_at))}}</td>
                                            <td>{{$checkout->ref_no}}</td>
                                            <td>{{$checkout->method}}</td>
                                            <td></td>
                                            <td>{{$checkout->amount ?? '0.00'}}</td>
                                            <td>{{$checkout->amount ?? '0.00'}}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="bg-danger/20 border-danger/20">
                                            <td>Purchase</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @foreach($purchases as $purchase)
                                        <tr>
                                            <td>{{ date('Y-m-d H:i', strtotime($purchase->created_at))}}</td>
                                            <td>{{$purchase->invoice_no}}</td>
                                            <td>{{$purchase->method}}</td>
                                            <td>{{$purchase->amount ?? '0.00'}}</td>
                                            <td></td>
                                            <td>{{$purchase->amount ?? '0.00'}}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="bg-danger/20 border-danger/20">
                                            <td>Expense</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @foreach($expenses as $expense)
                                        <tr>
                                            <td>{{ date('Y-m-d H:i', strtotime($expense->created_at))}}</td>
                                            <td>{{$expense->invoice_no}}</td>
                                            <td>{{$expense->method}}</td>
                                            <td>{{$expense->amount ?? '0.00'}}</td>
                                            <td></td>
                                            <td>{{$expense->amount ?? '0.00'}}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="bg-danger/20 border-danger/20">
                                            <td>Refund</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @foreach($canceled as $cancel)
                                        <tr>
                                            <td>{{ date('Y-m-d H:i', strtotime($cancel->created_at))}}</td>
                                            <td>{{$cancel->ref_no}}</td>
                                            <td>{{$cancel->method}}</td>
                                            <td>{{$cancel->amount ?? '0.00'}}</td>
                                            <td></td>
                                            <td>{{$cancel->amount ?? '0.00'}}</td>
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
@endsection