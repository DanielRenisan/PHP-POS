<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('BookingSourceController@update', $source->id), 'method' => 'post', 'id' => 'source_edit_form' , 'files' => true]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Edit Booking Source</h4>
    </div>

    <div class="modal-body">
        <div class="form-group">
            {!! Form::label('booking_type', __('Booking Type') . ':*') !!}
            {!! Form::select('booking_type', ['Wedding' => 'Wedding', 'Business Seminar' => 'Business Seminar', 'Allocation' => 'Allocation', 'Groups' => 'Groups', 'Instant' => 'Instant', 'Advance' => 'Advance', 'Test booking type' => 'Test booking type'], $source->booking_type, ['class' => 'form-input', 'id'=>'seachable-select',
                'placeholder' => __('Please Select One'),
                'required']); !!}
        </div>

        <div class="form-group">
            {!! Form::label('name', __('Booking Source') . ':*') !!}
            {!! Form::text('name', $source->name, ['class' => 'form-input',
                'required']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('rate', __('Rate') . ':*') !!}
            {!! Form::number('rate', $source->rate, ['class' => 'form-input',
                'required']); !!}
        </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">UPDATE</button>
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