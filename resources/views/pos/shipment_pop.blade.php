<div x-show.transition.duration.500ms="shipmentPopup" @click.away="shipmentPopup = null"
            :class="{ 'popup-hidden': !shipmentPopup, 'popup-visible': shipmentPopup }" class="popup">
    <div>
        <h6 style="
        background: skyblue;
        color: white;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 10px 20px;" class="menu-title">Shipment</h6>
    </div>
    <div style="margin-top: 35px;">
        <div>
            <div>
                <div style="margin-top: 20px;">
                    <!-- Radio buttons for default and new address -->
                    <div style="margin-bottom: 10px;">
                        <input type="radio" id="defaultAddress" name="address_type" value="default"
                            x-model="addressType" checked>
                        <label style="margin-bottom: 0;" for="defaultAddress">Default Address</label>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <input type="radio" id="newAddress" name="address_type" value="new"
                            x-model="addressType">
                        <label style="margin-bottom: 0;" for="newAddress">New Address</label>
                    </div>
                </div>
                <!-- Input fields for the new address (conditionally shown) -->
                <div x-show="addressType === 'new'">
                    <div class="grid grid-cols-2 gap-2">
                        <input class="form-input" type="text" placeholder="Address 1" name="shipment[address_one]"/>
                        <input class="form-input" type="text" placeholder="Address 2"  name="shipment[address_two]"/>
                        <input class="form-input" type="text" placeholder="City"  name="shipment[city]"/>
                        <input class="form-input" type="text" placeholder="State" name="shipment[state]"/>
                        <input class="form-input" type="text" placeholder="Zipcode"  name="shipment[zipcode]"/>
                        <input class="form-input" type="text" placeholder="Country"   name="shipment[country]"/>
                    </div>
                </div>
            </div>
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger" @click="shipmentPopup = null">Close</button>
        </div>
    </div>
</div>