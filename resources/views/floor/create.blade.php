<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('FloorController@store'), 'method' => 'post', 'id' => 'floor_add_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Add Floor</h4>
    </div>

    <div class="modal-body">
        <div class="form-group">
            {!! Form::label('name', __('Floor Name') . ':*') !!}
            {!! Form::text('name', null, ['class' => 'form-input',
                'required']); !!}
        </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">SAVE</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->