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
            padding: 10px;
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
<div class="receipt">
    <div class="image">
        <img src="{{ isset($business_details) && isset($business_details->logo) ? url( 'storage/business_logos/' . $business_details->logo)  : asset('img/logo.png')}}" alt="" width="250px" height="100px">
    </div>
    <h1 style="text-align: center;">{{isset($business_details) && isset($business_details->name) ? $business_details->name :'PEARL ISLAND INN- HOTEL BAR & RESTAURANT'}}</h1>
    <!--<p class="text-center">(Tourist Board Approved)</p>-->
    <p style="text-align: center;">{{isset($business_details) && isset($business_details->address) ? $business_details->address :'NO-603, HOSPITAL ROAD,'}} {{isset($business_details) && isset($business_details->city) ? $business_details->city :'JAFFNA'}}</p>
    <p style="text-align: center;">{{isset($business_details) && isset($business_details->mobile) ? $business_details->mobile :' '}}, {{isset($business_details) && isset($business_details->phone) ? $business_details->phone :' '}}</p>
    <p style="text-align: center;">{{isset($business_details) && isset($business_details->email) ? $business_details->email :'jaffnapearlislandinn@gmail.com'}}</p>
    <br>
    <div class="grid grid-cols-1">
        <table class="header-table">
            <tr>
                <td>Ref No.: {{$transaction->ref_no ?? ''}}</td>
                <td>{{ date('Y-m-d H:i:s A')}}</td>
            <tr>
            <tr>
                <td colspan="2">Supplier: {{ $transaction->supplier->first_name ?? ''}}</td>
            <tr>  
        </table>
    </div>
    <div class="grid grid-cols-1">
    <p class="text-end"></p>
    </div>
    <table style="margin: 10px 0;">
        <tbody style="border-top: 1px dotted black">
            @foreach($transaction->lines_of_purchase as $line)
            <tr>
                <td style="width: 70%;">{{$line->product->name ?? ''}}
                    
                    <span>{{$line->quantity ?? 0}} x {{$line->purchase_price ?? '0.00'}}</span>
                    @if($line->discount_amount > 0)
                    <br>
                    <span>Discount: {{number_format($line->discount, 2)}}</span>
                    @endif
                </td>
                <td style="width: 30%; text-align: right;">{{$line->line_total ?? '0.00'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="grid grid-cols-1">
        @php 
        $payments = App\Models\TransactionPayment::where('transaction_id', $transaction->id);
        $payment = $payments->first();
        $paid = $payments->sum('amount');
        $due = $transaction->final_total - $paid;
        $tax = $transaction->tax_amount > 0 ? ($transaction->tax_amount /100) * $transaction->lines_of_purchase->sum('line_total') : 0;
        @endphp
        <table style="right: 2px;line-height:5px;">
            <tr  style="border-top: 1px dotted black;line-height:1;">
                <td style="text-align:left;font-size:14px;">Sub Total:</td>
                <td style="text-align:right">{{number_format($transaction->lines_of_purchase->sum('line_total'), 2)}}</td>
            </tr>
            <tr>
                <td style="text-align:left">Tax : </td>
                <td style="text-align:right">{{$tax > 0 ? number_format($tax, 2) : '0.00'}}</td>
            </tr>
            <tr>
                <td style="text-align:left">Discount : </td>
                <td style="text-align:right">{{$transaction->discount_amount > 0 ? number_format($transaction->discount_amount, 2) : '0.00'}}</td>
            </tr>
            <tr  style="border-top: 1px dotted black;line-height:1;">
                <td style="text-align:left;font-size:14px;"><strong>Total:</strong></td>
                <td style="text-align:right;font-size:14px;"><strong>{{$transaction->final_total > 0 ? number_format($transaction->final_total, 2) : '0.00'}}</strong></td>
            </tr>
            <tr  style="border-top: 1px dotted black;">
                <td style="text-align:left">Total Items:</td>
                <td style="text-align:right">{{$transaction->lines_of_purchase->sum('quantity')}}</td>
            </tr>
        </table>  
    </div>
    <br>
    <p  style="line-height: 12px; margin-top: 10px; font-size: 9px;text-align: center;">YOUR TRIP
        ADVISER GUIDING - ARRANGEMENTS <br>
        LUXSURY ROOMS AVAILABLE <br>
        THANK YOU COME AGAIN
    </p>
    <div>
        <span style="font-size: 7px; display: flex; justify-content: center; margin-top: 20px;">Created
            by Loopdigi - +94768271573</span>
    </div>
</div>
