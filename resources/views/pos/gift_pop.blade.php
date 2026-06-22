<div x-show.transition.duration.500ms="giftCardPopup" @click.away="giftCardPopup = null"
    :class="{ 'popup-hidden': !giftCardPopup, 'popup-visible': giftCardPopup }"
    class="popup" @keydown.enter="giftCardPopup = null">
    <div>
        <h6 style="
                    background: skyblue;
                    color: white;
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    padding: 10px 20px;" class="menu-title">Gift Card</h6>
    </div>
    <div style="margin-top: 35px;">
        <div>
            <div style="margin-top: 20px;">
                <input type="number" class="form-input" min="0" placeholder="Gift Card"
                    x-model="giftCard">
            </div>
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger"
                @click="giftCardPopup = null">Close</button>
        </div>
    </div>
</div>