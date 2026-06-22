<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('WakeUpController@store'), 'method' => 'post', 'id' => 'call_add_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Add Wake Up Call</h4>
    </div>

    <div class="modal-body">
        <div class="form-group">
            {!! Form::label('customer_id', __('Customer') . ':*') !!}
            {!! Form::select('customer_id', $customers, null, ['class' => 'form-input', 'id'=>'seachable-select',
                'placeholder' => __('Select Customer'),
                'required']); !!}
        </div>

        <div class="form-group">
            <label for="ctnSelect1">Wake Up At</label>
            <div x-data="form">
                <input id="dateTime" x-model="date2" class="form-input flatpickr-input active" name="wake_up_at" type="text" readonly="readonly" value="">
            </div>
        </div>
        <div class="form-group">
            <label for="ctnSelect1">Remarks</label>
            <textarea name="remarks" cols="30" rows="3" autocomplete="off" class="form-input" placeholder="Remarks"></textarea>
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
<script src="{{ asset('assets/js/alpine-collaspe.min.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/alpine-persist.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-ui.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-focus.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine.min.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/nice-select2.js?v=' . $asset_v) }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function(e) {
        // seachable 
        var options = {
            searchable: true
        };
        NiceSelect.bind(document.getElementById("seachable-select"), options);
    });
    document.addEventListener("alpine:init", () => {
        Alpine.data("form", () => ({
            date2: '2022-07-05 12:00',
            init() {
                flatpickr(document.getElementById('dateTime'), {
                    defaultDate: this.date2,
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i'
                })
            }
        }));
    });
</script>