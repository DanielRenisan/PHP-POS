<div class="modal-dialog" role="document">
	<div class="modal-content">
    {!! Form::open(['url' => action('CheckinController@postExpense', $transaction->id), 'method' => 'post', 'id' => 'guest_add_form' ]) !!} 
    <input type="hidden" name="contact_id" value="{{isset($booking) ? $booking->contact_id : '' }}">
    <input type="hidden" name="room_no" value="{{isset($room) ? $room->room_no : '' }}">
		<div class="modal-header">
		    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      <h4 class="modal-title" id="modalTitle">{{$booking->ref_no}}</h4>
	    </div>
	    <div class="modal-body">
            <div class="mb-6 grid gap-6">
                <div class="panel h-full">
                    <div class="mb-5 flex items-center">
                        <h4>Room Expense</h4>
                    </div>
                    <div class="overflow-hidden">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('invoice_no', __('Reference No:').':') !!}
                                    {!! Form::text('invoice_no', isset($expense) && $expense->invoice_no ? $expense->invoice_no : '', ['class' => 'form-control']); !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ctnSelect1">Date </label>
                                    <div x-data="form">
                                        <input id="basic" x-model="date1" class="form-input flatpickr-input active" name="transaction_date" type="text" readonly="readonly" value="{{isset($expense) ? $expense->transaction_date : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ctnSelect1">Category</label>
                                    {!! Form::select('category_id', $categories , isset($expense) && $expense->category_id ? $expense->category_id : null, ['class' => 'form-input', 'id' => 'seachable-category',
                                    'placeholder' => __('Choose Category'), 'required']); !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ctnSelect1">Sub Category</label>
                                    {!! Form::select('sub_category_id', [] , isset($expense) && $expense->sub_category_id ? $expense->sub_category_id : null, ['class' => 'form-input', 'id' => 'seachable-sub-cate',
                                    'placeholder' => __('Sub Category')]); !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('quantity', 'Qty *') !!}
                                    {!! Form::number('quantity', isset($expense) && $expense->quantity ? $expense->quantity : null, ['class' => 'form-control', 'id' => 'quantity' , 'required']); !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('final_total', 'Amount *') !!}
                                    {!! Form::number('final_total', isset($expense) && $expense->final_total ? $expense->final_total : null, ['class' => 'form-control', 'id' => 'final_amount' , 'required']); !!}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('details', 'Note') !!}
                                            {!! Form::textarea('details', isset($expense) && $expense->details ? $expense->details : null, ['class' => 'form-control', 'rows' => 3, 'id' => 'details']); !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">SAVE</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
        </div>

    {!! Form::close() !!}
    </div>
</div>
<script src="{{ asset('assets/js/alpine-collaspe.min.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/alpine-persist.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-ui.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-focus.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine.min.js?v=' . $asset_v) }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/nice-select2.css?v='.$asset_v) }}">
<script src="{{ asset('assets/js/nice-select2.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(e) {
            // seachable 
            var options = {
                searchable: true
            };
            NiceSelect.bind(document.getElementById("seachable-category"), options);
            NiceSelect.bind(document.getElementById("seachable-sub-cate"), options);
        });
        document.addEventListener("alpine:init", () => {
            Alpine.data("form", () => ({
                date1: new Date().toISOString().substr(0, 10),
                init() {
                    flatpickr(document.getElementById('basic'), {
                        dateFormat: 'Y-m-d',
                        defaultDate: this.date1,
                    })
                }
            }));
        });
        $('#seachable-category').change(function () {
		        get_sub_categories();
            });
            function get_sub_categories() {
                var cat = $('#seachable-category').val();
                $.ajax({
                    method: "POST",
                    url: '/expenses/get-sub-category',
                    dataType: "html",
                    data: { 'cat_id': cat },
                    success: function (result) {
                        if (result) {
                            $('#seachable-sub-cate').html(result);
                        }
                    }
                });
            }
   
    </script>