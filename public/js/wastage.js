$(document).ready(function () {
    if ($("#search_ref").length > 0) {
        $("#search_ref").autocomplete({
            source: "/returns/get_purchase",
            minLength: 2,
            response: function (event, ui) {
                if (ui.content.length == 1) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                } else if (ui.content.length == 0) {
                    var term = $(this).data('ui-autocomplete').term;
                    swal({
                        title: 'No purchase Fount',
                        text: __translate('add_name_as_new_product', { 'term': term }),
                        buttons: [LANG.cancel, LANG.ok]
                    });
                }
            },
            select: function (event, ui) {
                $(this).val(null);
                get_purchase_entry_row(ui.item.product_id);
            }
        })
            .autocomplete("instance")._renderItem = function (ul, item) {
                return $("<li>").append("<div>" + item.text + "</div>").appendTo(ul);
            };
    }
    function get_purchase_entry_row(product_id) {
        $('table#purchase_entry_table tbody').empty().append('');
        if (product_id) {
            var row_count = $('#row_count').val();
            $.ajax({
                method: "POST",
                url: '/get-wastahe/purchase-row',
                dataType: "html",
                data: { 'transaction_id': product_id, 'row_count': row_count },
                success: function (result) {
                    $(result).find('.purchase_quantity').each(function () {

                        row = $(this).closest('tr');
                        $('table#purchase_entry_table tbody').append(update_purchase_entry_row_values(row));
                        // update_row_price_for_exchange_rate(row);

                        // update_inline_profit_percentage(row);

                        // update_table_total();
                        // update_grand_total();
                        // update_table_sr_number();
                    });
                    if ($(result).find('.purchase_quantity').length) {
                        $('#row_count').val($(result).find('.purchase_quantity').length + parseInt(row_count));
                    }
                }
            });

            $.ajax({
                method: "POST",
                url: '/get-transaction-data',
                dataType: "json",
                data: { 'transaction_id': product_id},
                success: function (result) {
                    $('#referenceNo').val(result.ref_no);
                    $('#referenceNo').val(result.ref_no)
                   console.log(result)
                }
            });
        }
    }

    function update_purchase_entry_row_values(row) {
        if (typeof row != 'undefined') {
    
            var quantity = __read_number(row.find('.purchase_quantity'), true);
            var unit_cost_price = __read_number(row.find('.purchase_cost'), true);
            var row_subtotal_before_tax = quantity * unit_cost_price;
            
            row.find('span.row_subtotal').text(__currency_trans_from_en(row_subtotal_before_tax, false, true));
            __write_number(row.find('input.row_subtotal_hidden'), row_subtotal_before_tax, true);
    
            return row;
        }
    }

    var searchIDs = [];
    $(document).on('change', '#check-box', function(){
        if($(this).is(":checked")) 
        {
            searchIDs.push($(this).val());
        }
        else
        {
            searchIDs.splice(searchIDs.indexOf($(this).val()), 1)
        }
        $('#purchase_return').val(searchIDs);

        calculate_total_amount($(this).val());
    });

    $(document).on('click', 'button#submit_purchase_return', function (e) {  
        e.preventDefault();
        //Check if product attr is present or not.
        
        // if (searchIDs.length == 0) {
        //     toastr.warning('select return product');
        //     return false;
        // }

        $('form#add_purchase_return_form').submit();
    });
    var total = 0;
    function calculate_total_amount(id)
    {
        console.log(searchIDs.indexOf(id));
        var row = $("tr[data-id='" + id + "']:first");
        var line_total = __read_number(row.find('.row_subtotal_hidden'), true);
        if(searchIDs.indexOf(id) != -1)
        {
            
    
            total += line_total;
            $('span#total_subtotal_return').text(__currency_trans_from_en(total, true, true));
            __write_number($('input#grand_total_hidden_return'), total, true);
            __write_number($('input#total_subtotal_input_return'), total, true);
            $('span#grand_total_text_return').text(__currency_trans_from_en(total, true, true));
        }
        else
        {
            total = total - line_total;
            $('span#total_subtotal_return').text(__currency_trans_from_en(total, true, true));
            __write_number($('input#grand_total_hidden_return'), total, true);
            __write_number($('input#total_subtotal_input_return'), total, true);
            $('span#grand_total_text_return').text(__currency_trans_from_en(total, true, true));
        }
    }

    $(document).on('change', '.purshase_wastage_qty', function () {

        var row = $(this).closest('tr');
        var quantity = __read_number($(this), true);
        var purchase_before_discount = __read_number(row.find('input.purchase_cost'), true);
        var discount_percent = __read_number(row.find('input.inline_discounts'), true);

        //Calculations.
        var purchase_before_tax = parseFloat(purchase_before_discount) - __calculate_amount('percentage', discount_percent, purchase_before_discount);
        //Calculate sub totals
        var sub_total_before_tax = quantity * purchase_before_tax ;

        console.log(sub_total_before_tax);

        row.find('.row_subtotal').text(__currency_trans_from_en(sub_total_before_tax, false, true));
        __write_number(row.find('input.row_subtotal_hidden'), sub_total_before_tax, true);

        update_table_total();
        update_grand_total();
    });
});