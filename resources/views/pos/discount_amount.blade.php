<div x-show.transition.duration.500ms="disAmountPopup" @click.away="disAmountPopup = null"
                                :class="{ 'popup-hidden': !disAmountPopup, 'popup-visible': disAmountPopup }"
                                class="popup" @keydown.enter="disAmountPopup = null">
    <div>
        <h6 style="
            background: skyblue;
            color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 20px;" class="menu-title">Discount Amount</h6>
    </div>
    <div style="margin-top: 35px;">
        <div>
            <div style="margin-top: 20px;">
                <input type="number" class="form-input" min="0" placeholder="Discount"
                    x-model="discount">
            </div>
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger"
                @click="disAmountPopup = null">Close</button>
        </div>
    </div>
</div>
