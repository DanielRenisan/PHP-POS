<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      <h4 class="modal-title" id="modalTitle">{{$booking->ref_no}}</h4>
	    </div>
        {!! Form::open(['url' => action('CancellationController@refund'), 'method' => 'post', 'id' => 'cancel_add_form' ]) !!}
	    <div class="modal-body">
            <input type="hidden" name="booking_id" value="{{$booking->id ?? '' }}">
            <input type="hidden" name="transaction_id" value="{{$transaction->id ?? '' }}">
            <div class="mb-6 grid gap-6">
                <div class="panel h-full">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('amount', 'Amount') !!}
                            {!! Form::number('amount', null, ['class' => 'form-control', 'id' => 'amount']); !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('note', 'Note') !!}
                            {!! Form::textarea('note', null, ['class' => 'form-control', 'rows' => 3, 'id' => 'details']); !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-danger">CANCEL</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>