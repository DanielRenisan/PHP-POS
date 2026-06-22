<style>
  /* Scope all receipt styles to the invoice container only */
  #invoice-POS {
    background: #FFF;
    width: 100%;
    max-width: 210mm; /* Default A4 width for screen */
    margin: 0 auto;
    padding: 5mm;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
    font-size: 14px;
    line-height: 1.4;
    color: #333;
  }

  #invoice-POS ::selection {
    background: #f31544;
    color: #FFF;
  }
  
  #invoice-POS ::moz-selection {
    background: #f31544;
    color: #FFF;
  }

  #invoice-POS p {
    font-size: 14px;
    color: #000;
    line-height: 1.4em;
    margin: 3px 0;
  }
   
  #invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
    border-bottom: 1px solid #EEE;
    width: 100%;
    box-sizing: border-box;
  }

  #invoice-POS #top {
    min-height: 100px;
    padding-bottom: 15px;
    margin-bottom: 15px;
    text-align: center;
  }
  
  #invoice-POS #mid {
    min-height: 80px;
    padding-bottom: 10px;
    margin-bottom: 10px;
  }
  
  #invoice-POS #bot {
    min-height: 50px;
    padding-bottom: 10px;
    margin-bottom: 10px;
  }

  #invoice-POS #top .logo {
    text-align: center;
    margin: 0 auto 5px;
    display: block;
    width: 100%;
  }
  
  #invoice-POS #top .logo img {
    display: block;
    margin: 0 auto;
    max-width: 150px;
    height: auto;
  }
  
  #invoice-POS .info {
    display: block;
    margin-left: 0;
    width: 100%;
    text-align: center;
  }
  
  /* NEW: Compact invoice details layout */
  #invoice-POS .invoice-details {
    text-align: left;
    margin: 15px 0;
  }

  #invoice-POS .invoice-details .compact-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    max-width: 100%;
  }

  #invoice-POS .invoice-details .left-column,
  #invoice-POS .invoice-details .right-column {
    display: flex;
    flex-direction: column;
  }

  #invoice-POS .invoice-details .detail-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin: 2px 0;
    font-size: 14px;
  }

  #invoice-POS .invoice-details .detail-label {
    font-weight: bold;
    margin-right: 10px;
    min-width: fit-content;
    white-space: nowrap;
  }

  #invoice-POS .invoice-details .detail-value {
    flex: 1;
    word-wrap: break-word;
  }
  /* END NEW STYLES */
  
  #invoice-POS table {
    width: 100%;
    border-collapse: collapse;
    margin: 10px 0;
    font-size: 14px;
  }
  
  #invoice-POS th, #invoice-POS td {
    padding: 8px 5px;
    border-bottom: 1px solid #EEE;
    text-align: left;
  }
  
  #invoice-POS th {
    background: #f8f8f8;
    font-weight: bold;
  }
  
  #invoice-POS .tabletitle {
    padding: 5px;
    font-size: 14px;
    background: #EEE;
    font-weight: bold;
  }
  
  #invoice-POS h4 {
    margin: 10px 0;
    font-size: 18px;
    color: #333;
    text-align: center;
  }

  #invoice-POS .row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
    width: 100%;
  }
  
  #invoice-POS .col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding-right: 15px;
    padding-left: 15px;
  }
  
  #invoice-POS .col-md-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
    padding-right: 15px;
    padding-left: 15px;
  }
  
  #invoice-POS .col-md-8 {
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
    padding-right: 15px;
    padding-left: 15px;
  }
  
  #invoice-POS .col-md-12 {
    flex: 0 0 100%;
    max-width: 100%;
    padding-right: 15px;
    padding-left: 15px;
  }

  #invoice-POS #legalcopy {
    margin-top: 10mm;
    text-align: center;
  }
  
  #invoice-POS .legal {
    font-size: 18px !important;
    margin: 5px 0;
  }

  #invoice-POS .invoice-footer {
    text-align: center;
    font-size: 8px;
    color: #000;
    margin-top: 10px;
  }

  /* A4 print styles - ONLY applied during print */
  @media print {
    /* Only apply these styles when printing the invoice */
    #invoice-POS {
      font-size: 14px;
      line-height: 1.4;
      color: #000;
      margin: 0;
      padding: 10mm;
      width: 210mm;
      max-width: 210mm;
    }

    @page {
      size: A4;
      margin: 5mm;
    }

    #invoice-POS p {
      font-size: 14px;
      color: #000;
      line-height: 1.4em;
      margin: 3px 0;
    }

    #invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
      min-height: auto;
      padding-bottom: 10px;
      margin-bottom: 10px;
    }

    #invoice-POS #top {
      text-align: center;
      padding-bottom: 15px;
      margin-bottom: 15px;
    }

    #invoice-POS #top .logo img {
      max-width: 150px;
      height: auto;
    }

    #invoice-POS table {
      margin: 10px 0;
      font-size: 14px;
    }

    #invoice-POS th, #invoice-POS td {
      padding: 8px 5px;
      font-size: 14px;
    }

    #invoice-POS th {
      background: #f8f8f8;
      font-weight: bold;
      border-bottom: 1px solid #000;
    }

    #invoice-POS .tabletitle {
      padding: 5px;
      font-size: 14px;
      background: #EEE;
      font-weight: bold;
      border-top: 1px solid #000;
      border-bottom: 1px solid #000;
    }

    #invoice-POS h4 {
      margin: 10px 0;
      font-size: 18px;
      color: #000;
      font-weight: bold;
    }

    /* NEW: Print styles for compact layout */
    #invoice-POS .invoice-details .compact-layout {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    /* END NEW PRINT STYLES */

    /* Keep flex layout for A4 - don't stack vertically like 80mm */
    #invoice-POS .row {
      display: flex;
      flex-wrap: wrap;
      margin-right: -15px;
      margin-left: -15px;
      width: 100%;
    }

    #invoice-POS .col-md-6 {
      flex: 0 0 50%;
      max-width: 50%;
      padding-right: 15px;
      padding-left: 15px;
    }
    
    #invoice-POS .col-md-4 {
      flex: 0 0 33.333333%;
      max-width: 33.333333%;
      padding-right: 15px;
      padding-left: 15px;
    }
    
    #invoice-POS .col-md-8 {
      flex: 0 0 66.666667%;
      max-width: 66.666667%;
      padding-right: 15px;
      padding-left: 15px;
    }
    
    #invoice-POS .col-md-12 {
      flex: 0 0 100%;
      max-width: 100%;
      padding-right: 15px;
      padding-left: 15px;
    }

    #invoice-POS .invoice-details .col-md-6 {
      width: 50%;
      margin-bottom: 10px;
    }

    /* Keep the totals section in its original layout for A4 */
    #invoice-POS #bot .col-md-8 {
      display: flex; /* Show this section again for A4 */
    }

    #invoice-POS #bot .col-md-4 {
      width: 33.333333%;
      max-width: 33.333333%;
    }

    #invoice-POS #bot .col-md-4 table td:last-child,
    #invoice-POS #bot .col-md-4 table th:last-child {
      text-align: right;
    }

    #invoice-POS #legalcopy {
      margin-top: 15mm;
    }

    #invoice-POS .legal {
      font-size: 18px !important;
      margin: 5px 0;
    }

    #invoice-POS .invoice-footer {
      position: fixed;
      bottom: 5mm;
      left: 0;
      right: 0;
      text-align: center;
      transform: scale(1.5);
      transform-origin: center;
    }

    #invoice-POS .invoice-footer p {
      font-size: 8px;
      margin: 0;
      line-height: 1;
    }

    /* Ensure text is dark enough for printing */
    #invoice-POS * {
      -webkit-print-color-adjust: exact !important;
      color-adjust: exact !important;
      print-color-adjust: exact !important;
    }

    /* Hide everything else on the page when printing */
    body * {
      visibility: hidden;
    }
    
    #invoice-POS, #invoice-POS * {
      visibility: visible;
    }
    
    #invoice-POS {
      position: absolute;
      left: 0;
      top: 0;
    }
  }

  /* NEW: Responsive design for smaller screens */
  @media (max-width: 600px) {
    #invoice-POS .invoice-details .compact-layout {
      grid-template-columns: 1fr;
      gap: 10px;
    }
  }
  /* END NEW RESPONSIVE STYLES */
