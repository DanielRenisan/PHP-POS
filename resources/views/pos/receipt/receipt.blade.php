<style>
        @page {
        margin: 0;
        }
        @media print {
            .no-screen{
                display: none !important;
            }  
        }
        .receipt {
            width: 78mm;
            font-size: 12px;
            padding: 12px;
            border: 1px solid #ccc;
        }

        .receipt h1 {
            font-size: 16px;
            font-weight: bold;
            margin-top: 0;
        }

        .receipt p {
            margin: 0;
        }

        .receipt table {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt .header-table {
            width: 100%;
            border-collapse: collapse;
            line-height:4px;
        }

        .receipt th,
        .receipt td {
            padding: 5px;
            text-align: left;
        }

        .receipt th {
            background-color: #f2f2f2;
        }

        .receipt .total p,
        .receipt .price p {
            font-size: 12px;
            font-weight: bold;
        }

        .image {
            display: flex;
            justify-content: center;
        }

        .image img {
            width: 100px;
            object-fit: cover;
        }
    </style>

@if(isset($type))
<div class="receipt">
    <h1 style="text-align: center;">{{isset($business_details) && isset($business_details->name) ? $business_details->name :'Loopdigipos'}}</h1>
    <p style="text-align: center;">{{isset($business_details) && isset($business_details->address) ? $business_details->address :'NO-603, HOSPITAL ROAD,'}} {{isset($business_details) && isset($business_details->city) ? $business_details->city :'Colombo'}}</p>
    <p style="text-align: center;">{{isset($business_details) && isset($business_details->mobile) ? $business_details->mobile :' '}}, {{isset($business_details) && isset($business_details->phone) ? $business_details->phone :' '}}</p>
    <h6 style="text-align: center;">Bill No.: {{$transaction->invoice_no ?? ''}}</h6>
    <br>
    @php
    $order_type = '';
            if($transaction->room_id && $transaction->order_type == 'Room Order')
            {
                $room_get =  App\Models\RoomAssign::find($transaction->room_id);
                $order_type = $transaction->order_type .'(' .$room_get->room_id. ')';
            }
            if($transaction->table_id && $transaction->order_type == 'Dine in')
            {
                $table =  App\Models\Table::find($transaction->table_id);
                $order_type = $transaction->order_type .'(' .$table->table_name. ')';
            }
            if($transaction->order_type == 'Take away')
            {
                $order_type = $transaction->order_type;
            }
            if($transaction->order_type == 'Online')
            {
                $order_type = $transaction->order_type;
            }
    @endphp
    <div class="grid grid-cols-1">
        <table class="header-table">
            <tr>
                <td>Cashier: {{ $transaction->staff->first_name ?? ''}}</td>
                <td>Date: {{$transaction->created_at ?? ''}}</td>
            <tr>
            <tr>
                <td colspan="2">Customer: {{ $transaction->customer->first_name ?? ''}}</td>
            <tr>
        </table>
    </div>
    <div class="grid grid-cols-1">
    <p class="text-end"></p>
    </div>
    <table style="margin: 10px 0;">
        <thead>
            <tr>
                <td>No</td>
                <td>Item</td>
                <td style="text-align: right;">Amt</td>
            </tr>
        </thead>
        <tbody style="border-top: 1px dotted black">
            @php
                $no = 1;
            @endphp
            @foreach($transaction->lines_of_sell as $line)
            <tr>
                <td>{{ $no++ }}</td>
                <td style="width: 70%;">{{$line->product->name ?? '' }}&nbsp;&nbsp;
                    
                    <span>{{ $line->quantity ?? 0}}x{{$line->unit_price ?? '0.00'}}</span>
                    @if($line->discount_amount > 0)
                    <br>
                    <span>Discount: {{number_format($line->discount_amount, 2)}}</span>
                    @endif
                </td>
                <td style="width: 30%; text-align: right;">{{$line->sub_total ?? '0.00'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="grid grid-cols-1">
        @php 
        $payments = App\Models\TransactionPayment::where('transaction_id', $transaction->id);
        $payment = $payments->first();
        $paid = $payments->sum('customer_paid');
        $due = $transaction->final_total - $paid;
        $tax = $transaction->tax_amount > 0 ? ($transaction->tax_amount /100) * $transaction->lines_of_sell->sum('sub_total') : 0;
        @endphp
        <table style="right: 2px;line-height:5px;">
            
            <tr  style="border-top: 1px dotted black;line-height:1;">
                <td style="text-align:left;font-size:14px;"><strong>Total:</strong></td>
                <td style="text-align:right;font-size:14px;"><strong>{{$transaction->lines_of_sell->sum('sub_total') > 0 ? number_format($transaction->lines_of_sell->sum('sub_total'), 2) : '0.00'}}</strong></td>
            </tr>
            <tr  style="border-top: 1px dotted black;">
                <td style="text-align:left">Total Items:</td>
                <td style="text-align:right">{{$transaction->lines_of_sell->sum('quantity')}}</td>
            </tr>
            @if(isset($payment))
            <tr>
                <td style="text-align:left">{{$payment->method}}: </td>
                <td style="text-align:right">{{$payment->amount > 0 ? number_format($payment->customer_paid, 2) : '0.00'}}</td>
            </tr>
            @endif
            <tr>
                <td style="text-align:left">Balance:</td>
                <td style="text-align:right">{{ $due < 0 ? number_format(str_replace('-', '', $due), 2) : '0.00'}}</td>
            </tr>
        </table>  
    </div>
    <br>
    <p  style="line-height: 12px; margin-top: 10px; font-size: 9px;text-align: center;">Thank you come again
    </p>
    <div>
        <span style="font-size: 7px; display: flex; justify-content: center; margin-top: 20px;">Software by 
            Loopdigi - +94768271573</span>
    </div>
</div>
@else

@if($business_details->printer_display == 'DirectPrint')
    @php
        $order_type = '';
        if($transaction->room_id && $transaction->order_type == 'Room Order') {
            $room_get =  App\Models\RoomAssign::find($transaction->room_id);
            $order_type = $transaction->order_type .'(' .$room_get->room_id. ')';
        }
        if($transaction->table_id && $transaction->order_type == 'Dine in') {
            $table =  App\Models\Table::find($transaction->table_id);
            $order_type = $transaction->order_type .'(' .$table->table_name. ')';
        }
        if($transaction->order_type == 'Take away') {
            $order_type = $transaction->order_type;
        }
        if($transaction->order_type == 'Online') {
            $order_type = $transaction->order_type;
        }
    @endphp

    {{-- =================== KOT BILL =================== --}}
    @if(collect($products ?? [])->contains(function ($product) use ($transaction) {
        $line = App\Models\TransactionSellLine::where('transaction_id', $transaction->id)
            ->where('product_id', $product['product_id'])->first();
        return $line && $line->product->is_kot == 1;
    }))
        <div class="receipt">
            <h1 class="text-center" style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">KOT</h1>
            <h3 class="text-center" style="font-size: 14px; font-weight: bold; margin-bottom: 10px;">{{ $order_type }}</h3>

            <div class="grid grid-cols-1">
                <table class="header-table"  style="font-size: 12px;">
                    <tr>
                        <td>Bill No.: {{$transaction->invoice_no ?? ''}}</td>
                        <td>{{ date('Y-m-d H:i:s A')}}</td>
                    <tr>
                    <tr>
                        <!-- <td>OD No.: {{$transaction->lines_of_sell->first() ? $transaction->lines_of_sell->first()->order_no : ''}}</td> -->
                        <td colspan="2">Customer: {{ $transaction->customer->first_name ?? ''}}</td>
                    <tr>
                    <tr>
                        <td  colspan="2">Cashier: {{ $transaction->staff->first_name ?? ''}}</td>
                    <tr>   
                </table>
            </div>

            <table style="margin: 10px 0;font-size: 18px;">
                <thead>
                    <tr>
                        <td>No</td>
                        <td>Item</td>
                        <td>Qty</td>
                    </tr>
                </thead>
                <tbody style="border-top: 1px solid lightgray;">
                    @php 
                        $total_qty = 0;
                        $no = 1;
                    @endphp
                    @foreach($products ?? [] as $product)
                        @php 
                            $line = App\Models\TransactionSellLine::where('transaction_id', $transaction->id)
                                ->where('product_id',$product['product_id'])->first(); 
                        @endphp
                        @if($line && $line->product->is_kot == 1)
                            @php $total_qty += $product['quantity']; @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td style="width: 70%;">{{$line->product->name ?? ''}} </td>
                                <td style="width: 10%; text-align: right;">{{$product['quantity']}}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <div style="display: flex; justify-content: flex-end; gap: 50px; margin-right: 5px; border-top: 1px solid lightgray; font-size: 14px; font-weight: bold; padding-top: 6px;">
                <p>Total Items: </p>
                <p class="text-end">{{$total_qty}}</p>
            </div>
            <div>
                <span style="font-size: 7px; display: flex; justify-content: center; margin-top: 20px;">Created by Loopdigi - +94768271573</span>
            </div>
        </div>
    @endif

    {{-- =================== BOT BILL =================== --}}
    @if(collect($products ?? [])->contains(function ($product) use ($transaction) {
        $line = App\Models\TransactionSellLine::where('transaction_id', $transaction->id)
            ->where('product_id', $product['product_id'])->first();
        return $line && $line->product->is_bot == 1;
    }))
        <div class="receipt">
            <h1 class="text-center" style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">BOT</h1>
            <h3 class="text-center" style="font-size: 14px; font-weight: bold; margin-bottom: 10px;">{{ $order_type }}</h3>

            <div class="grid grid-cols-1">
                <table class="header-table"  style="font-size: 12px;">
                    <tr>
                        <td>Bill No.: {{$transaction->invoice_no ?? ''}}</td>
                        <td>{{ date('Y-m-d H:i:s A')}}</td>
                    <tr>
                    <tr>
                        <!-- <td>OD No.: {{$transaction->lines_of_sell->first() ? $transaction->lines_of_sell->first()->order_no : ''}}</td> -->
                        <td colspan="2">Customer: {{ $transaction->customer->first_name ?? ''}}</td>
                    <tr>
                    <tr>
                        <td  colspan="2">Cashier: {{ $transaction->staff->first_name ?? ''}}</td>
                    <tr>   
                </table>
            </div>

            <table style="margin: 10px 0;font-size: 18px;">
                <thead>
                    <tr>
                        <td>No</td>
                        <td>Item</td>
                        <td>Qty</td>
                    </tr>
                </thead>
                <tbody style="border-top: 1px solid lightgray;">
                    @php 
                        $total_qty = 0; 
                        $no = 1;
                    @endphp
                    @foreach($products ?? [] as $product)
                        @php 
                            $line = App\Models\TransactionSellLine::where('transaction_id', $transaction->id)
                                ->where('product_id',$product['product_id'])->first(); 
                        @endphp
                        @if($line && $line->product->is_bot == 1)
                            @php $total_qty += $product['quantity']; @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td style="width: 70%;">{{$line->product->name ?? ''}} </td>
                                <td style="width: 10%; text-align: right;">{{$product['quantity']}}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <div style="display: flex; justify-content: flex-end; gap: 50px; margin-right: 5px; border-top: 1px solid lightgray; font-size: 14px; font-weight: bold; padding-top: 6px;">
                <p>Total Items: </p>
                <p class="text-end">{{$total_qty}}</p>
            </div>
            <div>
                <span style="font-size: 7px; display: flex; justify-content: center; margin-top: 20px;">Created by Loopdigi - +94768271573</span>
            </div>
        </div>
    @endif
@endif

@endif
