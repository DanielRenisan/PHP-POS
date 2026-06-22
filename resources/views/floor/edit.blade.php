<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('FloorController@update', $floor->id), 'method' => 'PUT', 'id' => 'floor_edit_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Edit Floor</h4>
    </div>

    <div class="modal-body">
        <div class="form-group">
            {!! Form::label('name', __('Floor Name') . ':*') !!}
            {!! Form::text('name', $floor->name, ['class' => 'form-input',
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