<tr>
        <td><span class="sr_number"></span></td>
        <td>
            {{ $product->sku_code}}
        </td>
        <td>
            {{ $product->name}}
        </td>
        
        <td>
            {!! Form::text('purchases[' . $row_count . '][purchase_price]',
            number_format($product->last_purchase_price, 2, $currency_details->decimal_separator, $currency_details->thousand_separator), ['class' => 'form-input input-sm purchase_cost input_number']); !!}
        </td>
        <td>
            {!! Form::text('purchases[' . $row_count . '][sale_price]',
            number_format($product->sale_price, 2, $currency_details->decimal_separator, $currency_details->thousand_separator), ['class' => 'form-input input-sm input_number']); !!}
        </td>
        <td>
            {!! Form::hidden('purchases[' . $row_count . '][product_id]', $product->id ); !!}

            @php
                $check_decimal = 'false';
                if($product->pur_unit->allow_decimal == 0){
                    $check_decimal = 'true';
                }
            @endphp
            {!! Form::text('purchases[' . $row_count . '][quantity]', number_format(1, 2, $currency_details->decimal_separator, $currency_details->thousand_separator), ['class' => 'form-input input-sm purchase_quantity input_number mousetrap', 'required', 'id' => 'product_quantity_'.$row_count, 'data-rule-abs_digit' => $check_decimal, 'data-msg-abs_digit' => __('lang_v1.decimal_value_not_allowed' ), 'width' => '80%']); !!}{{ $product->pur_unit->short_code }}
        </td>
        <td>
        {!! Form::hidden('purchases[' . $row_count . '][discount_type]',
            'percentage', ['class' => 'form-input input-sm product_discount_type input_number']); !!}
        {!! Form::text('purchases[' . $row_count . '][discount]',
            null, ['class' => 'form-input input-sm inline_discounts input_number']); !!}
        </td>
        <!-- <td>
        {!! Form::number('purchases[' . $row_count . '][product_value]',
            null, ['class' => 'form-input input-sm product_value input_number']); !!}
        </td> -->
        <td>
            <span class="row_subtotal">0</span>
            <input type="hidden" class="row_subtotal_hidden" name="purchases[{{$row_count}}][line_total]" value=0>
        </td>
        
        <?php $row_count++ ;?>

        <td><i class="fa fa-times remove_purchase_entry_row text-danger" title="Remove" style="cursor:pointer;"></i></td>
    </tr>

<input type="hidden" id="row_count" value="{{ $row_count }}">