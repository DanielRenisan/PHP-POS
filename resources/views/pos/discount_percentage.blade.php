<div x-show.transition.duration.500ms="disPercentagePopup"
                                @click.away="disPercentagePopup = null"
                                :class="{ 'popup-hidden': !disPercentagePopup, 'popup-visible': disPercentagePopup }"
                                class="popup" @keydown.enter="disPercentagePopup = null">
    <div>
        <h6 style="
            background: skyblue;
            color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 20px;" class="menu-title">Discount Percentage</h6>
    </div>
    <div style="margin-top: 35px;">
        <div>
            <div style="margin-top: 20px;">
                <input type="number" class="form-input" min="0" placeholder="0%"
                    x-model="discount_percentage">
            </div>
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger"
                @click="disPercentagePopup = null">Close</button>
        </div>
    </div>
</div>
