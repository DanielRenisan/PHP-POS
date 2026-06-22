<div x-show.transition.duration.500ms="taxPopup" @click.away="taxPopup = null"
            :class="{ 'popup-hidden': !taxPopup, 'popup-visible': taxPopup }" class="popup">
    <div>
        <h6 style="
        background: skyblue;
        color: white;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 10px 20px;" class="menu-title">Tax</h6>
    </div>
    <div style="margin-top: 35px;">
        <div>
            <div style="margin-top: 20px;">
                <input type="number" class="form-input" min="0" placeholder="Tax" x-model="tax">
            </div>
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger" @click="taxPopup = null">Close</button>
        </div>
    </div>
</div>