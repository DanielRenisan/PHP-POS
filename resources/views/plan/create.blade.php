<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('FloorPlaneController@store'), 'method' => 'post', 'id' => 'plan_add_form' , 'files' => true]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Add Floor Plan</h4>
    </div>

    <div class="modal-body">
        <div class="form-group">
            {!! Form::label('floor_id', __('Floor') . ':*') !!}
            {!! Form::select('floor_id', $floors, null, ['class' => 'form-input', 'id'=>'seachable-select',
                'placeholder' => __('Select Floor'),
                'required']); !!}
        </div>

        <div class="form-group">
            {!! Form::label('start_room_no', __('Start Room No') . ':*') !!}
            {!! Form::number('start_room_no', null, ['class' => 'form-input',
                'required']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('no_of_rooms', __('No Of Rooms') . ':*') !!}
            {!! Form::number('no_of_rooms', null, ['class' => 'form-input',
                'required']); !!}
        </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">SAVE</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<!-- script -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/nice-select2.css?v='.$asset_v) }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/file-upload-with-preview.min.css?v='.$asset_v) }}">
<script src="{{ asset('assets/js/nice-select2.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/file-upload-with-preview.iife.js?v=' . $asset_v) }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function(e) {
        // seachable 
        var options = {
            searchable: true
        };
        NiceSelect.bind(document.getElementById("seachable-select"), options);
    });
</script>