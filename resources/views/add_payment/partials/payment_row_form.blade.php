<div class=" payment_row">	
	<div class="grid grid-cols-2 gap-5">
		<input type="hidden" class="payment_row_index" value="{{ $row_index}}">
		<div style="align-items: center; gap: 20px;">
			<div class="form-group">
				{!! Form::label("amount_$row_index" ,__('Amount') . ':*') !!}
				{!! Form::text("payment[$row_index][amount]", $due, ['class' => 'form-input payment-amount input_number', 'required', 'id' => "amount_$row_index", 'placeholder' => __('Amount')]); !!}
			</div>
		</div>
		<div style="align-items: center; gap: 20px;">
			<div class="form-group">
				{!! Form::label("method_$row_index" , __('Payment Method') . ':*') !!}
				{!! Form::select("payment[$row_index][method]", $payment_types, $payment_line['method'], ['class' => 'form-input payment_types_dropdown', 'id' => "method_$row_index"]); !!}
			</div>
		</div>
	</div>
	<br>
	@include('add_payment.partials.payment_type_details')	
	<div class="grid grid-cols-1 gap-5">
		<div class="form-group">
			{!! Form::label("note_$row_index", __('Payment Remarks') . ':') !!}
			{!! Form::textarea("payment[$row_index][note]", $payment_line['note'], ['class' => 'form-input', 'rows' => 3, 'id' => "note_$row_index"]); !!}
		</div>
	</div>
</div>