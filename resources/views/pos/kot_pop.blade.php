<div x-show.transition.duration.500ms="KotPopup"
    :class="{ 'popup-hidden': !KotPopup, 'popup-visible': KotPopup }" class="popup"
    @click.away="KotPopup = null"
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
            padding: 10px 20px;" class="menu-title">KOT Orders</h6>
            <div style="margin-top: 35px;">
                <div class="buttons-panel">
                    <button type="button" :class="{ 'active': currentStatus === 'all' }"
                        @click="showOrders('all')">All</button>
                    <button type="button"
                        :class="{ 'active': currentStatus === 'ordered' }"
                        @click="showOrders('orders')">Orders</button>
                    <button type="button"
                        :class="{ 'active': currentStatus === 'ready' }"
                        @click="showOrders('ready')">Ready</button>
                    <button type="button"
                        :class="{ 'active': currentStatus === 'processing' }"
                        @click="showOrders('processing')">Processing</button>
                    <button type="button"
                        :class="{ 'active': currentStatus === 'pending' }"
                        @click="showOrders('pending')">Pending</button>
                    <button type="button"
                        :class="{ 'active': currentStatus === 'completed' }"
                        @click="showOrders('completed')">Completed</button>
                    <button type="button"
                        :class="{ 'active': currentStatus === 'canceled' }"
                        @click="showOrders('canceled')">canceled</button>
                </div>
                <div
                    style="display: flex; gap: 10px; margin-top: 20px; height: 470px; overflow: auto;">
                    <div x-show="currentStatus === 'all'">
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="order in kotDisplayOrders" :key="order.no">
                                <div :style="{
                                                'width': '350px',
                                                'border': '1px solid var(--primary-border-color)',
                                                'padding': '10px',
                                                'border-radius': '5px',
                                                'background-color': getOrderBackgroundColor(order.status)
                                            }">
                                    <div
                                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                        <p style="margin: 0; font-size: 16px;">
                                            <span x-text="order.type"></span>
                                        </p>
                                        <span
                                            style="font-size: 14px; font-weight: bold;"
                                            x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                    </div>
                                    <p
                                        style="margin: 0; font-size: 18px; font-weight: bold;">
                                        <span x-text="order.invoiceNo"></span>
                                    </p>
                                    <div
                                        style="display: flex; align-items: center; gap: 10px;">
                                        <p
                                            style="margin: 0; font-size: 18px; font-weight: bold;">
                                            <span x-text="order.cusName66"></span>
                                        </p>
                                        -
                                        <span
                                            style="font-size: 14px; font-weight: bold;"
                                            x-text="order.mobileNo"></span>
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
                                                        <div>
                                                            <span
                                                                style="font-size: 16px; font-weight: bold; margin-right: 10px;"
                                                                x-text="item.qty"></span>
                                                            <span
                                                                style="font-size: 16px; font-weight: bold;"
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
                                                                style="font-size: 14px; text-transform: capitalize;"
                                                                x-text="item.status"></span>
                                                        </div>
                                                    </div>
                                                    <ul>
                                                        <template
                                                            x-for="variation in item.variations"
                                                            :key="variation">
                                                            <li
                                                                style="margin-left: 15px;">
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

                    <div x-show="currentStatus === 'orders'">
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="order in kotDisplayOrders">
                                <template x-for="item in order.products">
                                    <div :style="{
                                        'width': '350px',
                                        'border': '1px solid var(--primary-border-color)',
                                        'padding': '10px',
                                        'border-radius': '5px',
                                        'background-color': getOrderBackgroundColor(order.status)
                                    }">
                                        <div
                                            style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                            <p
                                                style="margin: 0; font-size: 18px; font-weight: bold;">
                                                <span x-text="order.invoiceNo"></span>
                                            </p>
                                            <span
                                                style="font-size: 14px; font-weight: bold;"
                                                x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                        </div>
                                        <div
                                            style="display: flex; align-items: center; gap: 10px;">
                                            <p
                                                style="margin: 0; font-size: 18px; font-weight: bold;">
                                                <span x-text="order.cusName"></span>
                                            </p>
                                            -
                                            <span
                                                style="font-size: 14px; font-weight: bold;"
                                                x-text="order.mobileNo"></span>
                                        </div>

                                        <hr style="margin: 20px 0;">

                                        <div class="list">
                                            <ul>
                                                <li style="margin-bottom: 20px;">
                                                    <div
                                                        style="display: flex; align-items: center; justify-content: space-between;">
                                                        <div>
                                                            <span
                                                                style="font-size: 16px; font-weight: bold;"
                                                                x-text="item.qty"></span>
                                                            <span
                                                                style="font-size: 16px; font-weight: bold;"
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
                                                                style="font-size: 14px; text-transform: capitalize;"
                                                                x-text="item.status"></span>
                                                        </div>
                                                    </div>
                                                    <ul>
                                                        <template
                                                            x-for="variation in item.variations"
                                                            :key="variation">
                                                            <li
                                                                style="margin-left: 15px;">
                                                                <span
                                                                    x-text="variation"></span>
                                                            </li>
                                                        </template>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="buttons"
                                            style="display: flex; align-items: center; gap: 10px;">
                                            <template
                                                x-if="item.status !== 'ready' && item.status !== 'processing' && item.status !== 'pending' && item.status !== 'completed'">
                                                <button class=" ready-btn"
                                                    @click="markItemAsReady(item)">
                                                    Accept
                                                </button>
                                            </template>
                                            <template x-if="item.status === 'ready'">
                                                <button class="ready-btn"
                                                    @click="markItemAsProcessing(item)">
                                                    Ready
                                                </button>
                                            </template>
                                            <template
                                                x-if="item.status === 'processing'">
                                                <button class="ready-btn"
                                                    @click="markItemAsPending(item)">
                                                    Processing
                                                </button>
                                            </template>
                                            <template x-if="item.status === 'pending'">
                                                <button class="ready-btn"
                                                    @click="markItemAsCompleted(item)">
                                                    Pending
                                                </button>
                                            </template>
                                            <template
                                                x-if="item.status === 'completed'">
                                                <button class="ready-btn">
                                                    Completed
                                                </button>
                                            </template>
                                            <button type="button"
                                                @click="markItemAsCanceled(item)"
                                                class="cancel-btn">Cancellation</button>
                                        </div>

                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>

                    <div x-show="currentStatus === 'ready'">
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="order in kotDisplayOrders">
                                <template x-for="item in order.products">
                                    <template
                                        x-if="item.status.toLowerCase() === 'ready'">
                                        <div :style="{
                                            'width': '350px',
                                            'border': '1px solid var(--primary-border-color)',
                                            'padding': '10px',
                                            'border-radius': '5px',
                                            'background-color': getOrderBackgroundColor(order.status)
                                        }">
                                            <div
                                                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                                <p
                                                    style="margin: 0; font-size: 18px; font-weight: bold;">
                                                    <span x-text="order.orderNo"></span>
                                                </p>
                                                <span
                                                    style="font-size: 14px; font-weight: bold;"
                                                    x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                            </div>
                                            <div
                                                style="display: flex; align-items: center; gap: 10px;">
                                                <p
                                                    style="margin: 0; font-size: 18px; font-weight: bold;">
                                                    <span x-text="order.cusName"></span>
                                                </p>
                                                -
                                                <span
                                                    style="font-size: 14px; font-weight: bold;"
                                                    x-text="order.mobileNo"></span>
                                            </div>

                                            <hr style="margin: 20px 0;">

                                            <div class="list">
                                                <ul>
                                                    <li style="margin-bottom: 20px;">
                                                        <div
                                                            style="display: flex; align-items: center; justify-content: space-between;">
                                                            <div>
                                                                <span
                                                                    style="font-size: 16px; font-weight: bold;"
                                                                    x-text="item.qty"></span>
                                                                <span
                                                                    style="font-size: 16px; font-weight: bold;"
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
                                                                    style="font-size: 14px; text-transform: capitalize;"
                                                                    x-text="item.status"></span>
                                                            </div>
                                                        </div>
                                                        <ul>
                                                            <template
                                                                x-for="variation in item.variations"
                                                                :key="variation">
                                                                <li
                                                                    style="margin-left: 15px;">
                                                                    <span
                                                                        x-text="variation"></span>
                                                                </li>
                                                            </template>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>

                    <div x-show="currentStatus === 'processing'">
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="order in kotDisplayOrders">
                                <template x-for="item in order.products">
                                    <template
                                        x-if="item.status.toLowerCase() === 'processing'">
                                        <div :style="{
                                            'width': '350px',
                                            'border': '1px solid var(--primary-border-color)',
                                            'padding': '10px',
                                            'border-radius': '5px',
                                            'background-color': getOrderBackgroundColor(order.status)
                                        }">
                                            <div
                                                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                                <p
                                                    style="margin: 0; font-size: 18px; font-weight: bold;">
                                                    <span x-text="order.orderNo"></span>
                                                </p>
                                                <span
                                                    style="font-size: 14px; font-weight: bold;"
                                                    x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                            </div>
                                            <div
                                                style="display: flex; align-items: center; gap: 10px;">
                                                <p
                                                    style="margin: 0; font-size: 18px; font-weight: bold;">
                                                    <span x-text="order.cusName"></span>
                                                </p>
                                                -
                                                <span
                                                    style="font-size: 14px; font-weight: bold;"
                                                    x-text="order.mobileNo"></span>
                                            </div>

                                            <hr style="margin: 20px 0;">

                                            <div class="list">
                                                <ul>
                                                    <li style="margin-bottom: 20px;">
                                                        <div
                                                            style="display: flex; align-items: center; justify-content: space-between;">
                                                            <div>
                                                                <span
                                                                    style="font-size: 16px; font-weight: bold;"
                                                                    x-text="item.qty"></span>
                                                                <span
                                                                    style="font-size: 16px; font-weight: bold;"
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
                                                                    style="font-size: 14px; text-transform: capitalize;"
                                                                    x-text="item.status"></span>
                                                            </div>
                                                        </div>
                                                        <ul>
                                                            <template
                                                                x-for="variation in item.variations"
                                                                :key="variation">
                                                                <li
                                                                    style="margin-left: 15px;">
                                                                    <span
                                                                        x-text="variation"></span>
                                                                </li>
                                                            </template>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>

                    <div x-show="currentStatus === 'pending'">
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="order in kotDisplayOrders">
                                <template x-for="item in order.products">
                                    <template
                                        x-if="item.status.toLowerCase() === 'pending'">
                                        <div :style="{
                                            'width': '350px',
                                            'border': '1px solid var(--primary-border-color)',
                                            'padding': '10px',
                                            'border-radius': '5px',
                                            'background-color': getOrderBackgroundColor(order.status)
                                        }">
                                            <div
                                                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                                <p
                                                    style="margin: 0; font-size: 18px; font-weight: bold;">
                                                    <span x-text="order.orderNo"></span>
                                                </p>
                                                <span
                                                    style="font-size: 14px; font-weight: bold;"
                                                    x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                            </div>
                                            <div
                                                style="display: flex; align-items: center; gap: 10px;">
                                                <p
                                                    style="margin: 0; font-size: 18px; font-weight: bold;">
                                                    <span x-text="order.cusName"></span>
                                                </p>
                                                -
                                                <span
                                                    style="font-size: 14px; font-weight: bold;"
                                                    x-text="order.mobileNo"></span>
                                            </div>

                                            <hr style="margin: 20px 0;">

                                            <div class="list">
                                                <ul>
                                                    <li style="margin-bottom: 20px;">
                                                        <div
                                                            style="display: flex; align-items: center; justify-content: space-between;">
                                                            <div>
                                                                <span
                                                                    style="font-size: 16px; font-weight: bold;"
                                                                    x-text="item.qty"></span>
                                                                <span
                                                                    style="font-size: 16px; font-weight: bold;"
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
                                                                    style="font-size: 14px; text-transform: capitalize;"
                                                                    x-text="item.status"></span>
                                                            </div>
                                                        </div>
                                                        <ul>
                                                            <template
                                                                x-for="variation in item.variations"
                                                                :key="variation">
                                                                <li
                                                                    style="margin-left: 15px;">
                                                                    <span
                                                                        x-text="variation"></span>
                                                                </li>
                                                            </template>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>

                    <div x-show="currentStatus === 'completed'">
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="order in kotDisplayOrders">
                                <template x-for="item in order.products">
                                    <template
                                        x-if="item.status.toLowerCase() === 'completed'">
                                        <div :style="{
                                            'width': '350px',
                                            'border': '1px solid var(--primary-border-color)',
                                            'padding': '10px',
                                            'border-radius': '5px',
                                            'background-color': getOrderBackgroundColor(order.status)
                                        }">
                                            <div
                                                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                                <p
                                                    style="margin: 0; font-size: 18px; font-weight: bold;">
                                                    <span x-text="order.orderNo"></span>
                                                </p>
                                                <span
                                                    style="font-size: 14px; font-weight: bold;"
                                                    x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                            </div>
                                            <div
                                                style="display: flex; align-items: center; gap: 10px;">
                                                <p
                                                    style="margin: 0; font-size: 18px; font-weight: bold;">
                                                    <span x-text="order.cusName"></span>
                                                </p>
                                                -
                                                <span
                                                    style="font-size: 14px; font-weight: bold;"
                                                    x-text="order.mobileNo"></span>
                                            </div>

                                            <hr style="margin: 20px 0;">

                                            <div class="list">
                                                <ul>
                                                    <li style="margin-bottom: 20px;">
                                                        <div
                                                            style="display: flex; align-items: center; justify-content: space-between;">
                                                            <div>
                                                                <span
                                                                    style="font-size: 16px; font-weight: bold;"
                                                                    x-text="item.qty"></span>
                                                                <span
                                                                    style="font-size: 16px; font-weight: bold;"
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
                                                                    style="font-size: 14px; text-transform: capitalize;"
                                                                    x-text="item.status"></span>
                                                            </div>
                                                        </div>
                                                        <ul>
                                                            <template
                                                                x-for="variation in item.variations"
                                                                :key="variation">
                                                                <li
                                                                    style="margin-left: 15px;">
                                                                    <span
                                                                        x-text="variation"></span>
                                                                </li>
                                                            </template>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>

                    <div x-show="currentStatus === 'canceled'">
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="order in kotDisplayOrders">
                                <template x-for="item in order.products">
                                    <template
                                        x-if="item.status.toLowerCase() === 'canceled'">
                                        <div :style="{
                                            'width': '350px',
                                            'border': '1px solid var(--primary-border-color)',
                                            'padding': '10px',
                                            'border-radius': '5px',
                                            'background-color': getOrderBackgroundColor(order.status)
                                        }">
                                            <div
                                                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                                <p
                                                    style="margin: 0; font-size: 18px; font-weight: bold;">
                                                    <span x-text="order.orderNo"></span>
                                                </p>
                                                <span
                                                    style="font-size: 14px; font-weight: bold;"
                                                    x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                            </div>
                                            <div
                                                style="display: flex; align-items: center; gap: 10px;">
                                                <p
                                                    style="margin: 0; font-size: 18px; font-weight: bold;">
                                                    <span x-text="order.cusName"></span>
                                                </p>
                                                -
                                                <span
                                                    style="font-size: 14px; font-weight: bold;"
                                                    x-text="order.mobileNo"></span>
                                            </div>

                                            <hr style="margin: 20px 0;">

                                            <div class="list">
                                                <ul>
                                                    <li style="margin-bottom: 20px;">
                                                        <div
                                                            style="display: flex; align-items: center; justify-content: space-between;">
                                                            <div>
                                                                <span
                                                                    style="font-size: 16px; font-weight: bold;"
                                                                    x-text="item.qty"></span>
                                                                <span
                                                                    style="font-size: 16px; font-weight: bold;"
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
                                                                    style="font-size: 14px; text-transform: capitalize;"
                                                                    x-text="item.status"></span>
                                                            </div>
                                                        </div>
                                                        <ul>
                                                            <template
                                                                x-for="variation in item.variations"
                                                                :key="variation">
                                                                <li
                                                                    style="margin-left: 15px;">
                                                                    <span
                                                                        x-text="variation"></span>
                                                                </li>
                                                            </template>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style=" display: flex; align-items: center; gap: 20px; justify-content: flex-end;
                    margin-top: 20px;">
            <button type="button" class="btn btn-danger"
                @click="KotPopup = null">Close</button>
        </div>
    </div>
</div>