</style>

<div id="invoice-POS">
  @php 
    $bussiness = App\Models\Business::first();
  @endphp
  
  <div id="top">
    <div class="logo">
      <img src="{{ isset($bussiness) && isset($bussiness->logo) ? url( 'storage/business_logos/' . $bussiness->logo)  : asset('img/logo.png')}}" width="120" height="40" alt="Logo">
    </div>
    <div class="info"> 
      <h4>{{ isset($bussiness) && isset($bussiness->name) ? $bussiness->name : 'Hotel Management' }}</h4>
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
      </p>
    </div><!--End Info-->
  </div><!--End InvoiceTop-->
  
  @php 
    $startTimeStamp = strtotime($booking->check_in_at);
    $endTimeStamp = strtotime($booking->check_out_at);
    $timeDiff = abs($endTimeStamp - $startTimeStamp);
    $hour = request()->session()->get('business.day_duration') ?? 24;
    $numberDays = $timeDiff/($hour * 60 * 60);
    $discount = 0;
    $no_of_days = round($numberDays) < $numberDays ? round($numberDays) + 1 : round($numberDays);
    $total_rent = $room->sum('rent');
    if($booking->discount_type == 'percentage')
    {
        $discount = $booking->discount_amount/100 * $total_rent;
    }
    else
    {
        $discount = $booking->discount_amount;
    }
    $total_payable_aft_dis = $total_rent - $discount +  $booking->servise_charge;
  @endphp
