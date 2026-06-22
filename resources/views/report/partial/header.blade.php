<div class="total-details grid grid-cols-3 gap-4 mb-5" style="width: 100%;">
    <div class="box" style="padding: 10px;">
        <div class="top flex items-center gap-2">
            <div
                class="grid h-9 w-9 place-content-center rounded-full dark:bg-success dark:text-success-light" style="background:skyblue;color:#fff;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                        stroke="currentColor" stroke-width="1.5" />
                    <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                        transform="rotate(-45 8.60699 8.87891)" stroke="currentColor"
                        stroke-width="1.5" />
                    <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
            <h6>Total Sales</h6>
        </div>
        <div class="bottom">
            <h2><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true" id="total_payable">{{number_format($transactions->sum('grand_total'), 2)}}</span></h2>
        </div>
        <!-- <p class="m-0">Total Sales for this Month</p> -->
    </div>
    <div class="box" style="padding: 10px;">
        <div class="top flex items-center gap-2">
            <div
                class="grid h-9 w-9 place-content-center rounded-full  dark:bg-success dark:text-success-light"  style="background:skyblue;color:#fff;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                        stroke="currentColor" stroke-width="1.5" />
                    <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                        transform="rotate(-45 8.60699 8.87891)" stroke="currentColor"
                        stroke-width="1.5" />
                    <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
            <h6>Recieved Payment</h6>
        </div>
        <div class="bottom">
            <h2><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true" id="total_paid">{{number_format($transactions->sum('paid_amount'), 2)}}</span></h2>
        </div>
        <!-- <p class="m-0">Total Sales for this Month</p> -->
    </div>
    <div class="box" style="padding: 10px;">
        <div class="top flex items-center gap-2">
            <div
                class="grid h-9 w-9 place-content-center rounded-full  dark:bg-success dark:text-success-light"  style="background:skyblue;color:#fff;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                        stroke="currentColor" stroke-width="1.5" />
                    <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                        transform="rotate(-45 8.60699 8.87891)" stroke="currentColor"
                        stroke-width="1.5" />
                    <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
            <h6>Total Due</h6>
        </div>
        <div class="bottom">
            <h2><span class="ltr:text-right rtl:text-left display_currency" data-currency_symbol="true" id="total_due">{{number_format($transactions->sum('due_amount'), 2)}}</span></h2>
        </div>
        <!-- <p class="m-0">Total Sales for this Month</p> -->
    </div>
</div>