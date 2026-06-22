<div class="modal-dialog modal-xl" role="document">
  <div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="modalTitle">Job Card Details (<b>Ref No:</b> {{ $purchase->invoice_no }})
    </h4>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-sm-12">
      <p class="pull-right"><b>@lang('Date'):</b> {{ $purchase->created_at }}</p>
    </div>
  </div>
  <div class="row invoice-info">
    <div class="col-sm-3 invoice-col ">
      <b>@lang('Ref No'):</b> {{ $purchase->invoice_No }}<br/>
      <b>@lang('Date'):</b> {{ $purchase->created_at }}<br/>
      <b>@lang('Payment Status'):</b> {{ $purchase->created_at }}<br/>
    </div>
    <div class="col-sm-3 invoice-col ">
      <b>@lang('Total Qty'):</b> {{ $purchase->purchase_lines->sum('quantity') }}<br/>
      <b>@lang('Expiry Date'):</b> {{ date('Y-m-d', strtotime($purchase->expiry_date)) }}<br/>
      <b>@lang('Supplier'):</b> {{ $purchase->supplier->name ?? '' }}<br/>
    </div>
  </div>

  <br>
  <div class="row">
    <div class="col-sm-12 col-xs-12">
      <div class="table-responsive">
        <table class="table bg-gray">
          <thead>
            <tr  style="">
              <th style="">#</th>
              <th style="">Item</th>
              <th style="">Quantity</th>
              <th style="">Unit Price</th>
              <th style="">Sub Total</th>
            </tr>
          </thead>
          @php 
            $total_before_tax = 0.00;
          @endphp
          @foreach($purchase->purchase_lines as $kyy => $purchase_line)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $purchase_line->item }}</td>
              <td>
                {{ $purchase_line->quantity }}
              </td>
              
              <td>{{ number_format($purchase_line->unit_price, 2) }}</td>
              <td>{{ number_format($purchase_line->sub_total, 2) }}</td>
            </tr> 
          @endforeach
  </table>
      </div>
    </div>
  </div>
</div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">CLOSE</button>
    </div>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var element = $('div.modal-xl');
		__currency_convert_recursively(element);
	});
</script>