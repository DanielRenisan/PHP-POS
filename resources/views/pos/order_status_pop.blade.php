<div x-show.transition.duration.500ms="orderStatusPopup"
    :class="{ 'popup-hidden': !orderStatusPopup, 'popup-visible': orderStatusPopup }"
    class="popup" @click.away="orderStatusPopup = null"
    style="width: 82%; overflow: hidden; max-height: 100vh;">
    <div>
        <div>
            <h6 style="
            background: skyblue;
            color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 20px;" class="menu-title">Orders Status</h6>
            <div style="margin-top: 35px;">
                <div style="margin-top: 20px; height: 470px; overflow: auto;">
                    <div>
                        <div class="grid grid-cols-3 gap-2" style="margin-top: 12px">
                            <template x-for="order in kotDisplayOrders" :key="order.no">
                                <div :style="{
                                                'max-width': '500px',
                                                'width': 'auto',
                                                'border': '1px solid rgb(135 206 235)',
                                                'padding': '10px',
                                                'border-radius': '5px'
                                            }">
                                    <div class="deleteOrder" style="
                                    background: white;
                                    margin-top: -20px;
                                    margin-bottom: 10px;
                                    width: 10%;
                                    text-align: center;
                                    cursor: pointer;">
                                        <!-- Delete button for entire order -->
                                        <button @click="deleteOrder(order)" style="
                                        border: 1px solid #f40707;
                                        padding: 3px 7px;
                                        border-radius: 5px;
                                        font-size: 20px;
                                        margin: 0;
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;">
                                            <i style="font-size: 12px;color:#f40707;"
                                                class="fas fa-minus"></i></button>
                                    </div>

                                    <div
                                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                        <p
                                        style="margin: 0; font-size: 14px; font-weight: bold;">
                                        <span x-text="order.invoiceNo"></span>
                                        </p>
                                        <span
                                            style="font-size: 14px; font-weight: bold;"
                                            x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                    </div>
                                    <div>
                                    <p
                                        style="margin: 0; font-size: 11px; font-weight: bold;">
                                        <span x-text="order.type"></span>
                                        </p>
                                        <span
                                            style="font-size: 11px; font-weight: bold;"
                                            x-text="order.date"></span>
                                    </div>
                                    <div
                                        style="display: flex; align-items: center; gap: 10px;">
                                        <p
                                            style="margin: 0; font-size: 14px; font-weight: bold;">
                                            <span x-text="order.cusName"></span>
                                        </p>
                                    </div>

                                    <hr style="margin: 20px 0;">

                                    <div class="list">
                                        <template
                                            x-for="(item, index) in order.products"
                                            :key="index">
                                            <ul style="padding: 0;">
                                                <li style="margin-bottom: 20px;">
                                                    <div
                                                        style="display: flex; align-items: center; justify-content: space-between;">
                                                        <div style="width: 20%">
                                                            <span
                                                                style="font-size: 13px; font-weight: bold; margin-right: 10px;"
                                                                x-text="item.qty"></span>
                                                            <span
                                                                style="font-size: 13px; font-weight: bold;white-space: nowrap;"
                                                                x-text="item.description"></span>
                                                        </div>
                                                        <div class="status">
                                                            <span :class="{
                                                                'status-pill': true,
                                                                'status-ready': item.status === 'ready',
                                                                'status-processing': item.status === 'processing',
                                                                'status-pending': item.status === 'pending',
                                                                'status-completed': item.status === 'completed',
                                                                'status-canceled': item.status === 'canceled'
                                                                }"
                                                                style="font-size: 13px; text-transform: capitalize;"
                                                                x-text="item.status"></span>
                                                            <!-- Delete button for individual product -->
                                                            <template x-if="item.status !== 'canceled'">
                                                                <button class="ml-2" type="button"
                                                                    @click="cancelProduct(order, item)">
                                                                    <i
                                                                        class="fa fa-times"></i></button>
                                                            </template>
                                                            <!-- <template x-if="item.status !== 'canceled'">
                                                                <button class="ml-2" type="button"
                                                                    @click="deleteProduct(order, item)">
                                                                    <i style="color: rgb(248, 124, 124)"
                                                                        class="fas fa-trash-alt"></i></button>
                                                            </template> -->
                                                            
                                                        </div>
                                                    </div>
                                                    <ul>
                                                        <template
                                                            x-for="variation in item.variations"
                                                            :key="variation">
                                                            <li
                                                                style="margin-left: 10px;">
                                                                <span
                                                                    x-text="variation"></span>
                                                            </li>
                                                        </template>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style=" display: flex; align-items: center; gap: 20px; justify-content: flex-end;
                    margin-top: 20px;">
            <button type="button" class="btn btn-danger"
                @click="orderStatusPopup = null">Close</button>
        </div>
    </div>
</div>