<div class=" payment_row">	
	<div class="row grid grid-cols-2 gap-5">
		<input type="hidden" class="payment_row_index" value="{{ $row_index}}">
		<div style="align-items: center; gap: 20px;">
			<div class="form-group">
				{!! Form::label("amount_$row_index" ,__('Due Amount') . ':*') !!}
				{!! Form::text("payment[$row_index][amount]", $payment_line['amount'], ['class' => 'form-input payment-amount input_number', 'required', 'readonly', 'id' => "amount_$row_index", 'placeholder' => __('Amount')]); !!}
			</div>
		</div>
		<div style="align-items: center; gap: 20px;">
			<div class="form-group">
				{!! Form::label("method_$row_index" , __('Payment Method') . ':*') !!}
				{!! Form::select("payment[$row_index][method]", $payment_types, $payment_line['method'], ['class' => 'form-input col-md-12 payment_types_dropdown', 'id' => "method_$row_index", 'style' => 'width:100%;']); !!}
			</div>
		</div>
	</div>
	<br>
	@include('booking.partials.payment_type_details')	
	<div class="grid grid-cols-1 gap-5">
		<div class="form-group">
			{!! Form::label("note_$row_index", __('Payment Remarks') . ':') !!}
			{!! Form::textarea("payment[$row_index][note]", $payment_line['note'], ['class' => 'form-input', 'rows' => 3, 'id' => "note_$row_index"]); !!}
		</div>
	</div>
</div>