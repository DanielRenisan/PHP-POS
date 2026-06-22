
  <style>
    @media print {
    .margin{
        display: flex;
        justify-content: flex-end;
        margin-right: 0;
    }
    .row {
      display: flex;
      flex-wrap: wrap;
      margin-right: -15px;
      margin-left: -15px;
    }
    body {
      padding-left:2mm;
      padding-right:2mm;
    }
  }
    #invoice-POS{
  background: #FFF;
  
  
::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}

p{
  font-size: 12px;
  color: #00000;
  line-height: 1.2em;
}
 
#top, #mid,#bot{ /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}

#top{min-height: 100px;}
#mid{min-height: 80px;} 
#bot{ min-height: 50px;}

/* Fixed logo centering */
#top .logo {
  text-align: center;
  margin: 0 auto 15px;
  display: block;
  width: 100%;
}

#top .logo img {
  display: block;
  margin: 0 auto;
  max-width: 150px;
  height: auto;
}

.clientlogo {
  float: left;
  height: 60px;
  width: 60px;
  background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
  background-size: 60px 60px;
  border-radius: 50px;
}

.info{
  display: block;
  //float:left;
  margin-left: 0;
}
.title{
  float: right;
}
.title p{text-align: right;} 
td{
  //padding: 5px 0 5px 15px;
  //border: 1px solid #EEE
}
.tabletitle{
  //padding: 5px;
  font-size: .5em;
  background: #EEE;
}
.service{border-bottom: 1px solid #EEE;}
.item{width: 24mm;}
.itemtext{font-size: .5em;}

#legalcopy{
  margin-top: 5mm;
}

  
  
}
  </style>
  <div id="invoice-POS">
  @php 
					$bussiness = App\Models\Business::first();
					@endphp
    <center id="top">
     <div class="logo">
      <img src="{{ isset($bussiness) && isset($bussiness->logo) ? url( 'storage/business_logos/' . $bussiness->logo)  : asset('img/logo.png')}}" width="150" height="70" alt="Logo">
     </div>
      <div class="info"> 
        <h4>{{ isset($bussiness) && isset($bussiness->name) ? $bussiness->name : 'Pearl Island' }}</h4>
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
        @endif</p>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
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
    <div class="info">
  <div class="row">
    <div class="col-md-6">
      <p>
        Ref No: {{ $booking->ref_no ?? '' }}<br> 
        Booking Date: {{ $booking->created_at ?? '' }}<br> 
        Customer: {{$booking->customer->first_name ?? '' }} {{$booking->customer->last_name ?? '' }}<br>
        Address: {{ $booking->customer->address ?? '' }}<br>
        Email: {{ $booking->customer->email ?? '' }}<br>
        Phone: {{ $booking->customer->mobile_no ?? '' }}<br>
        NIC / Passport : {{$booking->customer->national_id ?? '' }}
        
      </p>
    </div>
    <div class="col-md-6">
      <p> 
        Checkin At: {{ $booking->check_in_at ?? '' }}<br>
        Checkout At: {{ $booking->check_out_at ?? '' }}<br>
        Room No: {!! $result !!}<br>
        Status: BOOKING<br>
        No of Rooms: {{ $room->count() }}<br>
        Days: {{ $no_of_days }}
      </p>
    </div>
  </div>
</div>
    </div><!--End Invoice Mid-->
    <div id="bot">
		  <div class="col-md-12">
        <table width="100%">
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
                $complementary =  App\Models\Complementary::where('id',  $single_room->complementry_id)->sum('rate');
                $complementary_amount = $complementary * $single_room->number;
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
    <br><br>
    <div id="bot">
      <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4">
          <table>
          <tr>
              <td>Total Amt.</td>
              <td  style="text-align:right;">{{ number_format($total_rent,2) }}</td>
            </tr>
            <tr>
              <td>Advance Amt.</td>
              <td  style="text-align:right;">{{ number_format($advance,2) }}</td>
            </tr>
            <tr  class="tabletitle">
              <th  class="Rate">Payable.</th>
              <th class="payment" style="text-align:right;">{{ number_format($total_rent-$advance ,2) }}</th>
            </tr>

          </table>
        </div><!--End Table-->
      </div>  
      <center id="legalcopy">
        <p class="legal" style="font-size:9px"><strong>Thank you for your business!</strong>
        </p>
      </center>
      <center id="legalcopy">
        <p class="legal" style="font-size:9px"><strong>System By Loopdigi (+94768271573)</strong>
        </p>
      </center>
	  </div><!--End InvoiceBot-->
  </div><!--End Invoice-->
