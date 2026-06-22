<div x-show.transition.duration.500ms="stockPopup"
            :class="{ 'popup-hidden': !stockPopup, 'popup-visible': stockPopup }" class="popup"
            @click.away="stockPopup = null" style="width: 900px;">
            <div>
                <h6 style="
                background: skyblue;
                color: white;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                padding: 10px 20px;" class="menu-title">Stock</h6>
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
            <div class="table-responsive border-colored mt-1">
                <table class="fixed-header">
                    <thead>
                        <tr>
                            <th>SKU Code</th>
                            <th>Barcode</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Stocks</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($stock_data as $stock)
                            <tr >
                                <td class="text-center"
                                    style="padding: 5px 0;display: flex;align-items: center; justify-content: center;"
                                    >{{$stock['skuCode']}}</td>
                                <td class="text-center" style="padding: 5px 0">{{$stock['barcode']}}
                                </td>
                                </td>
                                <td class="text-center" style="padding: 5px 0">{{$stock['category']}}
                                </td>
                                <td class="text-center" style="padding: 5px 0; padding-right: 10px;"
                                >{{$stock['name']}}</td>
                                <td class="text-center" style="padding: 5px 0; padding-right: 10px;"
                                    >{{$stock['stocks']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger" @click="stockPopup = null">Close</button>
        </div>
    </div>
</div>