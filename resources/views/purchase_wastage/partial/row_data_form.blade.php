<input type="hidden" name="location_id" value="1">
                    <input type="hidden" name="status" id="purchase-status">
                        <div class="grid grid-cols-2 gap-5" style="margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <label style="width: 20%;" for="referenceNo">Reference No</label>
                                <input class="form-input"  type="text" id="referenceNo" name="ref_no" readonly value="{{ $transaction->ref_no ?? ''}}">
                            </div>
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <label style="width: 20%;" for="datepicker">Purchase Date <span>*</span></label>
                                <input class="form-input" type="date" id="transaction_date" name="transaction_date" value="{{ isset($transaction) ? date('Y-m-d', strtotime($transaction->transaction_date)) :  date('Y-m-d')}}">
                            </div>
                        </div>
                        <input type="hidden" name="return_id[]" value="" id="purchase_return">
                        <div style="margin-top: 20px;">
                            <div class="customer-table">
                                <table  id="purchase_entry_table" class="whitespace-nowrap">
                                    <thead class="tble-head">
                                        <tr>
                                            <th>SKU</th>
                                            <th>Description</th>
                                            <!-- <th>Purchase Price</th> -->
                                            <th>Quantity</th>
                                            <th>Wastage Quantity</th>
                                            <!-- <th>Discount</th> -->
                                            <!-- <th>Product Val</th> -->
                                            <!-- <th>Line Total</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    @foreach($transaction->lines_of_purchase ?? [] as $line)
                                        @php 
                                        $return_line = 0;
                                        if($line->is_return == 1)
                                        {
                                            $return_line = App\Models\PurchaseReturnLine::where('removed_purchase_line', $line->id)->where('status', 1)->sum('quantity');
                                        }
                                        $quantity = $line->quantity - $return_line;
                                        @endphp
                                        @if($quantity > 0)
                                        <tr data-id="{{$line->id}}">
                                            <input type="hidden" name="purchases[{{$row_count}}][line_id]" value="{{$line->id}}">
                                            <td>
                                                {{ $line->product->sku_code}}
                                            </td>
                                            <td>
                                                {{ $line->product->name}}
                                            </td>
                                            
                                            <!-- <td> -->
                                                {!! Form::hidden('purchases[' . $row_count . '][purchase_price]',
                                                number_format($line->purchase_price, 2, $currency_details->decimal_separator, $currency_details->thousand_separator), ['class' => 'form-input input-sm purchase_cost input_number']); !!}
                                                <!-- <span>{{ number_format($line->purchase_price, 2, $currency_details->decimal_separator, $currency_details->thousand_separator) }}</span> -->
                                            <!-- </td> -->
                                            <!-- <td>
                                                {!! Form::text('purchases[' . $row_count . '][sale_price]',
                                                number_format($line->product->sale_price, 2, $currency_details->decimal_separator, $currency_details->thousand_separator), ['class' => 'form-input input-sm input_number']); !!}
                                            </td> -->
                                            <td>
                                                {!! Form::hidden('purchases[' . $row_count . '][product_id]', $line->product->id ); !!}

                                                @php
                                                    $check_decimal = 'false';
                                                    if($line->product->pur_unit->allow_decimal == 0){
                                                        $check_decimal = 'true';
                                                    }
                                                    
                                                @endphp
                                                {!! Form::hidden('purchases[' . $row_count . '][quantity]', number_format($quantity, 2, $currency_details->decimal_separator, $currency_details->thousand_separator), ['class' => 'form-input input-sm purchase_quantity input_number mousetrap', 'required', 'id' => 'product_quantity_'.$row_count, 'data-rule-abs_digit' => $check_decimal, 'data-msg-abs_digit' => __('lang_v1.decimal_value_not_allowed' ), 'width' => '80%', 'min' => '1', 'max' => $quantity ]); !!}
                                                <span>{{ number_format($quantity, 2, $currency_details->decimal_separator, $currency_details->thousand_separator) }}</span>{{ $line->product->pur_unit->short_code }}
                                            </td>
                                            <td>
                                                {!! Form::text('purchases[' . $row_count . '][quantity_wastage]',
                                                0, ['class' => 'form-input input-sm  purshase_wastage_qty input_number']); !!}
                                                
                                            </td>
                                            <!-- <td>
                                            {!! Form::hidden('purchases[' . $row_count . '][discount_type]',
                                                'percentage', ['class' => 'form-input input-sm product_discount_type input_number']); !!}
                                            {!! Form::text('purchases[' . $row_count . '][discount]',
                                                $line->discount, ['class' => 'form-input input-sm inline_discounts input_number']); !!}
                                            </td> -->
                                            <!-- <td>
                                            {!! Form::number('purchases[' . $row_count . '][product_value]',
                                                null, ['class' => 'form-input input-sm product_value input_number']); !!}
                                            </td> -->
                                            <!-- <td>
                                                <span class="row_subtotal">{{ $line->line_total }}</span>
                                                <input type="hidden" class="row_subtotal_hidden" name="purchases[{{$row_count}}][line_total]" value="{{ $line->line_total }}">
                                            </td> -->
                                            
                                            <?php $row_count++ ;?>
                                        </tr>
                                        @endif
                                    @endforeach
                                    <input type="hidden" id="row_count" value="{{ $row_count }}">
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>

                        <div class="grid grid-cols-2" style="margin-top: 20px; gap: 150px;">
                            <div class="left" style="padding: 20px;">
                            <div>
                                    <label style="width: 40%;" for="returnType">Note</label>
                                    <textarea class="form-input" placeholder="Note..." name="details" id="" cols="30"
                                        rows="4"></textarea>
                                </div>
                                <!-- <div class="grid grid-cols-2">
                                    <div style="align-items: center; gap: 10px; margin-bottom: 10px;width:90%;">
                                        <label for="discountType">Discount Type</label>
                                        <select class="form-select" name="discount_type" id="discount_type">
                                            <option value="percentage">Percentage</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                    </div>
                                    <div style="align-items: center; gap: 20px; margin-bottom: 10px;width:90%;">
                                        {!! Form::label('discount_amount', __( 'Discount Amount' ) . ':') !!}
							            {!! Form::text('discount_amount', 0, ['class' => 'form-input input_number']); !!}
                                    </div>
                                </div>
                                <div class="grid grid-cols-1">    
                                    <div style="align-items: center; gap: 20px; margin-bottom: 10px;width:95%;">
                                        <label style="width: 40%;" for="tax">Tax</label>
                                        <select class="form-select" name="tax_id" id="tax_id">
                                            <option value="" data-tax_amount="0" data-tax_type="fixed" selected>None</option>
                                            @foreach($taxes as $tax)
                                                <option value="{{ $tax->id }}" data-tax_amount="{{ $tax->amount }}" data-tax_type="{{ $tax->calculation_type }}">{{ $tax->name }}</option>
                                            @endforeach
                                        </select>
                                        {!! Form::hidden('tax_amount', 0, ['id' => 'tax_amount']); !!}
                                    </div>
                                </div> -->
                            </div>
                            <div class="right">
                                <!-- <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 10px;">
                                    <label style="width: 30%;" for="returnType">Sub Total</label>
                                    <span id="total_subtotal" class="display_currency"></span>
                                    {!! Form::hidden('final_total', 0 , ['id' => 'grand_total_hidden']); !!}
									<input type="hidden" id="total_subtotal_input" value=0  name="total_before_tax">
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <label style="width: 30%;" for="returnType">Grand Total</label>
                                    <span id="grand_total_text" class="display_currency">0</span>
                                </div> -->
                            </div>
                        </div>
