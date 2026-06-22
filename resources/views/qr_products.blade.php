<!DOCTYPE html>
<html lang="en" dir="ltr">

<!-- Mirrored from html.vristo.sbthemes.com/apps-invoice-edit.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 19 Oct 2023 05:06:07 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="favicon.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('asset/css/perfect-scrollbar.min.css')}}" />
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('asset/css/style.css')}}" />
    <link defer rel="stylesheet" type="text/css" media="screen" href="{{asset('asset/css/animate.css')}}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="{{asset('asset/js/perfect-scrollbar.min.js')}}"></script>
    <script defer src="{{asset('asset/js/popper.min.js')}}"></script>
    <script defer src="{{asset('asset/js/tippy-bundle.umd.min.js')}}"></script>
    <script defer src="{{asset('asset/js/sweetalert.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('asset/css/pos.css')}}"/>
</head>
<body class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased"
    :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ?  'dark' : '', $store.app.menu, $store.app.layout,$store.app.rtlClass]">
    <div class="main-container min-h-screen text-black dark:text-white-dark" :class="[$store.app.navbar]">
        <div x-data="form">
            <div class="main-content flex flex-col min-h-screen no-screen" style="margin-left: 0 !important;">
                @include('qr.header')

                <div class="animate__animated no-screen" :class="[$store.app.animation]"
                    style="max-height: 100vh; overflow: hidden; padding: 5px">

                    @include('qr.body')
                </div>
            </div>
		</div>
        <section class="invoice print_section" id="receipt_section">
        </section>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('asset/js/alpine-collaspe.min.js')}}"></script>
    <script src="{{asset('asset/js/alpine-persist.min.js')}}"></script>
    <script defer src="{{asset('asset/js/alpine-ui.min.js')}}"></script>
    <script defer src="{{asset('asset/js/alpine-focus.min.js')}}"></script>
    <script defer src="{{asset('asset/js/alpine.min.js')}}"></script>
    <script src="{{asset('asset/js/custom.js')}}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <!-- jQuery 2.2.3 -->
