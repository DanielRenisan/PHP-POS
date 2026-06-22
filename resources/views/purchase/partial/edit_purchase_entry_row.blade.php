<div class="customer-table">
    <table  id="purchase_entry_table" class="whitespace-nowrap">
        <thead class="tble-head">
            <tr>
                <th>No</th>
                <th>SKU</th>
                <th>Description</th>
                <th>Purchase Price</th>
                <th>Sale Price</th>
                <th>Quantity</th>
                <th>Discount</th>
                <!-- <th>Product Val</th> -->
                <th>Line Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php $row_count = 0; ?>
        @php
        $productId = $purchase->lines_of_purchase->pluck('product_id')->toArray();
        $productIds = implode(',', $productId)
        @endphp
        {!! Form::hidden('exit_product_id', $productIds, ['id' => 'exit_product_id']); !!}
        @foreach($purchase->lines_of_purchase as $purchase_line)
        <tr>
        {!! Form::hidden('purchases[' . $loop->index . '][purchase_line_id]',
                $purchase_line->id); !!}
            <td><span class="sr_number"></span></td>
            <td>
                {{ $purchase_line->product->sku_code}}
            </td>
            <td>
                {{ $purchase_line->product->name}}
            </td>
            
            <td>
                {!! Form::text('purchases[' . $loop->index . '][purchase_price]',
                number_format($purchase_line->purchase_price, 2, $currency_details->decimal_separator, $currency_details->thousand_separator), ['class' => 'form-input input-sm purchase_cost input_number']); !!}
            </td>
            <td>
                {!! Form::text('purchases[' . $loop->index . '][sale_price]',
                number_format($purchase_line->product->sale_price, 2, $currency_details->decimal_separator, $currency_details->thousand_separator), ['class' => 'form-input input-sm input_number']); !!}
            </td>
            <td>
                {!! Form::hidden('purchases[' . $loop->index . '][product_id]', $purchase_line->product->id ); !!}

                @php
                    $check_decimal = 'false';
                    if($purchase_line->product->pur_unit->allow_decimal == 0){
                        $check_decimal = 'true';
                    }
                @endphp
                {!! Form::text('purchases[' . $loop->index . '][quantity]', number_format($purchase_line->quantity, 2, $currency_details->decimal_separator, $currency_details->thousand_separator), ['class' => 'form-input input-sm purchase_quantity input_number mousetrap', 'required', 'id' => 'product_quantity_'.$loop->index, 'data-rule-abs_digit' => $check_decimal, 'data-msg-abs_digit' => __('lang_v1.decimal_value_not_allowed' )]); !!}
            </td>
            <td>
            {!! Form::hidden('purchases[' . $loop->index . '][discount_type]',
                'percentage', ['class' => 'form-input input-sm product_discount_type input_number']); !!}
            {!! Form::text('purchases[' . $loop->index . '][discount]',
                number_format($purchase_line->discount, 2, $currency_details->decimal_separator, $currency_details->thousand_separator), ['class' => 'form-input input-sm inline_discounts input_number']); !!}
            </td>
            <!-- <td>
            {!! Form::number('purchases[' . $row_count . '][product_value]',
                null, ['class' => 'form-input input-sm product_value input_number']); !!}
            </td> -->
            <td>
                <span class="row_subtotal">{{number_format($purchase_line->line_total, 2, $currency_details->decimal_separator, $currency_details->thousand_separator)}}</span>
                <input type="hidden" class="row_subtotal_hidden" name="purchases[{{$loop->index}}][line_total]" value="{{$purchase_line->line_total}}">
            </td>

            <td><i class="fa fa-times remove_purchase_entry_row text-danger" title="Remove" style="cursor:pointer;"></i></td>
        </tr>
        <?php $row_count = $loop->index + 1 ; ?>
        @endforeach
        </tbody>
    </table>
</div>
<input type="hidden" id="row_count" value="{{ $row_count }}">