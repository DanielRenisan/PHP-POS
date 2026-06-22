<div x-show.transition.duration.500ms="paymentDetailsPopup"
                                    :class="{ 'popup-hidden': !paymentDetailsPopup, 'popup-visible': paymentDetailsPopup }"
                                    class="popup" @click.away="paymentDetailsPopup = null"
                                    :class="{ 'popup-hidden': !paymentDetailsPopup, 'popup-visible': paymentDetailsPopup }"
                                    style="width: 80%; max-height: 100vh; height: 90vh; overflow: auto;">
                                    <form @submit.prevent="handlePayment">
                                        <div class="row">
                                            <div class="top"
                                                style="display: flex; align-items: center; justify-content: space-between;">
                                                <h6 style="
                                                    background: skyblue;
                                                    color: white;
                                                    position: absolute;
                                                    top: 0;
                                                    left: 0;
                                                    width: 100%;
                                                    padding: 10px 20px;" class="menu-title">Payment Details</h6>
                                                <h6 @click="paymentDetailsPopup = null"
                                                    style="position: absolute;
                                    top: 3px;
                                    right: 20px; cursor: pointer; background-color: red; padding: 5px 10px; border: 1px solid lightgray; margin: 0; color: white; border-radius: 5px;">
                                                    X</h6>
                                            </div>
                                            <div class="col-md-8">
                                                <div style="margin-top: 35px;">
                                                    <!-- search  -->
                                                    <div class="m-0" @click.outside="search = false">
                                                        <form style="margin: 0 !important;"
                                                            class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0"
                                                            @submit.prevent="handleStockSearchInput">
                                                            <div class="relative">
                                                                <input type="text"
                                                                    class="peer border-colored form-input bg-gray-100 placeholder:tracking-widest ltr:pl-9 ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                                                                    placeholder="Customer Name / Mobile No / Invoice No " />
                                                                <button type="button"
                                                                    class="absolute inset-0 h-9 w-9 appearance-none peer-focus:text-primary ltr:right-auto rtl:left-auto btn-hover">
                                                                    <svg class="mx-auto" width="16" height="16"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <circle cx="11.5" cy="11.5" r="9.5"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            opacity="0.5" />
                                                                        <path d="M18.5 18.5L22 22" stroke="currentColor"
                                                                            stroke-width="1.5" stroke-linecap="round" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <div class="grid grid-cols-8 gap-2 text-center mt-3"
                                                        style="max-height: 480px; height: auto; overflow: auto; grid-template-columns: repeat(6, minmax(0, 1fr));">
                                                        <template x-for="list in invoiceList" :key="list.id">
                                                            <div class="border border-colored p-2" style="
                                                                height: 60px;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;">
                                                                <div class="details">
                                                                    <h6 style="font-size: 14px; font-weight: bold; margin-bottom: 0;"
                                                                        x-text="list.no">
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div style="margin-top: 35px;">
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            <p style="margin-bottom: 0;">Invoice No: </p>
                                                        </div>
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            <p style="margin-bottom: 0;">Customer Name: <span
                                                                    x-text="selectedCustomer"></span></p>
                                                        </div>
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            <p style="margin-bottom: 0;">Total: <span
                                                                    x-text="multytotalAmount"></span></p>
                                                        </div>
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            <p style="margin-bottom: 0;">M: </p>
                                                        </div>
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            <p style="margin-bottom: 0;">Paid Ammount: <span
                                                                    x-text="remainingMultyAmountDetails"></span></p>
                                                        </div>
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            <p style="margin-bottom: 0;">Balance: <span
                                                                    x-text="remainingMultyAmountDetails"></span></p>
                                                        </div>
                                                    </div>

                                                    <div class="cal mt-2">
                                                        <input type="text"
                                                            class="peer form-input bg-gray-100 placeholder:tracking-widest ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4 border-colored"
                                                            placeholder="Calculator..." x-model="paymentCalculation"
                                                            @keydown.enter="paymentPerformCalculation" />
                                                        <div class="grid grid-cols-4">
                                                            <button type="button" @click="addToPaymentCalculation('7')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">7</button>
                                                            <button type="button" @click="addToPaymentCalculation('8')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">8</button>
                                                            <button type="button" @click="addToPaymentCalculation('9')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">9</button>
                                                            <button type="button" @click="clearPyamentCalculation"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">C</button>
                                                            <button type="button" @click="addToPaymentCalculation('4')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">4</button>
                                                            <button type="button" @click="addToPaymentCalculation('5')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">5</button>
                                                            <button type="button" @click="addToPaymentCalculation('6')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">6</button>
                                                            <button type="button" @click="addPaymentOperator('+')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">+</button>
                                                            <button type="button" @click="addToPaymentCalculation('1')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">1</button>
                                                            <button type="button" @click="addToPaymentCalculation('2')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">2</button>
                                                            <button type="button" @click="addToPaymentCalculation('3')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">3</button>
                                                            <button type="button" @click="addPaymentOperator('-')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">-</button>
                                                            <button type="button" @click="addToPaymentCalculation('0')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">0</button>
                                                            <button type="button" @click="addToPaymentCalculation('00')"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">00</button>
                                                            <button type="button" @click="addPaymentDecimal()"
                                                                class="border px-2 py-1 btn-hover border-colored btn-hover">.</button>
                                                            <button type="button" @click="paymentPerformCalculation"
                                                                class="col-span-2 border px-2 py-1 btn-hover border-colored btn-hover">
                                                                <i class="fas fa-sign-in-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="multyPaymentMethod mt-5">
                                                        <!-- <div class="total-ammount">
                                                            <h6>Total Amount</h6>
                                                            <h4 x-text="remainingMultyAmountDetails"></h4>
                                                        </div> -->
                                                        <h2>Payment Method</h2>

                                                        <div class="add=payment">
                                                            <!-- Quick Cash Payment Buttons -->
                                                            <div class="add-payment-btns details"
                                                                style="display: flex; justify-content: center; align-items: center; gap: 10px; justify-content: center;">
                                                                <button type="button" class="btn btn-dark"
                                                                    @click="addMultyPaymentDetails('cash')">CASH</button>
                                                                <button type="button" class="btn btn-dark"
                                                                    @click="addMultyPaymentDetails('card')">CARD</button>
                                                                <button type="button" class="btn btn-dark"
                                                                    @click="addMultyPaymentDetails('cheque')">CHEQUE</button>
                                                                <button type="button" class="btn btn-dark"
                                                                    @click="addMultyPaymentDetails('bank_transfer')">BANK
                                                                    TRANSFER</button>
                                                            </div>
                                                        </div>

                                                        <div class="methods">
                                                            <template x-for="(payment, index) in multyPaymentsDetails"
                                                                :key="index">
                                                                <div class="payment-method"
                                                                    style="background-color: lightgray; padding: 10px; border-radius: 8px; margin: 20px 0; position: relative">
                                                                    <div x-show="payment.showCloseIcon" x-cloak>
                                                                        <!-- Close icon for removing payment method -->
                                                                        <i style="position: absolute; right: 15px; top: 8px;"
                                                                            class="fas fa-times cursor-pointer"
                                                                            @click="removePaymentMethod(index)"></i>
                                                                    </div>
                                                                    <div class="grid grid-cols-2 gap-2">
                                                                        <div>
                                                                            <label for="ammount">Ammount</label>
                                                                            <input class="form-input" type="number"
                                                                                x-model="payment.amount"
                                                                                @input="updateTotalAmountDetails($event, index)">
                                                                        </div>
                                                                        <div>
                                                                            <!-- Payment Method Dropdown -->
                                                                            <label for="paymentMethod">Payment
                                                                                Method</label>
                                                                            <select class="form-select"
                                                                                id="paymentMethod"
                                                                                x-model="payment.method">
                                                                                <option value="cash">Cash</option>
                                                                                <option value="card">Card</option>
                                                                                <option value="cheque">Cheque</option>
                                                                                <option value="bank_transfer">Bank
                                                                                    Transfer
                                                                                </option>
                                                                                <option value="other">Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <template x-if="payment.method === 'cash'">
                                                                        <div>
                                                                            <div class="mt-2">
                                                                                <label for="">Payment Note</label>
                                                                                <textarea
                                                                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                                                    name="sellNote"
                                                                                    placeholder="Payment Note.."></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="payment.method === 'card'">
                                                                        <div>
                                                                            <div>
                                                                                <div class="cardNUmber mt-2">
                                                                                    <label for="cardNumber">Card
                                                                                        Number</label>
                                                                                    <input class="form-input"
                                                                                        type="text" id="cardNumber"
                                                                                        name="cardNumber">
                                                                                </div>
                                                                                <div style="display: flex; gap: 10px;">
                                                                                    <div class="cardName mt-2"
                                                                                        style="width: 70%;">
                                                                                        <label for="cardNumber">Card
                                                                                            Holder
                                                                                            Name</label>
                                                                                        <input class="form-input"
                                                                                            type="text" id="cardName"
                                                                                            name="cardName">
                                                                                    </div>
                                                                                    <div class="cardCvv mt-2"
                                                                                        style="width: 30%;">
                                                                                        <label for="cvv">CVV</label>
                                                                                        <input class="form-input"
                                                                                            type="text" id="cvv"
                                                                                            name="cvv">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="grid grid-cols-2 gap-2">
                                                                                    <div class="cardMonth mt-2">
                                                                                        <label
                                                                                            for="cardMonth">Month</label>
                                                                                        <input class="form-input"
                                                                                            type="text" id="cardMonth"
                                                                                            placeholder="Month">
                                                                                    </div>
                                                                                    <div class="cardYear mt-2">
                                                                                        <label
                                                                                            for="cardYear">Year</label>
                                                                                        <input class="form-input"
                                                                                            type="text" id="cardYear"
                                                                                            placeholder="Year">
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                            <div class="mt-2">
                                                                                <label for="">Payment Note</label>
                                                                                <textarea
                                                                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                                                    name="sellNote"
                                                                                    placeholder="Payment Note.."></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="payment.method === 'cheque'">
                                                                        <div>
                                                                            <div>
                                                                                <div class="cardNUmber mt-2">
                                                                                    <label for="chequeNo">Cheque
                                                                                        No</label>
                                                                                    <input class="form-input"
                                                                                        type="text" id="chequeNo"
                                                                                        name="chequeNo">
                                                                                </div>
                                                                                <div class="grid grid-cols-2 gap-2">
                                                                                    <div class="chequeIssueDate mt-2">
                                                                                        <label
                                                                                            for="chequeIssueDate">Cheque
                                                                                            Issue Date</label>
                                                                                        <input class="form-input"
                                                                                            type="date"
                                                                                            id="chequeIssueDate">
                                                                                    </div>
                                                                                    <div class="chequeDueDate mt-2">
                                                                                        <label
                                                                                            for="chequeDueDate">Cheque
                                                                                            Due Date</label>
                                                                                        <input class="form-input"
                                                                                            type="date"
                                                                                            id="chequeDueDate">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mt-2">
                                                                                <label for="">Payment Note</label>
                                                                                <textarea
                                                                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                                                    name="sellNote"
                                                                                    placeholder="Payment Note.."></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="payment.method === 'bank_transfer'">
                                                                        <div>
                                                                            <div class="bankAccNo mt-2">
                                                                                <label for="bankAccNo">Refference
                                                                                    No</label>
                                                                                <input class="form-input" type="text"
                                                                                    id="bankAccNo" name="bankAccNo"
                                                                                    placeholder="Refference No">
                                                                            </div>
                                                                            <div class="mt-2">
                                                                                <label for="">Payment Note</label>
                                                                                <textarea
                                                                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                                                    name="sellNote"
                                                                                    placeholder="Payment Note.."></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="payment.method === 'other'">
                                                                        <div>
                                                                            <div class="mt-2">
                                                                                <label for="">Payment Note</label>
                                                                                <textarea
                                                                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                                                    name="sellNote"
                                                                                    placeholder="Payment Note.."></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <button type="button" @click="handlePayment"
                                                        @keyup.enter="handlePayment"
                                                        style="width: 100%;height: 60px;margin-top: 20px;font-size: 30px;"
                                                        class="col-span-2 border px-2 py-1 btn-hover border-colored btn-hover">
                                                        Enter <i class="fas fa-sign-in-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
