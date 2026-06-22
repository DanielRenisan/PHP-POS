<div x-show.transition.duration.500ms="cashPopup"
                                    :class="{ 'popup-hidden': !cashPopup, 'popup-visible': cashPopup }" class="popup"
                                    @click.away="cashPopup = null"
                                    style="width: 85%; max-height: 100vh; height: auto; overflow: hidden;">
                                    <!-- <form @keyup.enter="cashPopup = null" id="cash-payment-form"> -->
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
                                                <h6 @click="cashPopup = null"
                                                    style="position: absolute; top: 3px; right: 20px; cursor: pointer; background-color: red; padding: 5px 10px; border: 1px solid lightgray; margin: 0; color: white; border-radius: 5px;">
                                                    X</h6>
                                            </div>

                                            <div style="display: flex; margin-top: 35px; align-items: center;">
                                                <div class="payment-method" style="width: 70%;">
                                                    <div class="total-ammount">
                                                        <h6>Total Amount</h6>
                                                        <h4 x-text="formatCurrency(calculateGrandTotal())"></h4>
                                                    </div>
                                                    <template x-if="selectedRoom">
                                                        <div>
                                                            <label class="checkbox-label">
                                                                <input class="checkbox-round" type="checkbox" value="1" name="is_include" id="is_include" @change="checkBoxChange()">
                                                                Include Hotel Bill
                                                            </label>
                                                        </div>
                                                    </template>
                                                    <!-- Payment Inputs -->
                                                    <div>
                                                        <label>
                                                                Sevice Charge (%)
                                                        </label>
                                                        <input class="form-input" type="text" value="" name="charge" x-model="seviceCharge"> 
                                                        <input class="form-input" type="hidden" value="" name="service_charge" x-model="calculateServiceCharge()">       
                                                    </div>
                                                    <div
                                                        style="margin: 20px 0; max-height: 240px; height: auto; overflow: auto;">
                                                        <template x-for="(payment, index) in payments" :key="index"
                                                            x-if="index > 0">
                                                            <div
                                                                class="grid grid-cols-2 gap-4 text-center payment-details">
                                                                <input type="hidden" name="payment[0][method]" value="cash">
                                                                <div
                                                                    style="border-right: 1px solid lightgray;">
                                                                    <label for="">Payable Ammount</label>
                                                                    <input class="form-input" type="number" name="payment[0][amount]"
                                                                        x-model="calculateGrandFinTotal"
                                                                        
                                                                        placeholder="Amount...">
                                                                </div>
                                                                <div>
                                                                    <label for="">Account Balance</label>
                                                                    <h2 x-text="formatCurrency(calculateGrandTotal())"></h2>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-4 text-center payment-notes">
                                                        <div
                                                            style="display: flex; flex-direction: column; align-items: flex-start;">
                                                            <label for="">Payment Note</label>
                                                            <textarea
                                                                style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                                name="payment[0][note]" placeholder="Payment Note.." x-model="note"></textarea>
                                                        </div>
                                                        <div
                                                            style="display: flex; flex-direction: column; align-items: flex-start;">
                                                            <label for="">Sell Note</label>
                                                            <textarea
                                                                style="border: 1px solid lightgray; width: 100%; padding: 15px; border-radius: 8px;"
                                                                name="sellNote" placeholder="Sell Note.."  x-model="sellNote"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="quick-payment" style="width: 30%;">
                                                    <!-- Quick Cash Payment Buttons -->
                                                    <div class="quick-payment-btns"
                                                        style="display: flex; justify-content: center; align-items: center; gap: 10px; flex-direction: column; justify-content: center;">
                                                        <button type="button" class="btn btn-dark"
                                                            @click="makeQuickPayment(100)"> Rs.100</button>
                                                        <button type="button" class="btn btn-dark"
                                                            @click="makeQuickPayment(500)"> Rs.500</button>
                                                        <button type="button" class="btn btn-dark"
                                                            @click="makeQuickPayment(1000)">Rs.1000</button>
                                                        <button type="button" class="btn btn-dark"
                                                            @click="makeQuickPayment(5000)">Rs.5000</button>
                                                        <button type="button" class="btn btn-dark"
                                                            @click="makeQuickPayment(10000)">Rs.10000</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
                                                <button style="width: 120px;" type="button" class="btn btn-primary"
                                                    @click="cashPopup = null">Close</button>
                                                <button style="width: 120px;" type="button" class="btn btn-primary"
                                                    @click="cashPayment()">Paid</button>
                                            </div>
                                        </div>
                                    <!-- </form> -->
                                </div>