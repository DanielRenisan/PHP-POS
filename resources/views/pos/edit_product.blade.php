<div x-show.transition.duration.500ms="editProductsPopup"
            :class="{ 'popup-hidden': !editProductsPopup, 'popup-visible': editProductsPopup }" class="popup"
            @keydown.enter="updateProduct" @click.away="editProductsPopup = null">
    <div>
        <h6 style="
        background: skyblue;
        color: white;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 10px 20px;" class="menu-title">Edit Product</h6>
    </div>
    <div style="margin-top: 35px;">
        <div style="margin-bottom: 10px;">
            <label for="description">Description</label>
            <input type="text" class="form-input" x-model="myProducts[selectedProduct].description"
                min="0" disabled style="opacity: 0.5;">
        </div>
        <div style="margin-bottom: 10px;">
            <label for="qty">Quantity</label>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="custom-qty-button" @click="decrementQty">-</button>
                <input type="number" class="form-input"
                    x-model="myProducts[selectedProduct].qty" min="0" style="width: 30%;">
                <button type="button" class="custom-qty-button" @click="incrementQty">+</button>
            </div>
            <!-- <input type="text" class="form-input" x-model="myProducts[selectedProduct].qty" min="0"> -->
        </div>
        <div style="margin-bottom: 10px;">
            <label for="dis">Discount</label>
            <input type="text" class="form-input" x-model="myProducts[selectedProduct].dis" min="0">
        </div>
        <div style="margin-bottom: 10px;">
            <label for="price">Price</label>
            <input type="text" class="form-input" x-model="myProducts[selectedProduct].price" min="0">
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger"
                @click="editProductsPopup = null">Close</button>
            <button type="button" class="btn btn-primary" @click="updateProduct">Update</button>
        </div>
    </div>
</div>