<!-- Multy Payment popup -->
<div x-show.transition.duration.500ms="multyPaymentPopup"
    :class="{ 'popup-hidden': !multyPaymentPopup, 'popup-visible':multyPaymentPopup }"
    class="popup" @click.away="multyPaymentPopup = null"
    style="width: 85%; max-height: 100vh; height: 95vh; overflow: hidden;">
    <!-- <form @keyup.enter="multyPaymentPopup = null"> -->
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
                    padding: 10px 20px;" class="menu-title">Cash Payment</h6>
                <h6 @click="multyPaymentPopup = null"
                    style="position: absolute; top: 3px; right: 20px; cursor: pointer; background-color: red; padding: 5px 10px; border: 1px solid lightgray; margin: 0; color: white; border-radius: 5px;">
                    X</h6>
            </div>

            <div class="payment-body"
                style="max-height: 78vh; height: auto; overflow: auto; margin: 35px 0 20px 0;">
                <div style="display: flex; align-items: center;">
                    <div class="payment-method" style="width: 70%;">
                        <div class="total-ammount">
                            <h6>Total Amount</h6>
                            <h4 x-text="'Rs' + calculateGrandTotal().toFixed(2)"></h4>
                            <small>Remaining: Rs<span x-text="remainingMultyAmount.toFixed(2)"></span></small>
                        </div>
                        <template x-if="selectedRoom">
                        <div>
                            <label class="checkbox-label">
                                <input class="checkbox-round" type="checkbox" value="1" name="is_include" id="is_include" @change="checkBoxChange()">
                                Include Hotel Bill
                            </label>
                        </div>
                        </template>
                        <div>
                            <label>
                                    Sevice Charge  (%)
                            </label>
                            <input class="form-input" type="number" step="0.1" 
           x-model.number="seviceCharge" 
           name="charge"> 
    <input class="form-input" type="hidden" 
           name="service_charge" 
           x-bind:value="calculateServiceCharge()">
                        
                        </div>
                        <div class="add=payment">
                            <!-- Quick Cash Payment Buttons -->
                            <div class="add-payment-btns"
                                style="display: flex; justify-content: center; align-items: center; gap: 10px; justify-content: center;">
                                <button type="button" class="btn btn-dark"
                                    @click="addMultyPayment('cash')">CASH</button>
                                <button type="button" class="btn btn-dark"
                                    @click="addMultyPayment('card')">CARD</button>
                                <button type="button" class="btn btn-dark"
                                    @click="addMultyPayment('cheque')">CHEQUE</button>
                                <button type="button" class="btn btn-dark"
                                    @click="addMultyPayment('bank_transfer')">BANK
                                    TRANSFER</button>
                            </div>
                        </div>

                        <div class="methods">
                            <template x-for="(payment, index) in multyPayments"
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
                                            <label for="ammount">Amount</label>
                                            <input class="form-input" 
                                                type="number" 
                                                x-bind:name="`payment[${index}][amount]`"
                                                x-model="payment.amount"
                                                x-bind:readonly="payment.method === 'credit'"
                                                x-bind:style="payment.method === 'credit' ? 'background-color: #f5f5f5; cursor: not-allowed;' : ''"
                                                @input="updateTotalAmount($event, index)">
                                        </div>
                                        <div>
                                            <!-- Payment Method Dropdown -->
                                            <label for="paymentMethod">Payment Method</label>
                                            <select class="form-select" 
                                                    x-bind:name="`payment[${index}][method]`"
                                                    id="paymentMethod"
                                                    x-model="payment.method"
                                                    @change="if (payment.method === 'credit') { 
                                                        payment.amount = 0; 
                                                        updateTotalAmount({ target: { value: 0 } }, index);
                                                        calculateGrandFinTotal = calculateGrandFinTotal;
                                                    }">
                                                <option value="cash">Cash</option>
                                                <option value="card">Card</option>
                                                <option value="cheque">Cheque</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="credit">Credit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <template x-if="payment.method === 'cash'">
                                        <div>
                                            <div class="mt-2">
                                                <label for="">Payment Note</label>
                                                <textarea
                                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                    x-bind:name="`payment[${index}][note]`"   x-model="note"
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
                                                        x-bind:name="`payment[${index}][card_number]`">
                                                </div>
                                                <div style="display: flex; gap: 10px;">
                                                    <div class="cardTransactionNUmber mt-2" style="width: 70%;">
                                                        <label for="cardNumber">Transaction 
                                                            Number</label>
                                                        <input class="form-input"
                                                            type="text" id="cardTransactionNumber"
                                                            x-bind:name="`payment[${index}][card_transaction_number]`">
                                                    </div>
                                                    <div class="cardType mt-2" style="width: 30%;">
                                                        <label for="paymentMethod">Card Type</label>
                                                        <select class="form-select" x-bind:name="`payment[${index}][card_type]`"
                                                            id="cardType">
                                                            <option value="visa">Visa</option>
                                                            <option value="master">MasterCard</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div style="display: flex; gap: 10px;">
                                                    <div class="cardName mt-2"
                                                        style="width: 70%;">
                                                        <label for="cardNumber">Card
                                                            Holder
                                                            Name</label>
                                                        <input class="form-input"
                                                            type="text" id="cardName"
                                                            x-bind:name="`payment[${index}][card_holder_name]`">
                                                    </div>
                                                    <div class="cardCvv mt-2"
                                                        style="width: 30%;">
                                                        <label for="cvv">CVV</label>
                                                        <input class="form-input"
                                                            type="text" id="cvv"
                                                            x-bind:name="`payment[${index}][card_security]`">
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div class="cardMonth mt-2">
                                                        <label
                                                            for="cardMonth">Month</label>
                                                        <input class="form-input"
                                                            type="text" id="cardMonth"
                                                            placeholder="Month" x-bind:name="`payment[${index}][card_month]`">
                                                    </div>
                                                    <div class="cardYear mt-2">
                                                        <label
                                                            for="cardYear">Year</label>
                                                        <input class="form-input"
                                                            type="text" id="cardYear"
                                                            placeholder="Year" x-bind:name="`payment[${index}][card_year]`">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="mt-2">
                                                <label for="">Payment Note</label>
                                                <textarea
                                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                    x-bind:name="`payment[${index}][note]`"
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
                                                        x-bind:name="`payment[${index}][cheque_number]`">
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div class="chequeIssueDate mt-2">
                                                        <label
                                                            for="chequeIssueDate">Cheque
                                                            Issue Date</label>
                                                        <input class="form-input"
                                                            type="date" x-bind:name="`payment[${index}][cheque_issued_date]`"
                                                            id="chequeIssueDate">
                                                    </div>
                                                    <div class="chequeDueDate mt-2">
                                                        <label
                                                            for="chequeDueDate">Cheque
                                                            Due Date</label>
                                                        <input class="form-input"
                                                            type="date" x-bind:name="`payment[${index}][cheque_due_date]`"
                                                            id="chequeDueDate">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <label for="">Payment Note</label>
                                                <textarea
                                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                    x-bind:name="`payment[${index}][note]`"
                                                    placeholder="Payment Note.."></textarea>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="payment.method === 'bank_transfer'">
                                        <div>
                                            <div class="bankAccNo mt-2">
                                                <label for="bankAccNo">Account No</label>
                                                <input class="form-input" type="text"
                                                    id="bankAccNo" x-bind:name="`payment[${index}][bank_account_number]`"
                                                    placeholder="Refference No">
                                            </div>
                                            <div class="mt-2">
                                                <label for="">Payment Note</label>
                                                <textarea
                                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                    x-bind:name="`payment[${index}][note]`"
                                                    placeholder="Payment Note.."></textarea>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="payment.method === 'credit'">
                                        <div>
                                            <div class="mt-2">
                                                <label for="">Payment Note</label>
                                                <textarea
                                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                    x-bind:name="`payment[${index}][note]`"
                                                    placeholder="Payment Note.."></textarea>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="grid grid-cols-1 gap-4 text-center payment-notes">
                            <div
                                style="display: flex; flex-direction: column; align-items: flex-start;">
                                <label for="">Sell Note</label>
                                <textarea
                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;" x-model="sellNote"
                                    name="sellNote" placeholder="Sell Note.."></textarea>
                            </div>
                            <!-- <div
                                style="display: flex; flex-direction: column; align-items: flex-start;">
                                <label for="">Sell Note</label>
                                <textarea
                                    style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                    name="sellNote" placeholder="Sell Note.."></textarea>
                            </div> -->
                        </div>
                    </div>

                    <div class="quick-payment" style="width: 30%;">
                        <!-- Quick Cash Payment Buttons -->
                        <div class="quick-payment-btns"
                            style="display: flex; justify-content: center; align-items: center; gap: 10px; flex-direction: column; justify-content: center;">
                            <button type="button" class="btn btn-dark"
                                @click="quickPayment(100)"> Rs.100</button>
                            <button type="button" class="btn btn-dark"
                                @click="quickPayment(500)"> Rs.500</button>
                            <button type="button" class="btn btn-dark"
                                @click="quickPayment(1000)"> Rs.1000</button>
                            <button type="button" class="btn btn-dark"
                                @click="quickPayment(5000)"> Rs.5000</button>
                            <button type="button" class="btn btn-dark"
                                @click="quickPayment(10000)"> Rs.10000</button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                style="display: flex; align-items: center; gap: 20px; justify-content: flex-end;">
                <button style="width: 120px;" type="button" class="btn btn-primary"
                    @click="cashPopup = null">Close</button>
                <button style="width: 120px;" type="button" class="btn btn-primary"
                    @click="multyPayment()">Paid</button>
            </div>
        </div>
    <!-- </form>     -->
</div>