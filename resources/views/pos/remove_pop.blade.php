<div x-show.transition.duration.500ms="removePopup"
            :class="{ 'popup-hidden': !removePopup, 'popup-visible': removePopup}" class="popup"
            @keydown.enter="removeFromPopup(selectedProduct)" @click.away="removePopup = null">
    <div>
        <h6 style="
        background: skyblue;
        color: white;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 10px 20px;" class="menu-title">Remove Product</h6>
    </div>
    <div style="margin-top: 35px;">
        <div>
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 10px;">
                <label class="m-0" style="width: 30%;" for="description">Description
                    :</label>
                <p class="form-input m-0" x-text="myProducts[selectedProduct].description">
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 10px;">
                <label class="m-0" style="width: 30%;" for="quantity">Quantity :</label>
                <p class="form-input m-0" x-text="myProducts[selectedProduct].qty"></p>
            </div>
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 10px;">
                <label class="m-0" style="width: 30%;" for="discount">Discount :</label>
                <p class="form-input m-0" x-text="myProducts[selectedProduct].dis"></p>
            </div>
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 10px;">
                <label class="m-0" style="width: 30%;" for="price">Price :</label>
                <p class="form-input m-0" x-text="myProducts[selectedProduct].price"></p>
            </div>
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger" @click="removePopup = null">Close</button>
            <button type="button" class="btn btn-primary"
                @click="removeFromPopup(selectedProduct)">Remove</button>
        </div>
    </div>
</div>