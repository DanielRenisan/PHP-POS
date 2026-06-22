<div x-show.transition.duration.500ms="quantityPopup"
            :class="{ 'popup-hidden': !quantityPopup, 'popup-visible': quantityPopup }" class="popup"
            @keydown.enter="updateSelectedProductQty" @click.away="quantityPopup = null">
    <div>
        <h6 style="
        background: skyblue;
        color: white;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 10px 20px;" class="menu-title">Quantity</h6>
    </div>
    <div style="margin-top: 35px;">
        <div>
            <label style="margin-bottom: 10px;" for="qty">Selected Product Quantity</label>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="custom-qty-button" @click="decrementQty">-</button>
                <input type="number" class="form-input"
                    x-model="myProducts[selectedProduct].qty" min="0"  style="width: 30%;">
                <button type="button" class="custom-qty-button" @click="incrementQty">+</button>
            </div>
            <!-- <input type="text" class="form-input" x-model="myProducts[selectedProduct].qty" min="0"> -->
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger" @click="quantityPopup = null">Close</button>
            <button type="button" class="btn btn-primary" @click="updateSelectedProductQty">Update
                Quantity</button>
        </div>
    </div>
</div>