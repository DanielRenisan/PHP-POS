<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('RoomController@update', $room->id), 'method' => 'PUT', 'id' => 'room_edit_form' , 'files' => true]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Edit Room</h4>
    </div>

    <div class="modal-body">
        <div class="form-group">
            {!! Form::label('room_type', __('Room Type') . ':*') !!}
            <select class="form-input" name="room_type" required>
              <option>Please Select One</option>
              @foreach($room_types as $key => $room_type)
                <option value="{{$room_type}}" {{$room->room_type == $room_type ? 'selected' : '' }}>{{$room_type}}</option>
              @endforeach
            </select>
        </div>
        <div class="form-group">
            {!! Form::label('capacity', __('Capacity') . ':*') !!}
            {!! Form::number('capacity', $room->capacity, ['class' => 'form-input',
                'required']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('extra_capacity', __('Extra Capability ') . ':*') !!}
            {!! Form::select('extra_capacity', ['Yes' => 'Yes', 'No' => 'NO'], $room->extra_capacity, ['class' => 'form-input',
              'placeholder' => __('Please Select'), 'required']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('rate', __('Rate') . ':*') !!}
            {!! Form::number('rate', $room->rate, ['class' => 'form-input',
                'required']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('bed_charge', __('Bed Charge') . ':') !!}
            {!! Form::number('bed_charge', $room->bed_charge, ['class' => 'form-input']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('person_charge', __('Person Charge') . ':') !!}
            {!! Form::number('person_charge', $room->person_charge, ['class' => 'form-input']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('room_size', __('Room Size') . ':') !!}
            <br>
            <div class="col-md-6">
              {!! Form::number('room_size', $room->room_size, ['class' => 'form-input']); !!}
            </div>
            <div class="col-md-6">
              {!! Form::select('room_size_id', $sizes , $room->room_size_id, ['class' => 'form-input',
                'placeholder' => __('Please Select')]); !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('bed_no', __('Bed No') . ':') !!}
            <br>
            <div class="col-md-6">
              {!! Form::number('bed_no', $room->bed_no, ['class' => 'form-input']); !!}
            </div>
            <div class="col-md-6">
              {!! Form::select('bed_id', $beds , $room->bed_id, ['class' => 'form-input',
                'placeholder' => __('Please Select')]); !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('review', __('Review') . ':') !!}
            {!! Form::number('review', $room->review, ['class' => 'form-input', 'max' => '5']); !!}
        </div>
        <div class="form-group">
            <label for="ctnSelect1">Room Drescription </label>
            <textarea name="description" cols="30" rows="3" autocomplete="off" class="form-input" placeholder="Room Drescription">{!!$room->description!!}</textarea>
        </div>
        <div class="form-group">
            <label for="ctnSelect1">Reserve Condition </label>
            <textarea name="condition" cols="30" rows="3" autocomplete="off" class="form-input" placeholder="Reserve Condition">{!!$room->condition!!}</textarea>
        </div>
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