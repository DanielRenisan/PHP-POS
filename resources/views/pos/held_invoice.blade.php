<div x-show.transition.duration.500ms="pickHeldInvoicePopup"
    :class="{ 'popup-hidden': !pickHeldInvoicePopup, 'popup-visible': pickHeldInvoicePopup }"
    class="popup"
    @click.away="pickHeldInvoicePopup = null"
    style="width: 80%; max-height: 90vh; overflow: hidden;">

    <!-- Header -->
    <div>
        <h6 style="
            background: skyblue;
            color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 20px;"
            class="menu-title">
            Pick Held Invoice
        </h6>
    </div>
    <div style="margin-top: 35px;">

        <!-- Search -->
        <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 20px;">
            <div class="m-0" style="width: 100%;">
                <form style="margin: 0 !important;"
                    @submit.prevent="handleStockSearchInput">
                    <div class="relative">
                        <input type="text"
                            class="peer border-colored form-input bg-gray-100"
                            placeholder="Product Name / SKU / Barcode"
                            x-model="StockSearchTerm"
                            @input="handleStockSearchInput" />
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div style="max-height: 70vh; overflow: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Reference Number</th>
                        <th>Order Type</th>
                        <th>Customer Name</th>
                        <th>Mobile No</th>
                        <th>Line Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <!-- Held Invoices -->
                    <template x-for="(invoice, invoiceIndex) in heldInvoices"
                        :key="'invoice-' + invoiceIndex">
                        <tr>
                            <td x-text="invoice.location"></td>

                            <td style="font-weight: bold;"
                                x-text="invoice.invoiceNo"></td>

                            <td x-text="invoice.type"></td>

                            <td x-text="invoice.cusName"></td>

                            <td x-text="invoice.mobileNo"></td>

                            <td x-text="invoice.lineTotal"></td>

                            <td>
                                <span
                                    style="background-color: orange; padding: 5px 20px; border-radius: 5px; text-transform: uppercase;"
                                    x-text="invoice.status">
                                </span>
                            </td>

                            <td>
                                <button type="button"
                                    class="btn btn-primary btn-sm"
                                    @click.stop="returnHeldInvoice(invoiceIndex)">
                                    Select
                                </button>

                                <button type="button"
                                    class="btn btn-info btn-sm print-invoice"
                                    data-href="{{ action('SaleController@printInvoice', '__ID__') }}"
                                    @click="$el.dataset.href = $el.dataset.href.replace('__ID__', invoice.id)">
                                    Print
                                </button>
                            </td>
                        </tr>
                    </template>

                    <!-- Held Orders -->
                    <template x-for="(order, orderIndex) in heldOrders"
                        :key="'order-' + orderIndex">
                        <tr>
                            <td x-text="order.location"></td>

                            <td style="font-weight: bold;"
                                x-text="order.invoiceNo"></td>

                            <td x-text="order.type"></td>

                            <td x-text="order.cusName"></td>

                            <td x-text="order.mobileNo"></td>

                            <td x-text="order.lineTotal"></td>

                            <td>
                                <span
                                    style="background-color: yellow; padding: 5px 20px; border-radius: 5px; text-transform: uppercase;"
                                    x-text="order.status">
                                </span>
                            </td>

                            <td>
                                <button type="button"
                                    class="btn btn-success btn-sm"
                                    @click.stop="returnHeldOrder(orderIndex)">
                                    Select
                                </button>

                                <button type="button"
                                    class="btn btn-info btn-sm print-invoice"
                                    :data-href="`{{ action('SaleController@printInvoice', ['']) }}/${order.id}`">
                                    Print
                                </button>
                            </td>
                        </tr>
                    </template>

                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            <button type="button"
                class="btn btn-danger"
                @click="pickHeldInvoicePopup = null">
                Close
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('click', function (e) {
    if (!e.target.closest('button.print-invoice')) return;

    e.preventDefault();

    const button = e.target.closest('button.print-invoice');
    const href = button.dataset.href;

    if (!href) {
        console.error('Print URL missing');
        return;
    }

    fetch(href)
        .then(res => res.json())
        .then(result => {
            if (result.html_content) {
                document.getElementById('receipt_section').innerHTML = result.html_content;
                setTimeout(() => window.print(), 1000);
            } else {
                alert(result.msg || 'Print failed');
            }
        })
        .catch(err => console.error(err));
});
</script>
