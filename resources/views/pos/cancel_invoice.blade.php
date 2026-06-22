<div x-show.transition.duration.500ms="cancelInvoicePopup"
            :class="{ 'popup-hidden': !cancelInvoicePopup, 'popup-visible': cancelInvoicePopup }" class="popup"
            style="width: 80%;" @click.away="cancelInvoicePopup = null">
    <div>
        <h6 style="
        background: skyblue;
        color: white;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 10px 20px;" class="menu-title">Cancel Invoice</h6>
    </div>
    <div style="margin-top: 35px;">
        <div>
            <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 20px;">
                <!-- search  -->
                <div class="m-0" @click.outside="search = false" style="width: 100%;">
                    <form style="margin: 0 !important;"
                        class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0"
                        @submit.prevent="handleStockSearchInput">
                        <div class="relative">
                            <input type="text"
                                class="peer border-colored form-input bg-gray-100 placeholder:tracking-widest ltr:pl-9 ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                                placeholder="Product Name / SKU / Barcode" x-model="StockSearchTerm"
                                @input="handleStockSearchInput" />
                            <button type="button"
                                class="absolute inset-0 h-9 w-9 appearance-none peer-focus:text-primary ltr:right-auto rtl:left-auto btn-hover">
                                <svg class="mx-auto" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor"
                                        stroke-width="1.5" opacity="0.5" />
                                    <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div style="max-height: 70vh; height: auto; overflow: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Location</th>
                            <th>Referance Number</th>
                            <th>Order Type</th>
                            <th>Customer Name</th>
                            <th>Mobile No</th>
                            <th>Line Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop through heldInvoices -->
                        <template x-for="(invoice, invoiceIndex) in canceledData"
                            :key="'invoice-' + invoiceIndex">
                            <tr @click="cancelInvoice(invoiceIndex)" style="cursor: pointer;">
                                <td style="font-weight: bold;">
                                    <span style="text-transform: capitalize;"
                                        x-text="invoice.no"></span>
                                </td>
                                <td>
                                    <span style="text-transform: capitalize;"
                                        x-text="invoice.location"></span>
                                </td>
                                <td style="font-weight: bold;">
                                    <span style="text-transform: capitalize;"
                                        x-text="invoice.invoiceNo"></span>
                                </td>
                                <td>
                                    <span style="text-transform: capitalize;"
                                        x-text="invoice.type"></span>
                                </td>
                                <td>
                                    <span style="text-transform: capitalize;"
                                        x-text="invoice.cusName"></span>
                                </td>
                                <td>
                                    <span style="text-transform: capitalize;"
                                        x-text="invoice.mobileNo"></span>
                                </td>
                                <td>
                                    <span style="text-transform: capitalize;"
                                        x-text="invoice.lineTotal"></span>
                                </td>
                                <td>
                                    <span
                                        style="background-color: orange; padding: 5px 20px; border-radius: 5px; width: fit-content; text-transform: uppercase;"
                                        x-text="invoice.status"></span>
                                </td>
                                <td colspan="4"></td> <!-- Empty columns for order-specific data -->
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger"
                @click="cancelInvoicePopup = null">Close</button>
        </div>
    </div>
</div>