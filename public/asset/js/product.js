
document.addEventListener("alpine:init", () => {
    Alpine.data("form", () => ({
        showDepartmentDropdown: false,
        showIsForSales: false,
        showIsPurchased: false,
        showIsForCategory: false,
        showSalesBeforeTax: false,
        showSalesPriceIncludingTax: false,
        showOnePurchaseUnit: false,
        ableIsChecked: false,
        showIsEnable:false,
        showIsOpenStock:false,
        selectedOption: "0",
        check: 1,
        openStock: 1,
        openCheck:false,
        disableCuisine: false,
        disableType: false,
        decimal:0,
        variations: [],
        selectedDepartments: [],
        enableChange() {
            if(this.check == 1)
            {
                this.ableIsChecked = true;
                this.showIsEnable = true;
            }
            else
            {
                this.ableIsChecked = false;
                this.showIsEnable = false;
                $('#open_stock').attr('checked', false);
                this.showIsOpenStock = false;
                $('#alert_quantity').val('');
            }
        },
        openChange(){
            if(this.openStock == 1)
            {
                this.showIsOpenStock = true;
                document.getElementById("stock_quantity").required = true;
            }
            else
            {
                this.showIsOpenStock = false;
                $('#stock_quantity').val('');
                document.getElementById("stock_quantity").required = false;
            }
            
        },
        variantChange(index){
            const decimals = $('#variant-select-dd-'+index+' option:selected').data('value');
            if(decimals == 1)
            {
                $('#variant-amount-'+index).prop('disabled', false);
            }
            else {
                $('#variant-amount-'+index).prop('disabled', true);
            }
        },
        
    }));
});
const input = document.getElementById('file-upload');
const previewPhoto = () => {
    const file = input.files;
    if (file) {
        const fileReader = new FileReader();
        const preview = document.getElementById('file-preview');
        fileReader.onload = event => {
            preview.setAttribute('src', event.target.result);
        }
        fileReader.readAsDataURL(file[0]);
    }
}
input.addEventListener('change', previewPhoto);
$(document).ready(function () {
    var searchIDs = [];
    $('#product_add_form input[name="product_attry[]"]:checked').each(function(){
        searchIDs.push($(this).val());
    });
    $(document).on('click', 'button#product-submit-btn', function (e) {  
        e.preventDefault();
        //Check if product attr is present or not.
        
        if ($('#product_add_form input[name="product_attry[]"]:checked').length <= 0) {
            toastr.warning('select any product attry');
            return false;
        }
        if(searchIDs.includes('1'))
        {
            document.getElementById("sale_unit_id").required = true;
            document.getElementById("purchase_unit_id").required = false;
            document.getElementById("alert_quantity").required = true;
            document.getElementById("new_category_id").required = true;
            document.getElementById("brand").required = false;
            document.getElementById("last_purchase_price").required = false;
        }
        if(searchIDs.includes('2'))
        {
            document.getElementById("sale_unit_id").required = false;
            document.getElementById("purchase_unit_id").required = true;
            document.getElementById("alert_quantity").required = false;
            document.getElementById("new_category_id").required = true;
            document.getElementById("brand").required = false;
            document.getElementById("last_purchase_price").required = true;
        }
        if(!searchIDs.includes('1') && !searchIDs.includes('2'))
        {
            
            document.getElementById("sale_unit_id").required = false;
            document.getElementById("purchase_unit_id").required = false;
            document.getElementById("alert_quantity").required = false;
            document.getElementById("new_category_id").required = false;
            document.getElementById("brand").required = false;
            document.getElementById("last_purchase_price").required = false;
        }
        $('form#product_add_form').validate({
            rules: {
                name: "required",
                category_id: "required",
                // brand_id: "required",
                alert_quantity: "required",
            },
            messages: {
                name: "Required Field",
                category_id: "Required Field",
                // brand_id: "Required Field",
                alert_quantity: "Required Field",
            }
        });
        if ($('form#product_add_form').valid()) {
            $('form#product_add_form').submit();
        }
    });
    
    $(document).on('change', '#new_category_id', function () {
        var val = $(this).val();
        get_sub_categories(val);
    });
    function get_sub_categories(cat) {
        $.ajax({
            method: "POST",
            url: '/rest/product/sub-category',
            dataType: "html",
            data: { 'cat_id': cat },
            success: function (result) {
                if (result) {
                    $('#new_sub_category_id').html(result);
                }
            }
        });
    }

    var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
        removeItemButton: true,
        maxItemCount:5,
        searchResultLimit:5,
        renderChoiceLimit:5
    });
});