<script src="{{ asset('AdminLTE/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <script>
        var socket = null;
    var socket_host = 'ws://127.0.0.1:6441';
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    initializeSocket = function() {
        try {
            if(socket == null){
                socket = new WebSocket(socket_host);
                socket.onopen = function () {
                    
                };
                socket.onmessage = function (msg) {
                    
                };
                socket.onclose = function () {
                    socket = null;
                };
            }
        } catch (e) {
            console.log(e);
        }
    }
        localStorage.setItem("selectedDepartment", '');
        localStorage.setItem("settings", '{{$business->printer_display}}')
        localStorage.setItem("selectedTable", '');
        localStorage.setItem("selectedCustomer", '{{request()->get('customer_id')}}');
        
        $('#hold-btn-invoice').prop('disabled', false);
        $('#order-btn-invoice').prop('disabled', false);
        showMessage = (
            msg = "Example notification text.",
            position = "top-end",
            showCloseButton = true,
            duration = 3000
            ) => {
            const toast = window.Swal.mixin({
                toast: true,
                position: position || "top-end",
                showConfirmButton: false,
                timer: duration,
                showCloseButton: showCloseButton,
                customClass: {
                container: "custom-toast-container",
                title: "custom-toast-title",
                },
            });
            toast.fire({
                title: msg,
            });
        };
        document.addEventListener("alpine:init", () => {
        Alpine.data("form", () => ({
            selectedOrderType: null,
            selectedIsInclude: 0,
            seviceCharge: 0,
            buttonDisabled: false,
            productDisabled: false,
            cashPopUp :false,
            productPopUp :false,
            closePopUp :false,
            note: '',
            sellNote: '',
            // $products
            categories: <?php echo $categories; ?>,
            tableData: <?php echo $products; ?>,
            canceledData: [],
            isActive: false,
            
            cuisines: [],
            brands: [],
            menus: <?php echo $menus; ?>,
            buttons: [
                {
                    name: "Quantity",
                    icon: "fas fa-plus",
                    shortcut: "q",
                    id: "",
                },
                {
                    name: "Remove",
                    icon: "fas fa-trash-alt",
                    shortcut: "",
                    id: "",
                },
                {
                    name: "Stock",
                    icon: "fas fa-box",
                    shortcut: "",
                    id: "",
                },
                {
                    name: "Hold Invoice",
                    icon: "far fa-hand-paper",
                    shortcut: "",
                    id: "hold-btn-invoice",
                },
                {
                    name: "Pick Held Invoice",
                    icon: "far fa-hand-rock",
                    shortcut: "",
                    id: "",
                },
                {
                    name: "Cancel Invoice",
                    icon: "fas fa-ban",
                    shortcut: "",
                    id: "",
                },
                {
                    name: "Void Invoice",
                    icon: "fas fa-ban",
                    shortcut: "",
                    id: "",
                },
                {
                    name: "Add Customer",
                    icon: "fas fa-user",
                    shortcut: "",
                    id: "",
                },
                {
                    name: "Discount Percentage",
                    icon: "fas fa-percent",
                    id: "",
                },
                {
                    name: "Discount Ammount",
                    icon: "fas fa-money-bill-wave",
                    id: "",
                },
                {
                    name: "Tax",
                    icon: "fas fa-dollar-sign",
                    id: "",
                },
                {
                     name: "Order",
                     icon: "fas fa-shopping-cart",
                     id: "order-btn-invoice",
                },
                // {
                //     name: "Refunds",
                //     icon: "fas fa-hand-holding-usd",
                // },
                // {
                //     name: "Returns",
                //     icon: "fas fa-undo-alt",
                // },
                // {
                //     name: "Gift Cards",
                //     icon: "fas fa-gift",
                // },
            ],

            stocks: [],
            dineInButtons: [],

            roomsButtons: [],

            invoiceList: [
            { id: 1, no: "Invoice No 1" },
            { id: 2, no: "Invoice No 2" },
            { id: 3, no: "Invoice No 3" },
            { id: 4, no: "Invoice No 4" },
            { id: 5, no: "Invoice No 5" },
            { id: 6, no: "Invoice No 5" },
            { id: 7, no: "Invoice No 5" },
            { id: 8, no: "Invoice No 5" },
            { id: 9, no: "Invoice No 5" },
            { id: 10, no: "Invoice No 5" },
            { id: 11, no: "Invoice No 5" },
            { id: 12, no: "Invoice No 5" },
            { id: 13, no: "Invoice No 5" },
            { id: 14, no: "Invoice No 5" },
            { id: 15, no: "Invoice No 5" },
            { id: 17, no: "Invoice No 5" },
            { id: 18, no: "Invoice No 5" },
            { id: 19, no: "Invoice No 5" },
            ],


            // Add the event listener
            keydownHandler(event) {
            const shortcutKey = event.key;
            const matchingButton = this.buttons.find(
                (button) => button.shortcut === shortcutKey
            );

            // if (matchingButton) {
            //     this.togglePopup(matchingButton.name);
            // }
            },

            updateSelectedProductQty() {
            console.log("update works");
            if (this.selectedProduct !== null) {
                const newQty = this.myProducts[this.selectedProduct].qty;
                this.updateQuantity(this.selectedProduct, newQty);
            }
            this.quantityPopup = null; // Close the quantity popup after updating
            },

            // Function to update quantity in myProducts array
            updateQuantity(index, newQty) {
            this.myProducts[index].qty = newQty;
            },

            confirmRemove() {
            if (this.selectedProduct !== null) {
                this.deleteRow(this.selectedProduct);
                this.activePopup = null; // Close the remove popup after removing the product
            }
            },

            selectedProduct: null,
            selectProduct(index) {
            this.selectedProduct = index;
            },
            showSubcategories: false,
            selectedCategory: null, // This will store the selected category
            selectedSubcategory: null, // This will store the selected subcategory
            selectedCuisine: null, // This will store the selected cuisine
            selectedBrand: null, // This will store the selected Brand
            searchTerm: "",
            filteredProducts() {
                let filteredProducts = this.tableData;

                if (this.selectedCategory) {
                    filteredProducts = filteredProducts.filter(
                    (product) => product.category === this.selectedCategory
                    );
                }

                if (this.selectedSubcategory) {
                    filteredProducts = filteredProducts.filter(
                    (product) => product.subCategory === this.selectedSubcategory
                    );
                }

                if (this.selectedCuisine) {
                    filteredProducts = filteredProducts.filter(
                    (product) => product.brand === this.selectedCuisine
                    );
                }

                if (this.selectedBrand) {
                    filteredProducts = filteredProducts.filter(
                    (product) => product.menu === this.selectedBrand
                    );
                }

                // Filter by search term
                if (this.searchTerm.trim() !== "") {
                    filteredProducts = this.searchProducts(
                    this.searchTerm.trim(),
                    filteredProducts
                    );
                }

                return filteredProducts;
            },
            resetSelected() {
                this.selectedSubcategory = null;
                this.selectedCuisine = null;
                this.selectedBrand = null;
            },
            addedProduct: null,
            addNewProduct(product) {
                this.addedProduct = product;
                this.productPopUp = true;
            },

            selectCategory(categoryName) {
                // Close the subcategories of the previously selected category
                if (this.selectedCategory !== categoryName) {
                    this.selectedSubcategory = null;
                    this.showSubcategories = false;
                }

                // Toggle selection of the current category
                this.selectedCategory =
                    this.selectedCategory === categoryName ? null : categoryName;
            },

            selectSubcategory(subcategoryName) {
                this.selectedSubcategory =
                this.selectedSubcategory === subcategoryName ? null : subcategoryName;
            },
            selectCuisine(cuisineName) {
                this.selectedCuisine =
                this.selectedCuisine === cuisineName ? null : cuisineName;
                this.selectedCategory = null;
                this.selectedBrand = null;
            },
            selectBrand(brandName) {
                this.selectedBrand = this.selectedBrand === brandName ? null : brandName;
                this.selectedCategory = null;
                this.selectedCuisine = null;
            },   
            onDepartmentChange(event) {
                this.selectedDepart = event.target.value;
                const href = "{{ route('department.product') }}";
                const emp_url = "{{ route('department.employee') }}";
                const table_url = "{{ route('table.department') }}";
                var product_datas = null;
                var table_datas = null;
                var cat_datas = null;
                var menu_datas = null;
                var type_datas = null;
                
                $.ajax({
                    async: false,
                    method: "GET",
                    url: table_url,
                    dataType: "json",
                    data: {
                        department_id :this.selectedDepart
                    },
                    success: function (result) {
                        table_datas = result;
                    }
                });
                $.ajax({
                    async: false,
                    method: "GET",
                    url: href,
                    dataType: "json",
                    data: {
                        department_id :this.selectedDepart
                    },
                    success: function (result) {
                        console.log(result)
                        product_datas = result['products'];
                        cat_datas = result['categories'];
                        menu_datas = result['menus'];
                        type_datas = result['drint_type'];
                    }
                });
                // $.ajax({
                //     async: false,
                //     method: "GET",
                //     url: emp_url,
                //     dataType: "json",
                //     data: {
                //         department_id :this.selectedDepart
                //     },
                //     success: function (result) {
                //         var len = result.length;
                //         $("#employee").empty();
                //         $("#employee").append("<option value='all'>All Employee</option>");
                //         for( var i = 0; i<len; i++){
                //             var id = result[i]['id'];
                //             var name = result[i]['name'];
                //             $("#employee").append("<option value='"+id+"'>"+name+"</option>");

                //         }
                //     }
                // });
                this.cuisines = type_datas;
                this.categories = cat_datas;
                this.menus = menu_datas;
                this.tableData = product_datas;
                this.filteredDineInButtons = table_datas;
                return this.filteredProducts();
            },
            myTables: [],
            handleTableSearchInput(searchTerm) {
                if (this.searchTerm.length >= 2) {
                    const searchTermLowerCase = this.searchTerm.toLowerCase();
                    const matchingProducts = this.dineInButtons.filter(
                    (table) =>
                        table.id.toString() === searchTermLowerCase || // Match by ID
                        table.label
                            .toLowerCase()
                            .includes(searchTermLowerCase)// Check if the product is in stock
                    );
                    console.log(matchingProducts)
                    if (matchingProducts.length > 0) {
                        matchingProducts.forEach((table) => {
                            if (!this.myTables.includes(table)) {
                            this.myTables.push(table); // Append the matched product if it's not already in myProducts
                            }
                        });
                        this.searchTerm = ""; // Clear the search field after adding the product
                    }
                }
            },
            // Add a search method
            searchProducts(searchTerm) {
                if (!searchTerm) {
                    return this.tableData;
                } else {
                    const lowerCaseSearchTerm = searchTerm.toLowerCase();
                    return this.tableData.filter(
                    (product) =>
                        product.id.toString().includes(lowerCaseSearchTerm) || // Check for ID match
                        product.description.toLowerCase().includes(lowerCaseSearchTerm) // Check for description match
                    );
                }
            },

            myProducts: [],
            searchResults: [],
            handleSearchInput() {
                if (this.searchTerm.length >= 2) {
                    const searchTermLowerCase = this.searchTerm.toLowerCase();
                    const matchingProducts = this.tableData.filter(
                    (product) =>
                        (
                        product.description.toLowerCase().includes(searchTermLowerCase) || // Match by description
                        product.skuCode.toString().includes(searchTermLowerCase)) && // Match by SKU code
                        product.availability === "in stock" // Check if the product is in stock
                    );

                    if (matchingProducts.length === 1) {
                    this.addProduct(matchingProducts[0]); // Directly add the product if there's only one match
                    this.searchResults = []; // Clear searchResults
                    } else if (matchingProducts.length > 1) {
                    this.searchResults = matchingProducts; // Store the matching products in searchResults
                    } else {
                    this.searchResults = []; // Clear searchResults if no matches found
                    }
                } else {
                    this.searchResults = []; // Clear searchResults if search term is too short
                }
            },

            addProduct(product) {
                if (!this.myProducts.includes(product)) {
                    this.myProducts.push(product);
                }
                this.searchResults = []; // Clear search results after adding the product
                this.searchTerm = ""; // Clear search term after selecting a product
            },

            selectedProductWithVariation: null,
            selectedVariations: [],
            selectedVariationsInfo: [],
            addToMyProducts(product) {
                

                const existingProductIndex = this.myProducts.findIndex(
                    (p) => p.id === product.id
                );

                if (existingProductIndex !== -1) {
                    // If the product already exists in myProducts, increase its quantity
                    this.myProducts[existingProductIndex].qty++;
                } else {
                    if (product.variations && product.variations.length > 0) {
                    // Product has variations, show a popup to select variations
                    this.showVariationPopup(product);
                    } else {
                    // Product has no variations, add it directly
                    this.addProductWithoutVariations(product);
                    }
                }
            },

            dineOrderType() {
                this.dineInPopup = true;
            },
            roomOrderType(){
                this.roomPopup = true;
            },

            showVariationPopup(product) {
                this.selectedProductWithVariation = product;
                this.initializeSelectedVariations(); // Initialize selectedVariations
                this.variationPopupVisible = true;

            },

            initializeSelectedVariations() {
                if (
                    this.selectedProductWithVariation &&
                    this.selectedProductWithVariation.variations
                ) {
                    this.selectedProductWithVariation.variations.forEach((variation) => {
                    this.selectedVariations[variation.name] = [];
                    });
                }
            },

            addProductWithoutVariations(product) {
            if (product.availability === "in stock") {
                const productToAdd = { ...product, qty: product.qty };
                this.myProducts.push(productToAdd);
            }
            },

            addProductWithVariations() {
                if (this.selectedProductWithVariation) {
                    // Check if any variation is selected
                    const isAnyVariationSelected = Object.values(
                    this.selectedVariations
                    ).some((variation) => variation.length > 0);

                    if (isAnyVariationSelected) {
                    const selectedProduct = { ...this.selectedProductWithVariation };
                    selectedProduct.variations = this.selectedVariationsInfo;

                    // Now you can use this.selectedVariationsInfo
                    console.log(
                        "Selected Variations Information:",
                        this.selectedVariationsInfo
                    );

                    // Now you can use this.totalSelectedVariationPrice
                    console.log(
                        "Total Selected Variation Price:",
                        this.totalSelectedVariationPrice()
                    );

                    if (selectedProduct.availability === "in stock") {
                        this.myProducts.push(selectedProduct);
                        console.log("selectedProduct", selectedProduct.variations);
                    }
                    this.variationPopupVisible = false;
                    this.selectedProductWithVariation = null;
                    this.selectedVariationsInfo = []; // Clear selected variations
                    } else {
                    showMessage("Please select at least one variation.");
                    }
                }
            },

            // Now you can use this.totalSelectedVariationPrice
            totalSelectedVariationPrice() {
                        return this.selectedVariationsInfo.reduce((total, variation) => {
                            return total + variation.price;
                        }, 0);
            },
            // Calculate total quantity method
            calculateTotalQuantity() {
                return this.myProducts.reduce(
                    (total, product) => total + parseInt(product.qty),
                    0
                );
            },

            updateQuantity(productId, amount) {
                const product = this.myProducts.find((p) => p.id === productId);
                if (product) {
                    product.qty += amount;
                }
            },

            incrementQuantity(index) {
                const product = this.myProducts[index];
                if (product) {
                    product.qty = parseInt(product.qty) + 1; // Parse qty as integer before incrementing
                }
            },

            decrementQuantity(index) {
                const product = this.myProducts[index];
                if (product && parseInt(product.qty) > 1) {
                    // Check if qty is greater than 1 before decrementing
                    product.qty = parseInt(product.qty) - 1; // Parse qty as integer before decrementing
                }
            },

            increQuantity(product) {
                if (product) {
                    product.qty = parseInt(product.qty) + 1; // Parse qty as integer before incrementing
                }
            },

            decreQuantity(product) {
                if (product && parseInt(product.qty) > 1) {
                    // Check if qty is greater than 1 before decrementing
                    product.qty = parseInt(product.qty) - 1; // Parse qty as integer before decrementing
                }
            },

            updateQuantityFromInput(newQty, index) {
                const parsedQty = parseInt(newQty);
                if (!isNaN(parsedQty) && parsedQty >= 0) {
                    this.myProducts[index].qty = parsedQty;
                }
            },

            tax: 0,
            discount: 0,
            coupon: 0,
            loyaltyPoints: 0,
            giftCard: 0,
            discount_percentage: 0,

            calculateDiscountPercentage()
            {
                const dis = parseInt(this.discount_percentage);
                if(!isNaN(dis) && dis > 0);
                {
                    const subtotal = this.myProducts.reduce((total, data) => {
                        return total + this.calculateSubTotal(data);
                    }, 0);
                    const discount_amount =   ((dis/100) * subtotal);
                    return discount_amount > 0 ? discount_amount : 0;
                }
                return 0;
            },

            calculateServiceCharge()
            {
                const charge = parseInt(this.seviceCharge);
                if(!isNaN(charge) && charge > 0);
                {
                    const subtotal = this.myProducts.reduce((total, data) => {
                        return total + this.calculateSubTotal(data);
                    }, 0);
                    const charge_amount =   ((charge/100) * subtotal);
                    return charge_amount > 0 ? charge_amount : 0;
                }
                return 0;
            },

            calculateTaxCharge()
            {
                const charge = parseInt(this.tax);
                if(!isNaN(charge) && charge > 0);
                {
                    const subtotal = this.myProducts.reduce((total, data) => {
                        return total + this.calculateSubTotal(data);
                    }, 0);
                    const tax_amount =   ((charge/100) * subtotal);
                    return tax_amount > 0 ? tax_amount : 0;
                }
                return 0;
            },

            totalDiscount() {
                return (
                    Number(this.discount) +
                    this.calculateDiscountPercentage() +
                    Number(this.coupon) +
                    Number(this.loyaltyPoints) +
                    Number(this.giftCard)
                );
            },

            totalDiscountAmount() {
                return (
                    Number(this.discount) +
                    this.calculateDiscountPercentage()
                );
            },


            calculateSubTotal(data) {
                const product = this.myProducts.find((item) => item.id === data.id);

                if (product) {
                    const totalBeforeDiscount = product.qty * product.price;
                    const discountAmount = product.dis; // Calculate discount amount

                    // Check if there are any variations
                    const hasVariations =
                    product.variations && product.variations.length > 0;

                    // Calculate total variation price if variations are present
                    const totalVariationPrice = hasVariations
                    ? product.variations.reduce((acc, variation) => {
                        return acc + variation.price;
                        }, 0)
                    : 0;

                    const subtotal =
                    totalBeforeDiscount - discountAmount + totalVariationPrice;

                    return subtotal > 0 ? subtotal : 0;
                }

                return 0;
            },

            // Calculate the total sum of subtotals from myProducts
            totalSubtotal() {
                return this.myProducts.reduce((total, product) => {
                    const totalBeforeDiscount = product.qty * product.price;
                    const discountAmount = product.dis;

                    // Check if there are any variations
                    const hasVariations =
                    product.variations && product.variations.length > 0;

                    // Calculate total variation price if variations are present
                    const totalVariationPrice = hasVariations
                    ? product.variations.reduce((acc, variation) => {
                        return acc + variation.price;
                        }, 0)
                    : 0;

                    const subtotal =
                    totalBeforeDiscount - discountAmount + totalVariationPrice;
                    return total + (subtotal > 0 ? subtotal : 0);
                }, 0);
            },

            incrementQty() {
                this.myProducts[this.selectedProduct].qty++;
            },
            decrementQty() {
                if (this.myProducts[this.selectedProduct].qty > 0) {
                    this.myProducts[this.selectedProduct].qty--;
                }
            },


            deleteRow(index) {
            this.myProducts.splice(index, 1);
            },

            removeFromPopup(index) {
            this.myProducts.splice(index, 1);
            this.removePopup = null;
            },
            calculateGrandFinTotal : 0,
            // Function to calculate Grand Total
            calculateGrandTotal() {
                const sub_total = this.myProducts.reduce((total, data) => {
                    return total + this.calculateSubTotal(data);
                }, 0);
                this.calculateGrandFinTotal = (sub_total - this.totalDiscount()) +  this.calculateTaxCharge() + this.calculateServiceCharge();
                return (sub_total - this.totalDiscount()) +  this.calculateTaxCharge() + this.calculateServiceCharge();
            },

            formatCurrency(value) {
            // Convert the number to a fixed format with 2 decimal places
            const formattedValue = value.toFixed(2);
            // Separate the integer part and decimal part
            const [integerPart, decimalPart] = formattedValue.split(".");

            // Add commas to the integer part for better readability
            const integerWithCommas = integerPart.replace(
                /\B(?=(\d{3})+(?!\d))/g,
                ","
            );

            // Combine the formatted parts with a currency symbol
            return `Rs${integerWithCommas}.${decimalPart}`;
            },

            showThirdColumn: true,
            toggleThirdColumn() {
            this.showThirdColumn = !this.showThirdColumn;
            },

            calculation: "",
            addToCalculation(num) {
            this.calculation += num;
            this.updateQuantityFromInput(this.calculation, this.selectedProduct);
            },
            addOperator(operator) {
            this.calculation += operator;
            },
            addDecimal() {
            if (!this.calculation.includes(".")) {
                this.calculation += ".";
            }
            },

            performCalculation() {
            console.log("works cal enter");
            try {
                this.calculation = eval(this.calculation).toString();
            } catch (e) {
                this.calculation = "Error";
            }
            },

            clearCalculation() {
            this.calculation = "";
            },

            calculatorHidden: false,
            toggleCalculator() {
            this.calculatorHidden = !this.calculatorHidden;
            },

            StockSearchTerm: "",
            myStocks: [],
            handleStockSearchInput() {
            if (this.StockSearchTerm.length >= 8) {
                const searchTermLowerCase = this.StockSearchTerm.toLowerCase();
                const matchingStocks = this.tableData.filter(
                (stock) =>
                    (stock.id.toString() === searchTermLowerCase || // Match by ID
                    stock.name || // Match by name
                    stock.skuCode || // Match by SKU code
                    stock.barcode) && // Match by barcode
                    stock.qty > 0 // Assuming you want to check if the stock quantity is greater than 0
                );

                if (matchingStocks.length > 0) {
                matchingStocks.forEach((stock) => {
                    if (!this.myStocks.includes(stock)) {
                    this.myStocks.push(stock); // Append the matched stock if it's not already in myProducts
                    }
                });
                this.StockSearchTerm = ""; // Clear the search field after adding the stock
                }
            }
            },

            quantityPopup: false,
            holdInvoicePopup: false,
            pickHeldInvoicePopup: false,
            removePopup: false,
            stockPopup: false,
            cancelInvoicePopup: false,
            shipmentPopup: false,
            disPercentagePopup: false,
            disAmountPopup: false,
            taxPopup: false,
            locationPopup: false,
            employeePopup: false,
            userPopup: false,
            dineInPopup: false,
            roomPopup: false,
            orderStatusPopup: false,
            kotOrdersPopup: false,
            entryPopup: false,
            paymentDetailsPopup: false,
            orderPopup: false,
            editProductsPopup: false,
            variationPopupVisible: false,
            KotPopup: false,
            cashPopup: false,
            giftCardPopup: false,
            multyPaymentPopup: false,
            addCustomerPop: false,
            productPopUp:false,
            viewCardPopUp:false,

            get anyPopupOpen() {
                return (
                    this.quantityPopup ||
                    this.holdInvoicePopup ||
                    this.pickHeldInvoicePopup ||
                    this.removePopup ||
                    this.stockPopup ||
                    this.cancelInvoicePopup ||
                    this.shipmentPopup ||
                    this.disPercentagePopup ||
                    this.disAmountPopup ||
                    this.taxPopup ||
                    this.locationPopup ||
                    this.employeePopup ||
                    this.userPopup ||
                    this.dineInPopup ||
                    this.roomPopup ||
                    this.kotOrdersPopup ||
                    this.orderStatusPopup ||
                    this.entryPopup ||
                    this.paymentDetailsPopup ||
                    this.orderPopup ||
                    this.editProductsPopup ||
                    this.variationPopupVisible ||
                    this.KotPopup ||
                    this.cashPopup ||
                    this.giftCardPopup ||
                    this.multyPaymentPopup
                    ||
                    this.addCustomerPop ||
                    this.productPopUp ||
                    this.viewCardPopUp
                );
            },

            openEditPopup(index) {
                this.selectedProduct = index; // Select the double-clicked product
                this.editProductsPopup = true; // Show the edit popup
            },

            viewCart()
            {
                this.productPopUp = false;
                this.viewCardPopUp = true;
            },

            updateProduct() {
                this.editProductsPopup = false;
            },

            openCashPop()
            {
                const allSelectProducts = this.myProducts;
                if (allSelectProducts.length <= 0)
                {
                    showMessage('No products had been selected');
                    return false;
                }
                else
                {
                    this.cashPopup = true;
                }
            },
            openMultyPaymentPop()
            {
                const allSelectedProducts = this.myProducts;
                console.log(this.calculateGrandFinTotal);
                if (allSelectedProducts.length <= 0)
                {
                    showMessage('No products had been selected');
                    return false;
                }
                else
                {
                    this.remainingMultyAmount = this.calculateGrandFinTotal;
                    this.multyPaymentPopup = true;
                }
            },
            checkBoxChange() {
                if($('#is_include:checked').val() == 1)
                {
                    this.selectedIsInclude = 1;
                }
                else
                {
                    this.selectedIsInclude = 0;
                }
                
            },
            getCashPayment() {
                const href = "{{ action('POSController@addPaidorder') }}";
                const draft_data = $('form#pos_add_form').serialize();
                var invoice = null;
                $.ajax({
                    async: false,
                    method: "POST",
                    url: href,
                    dataType: "json",
                    data: draft_data,
                    success: function (result) {
                        invoice = result;
                    }
                });
                return invoice;
            },

            cashPayment() {
                
                const invoice = this.getCashPayment();
                const invoiceData = invoice.data;
                const receipt = invoice.receipt;
                this.cashPopup = false;
                localStorage.setItem("selectedLocation", '{{$default_location}}');
                localStorage.setItem("selectedDepartment", '');
                
                localStorage.setItem("selectedCustomer", '{{request()->get('customer_id')}}');
                localStorage.setItem("selectedRoom", '');
                localStorage.setItem("selectedTable", '');
                //  Optional: Reset the selectedLocation and selectedCustomer to their default values
                this.selectedLocation = localStorage.getItem("selectedLocation");
                this.selectedDepartment = localStorage.getItem("selectedDepartment");
                this.selectedEmployee = localStorage.getItem("selectedEmployee");
                this.selectedCustomer = localStorage.getItem("selectedCustomer");
                this.selectedRoom = null;
                this.selectedTable = null;
                this.selectedDineInButtons = null;
                this.selectedRoomInButtons = null;
                this.selectedOrderType = null;
                this.buttonDisabled = false;
                this.productDisabled = false;
                this.myProducts = [];
                $('#transaction_id').val('');
                this.tax = 0;
                this.discount = 0;
                this.coupon = 0;
                this.loyaltyPoints = 0;
                this.giftCard = 0;
                this.discount_percentage = 0;
                this.seviceCharge = 0;
                $('.take-away-btn').removeClass('active');
                $('.take-online-btn').removeClass('active');
                this.pos_print(receipt);
                return;
            },

            multyPayment() {
                const invoice = this.getCashPayment();
                const invoiceData = invoice.data;
                const receipt = invoice.receipt;
                localStorage.setItem("selectedLocation", '{{$default_location}}');
                localStorage.setItem("selectedDepartment", '');
                
                localStorage.setItem("selectedCustomer", '{{request()->get('customer_id')}}');
                localStorage.setItem("selectedRoom", '');
                localStorage.setItem("selectedTable", '');
                //  Optional: Reset the selectedLocation and selectedCustomer to their default values
                this.selectedLocation = localStorage.getItem("selectedLocation");
                this.selectedDepartment = localStorage.getItem("selectedDepartment");
                this.selectedEmployee = localStorage.getItem("selectedEmployee");
                this.selectedCustomer = localStorage.getItem("selectedCustomer");
                this.selectedRoom = null;
                this.selectedTable = null;
                this.selectedDineInButtons = null;
                this.selectedRoomInButtons = null;
                this.selectedOrderType = null;
                this.productDisabled = false;
                $('#transaction_id').val('');
                this.tax = 0;
                this.discount = 0;
                this.coupon = 0;
                this.loyaltyPoints = 0;
                this.giftCard = 0;
                this.discount_percentage = 0;
                this.seviceCharge = 0;
                this.myProducts = [];
                this.multyPaymentPopup = false;
                $('.take-away-btn').removeClass('active');
                $('.take-online-btn').removeClass('active');
                this.pos_print(receipt);

                return;
            },

            addressType: "default", // Initially set to 'default'
            heldInvoices: [],
            heldOrders: [],
            printOrders: [],
            kotDisplayOrders: [],

            lastInvoiceNumber: parseInt(localStorage.getItem("lastInvoiceNumber")) || 0,
            lastOrderNumber: parseInt(localStorage.getItem("lastOrderNumber")) || 0,

            generateInvoiceNumber() {
                this.lastInvoiceNumber++;
                localStorage.setItem("lastInvoiceNumber", this.lastInvoiceNumber);
                return `${this.lastInvoiceNumber}`;
            },

            generateOrderNumber() {
            this.lastOrderNumber++;
            localStorage.setItem("lastOrderNumber", this.lastOrderNumber);
            return `${this.lastOrderNumber}`;
            },

            getInvoice() {
                const href = "{{ action('POSController@draft') }}";
                const draft_data = $('form#pos_add_form').serialize();
                var newInvoice = null;
                $.ajax({
                    async: false,
                    method: "POST",
                    url: href,
                    dataType: "json",
                    data: draft_data,
                    success: function (result) {
                        newInvoice = result.data;
                    }
                });
                return newInvoice;
            },
            
            holdInvoice() {
                const selectedLocation =
                    localStorage.getItem("selectedLocation") || "All";
                const selectedEmployee =
                    localStorage.getItem("selectedEmployee") || "All";
                const selectedDepartment =
                    localStorage.getItem("selectedDepartment") || "All";
                const selectedCustomer =
                    localStorage.getItem("selectedCustomer") || "Customer";
                const newInvoiceNumber = this.generateInvoiceNumber();
                const selectedDineInButton =
                this.dineInButtons[this.selectedDineInButtons];
                const selectedRoomButton = this.roomsButtons[this.selectedRoomInButtons];
                const allProducts = this.myProducts;
                const existingInvoice = this.heldInvoices.find(
                    (invoice) => invoice.invoiceNo === newInvoiceNumber
                    
                );
                if(allProducts.length <= 0)
                {
                    showMessage('No products had been selected');
                    return false;
                }
                
                // if (allProducts.length > 0)
                // {
                //     const newInvoice = this.getInvoice();
                //     // allProducts.forEach((product) => {
                //     // newInvoice.products.push(product);
                //     // });

                //     // Calculate line total by summing prices of all products
                //     const total = allProducts.reduce(
                //     (acc, product) => acc + product.price,
                //     0
                //     );
                //     newInvoice.lineTotal = total;

                //     if (existingInvoice) {
                //     // Update products in the existing invoice
                //     existingInvoice.products = newInvoice.products;
                //     existingInvoice.lineTotal = newInvoice.lineTotal;
                //     } else {
                //     // Add new invoice to heldInvoices;
                //     this.heldInvoices.push(newInvoice);
                //     }

                //     this.myProducts = [];
                //     localStorage.setItem("selectedLocation", newOrder.location_id);
                //     localStorage.setItem("selectedDepartment", newOrder.department_id);
                //     localStorage.setItem("selectedEmployee", newOrder.employee_id);
                //     localStorage.setItem("selectedCustomer", newOrder.cus_id);
                //     localStorage.setItem("selectedRoom", newOrder.room_id);
                //     localStorage.setItem("selectedTable", newOrder.table_id);
                //     //  Optional: Reset the selectedLocation and selectedCustomer to their default values
                //     this.selectedLocation = localStorage.getItem("selectedLocation");
                //     this.selectedDepartment = localStorage.getItem("selectedDepartment");
                //     this.selectedEmployee = localStorage.getItem("selectedEmployee");
                //     this.selectedCustomer = localStorage.getItem("selectedCustomer");
                //     this.selectedRoom = localStorage.getItem("selectedRoom");
                //     this.selectedTable = localStorage.getItem("selectedTable");
                //     this.selectedDineInButtons = localStorage.getItem("selectedTable");
                //     this.selectedRoomInButtons = localStorage.getItem("selectedRoom");
                // }

                if (this.selectedDineInButtons !== null) {
                    // Table is selected
                    const lineTotal = allProducts.reduce(
                    (total, product) => total + product.price,
                    0
                    );

                    if (allProducts.length > 0) {
                        const newInvoice = this.getInvoice();
                        const existingOrder = this.heldInvoices.find(
                            (order) =>
                            order.invoiceNo === newInvoice.invoiceNo
                        );
                    
                        if (existingOrder) {
                            // If a table order exists, add products to the existing order
                            allProducts.forEach((product) => {
                            existingOrder.products.push(product);
                            });
                            
                            existingOrder.lineTotal = lineTotal;
                        } else {
                            
                            this.heldInvoices.push(newInvoice);
                        }

                        this.myProducts = [];
                        localStorage.setItem("selectedLocation", newOrder.location_id);
                        localStorage.setItem("selectedDepartment", newOrder.department_id);
                        localStorage.setItem("selectedEmployee", newOrder.employee_id);
                        localStorage.setItem("selectedCustomer", newOrder.cus_id);
                        localStorage.setItem("selectedRoom", newOrder.room_id);
                        localStorage.setItem("selectedTable", newOrder.table_id);
                        //  Optional: Reset the selectedLocation and selectedCustomer to their default values
                        this.selectedLocation = localStorage.getItem("selectedLocation");
                        this.selectedDepartment = localStorage.getItem("selectedDepartment");
                        this.selectedEmployee = localStorage.getItem("selectedEmployee");
                        this.selectedCustomer = localStorage.getItem("selectedCustomer");
                        this.selectedRoom = null;
                        this.selectedTable = null;
                        this.selectedDineInButtons = null;
                        this.tax = 0;
                        this.discount = 0;
                        this.coupon = 0;
                        this.loyaltyPoints = 0;
                        this.giftCard = 0;
                        this.discount_percentage = 0;
                        
                        return;
                    }
                } else if (this.selectedRoomInButtons !== null) {
                    // Room is selected
                    const lineTotal = allProducts.reduce(
                    (total, product) => total + product.price,
                    0
                    );

                    if (allProducts.length > 0) {
                        const newInvoice = this.getInvoice();
                        const existingOrder = this.heldInvoices.find(
                            (order) =>
                            order.invoiceNo === newInvoice.invoiceNo
                        );

                        if (existingOrder) {
                            // If a room order exists, add products to the existing order
                            allProducts.forEach((product) => {
                            existingOrder.products.push(product);
                            });

                            existingOrder.lineTotal = lineTotal;
                        } else {
                            this.heldInvoices.push(newInvoice);
                        }

                        this.myProducts = [];
                        localStorage.setItem("selectedLocation", newOrder.location_id);
                        localStorage.setItem("selectedDepartment", newOrder.department_id);
                        localStorage.setItem("selectedEmployee", newOrder.employee_id);
                        localStorage.setItem("selectedCustomer", newOrder.cus_id);
                        localStorage.setItem("selectedRoom", newOrder.room_id);
                        localStorage.setItem("selectedTable", newOrder.table_id);
                        //  Optional: Reset the selectedLocation and selectedCustomer to their default values
                        this.selectedLocation = localStorage.getItem("selectedLocation");
                        this.selectedDepartment = localStorage.getItem("selectedDepartment");
                        this.selectedEmployee = localStorage.getItem("selectedEmployee");
                        this.selectedCustomer = localStorage.getItem("selectedCustomer");
                        this.selectedRoom = null;
                        this.selectedTable = null;
                        this.selectedRoomInButtons = null;
                        this.tax = 0;
                        this.discount = 0;
                        this.coupon = 0;
                        this.loyaltyPoints = 0;
                        this.giftCard = 0;
                        this.discount_percentage = 0;
                        return;
                    }
                }
            },
            searchInput: "",
            filteredDineInButtons: [],
            filteredDineInButtons() {
                const searchTerm = this.searchInput.toLowerCase();
                return this.dineInButtons.filter((button) =>
                    button.label.toLowerCase().includes(searchTerm)
                );
            },

            searchRoom: "",
            filteredRooms: [],
            filteredRooms() {
                const searchTerm = this.searchRoom.toLowerCase();
                return this.roomsButtons.filter((room) =>
                    room.label.toString().includes(searchTerm)
                );
            },

            getOrder() {
                const href = "{{ action('POSController@order') }}";
                const draft_data = $('form#pos_add_form').serialize();
                var newOrder = null;
                var receipt = null;
                $.ajax({
                    async: false,
                    method: "POST",
                    url: href,
                    dataType: "json",
                    data: draft_data,
                    success: function (result) {
                        newOrder = result.data;
                        receipt = result.receipt;
                    }
                });
                return {
                    'newOrder': newOrder,
                    'receipt': receipt
                };
            },

            holdOrder() {
                const selectedLocation =
                    localStorage.getItem("selectedLocation") || "All";
                const selectedDepartment =
                    localStorage.getItem("selectedDepartment") || "All";
                const selectedEmployee =
                    localStorage.getItem("selectedEmployee") || "All";
                const selectedCustomer =
                    localStorage.getItem("selectedCustomer") || "Customer";
                const newOrderNumber = this.generateOrderNumber();
                const selectedDineInButton =
                    this.dineInButtons[this.selectedDineInButtons];
                const selectedRoomButton = this.roomsButtons[this.selectedRoomInButtons];
                const settings = localStorage.getItem("settings") || "KotDisplay"; // Fetch settings

                const allProducts = this.myProducts;

                if (this.selectedDineInButtons !== null || this.selectedOrderType !== null) {
                    // Table is selected
                    const lineTotal = allProducts.reduce(
                    (total, product) => total + product.price,
                    0
                    );

                    if (allProducts.length > 0) {
                        const getOrder = this.getOrder();
                        const outOrder = getOrder['receipt'];
                        const newOrder = getOrder['newOrder'];
                        const existingOrder = this.heldOrders.find(
                            (order) =>
                            order.invoiceNo === newOrder.invoiceNo
                        );
                    
                        if (existingOrder) {
                            // If a table order exists, add products to the existing order
                            allProducts.forEach((product) => {
                            product.status = 'Accept';   
                            existingOrder.products.push(product);
                            });
                            existingOrder.lineTotal = existingOrder.products.reduce((total, product) => {
                                const totalBeforeDiscount = product.qty * product.price;

                                const subtotal = totalBeforeDiscount;
                                return total + (subtotal > 0 ? subtotal : 0);
                            }, 0);
                            this.kotDisplayOrders = this.heldOrders;
                            this.pos_print(outOrder);
                        } else {
                            // If no existing table order, create a new table order
                            // const newOrder = this.getOrder();
                            // Sort based on settings
                            
                            if (settings === "KotDisplay") {
                                if(newOrder.products.length > 0)
                                {   
                                    this.kotDisplayOrders.push(newOrder);
                                    this.pos_print(outOrder);
                                }
                            } else if (settings === "DirectPrint") {
                                this.pos_print(outOrder);
                                if(newOrder.products.length > 0)
                                {
                                    this.kotDisplayOrders.push(newOrder); // Push to KotDisplay array
                                }
                            }
                            this.heldOrders.push(newOrder);
                            console.log("New table order added", newOrder);
                        }

                        this.myProducts = [];
                        localStorage.setItem("selectedCustomer", '{{request()->get('customer_id')}}');
                        
                        localStorage.setItem("selectedLocation", newOrder.location_id);
                        localStorage.setItem("selectedDepartment", '');
                        localStorage.setItem("selectedRoom", '');
                        localStorage.setItem("selectedTable", '');
                        //  Optional: Reset the selectedLocation and selectedCustomer to their default values
                        this.selectedLocation = localStorage.getItem("selectedLocation");
                        this.selectedDepartment = localStorage.getItem("selectedDepartment");
                        this.selectedEmployee = localStorage.getItem("selectedEmployee");
                        this.selectedCustomer = localStorage.getItem("selectedCustomer");
                        this.selectedRoom = null;
                        this.selectedTable = null;
                        this.selectedDineInButtons = null;
                        this.selectedOrderType = null;
                        this.tax = 0;
                        this.discount = 0;
                        this.coupon = 0;
                        this.loyaltyPoints = 0;
                        this.giftCard = 0;
                        this.discount_percentage = 0;
                        $('.take-away-btn').removeClass('active');
                        $('.take-online-btn').removeClass('active');
                        return;
                    }
                } else if (this.selectedRoomInButtons !== null || this.selectedOrderType !== null) {
                // Room is selected
                    const lineTotal = allProducts.reduce(
                    (total, product) => total + product.price,
                    0
                    );

                    if (allProducts.length > 0) {
                        const getOrder = this.getOrder();
                        const outOrder = getOrder['receipt'];
                        const newOrder = getOrder['newOrder'];
                        const existingOrder = this.heldOrders.find(
                            (order) =>
                            order.invoiceNo === newOrder.invoiceNo
                        );

                        if (existingOrder) {
                            // If a room order exists, add products to the existing order
                            allProducts.forEach((product) => {
                                product.status = 'Accept'; 
                                existingOrder.products.push(product);
                            });

                            existingOrder.lineTotal = existingOrder.products.reduce((total, product) => {
                                const totalBeforeDiscount = product.qty * product.price;

                                const subtotal = totalBeforeDiscount;
                                return total + (subtotal > 0 ? subtotal : 0);
                            }, 0);
                            this.kotDisplayOrders = this.heldOrders;
                            this.pos_print(outOrder);
                        } else {
                            if (settings === "KotDisplay") {
                                this.pos_print(outOrder);
                                if(newOrder.products.length > 0)
                                {
                                    this.kotDisplayOrders.push(newOrder); // Push to KotDisplay array
                                }
                                // this.kotDisplayOrders.push(newOrder); // Push to KotDisplay array
                                console.log("kotDisplayOrders", newOrder);
                            } else if (settings === "DirectPrint") {
                                this.pos_print(outOrder);
                                if(newOrder.products.length > 0)
                                {
                                    this.kotDisplayOrders.push(newOrder); // Push to KotDisplay array
                                }
                                // this.printOrders.push(newOrder); // Push to Print array
                                // console.log("DirectPrint", newOrder);
                            }
                            this.heldOrders.push(newOrder);
                            console.log("New room order added", newOrder);
                        }

                        this.myProducts = [];
                        localStorage.setItem("selectedCustomer", '{{request()->get('customer_id')}}');
                        
                        localStorage.setItem("selectedLocation", newOrder.location_id);
                        localStorage.setItem("selectedDepartment", '');
                        localStorage.setItem("selectedRoom", '');
                        localStorage.setItem("selectedTable", '');
                        //  Optional: Reset the selectedLocation and selectedCustomer to their default values
                        this.selectedLocation = localStorage.getItem("selectedLocation");
                        this.selectedDepartment = localStorage.getItem("selectedDepartment");
                        this.selectedEmployee = localStorage.getItem("selectedEmployee");
                        this.selectedCustomer = localStorage.getItem("selectedCustomer");
                        this.selectedRoom = null;
                        this.selectedTable = null;
                        this.selectedRoomInButtons = null;
                        this.selectedOrderType = null;
                        this.tax = 0;
                        this.discount = 0;
                        this.coupon = 0;
                        this.loyaltyPoints = 0;
                        this.giftCard = 0;
                        this.discount_percentage = 0;
                        $('.take-away-btn').removeClass('active');
                        $('.take-online-btn').removeClass('active');
                        return;
                    }
                    $('#hold-btn-invoice').prop('disabled', false);
                    $('#order-btn-invoice').prop('disabled', false);
                    this.productDisabled = false;
                }

                // Alert if no room or table is selected
                showMessage("Please select a table or room to add the order.");
            },
            pos_print(receipt) {
                if(receipt.html_content)
                {
                    const settings = localStorage.getItem("settings") || "KotDisplay";
                    if(settings === "KotDisplay" && receipt.check && receipt.check.length > 0)
                    {
                        $('#receipt_section').html(receipt.html_content);
                        setTimeout(function () { window.print(); }, 1000);
                        // window.onafterprint = function() {
                        //     //alert("Hello");
                        //     window.location.reload(true);
                        // };
                    }
                    else if(settings === "KotDisplay" && receipt.check && receipt.check.length == 0)
                    {
                        // window.onafterprint = function() {
                        //     //alert("Hello");
                        //     window.location.reload(true);
                        // };
                    }
                    else
                    {
                        $('#receipt_section').html(receipt.html_content);
                        setTimeout(function () { window.print(); }, 1000);
                        // window.onafterprint = function() {
                        //     //alert("Hello");
                        //     window.location.reload(true);
                        // };
                    }
                }
                
            },
            performAction(actionName, shortcut) {
                switch (actionName) {
                    case "Quantity":
                        if (this.selectedProduct !== null) {
                            this.quantityPopup = true;
                        }
                        break;
                    case "Remove":
                        if (this.selectedProduct !== null) {
                            this.removePopup = true;
                        }
                        break;
                    case "Stock":
                        this.stockPopup = true;
                        break;
                    case "Hold Invoice":
                        this.holdInvoice();
                        break;
                    case "Order":
                        this.holdOrder();
                        break;
                    case "Pick Held Invoice":
                        this.pickHeldInvoicePopup = true;
                        break;
                    case "Cancel Invoice":
                        this.cancelInvoicePopup = true;
                        break;
                    case "Void Invoice":
                        this.voidInvoice();
                        break;
                    case "Add Customer":
                        this.addNewCustomer();
                        break;    
                    case "Delivery":
                        if (this.myProducts.length > 0) {
                            this.shipmentPopup = true;
                        }
                        break;
                    case "Discount Percentage":
                        if (this.myProducts.length > 0) {
                            this.disPercentagePopup = true;
                        }
                        break;
                    case "Discount Ammount":
                        if (this.myProducts.length > 0) {
                            this.disAmountPopup = true;
                        }
                        break;
                    case "Tax":
                        if (this.myProducts.length > 0) {
                            this.taxPopup = true;
                        }
                        break;
                    case "Gift Cards":
                        if (this.myProducts.length > 0) {
                            this.giftCardPopup = true;
                        }
                    break;
                    default:
                        break;
                }
            },

            addNewCustomer()
            {
                this.addCustomerPop = true;
            },

            returnHeldInvoice(index) {
                if (index >= 0 && index < this.heldInvoices.length) {
                    const invoice = this.heldInvoices[index];
                    this.myProducts = [];
                    $('#transaction_id').val(invoice.id);
                    this.buttonDisabled = true;
                    this.productDisabled = true;
                    invoice.products.forEach((product) => {
                    this.myProducts.push(product);
                    });

                    // Remove the selected invoice from heldInvoices
                    this.heldInvoices.splice(index, 1);
                   
                    localStorage.setItem("selectedLocation", invoice.location_id);
                    localStorage.setItem("selectedDepartment", invoice.department_id);
                    localStorage.setItem("selectedEmployee", invoice.employee_id);
                    localStorage.setItem("selectedCustomer", invoice.cus_id);
                    localStorage.setItem("selectedRoom", invoice.room_id);
                    localStorage.setItem("selectedTable", invoice.table_id);
                    //  Optional: Reset the selectedLocation and selectedCustomer to their default values
                    this.selectedLocation = localStorage.getItem("selectedLocation");
                    this.selectedDepartment = localStorage.getItem("selectedDepartment");
                    this.selectedEmployee = localStorage.getItem("selectedEmployee");
                    this.selectedCustomer = localStorage.getItem("selectedCustomer");
                    this.selectedRoom = localStorage.getItem("selectedRoom");
                    this.selectedTable = localStorage.getItem("selectedTable");
                    this.selectedRoomInButtons = localStorage.getItem("selectedRoom");
                    this.selectedDineInButtons = localStorage.getItem("selectedTable");
                    this.selectedOrderType = invoice.order_type;
                    this.pickHeldInvoicePopup = false;
                } else {
                    console.log("Invalid index provided.");
                }

            },

            deleteProduct(order, product) {
                // Find the index of the order
                var delete_url = "{{action('OrderController@delete')}}";
                $.ajax({
                    url: delete_url,
                    method: "POST",
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        line_id: product.line_id,
                        id: product.id,
                    },
                    success: function (result) {
                        
                    }
                });
                const orderIndex = this.kotDisplayOrders.findIndex(
                    (o) => o.no === order.no
                );

                if (orderIndex !== -1) {
                    // Find the index of the product within the order
                    const productIndex = this.kotDisplayOrders[
                    orderIndex
                    ].products.findIndex((p) => p === product);

                    if (productIndex !== -1) {
                    // Remove the product from the order's products array
                    this.kotDisplayOrders[orderIndex].products.splice(productIndex, 1);

                    // If there are no more products in the order, delete the entire order
                    if (this.kotDisplayOrders[orderIndex].products.length === 0) {
                        this.kotDisplayOrders.splice(orderIndex, 1);
                    }
                    }
                }
            },

            deleteOrder(order) {
                // Implement the logic to delete the entire order
                console.log(order)
                const cancel_url = "{{action('OrderController@orderCancel')}}";
                $.ajax({
                    url: cancel_url,
                    method: "POST",
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: order.id,
                    },
                    success: function (result) {
                        window.location.reload(); 
                    }
                });
                const index = this.kotDisplayOrders.findIndex((o) => o.no === order.no);
                if (index !== -1) {
                    this.kotDisplayOrders.splice(index, 1);
                }
            },

            cancelProduct(order, product) {
                const orderIndex = this.kotDisplayOrders.findIndex(
                    (o) => o.no === order.no
                );
                console.log(this.kotDisplayOrders[orderIndex].status)
                product.status = "canceled";

                var cancel_url = "{{action('OrderController@cancel')}}";
                $.ajax({
                    url: cancel_url,
                    method: "POST",
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        line_id: product.line_id,
                        id: product.id,
                    },
                    success: function (result) {
                        window.location.reload(); 
                    }
                });
            },
            
            returnHeldOrder(orderIndex) {
                const heldOrder = this.heldOrders[orderIndex];
                this.buttonDisabled = true;
                this.productDisabled = true;
                this.myProducts = [];
                $('#hold-btn-invoice').prop('disabled', true);
                $('#order-btn-invoice').prop('disabled', true);
                $('#transaction_id').val(heldOrder.id);
                this.discount = heldOrder.discount;
                this.tax = heldOrder.tax;
                // Add products from held order back to myProducts
                heldOrder.products.forEach((product) => {
                    this.myProducts.push(product);
                });

                // Remove the held order from heldOrders
                this.heldOrders.splice(orderIndex, 1);
                localStorage.setItem("selectedLocation", heldOrder.location_id);
                localStorage.setItem("selectedDepartment", heldOrder.department_id);
                localStorage.setItem("selectedEmployee", heldOrder.employee_id);
                localStorage.setItem("selectedCustomer", heldOrder.cus_id);
                localStorage.setItem("selectedRoom", heldOrder.room_id);
                localStorage.setItem("selectedTable", heldOrder.table_id);
                //  Optional: Reset the selectedLocation and selectedCustomer to their default values
                this.selectedLocation = localStorage.getItem("selectedLocation");
                this.selectedDepartment = localStorage.getItem("selectedDepartment");
                this.selectedEmployee = localStorage.getItem("selectedEmployee");
                this.selectedCustomer = localStorage.getItem("selectedCustomer");
                this.selectedRoom = localStorage.getItem("selectedRoom");
                this.selectedTable = localStorage.getItem("selectedTable");
                this.selectedRoomInButtons = localStorage.getItem("selectedRoom");
                this.selectedDineInButtons = localStorage.getItem("selectedTable");
                this.selectedOrderType = heldOrder.order_type;
                this.pickHeldInvoicePopup = false;
            },

            cancelInvoice(index) {
            // Clear heldInvoices as products
                this.heldInvoices.splice(index, 1);
                this.cancelInvoicePopup = false;
            },

            voidInvoice() {
                this.myProducts = [];
                localStorage.setItem("selectedLocation", '{{$default_location}}');
                localStorage.setItem("selectedDepartment", '');
                
                localStorage.setItem("selectedCustomer", '{{request()->get('customer_id')}}');
                localStorage.setItem("selectedRoom", '');
                localStorage.setItem("selectedTable", '');
                //  Optional: Reset the selectedLocation and selectedCustomer to their default values
                this.selectedLocation = localStorage.getItem("selectedLocation");
                this.selectedDepartment = localStorage.getItem("selectedDepartment");
                this.selectedEmployee = localStorage.getItem("selectedEmployee");
                this.selectedCustomer = localStorage.getItem("selectedCustomer");
                this.selectedRoom = null;
                this.selectedTable = null;
                this.selectedDineInButtons = null;
                this.selectedRoomInButtons = null;
                this.selectedOrderType = null;
                this.buttonDisabled = false;
                this.productDisabled = false;
                this.myProducts = [];
                $('#transaction_id').val('');
                this.tax = 0;
                this.discount = 0;
                this.coupon = 0;
                this.loyaltyPoints = 0;
                this.giftCard = 0;
                this.discount_percentage = 0;
                this.seviceCharge = 0;
                $('#hold-btn-invoice').prop('disabled', false);
                $('#order-btn-invoice').prop('disabled', false);
                window.location.reload(true);
                return ;
            },

            cancelHeldOrder(index) {
            // Clear heldInvoices as products
                this.heldOrders.splice(index, 1);
                this.cancelInvoicePopup = false;
            },

            currentStatus: "all",

            selectedDineInButtons: null, // Track selected buttons
            selectedRoomInButtons: null, // Track selected buttons
            selectedTable: '',
            selectedRoom: '',
            toggleSelected(index) {
                this.selectedTable = index;
                this.selectedRoom = '';
                this.selectedOrderType = "Dine in";
                this.selectedIsInclude = 0;
                $('.take-away-btn').removeClass('active');
                $('.take-online-btn').removeClass('active');
                localStorage.setItem("selectedTable", index);
                if (this.selectedDineInButtons === index) {
                    // If the clicked button is already selected, deselect it
                    this.selectedDineInButtons = null;
                } else {
                    // Otherwise, update the selected button index
                    this.selectedDineInButtons = index;
                    this.selectedRoomInButtons = null;
                    localStorage.setItem("selectedRoom", '');
                }
                this.dineInPopup = false;
            },

            toggleOnline()
            {
                $('.take-online-btn').addClass('active');
                $('.take-away-btn').removeClass('active');
                this.selectedRoomInButtons = null;
                this.selectedDineInButtons = null;
                this.selectedRoom = '';
                this.selectedTable = '',
                this.selectedOrderType = "Online";
                this.selectedIsInclude = 0;
            },
            toggleAway()
            {
                $('.take-away-btn').addClass('active');
                $('.take-online-btn').removeClass('active');
                this.selectedRoomInButtons = null;
                this.selectedDineInButtons = null;
                this.selectedRoom = '';
                this.selectedTable = '',
                this.selectedOrderType = "Take away";
                this.selectedIsInclude = 0;
            },

            toggleRoomSelected(index) {
                this.selectedRoom = index;
                var is_include = $('#is_include:checked').val();
                this.selectedTable = '',
                this.selectedOrderType = "Room Order";
                $('.take-away-btn').removeClass('active');
                this.selectedIsInclude = is_include;
                localStorage.setItem("selectedRoom", index);
                if (this.selectedRoomInButtons === index) {
                    // If the clicked button is already selected, deselect it
                    this.selectedRoomInButtons = null;
                } else {
                    // Otherwise, update the selected button index
                    this.selectedRoomInButtons = index;
                    this.selectedDineInButtons = null;
                    localStorage.setItem("selectedTable", '');
                }
                this.roomPopup = false;
            },

            handleButtonSelection(buttonType, index) {
                if (buttonType === "table") {
                    // Check if a room button is already selected
                    if (this.selectedRoomInButtons !== null) {
                    showMessage(
                        "A room is already selected. Please deselect the room before selecting a table."
                    );
                    this.dineInPopup = false;
                    return;
                    }

                    // Toggle selected Dine In button
                    this.selectedDineInButtons =
                    this.selectedDineInButtons === index ? null : index;
                    this.selectedTable = index;
                    localStorage.setItem("selectedTable", index);
                    this.entryPopup = null;
                } else if (buttonType === "room") {
                    // Check if a table button is already selected
                    if (this.selectedDineInButtons !== null) {
                    showMessage(
                        "A table is already selected. Please deselect the table before selecting a room."
                    );
                    this.roomPopup = false;
                    return;
                    }

                    // Toggle selected Room button
                    this.selectedRoomInButtons =
                    this.selectedRoomInButtons === index ? null : index;
                    this.selectedRoom = index;
                    localStorage.setItem("selectedRoom", index);
                    this.entryPopup = null;
                }
            },

            // This method returns the background color based on order status
            getOrderBackgroundColor(status) {
                if (status === "ready") {
                    return "#ffff004f"; // Example color for pending status
                } else if (status === "process") {
                    return "#ffa50040"; // Example color for processing status
                } else if (status === "completed") {
                    return "#00800033"; // Example color for completed status
                } else if (status === "pending") {
                    return "#0000ff24"; // Example color for completed status
                } else if (status === "canceled") {
                    return "#ff000036"; // Example color for completed status
                } else {
                    return "#ffffff"; // Default color if status doesn't match
                }
            },

            showOrders(status) {
                this.currentStatus = status;
            },

            // calculateRemainingTime(order) {
            //     const orderTime = new Date(order.time).getTime();

            //     if (isNaN(orderTime)) {
            //         return "Invalid time"; // Return an error message if the time is invalid
            //     } else {
            //         const now = new Date().getTime();
            //         const remainingTime = orderTime - now;

            //         if (remainingTime <= 0) {
            //         return "Expired";
            //         } else {
            //         const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
            //         const minutes = Math.floor(
            //             (remainingTime % (1000 * 60 * 60)) / (1000 * 60)
            //         );

            //         return `${minutes}m ${seconds}s`;
            //         }
            //     }
            // },

            paymentCalculation: "",
            addToPaymentCalculation(num) {
                this.paymentCalculation += num;
            },

            addPaymentOperator(operator) {
                this.paymentCalculation += operator;
            },

            addPaymentDecimal() {
                if (!this.paymentCalculation.includes(".")) {
                    this.paymentCalculation += ".";
                }
            },

            paymentPerformCalculation() {
                console.log("works cal enter");
                try {
                    this.paymentCalculation = eval(this.paymentCalculation).toString();
                } catch (e) {
                    this.paymentCalculation = "Error";
                }
            },

            clearPyamentCalculation() {
                this.paymentCalculation = "";
            },

            handlePayment() {
                console.log("payment button clicked");
                this.paymentDetailsPopup = false;
            },
            markItemAsReady(item) {
                item.status = "ready";
            },
            markItemAsProcessing(item) {
                item.status = "processing";
            },
            markItemAsPending(item) {
                item.status = "pending";
            },
            markItemAsCompleted(item) {
                item.status = "completed";
            },

            markItemAsCanceled(item) {
                item.status = "canceled";
            },

            currentStatus: "all",

    // This method returns the background color based on order status
            getOrderBackgroundColor(status) {
                if (status === "ready") {
                    return "#ffff004f"; // Example color for pending status
                } else if (status === "processing") {
                    return "#ffa50040"; // Example color for processing status
                } else if (status === "completed") {
                    return "#00800033"; // Example color for completed status
                } else if (status === "pending") {
                    return "#0000ff24"; // Example color for completed status
                } else if (status === "canceled") {
                    return "#ff000094"; // Example color for completed status
                } else {
                    return "#ffffff"; // Default color if status doesn't match
                }
            },

            showOrders(status) {
                this.currentStatus = status;
            },

            calculateRemainingTime(order) {
                const orderTime = new Date(order.time).getTime();

                if (isNaN(orderTime)) {
                    return "Invalid time"; // Return an error message if the time is invalid
                } else {
                    const now = new Date().getTime();
                    const remainingTime =  now - orderTime;
                    // if (remainingTime <= 0) {
                    // return "Expired";
                    // } else {
                    const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
                    const minutes = Math.floor(
                        (remainingTime % (1000 * 60 * 60)) / (1000 * 60)
                    );

                    return `${minutes}m ${seconds}s`;
                    // }
                }
            },

            updateImage(imgUrl, price, name, id) {
                this.selectedProductWithVariation.imageUrl = imgUrl;

                // Find and update the existing variation object:
                const existingVariation = this.selectedVariationsInfo.find(
                    (variation) => variation.name === name
                );
                if (existingVariation) {
                    existingVariation.price = price;
                } else {
                    // If not found, push a new object:
                    this.selectedVariationsInfo.push({
                    id: id,
                    name: name,
                    price: price,
                    });
                }
            },

            updateImageFromDropdown(variation) {
                const selectedValue = this.selectedVariations[variation.name];
                const selectedVariation = variation.values.find(
                    (v) => v.name === selectedValue
                );

                if (selectedVariation) {
                    this.selectedProductWithVariation.imageUrl = variation.varImg;

                    // Push the selected variation information into the array
                    this.selectedVariationsInfo.push({
                    name: selectedVariation.name,
                    price: selectedVariation.price,
                    });
                }
            },


            // --------------------payment Method-------------------
            totalAmount: 20000,
            payments: [{ method: "Cash", amount: "" }],
            remainingAmount: 20000,
            showDropdown: false,
            addPayment(method) {
                // Add a new payment method object to the payments array
                this.payments.push({
                    method: method,
                    amount: "",
                });
            },

            updateRemainingAmount() {
                let paidAmount = 0;
                this.payments.forEach((payment) => {
                    // Check if the payment amount is a valid number before adding to paidAmount
                    if (!isNaN(parseFloat(payment.amount))) {
                    paidAmount += parseFloat(payment.amount);
                    }
                });
                this.remainingAmount = this.totalAmount - paidAmount;
            },

            makeQuickPayment(amount) {
                // Update the amount of the default Cash payment
                if (this.payments.length > 0 && this.payments[0].method === 'Cash') {
                    this.payments[0].amount = parseFloat(this.payments[0].amount || 0) + amount;
                } else {
                    // If no default Cash payment exists, create one
                    this.payments.unshift({
                        method: "Cash",
                        amount: amount,
                    });
                }
                this.remainingAmount -= amount;
            },


            selectedLocation: localStorage.getItem("selectedLocation") || "All",
            setLocation(location) {
                this.selectedLocation = location;
                localStorage.setItem("selectedLocation", location);
            },
            selectedDepartment : localStorage.getItem("selectedDepartment") || "All",
            setDepartment(department) {
                this.selectedDepartment = department;
                localStorage.setItem("selectedDepartment", department);
            },
            selectedEmployee : localStorage.getItem("selectedEmployee") || "All",
            setEmployee(employee) {
                this.selectedEmployee = employee;
                localStorage.setItem("selectedEmployee", employee);
            },
            selectedCustomer: localStorage.getItem("selectedCustomer") || "customer",
            setCustomer(customer) {
                this.selectedCustomer = customer;
                localStorage.setItem("selectedCustomer", customer);
            },
            selectedValue: null,
            barOptions: [],
            onLocationChange(event) {
                this.selectedValue = event.target.value;
                const href = "{{ route('get.department') }}";
                $.ajax({
                    method: "GET",
                    url: href,
                    dataType: "json",
                    data: {
                        location_id :this.selectedValue
                    },
                    success: function (result) {
                        var len = result.length;
                        $("#department").empty();
                        $("#department").append("<option value=' '>Select</option>");
                        for( var i = 0; i<len; i++){
                            var id = result[i]['id'];
                            var name = result[i]['name'];
                            $("#department").append("<option value='"+id+"'>"+name+"</option>");

                        }
                    }
                });
            },

            selectedPaymentMethod: "cash",
            multytotalAmount: 15000,
            paymentAmount: 0,
            remainingMultyAmount: 15000,
            multyPayments: [
                {
                    amount: 15000,
                    method: "cash", // Initially set to cash
                    showCloseIcon: false,
                },
            ],

            updateTotalAmount(event, index) {
                // Ensure the entered amount is not negative
                this.multyPayments[index].amount = Math.max(0, event.target.value);
                this.remainingMultyAmount = this.calculateRemainingMultyAmount();
            },

            calculateRemainingMultyAmount() {
                let totalAmount = this.multytotalAmount;
                for (let payment of this.multyPayments) {
                    totalAmount -= parseFloat(payment.amount) || 0;
                }
                return Math.max(0, totalAmount);
            },

            addMultyPayment(method) {
                const remainingAmount = this.calculateRemainingMultyAmount();

                if (remainingAmount > 0) {
                    this.multyPayments.push({
                    amount: remainingAmount,
                    method: method, // Reset to the specified method for new payment
                    showCloseIcon: true,
                    });

                    // Optionally, you can update the remaining amount here if needed
                    this.remainingMultyAmount = this.calculateRemainingMultyAmount();
                } else {
                    showMessage("No remaining amount to add.");
                }
            },

            removePaymentMethod(index) {
                // Remove the payment method at the specified index
                this.multyPayments.splice(index, 1);

                // Optionally, you can update the remaining amount here if needed
                this.remainingMultyAmount = this.calculateRemainingMultyAmount();
            },

            quickPayment(amount) {
                const remainingAmount = this.calculateRemainingMultyAmount();

                if (remainingAmount > 0) {
                    // Find the index of the selected payment method
                    const selectedIndex = this.multyPayments.findIndex(
                    (payment) => payment.method === this.selectedPaymentMethod
                    );

                    if (selectedIndex !== -1) {
                    // If the selected payment method exists, update its amount
                    const quickPaymentAmount = Math.min(amount, remainingAmount);
                    // Ensure the existing amount is treated as a number
                    const existingAmount =
                        parseFloat(this.multyPayments[selectedIndex].amount) || 0;
                    this.multyPayments[selectedIndex].amount = (
                        existingAmount + quickPaymentAmount
                    ).toFixed(2);
                    this.remainingMultyAmount = this.calculateRemainingMultyAmount();
                    } else {
                    // If the selected payment method does not exist, show an alert
                    showMessage("Selected payment method not found.");
                    }
                } else {
                    showMessage("No remaining amount to add.");
                }
            },
            sidebarButtons: [
                {
                    name: "Quantity",
                    icon: "fas fa-plus",
                    status: 1,
                    subMenu: [
                    {
                        name: "Home 1",
                        link: "demo-dashboard.html",
                    },
                    {
                        name: "About 1",
                    },
                    {
                        name: "Pos 1",
                    },
                    ],
                },
                {
                    name: "Remove",
                    icon: "fas fa-trash-alt",
                    status: 1,
                    subMenu: [
                    {
                        name: "Home 2",
                    },
                    {
                        name: "About 2",
                    },
                    {
                        name: "Pos 2",
                    },
                    ],
                },
                {
                    name: "Stock",
                    icon: "fas fa-box",
                    status: 1,
                },
                {
                    name: "Multy Payment",
                    icon: "fab fa-cc-visa",
                    status: 1,
                },
                {
                    name: "Delivery",
                    icon: "fas fa-shipping-fast",
                    status: 1,
                },
                {
                    name: "Take Away",
                    icon: "fas fa-shopping-bag",
                    status: 1,
                },
                {
                    name: "Tax",
                    icon: "fas fa-dollar-sign",
                    status: 1,
                },
                // {
                //     name: "Order",
                //     icon: "fas fa-shopping-cart",
                //     status: 1,
                // },
                {
                    name: "Refunds",
                    icon: "fas fa-hand-holding-usd",
                    status: 1,
                },
                {
                    name: "Returns",
                    icon: "fas fa-undo-alt",
                    status: 1,
                },
                {
                    name: "Gift Cards",
                    icon: "fas fa-gift",
                    status: 1,
                },
            ],

            isGrid: false,
            openSubMenu: false,
            isGrid: document.querySelectorAll(".buttons > button").length > 6,
            opneSidebar: false,
            activeButtonIndex: null,

            filteredButtons() {
                return this.sidebarButtons.filter((button) => button.status === 1);
            },

            toggleSidebarMenu(btnIndex) {
                const button = this.sidebarButtons[btnIndex];
                if (button.subMenu.length > 0) {
                    if (this.activeButtonIndex === btnIndex) {
                    // If the same button is clicked again, close its submenu
                    button.openSubMenu = !button.openSubMenu;
                    if (!button.openSubMenu) {
                        this.activeButtonIndex = null; // Reset activeButtonIndex
                    }
                    } else {
                    // Close the currently active submenu, if any
                    if (this.activeButtonIndex !== null) {
                        this.sidebarButtons[this.activeButtonIndex].openSubMenu = false;
                    }
                    // Open the clicked button's submenu
                    button.openSubMenu = true;
                    this.activeButtonIndex = btnIndex; // Set activeButtonIndex to the clicked button's index
                    }
                }
            },

            toggleSidebar() {
                // If sidebar is being closed, deselect the active button
                if (!this.opneSidebar) {
                    this.activeButtonIndex = null;
                }
                this.opneSidebar = !this.opneSidebar;
            },

            selectedPaymentDetailsMethod: "cash",
    multytotalAmount: 15000,
    paymentAmount: 0,
    remainingMultyAmountDetails: 15000,
    multyPaymentsDetails: [
      {
        amount: 15000,
        method: "cash", // Initially set to cash
        showCloseIcon: false,
      },
    ],

    updateTotalAmountDetails(event, index) {
      // Ensure the entered amount is not negative
      this.multyPaymentsDetails[index].amount = Math.max(0, event.target.value);
      this.remainingMultyAmountDetails =
        this.calculateRemainingMultyAmountDetails();
    },

    calculateRemainingMultyAmountDetails() {
      let totalAmount = this.multytotalAmount;
      for (let payment of this.multyPaymentsDetails) {
        totalAmount -= parseFloat(payment.amount) || 0;
      }
      return Math.max(0, totalAmount);
    },

    addMultyPaymentDetails(method) {
      const remainingAmount = this.calculateRemainingMultyAmountDetails();

      if (remainingAmount > 0) {
        this.multyPaymentsDetails.push({
          amount: remainingAmount,
          method: method, // Reset to the specified method for new payment
          showCloseIcon: true,
        });

        // Optionally, you can update the remaining amount here if needed
        this.remainingMultyAmountDetails =
          this.calculateRemainingMultyAmountDetails();
      } else {
        showMessage("No remaining amount to add.");
      }
    },

    removePaymentMethod(index) {
      // Remove the payment method at the specified index
      this.multyPaymentsDetails.splice(index, 1);

      // Optionally, you can update the remaining amount here if needed
      this.remainingMultyAmountDetails =
        this.calculateRemainingMultyAmountDetails();
    },

    isNewCustomerFormVisible: false,
    isExistingCustomerSearchVisible: false,
    isNewCustomerSelected: false,
    isExistingCustomerSelected: false,
    customerSectionVisible: true,
    orderTypeSectionVisible: false,
    dineInSection: false,
    hotelSection: false,

    newCustomer: {
      name: "",
      mobileNo: "",
    },
    existingCustomers: [],

    customerSearchQuery: "",
    customerSearchResults: [],
    selectedExistingCustomer: null,

    showNewCustomerForm() {
      this.isNewCustomerFormVisible = true;
      this.isExistingCustomerSearchVisible = false;
      this.isExistingCustomerSelected = false;
    },
    showExistingCustomerSearch() {
      this.isNewCustomerFormVisible = false;
      this.isExistingCustomerSearchVisible = true;
      this.isNewCustomerSelected = false;
    },
    addCustomer() {
        console.log("Adding new customer:", this.newCustomer);
        
        // Store only the name of the new customer in localStorage
        const quick_url = "{{action('Rest\ContactController@quickAdd')}}";
        var newCust = null;
        $.ajax({
            url: quick_url,
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                first_name: $('#quick_name').val(),
                mobile_no: $('#quick_mobile').val(),
            },
            success: function (result) {
                if (result.success == true) 
                {
                    showMessage(result.msg);
                    $("select#customer").append($('<option>', {
                            value: result.data.id,
                            text: result.data.first_name
                        }));
                        $('select#customer').val(result.data.id).trigger("change");
                        localStorage.setItem("selectedCustomer", result.data.id);
                }
                else
                {
                    showMessage(result.msg);
                }
                
            }
        });
        // Reset form fields
        this.newCustomer = {
            name: "",
            code: "",
            place: "",
            country: "",
            age: "",
        };
        
        this.addCustomerPop = false;
        this.isNewCustomerFormVisible = false;
        this.isNewCustomerSelected = true; // Mark a new customer as selected
        // Hide customer section and display order type section
        this.customerSectionVisible = false;
        this.orderTypeSectionVisible = true;
    
    },

    searchExistingCustomers() {
      if (this.customerSearchQuery.trim() === "") {
        this.customerSearchResults = []; // If search query is empty, clear search results
        return;
      }

      this.customerSearchResults = this.existingCustomers.filter((customer) => {
        return (
          customer.name
            .toLowerCase()
            .includes(this.customerSearchQuery.toLowerCase()) ||
          customer.mobile
            .toString()
            .includes(this.customerSearchQuery.toLowerCase())
        );
      });
    },

    selectExistingCustomer(customer) {
      // Store only the name of the selected existing customer in localStorage
      localStorage.setItem("selectedCustomer", customer.id);
      $('#customer [value='+customer.id+']').attr('selected', 'true');
      this.selectedExistingCustomer = customer;
      this.isExistingCustomerSelected = true;
      this.isNewCustomerSelected = false;
      this.isExistingCustomerSearchVisible = false;
      this.customerSearchQuery = "";
      this.customerSearchResults = [];

      // Hide customer section and display order type section
      this.customerSectionVisible = false;
      this.orderTypeSectionVisible = true;
    },

    selectedOrderType: null, // Add this property
    selectOrderType(orderType) {
      this.selectedOrderType = orderType;
      this.orderTypeSectionVisible = false; // Hide the order type section

      // Show the specific section based on the selected order type
      if (orderType === "Dine in") {
        this.dineInSection = true;
      } else if (orderType === "Room Order") {
        this.hotelSection = true;
      } else {
        this.orderTypeSectionVisible = true;
        this.entryPopup = null;
        this.customerSectionVisible = true;
      }
    },

    selectDineInButton(index) {
      // Update selection in the order type section
      this.selectedDineInButtonIndex = index;
    },

    goBackToCustomer() {
      this.customerSectionVisible = true; // Show the order type section
      this.orderTypeSectionVisible = false; // Show the order type section
    },

    goBack() {
      this.orderTypeSectionVisible = true; // Show the order type section
      this.dineInSection = false; // Hide the dine-in section
      this.hotelSection = false; // Hide the hotel section
    },

            openCash()
            {
                this.cashPopUp = true;
            },
            closeCash()
            {
                this.closePopUp = true;
                const url = "{{action('CashRegisterController@getRegisterDetails')}}";
                $.ajax({
                    method: "GET",
                    url: url,
                    dataType: "json",
                    success: function (result) {
                        const total = parseFloat(result.cash_in_hand) + parseFloat(result.total_cash_sale) - parseFloat(result.total_cash_purchase);
                        $('form#cash_register_form span#opening_balance').text(result.cash_in_hand);
                        $('form#cash_register_form span#cre_cash_payment').text(result.total_cash_sale);
                        $('form#cash_register_form span#deb_cash_payment').text(result.total_cash_purchase);
                        $('form#cash_register_form span#cre_cheque_payment').text(result.total_cheque_sale);
                        $('form#cash_register_form span#deb_cheque_payment').text(result.total_cheque_purchase);
                        $('form#cash_register_form span#cre_card_payment').text(result.total_card_sale);
                        $('form#cash_register_form span#deb_card_payment').text(result.total_card_purchase);
                        $('form#cash_register_form span#cre_credit_payment').text(result.total_credit_sale);
                        $('form#cash_register_form span#deb_credit_payment').text(result.total_credit_purchase);
                        $('form#cash_register_form span#cre_bank_transfer_payment').text(result.total_bank_transfer_sale);
                        $('form#cash_register_form span#deb_bank_transfer_payment').text(result.total_bank_transfer_purchase);
                        $('form#cash_register_form span#cre_other_payment').text(result.total_other_sale);
                        $('form#cash_register_form span#deb_other_payment').text(result.total_other_purchase);
                        $('form#cash_register_form span#total_sale').text(result.total_sale);
                        $('form#cash_register_form span#total_purchase').text(result.total_purchase);
                        $('form#cash_register_form input#closing_amount').val(total)
                        $('form#cash_register_form input#closing_grand_total').val(total)
                    }
                });
            }
        }));
    });
    </script>
</body>

</html>