<br>
  <div class="invoice-details">
    <div class="compact-layout">
      <div class="left-column">
        <div class="detail-item">
          <span class="detail-label">Ref No:</span>
          <span class="detail-value">{{ $booking->ref_no ?? '' }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Booking Date:</span>
          <span class="detail-value">{{ $booking->created_at ?? '' }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Customer:</span>
          <span class="detail-value">{{$booking->customer->first_name ?? '' }} {{$booking->customer->last_name ?? '' }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Address:</span>
          <span class="detail-value">{{$booking->customer->address_one ?? '' }} {{$booking->customer->address_two ?? '' }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Email:</span>
          <span class="detail-value">{{$booking->customer->email ?? '' }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Phone:</span>
          <span class="detail-value">{{$booking->customer->mobile_no ?? '' }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">NIC / Passport:</span>
          <span class="detail-value">{{$booking->customer->national_id ?? '' }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Country:</span>
          <span class="detail-value">{{$booking->customer->country ?? '' }}</span>
        </div>
      </div>

      <div class="right-column">
        <div class="detail-item">
          <span class="detail-label">Checkin At:</span>
          <span class="detail-value">{{ $booking->check_in_at ?? '' }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Checkout At:</span>
          <span class="detail-value">{{ $booking->check_out_at ?? '' }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Room No:</span>
          <span class="detail-value">{!! $result !!}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Status:</span>
          <span class="detail-value">INVOICED</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">No of Rooms:</span>
          <span class="detail-value">{{$room->count()}}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Days:</span>
          <span class="detail-value">{{$no_of_days}}</span>
        </div>
      </div>
    </div>
  </div><!--End Invoice Details-->
  <br>
  <div id="bot">
    <div class="row">
      <table>
        <thead>
              <tr>
                <th>No</th>
                <th>Date</th>
                <th style="text-align:right">Amount</th>
              </tr>
        </thead>
        <tbody>
          @foreach($room as $key => $single_room)
          @php 
            $total = $single_room->rent + $single_room->bed_amount + $single_room->person_amount + $single_room->child_amount ;
            $com_rate =  App\Models\Complementary::where('id',  $single_room->complementry_id)->sum('rate');
            $complementary_amount = $single_room->number;
            $complementary = $com_rate * $complementary_amount;
          @endphp
          <tr>
            <td>{{$key + 1}}</td>
            <td>{{ date('Y-m-d', strtotime($single_room->created_at)) }}</td>
            <td style="text-align:right">{{number_format($total + $complementary, 2)}}</td>
          </tr>
          @endforeach    
        </tbody>
      </table>
    </div>
  </div>
  
  
   @php
      $startTimeStamp = strtotime($booking->check_in_at);
      $endTimeStamp = strtotime($booking->check_out_at);
      $timeDiff = abs($endTimeStamp - $startTimeStamp);
      $hour = request()->session()->get('business.day_duration') ?? 24;            
      $numberDays = $timeDiff/($hour * 60 * 60);
      $discount = 0;
      $no_of_days = round($numberDays) < $numberDays ? round($numberDays) + 1 : round($numberDays);
      $total_rent = $room->sum('rent');
      if($booking->discount_type == 'percentage')
      {
          $discount = $booking->discount_amount/100 * $total_rent;
      }
      else
      {
          $discount = $booking->discount_amount;
      }
      $total_payable_aft_dis = $total_rent - $discount +  $booking->servise_charge;
      $comIds = $room->pluck('complementry_id')->toArray();
      $com_rate =  App\Models\Complementary::whereIn('id', $comIds)->sum('rate');
      $complementary_amount = $room->sum('number');
      $complementary =  $com_rate * $complementary_amount;

      $due_order = 0;

      if(isset($booking) && isset($booking->contact_id)) {
          try {
              // Simple query to get due orders for this customer
                  
              $due_order = DB::table('transactions')
                ->where('type', 'order')
                ->where('status', 'final')
                ->where('contact_id', $booking->contact_id)
                ->where('payment_status', 'paid')
                ->where('is_include', 1)
                ->where('hotel_transaction_id', $booking->transaction_id)
                ->sum('final_total');
                
              \Log::info('Blade SIMPLE: Due order calculated: ' . $due_order);
              
          } catch (\Exception $e) {
              \Log::error('Blade SIMPLE: Error: ' . $e->getMessage());
              $due_order = 0;
          }
      }
    @endphp

  <div id="bot">
    <div class="row">
      <div class="col-md-8" style="text-align:right">
      </div>
        <table>
          <tr>
            <td>Room Rent Amt.</td>
            <td  style="text-align:right;">{{ number_format($total_rent,2) }}</td>
          </tr>
          <tr>
            <td>Discount Amt.</td>
            <td  style="text-align:right;">{{ number_format($discount, 2) }}</td>
          </tr>
          @if($booking->additional_note)
          <tr>
            <td>{{$booking->additional_note}}</td>
            <td  style="text-align:right;">{{ $booking->additional_charge ?? '0.00' }}</td>
          </tr>
          @endif
          <tr>
            <td>Total Room Rent Amt.</td>
            <td  style="text-align:right;">{{ number_format($total_payable_aft_dis, 2) }}</td>
          </tr>
          <tr>
            <td>Complementary Amt.</td>
            <td  style="text-align:right;">{{ number_format($complementary,2)}}</td>
          </tr>
          <tr>
            <td>Room Expense.</td>
            <td  style="text-align:right;">{{ $expenses->sum('final_total') > 0 ? number_format($expenses->sum('final_total'), 2) : '0.00' }}</td>
          </tr>
          <tr>
            <td>Rest Orders.</td>
            <td  style="text-align:right;">{{ $due_order > 0 ? number_format($due_order, 2) : '0.00' }}</td>
          </tr>
          <tr  class="tabletitle">
            <th  class="Rate">Total Amt.</th>
            <th class="payment" style="text-align:right;">{{ number_format($total_payable_aft_dis +  $complementary +  $due_order + $expenses->sum('final_total'), 2)}}</th>
          </tr>
        </table>
    </div>  
    
    <center id="legalcopy">
      <p class="legal"><strong>Thank you for your business!</strong></p>
    </center>
  </div>
  <div class="invoice-footer">
    <p>System By Loopdigi (+94768271573)</p>
  </div>
</div><!--End Invoice-->

<script>
// Override default print behavior to auto-detect format
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        window.print(); // Let CSS media queries handle the formatting
    }
});

// For programmatic printing, you can call this function
function printInvoice() {
    window.print(); // CSS media queries will automatically format
}
</script>