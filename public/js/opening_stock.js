$(document).ready( function() {
	$(document).on('change', '.purchase_quantity', function(){
		update_table_total( $(this).closest('table'));
	});
	$(document).on('change', '.unit_price', function(){
		update_table_total( $(this).closest('table'));
	});

	$('.os_exp_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        clearBtn: true
    });

	$(document).on('click', '.add_stock_row', function(){
    	var tr = $(this).data('row-html');
    	var key = parseInt($(this).data('sub-key'));
    	tr = tr.replace(/\__subkey__/g, key);
    	$(this).data('sub-key', key+1);

    	$(tr).insertAfter($(this).closest('tr')).find('.os_exp_date').datepicker({
	        autoclose: true,
	        format: 'dd-mm-yyyy',
	        clearBtn: true
	    });
    });

    $(document).on( 'click', '.add-opening-stock', function(e){
    	e.preventDefault();
    	$.ajax({
			url: $(this).data("href"),
			dataType: "html",
			success: function(result){
				$('#opening_stock_modal').html(result).modal('show');
			}
		});
    });

	//Re-initialize data picker on modal opening
    $('#opening_stock_modal').off().on('shown.bs.modal', function (e) {
    	$('.os_exp_date').datepicker({
	        autoclose: true,
	        format: 'dd-mm-yyyy',
	        clearBtn: true
	    });

	    $(document).on('click', 'button#add_opening_stock_btn', function(e){
			e.preventDefault();
			$('button#add_opening_stock_btn').attr('disabled', true)
			var data = $('form#add_opening_stock_form').serialize();

			$.ajax({
				method: "POST",
				url: $('form#add_opening_stock_form').attr("action"),
				dataType: "json",
				data: data,
				success: function(result){
					if(result.success == true){
						$('#opening_stock_modal').modal('hide');
						window.location.reload();
						toastr.success(result.msg);
					} else {
						toastr.error(result.msg);
					}
				}
			});
		});
    });
});

function update_table_total(table){
	var total_subtotal = 0;
	table.find('tbody tr').each( function(){
		var qty = __read_number($(this).find('.purchase_quantity'));
		var unit_price = __read_number($(this).find('.unit_price'));
		var row_subtotal = qty * unit_price;
		$(this).find('.row_subtotal_before_tax').text(__number_f(row_subtotal));
		total_subtotal += row_subtotal
	});
	table.find('tfoot tr #total_subtotal').text(__currency_trans_from_en(total_subtotal, true));
	table.find('tfoot tr #total_subtotal_hidden').val(total_subtotal);

}
$(document).on('click', 'button#open-AddBookDialog', function(){
    $('#serial_modal').modal('show');
	var id = $(this).data('id');
    var row = $(this).data('row');
    var count = $("#product_quantity_"+ row ).val();
    var oldValues = $("#serial-nums-"+ id).val();
    $('input[name=hidden-tags]').val(oldValues);
    $('input[name=tags] + p').remove();
    $('input[name=tags]').val('');
    $("#serial_modal .model-body #product-id").val(id);
    $('button#submit_serial_number').attr('id','submit_serial_number-'+id);
    $(document).on('click', 'button#submit_serial_number-'+id, function(){
         var seialNO = $('input[name=hidden-tags]').val();
        $("#serial-nums-"+ id).val(seialNO);
        $.ajax({
            type : 'POST',
            data : {
                product_id : id,
                serial_no : seialNO,
                count:  count,
            },
            url: $(this).data("href"),
            success:function(e){
                $('button#submit_serial_number-'+id).attr('id','submit_serial_number');
                toastr.success('Successfully, Updated').fadeOut('2000');
                $('#open-AddBookDialog').data('code',seialNO);
                $('#serial_modal').modal('hide');
				var old_cound = $('input.purchase_quantity').val();
				const total = parseInt(old_cound) + parseInt(e);
				$('input.purchase_quantity').val(total);
            },
            error:function(w){
                if(w.responseJSON.errors.serial_no){

                    $('input[name=tags]').after('<p class="error">'+w.responseJSON.errors.serial_no[0]+'</p>');

                }
            }
        });
    });
});

$(".tm-input").tagsManager();