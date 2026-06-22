@extends('layouts.app')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li>
                <a href="{{action('TransactionController@index')}}" class="text-primary hover:underline">Purchases</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Add Purchase</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                </div>
                <div class="mb-5">
                {!! Form::open(['url' => action('TransactionController@store'), 'method' => 'post', 
'id' => 'transaction_add_form','class' => 'transaction_form', 'files' => true ]) !!}
                        <input type="hidden" name="transaction_id" value="{{isset($transaction) ? $transaction->id : '' }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('contact_id', __('Supliers') . ':*') !!}
                                {!! Form::select('contact_id', $suppliers, isset($transaction) ? $transaction->contact_id : '', ['class' => 'form-input', 'id'=>'seachable-select',
                                    'placeholder' => __('Please Select One'),
                                    'required']); !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">Invoice No <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Invoice No" class="form-input" name="invoice_no" required="required" value="{{isset($transaction) ? $transaction->invoice_no : '' }}">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">Purchase Date </label>
                                <div x-data="form">
                                    <input id="basic-tra" x-model="date1" class="form-input flatpickr-input active" name="transaction_date" type="text" readonly="readonly" value="{{isset($transaction) ? $transaction->transaction_date : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">Expiry Date </label>
                                <div x-data="form">
                                    <input id="basic-date" x-model="date1" class="form-input flatpickr-input active" name="expiry_date" type="text" readonly="readonly" value="{{isset($transaction) ? $transaction->expiry_date : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-bordered table-th-green text-center table-striped" id="item-table">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Sub Total</th>
                                                <th></th>
                                            </tr>         
                                        </thead>
                                        <thead>
                                        <tbody class="input_fields">
                                            <tr class="row_set">
                                                <td><input type="text" placeholder="item description" class="form-input" name="items[0][item]" required="required" value=""></td>
                                                <td> <input type="number" class="form-input item_quantity input_number" name="items[0][quantity]" required="required" value="1"></td>
                                                <td> <input type="text" class="form-input item_unit_price input_number" name="items[0][unit_price]" required="required" value="0.00"></td>
                                                <td>
                                                <span class="row_subtotal_text display_currency">0</span>
                                                <input type="hidden" class="row_subtotal_input_hidden" value=0 name="items[0][sub_total]"> 
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                        
                                    </table>
                                    <table>
                                        <tfoot>
                                            <tr>
                                                <td><button type="button" class="btn btn-primary rounded-pill add-more-btn" style="color:white;">Add More Item</button></td>
                                                <td colspan="3">Total</td>
                                                <td>
                                                    <span id="total_subtotal" class="display_currency">0.00</span>
                                                <!-- This is total before purchase tax-->
                                                    <input type="hidden" id="total_subtotal_input" value=0  name="final_total">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>  
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ctnSelect1">Details</label>
                                <textarea name="details" cols="30" rows="3" autocomplete="off" class="form-input" placeholder="Details">{{isset($transaction) ? $transaction->details : '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary mt-6">{{isset($transaction) ? 'UPDATE' : 'CREATE' }}</button>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('assets/js/alpine-collaspe.min.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/alpine-persist.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-ui.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-focus.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine.min.js?v=' . $asset_v) }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/nice-select2.css?v='.$asset_v) }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/file-upload-with-preview.min.css?v='.$asset_v) }}">
<script src="{{ asset('assets/js/nice-select2.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/file-upload-with-preview.iife.js?v=' . $asset_v) }}"></script>
    
    <script type="text/javascript">
document.addEventListener("DOMContentLoaded", function(e) {
        // seachable 
        var options = {
            searchable: true
        };
        NiceSelect.bind(document.getElementById("seachable-select"), options);
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

    document.addEventListener("alpine:init", () => {
        Alpine.data("form", () => ({
            date1: new Date().toISOString().substr(0, 10),
            init() {
                flatpickr(document.getElementById('basic-date'), {
                    dateFormat: 'Y-m-d',
                    defaultDate: this.date1,
                })
            }
        }));
    });
    var wrapper = $(".input_fields_wrap");
    var index = $(".row_set").length;
    $('.add-more-btn').on('click', function(){
        $('#item-table > tbody:last-child').append(
            '<tr>'+
            '<td> <input type="text" placeholder="item description" class="form-input" name="items['+index+'][item]" required="required" value=""></td>'+
            '<td> <input type="number" class="form-input  item_quantity input_number" name="items['+index+'][quantity]" required="required" value="1"></td>'+
            '<td> <input type="text" class="form-input item_unit_price input_number" name="items['+index+'][unit_price]" required="required" value="0.00"></td>'+
            '<td>'+
            '<span class="row_subtotal_text display_currency">0</span>'+
            '<input type="hidden" class="row_subtotal_input_hidden" value="0" name="items['+index+'][sub_total]"> </td>'+
            '<td><button type="button" class="btn btn-danger rounded-pill remove_field" style="color:white;">X</button></td>'+
            '</tr>'
        );
        index ++;
    });
    $(document).on("click", ".remove_field", function(e) { 
            e.preventDefault();
            $(this).parent('td').parent('tr').remove();
        })

        $('table#item-table tbody').on('change', 'input.item_quantity', function () {
            var entered_qty = __read_number($(this));

            var tr = $(this).parents('tr');

            var unit_price = __read_number(tr.find('input.item_unit_price'));
            var line_total = entered_qty * unit_price;

            __write_number(tr.find('input.row_subtotal_input_hidden'), line_total, false, 2);
            tr.find('span.row_subtotal_text').text(__currency_trans_from_en(line_total, true));
            update_table_total();
        });

        $('table#item-table tbody').on('change', 'input.item_unit_price', function () {
            var unit_price = __read_number($(this));
            var tr = $(this).parents('tr');

            var entered_qty = __read_number(tr.find('input.item_quantity'));
            var line_total = entered_qty * unit_price;

            __write_number(tr.find('input.row_subtotal_input_hidden'), line_total, false, 2);
            tr.find('span.row_subtotal_text').text(__currency_trans_from_en(line_total, true));
            update_table_total();
        });

        function update_table_total() {
            var total_subtotal = 0;

            $('#item-table tbody').find('tr').each(function () {
                total_subtotal += __read_number($(this).find('input.row_subtotal_input_hidden'));
            });
            $('span#total_subtotal').text(__currency_trans_from_en(total_subtotal, true));
            __write_number($('input#total_subtotal_input'), total_subtotal,false);
        }
    </script>
@endsection