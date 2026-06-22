<div class="payment_details_div @if( $payment_line['method'] !== 'card' ) {{ 'hide' }} @endif" data-type="card" >
	<div class="grid grid-cols-3 gap-5">
		<div class="col-md-4">
			<div class="form-group">
				{!! Form::label("card_number_$row_index", __('Card No')) !!}
				{!! Form::text("payment[$row_index][card_number]", $payment_line['card_number'], ['class' => 'form-input', 'placeholder' => __('card no'), 'id' => "card_number_$row_index"]); !!}
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{!! Form::label("card_holder_name_$row_index", __('Card Holder Name')) !!}
				{!! Form::text("payment[$row_index][card_holder_name]", $payment_line['card_holder_name'], ['class' => 'form-input', 'placeholder' => __('card holder name'), 'id' => "card_holder_name_$row_index"]); !!}
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{!! Form::label("card_transaction_number_$row_index",__('Card Transaction No')) !!}
				{!! Form::text("payment[$row_index][card_transaction_number]", $payment_line['card_transaction_number'], ['class' => 'form-input', 'placeholder' => __('card transaction no'), 'id' => "card_transaction_number_$row_index"]); !!}
			</div>
		</div>
	</div>
	<div class="grid grid-cols-4 gap-5">
		<div class="col-md-3">
			<div class="form-group">
				{!! Form::label("card_type_$row_index", __('Card Type')) !!}
				{!! Form::select("payment[$row_index][card_type]", ['visa' => 'Visa', 'master' => 'MasterCard'], $payment_line['card_type'],['class' => 'form-input', 'id' => "card_type_$row_index" ]); !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				{!! Form::label("card_month_$row_index", __('Month')) !!}
				{!! Form::text("payment[$row_index][card_month]", $payment_line['card_month'], ['class' => 'form-input', 'placeholder' => __('month'),
				'id' => "card_month_$row_index" ]); !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				{!! Form::label("card_year_$row_index", __('Year')) !!}
				{!! Form::text("payment[$row_index][card_year]", $payment_line['card_year'], ['class' => 'form-input', 'placeholder' => __('year'), 'id' => "card_year_$row_index" ]); !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				{!! Form::label("card_security_$row_index",__('Security Code')) !!}
				{!! Form::text("payment[$row_index][card_security]", $payment_line['card_security'], ['class' => 'form-input', 'placeholder' => __('security code'), 'id' => "card_security_$row_index"]); !!}
			</div>
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line['method'] !== 'cheque' ) {{ 'hide' }} @endif" data-type="cheque" >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("cheque_number_$row_index",__('Cheque No')) !!}
			{!! Form::text("payment[$row_index][cheque_number]", $payment_line['cheque_number'], ['class' => 'form-input', 'placeholder' => __('cheque no'), 'id' => "cheque_number_$row_index"]); !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label("cheque_issued_date_$row_index",__('Cheque Issued Date')) !!}
			{!! Form::date("payment[$row_index][cheque_issued_date]", $payment_line['cheque_issued_date'], ['class' => 'form-input', 'placeholder' => __('Cheque Issued Date'), 'id' => "cheque_issued_date_$row_index"]); !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label("cheque_due_date_$row_index",__('Cheque Due Date')) !!}
			{!! Form::date("payment[$row_index][cheque_due_date]", $payment_line['cheque_due_date'], ['class' => 'form-input', 'placeholder' => __('Cheque Due Date'), 'id' => "cheque_due_date_$row_index"]); !!}
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line['method'] !== 'bank_transfer' ) {{ 'hide' }} @endif" data-type="bank_transfer" >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("bank_account_number_$row_index",__('Bank Account Number')) !!}
			{!! Form::text( "payment[$row_index][bank_account_number]", $payment_line['bank_account_number'], ['class' => 'form-input', 'placeholder' => __('bank account number'), 'id' => "bank_account_number_$row_index"]); !!}
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line['method'] !== 'credit' ) {{ 'hide' }} @endif" data-type="credit" >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("credit_date_$row_index", 'Credit Date') !!}
			{!! Form::text("payment[$row_index][credit_date]", @format_date('now'), ['class' => 'form-input', 'placeholder' => __('credit date'), 'id' => "credit_date"]); !!}
		</div>
	</div>
</div>

<!-- <div class="test_div" data-type=" " >
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("ref_no_$row_index", 'Reference No') !!}
			{!! Form::text("payment[$row_index][ref_no]", null, ['class' => 'form-input', 'placeholder' => __('Reference No'), 'id' => "ref_no_$row_index"]); !!}
		</div>
	</div>
</div> -->