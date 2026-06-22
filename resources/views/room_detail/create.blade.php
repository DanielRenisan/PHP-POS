<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('RoomDetailController@store'), 'method' => 'post', 'id' => 'detail_add_form' , 'files' => true]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Add Room Details</h4>
    </div>

    <div class="modal-body">
        <div class="form-group">
            {!! Form::label('room_facility_id', __('Facility Type') . ':*') !!}
            {!! Form::select('room_facility_id', $facilities, null, ['class' => 'form-input', 'id'=>'seachable-select',
                'placeholder' => __('Select facility'),
                'required']); !!}
        </div>

        <div class="form-group">
            {!! Form::label('name', __('Facility') . ':*') !!}
            {!! Form::text('name', null, ['class' => 'form-input',
                'required']); !!}
        </div>
        <div class="form-group">
            <div class="custom-file-container" data-upload-id="myFirstImage">
                <label class="input-container">
                    <input accept="*" name='image' aria-label="Choose File" class="input-hidden" id="file-upload-with-preview-myFirstImage" type="file">
                    <span class="input-visible">Choose file...<span class="browse-button">Browse</span></span>
                </label>
            </div>
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