<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('BedController@update', $bed->id), 'method' => 'PUT', 'id' => 'bed_edit_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Edit Bed</h4>
    </div>

    <div class="modal-body">
        <div class="form-group">
            {!! Form::label('name', __('Bed Name') . ':*') !!}
            {!! Form::text('name', $bed->name, ['class' => 'form-input',
                'required']); !!}
        </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">UPDATE</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->