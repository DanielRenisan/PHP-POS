@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]" style=" padding:2rem !important;">
            <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>KOT</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div x-data="menu">
                <div class="panel">
                    <div>
                        <div class="buttons-panel">
                            <button :class="{ 'active': currentStatus === 'all' }"
                                @click="showOrders('all')">All</button>
                            <button :class="{ 'active': currentStatus === 'orders' }"
                                @click="showOrders('orders')">Orders</button>
                            <button :class="{ 'active': currentStatus === 'ready' }"
                                @click="showOrders('ready')">Ready</button>
                            <button :class="{ 'active': currentStatus === 'processing' }"
                                @click="showOrders('processing')">Processing</button>
                            <!-- <button :class="{ 'active': currentStatus === 'pending' }"
                                @click="showOrders('pending')">Pending</button> -->
                            <button :class="{ 'active': currentStatus === 'completed' }"
                                @click="showOrders('completed')">Completed</button>
                            <button :class="{ 'active': currentStatus === 'canceled' }"
                                @click="showOrders('canceled')">canceled</button>
                        </div>
                        <div
                            style="display: flex; gap: 10px; margin-top: 20px; height: calc(100vh - 217px); overflow: auto;">
                            <div class="all" x-show="currentStatus === 'all'">
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="order in orders" :key="order.id">
                                        <div class="body" :style="{
                                                        'border': '1px solid var(--primary-border-color)',
                                                        'padding': '10px',
                                                        'border-radius': '5px',
                                                        'background-color': getOrderBackgroundColor(order.status)
                                                    }">
                                            <div
                                                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                                <p style="margin: 0; font-size: 14px; font-weight: bold;">
                                                    <span x-text="order.invoiceNo"></span>
                                                </p>
                                                <span style="font-size: 14px; font-weight: bold;"
                                                    x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                            </div>
                                            <div
                                                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 3px;">
                                                <span style="font-size: 12px; font-weight: bold;"
                                                    x-text="order.date"></span>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <p style="margin: 0; font-size: 14px; font-weight: bold;">
                                                    <span x-text="order.cusName"></span>
                                                </p>
                                                -
                                                <span style="font-size: 14px; font-weight: bold;"
                                                    x-text="order.mobileNo"></span>
                                            </div>

                                            <hr style="margin: 20px 0;">

                                            <div class="list">
                                                <template x-for="(item, index) in order.items" :key="index">
                                                    <ul>
                                                        <li style="margin-bottom: 20px;">
                                                            <div
                                                                style="display: flex; align-items: center; justify-content: space-between;">
                                                                <div>
                                                                    <span
                                                                        style="font-size: 14px; font-weight: bold;"
                                                                        x-text="item.qty"></span>
                                                                    <span
                                                                        style="font-size: 14px; font-weight: bold;"
                                                                        x-text="item.name"></span>
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
                                                                </div>
                                                            </div>
                                                            <ul>
                                                                <template x-for="variation in item.variations"
                                                                    :key="variation">
                                                                    <li style="margin-left: 12px;">
                                                                        <span x-text="variation"></span>
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

                            <div class="orders" x-show="currentStatus === 'orders'">
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="order in orders">
                                        <template x-for="item in order.items" :key="item.line_id">
                                            <template x-if="item.status !== 'canceled' &&  item.status !== 'Completed'">
                                                <div class="body" :style="{
                                                    'border': '1px solid var(--primary-border-color)',
                                                    'padding': '10px',
                                                    'border-radius': '5px',
                                                    'background-color': getOrderBackgroundColor(order.status)
                                                     }">
                                                    <div
                                                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                                        <p
                                                            style="margin: 0; font-size: 14px; font-weight: bold;">
                                                            <span x-text="order.invoiceNo"></span>
                                                        </p>
                                                        <span style="font-size: 14px; font-weight: bold;"
                                                            x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                                    </div>
                                                    <div
                                                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                                        <p
                                                            style="margin: 0; font-size: 14px; font-weight: bold;">
                                                            <span x-text="item.orderNo"></span>
                                                        </p>
                                                        <span style="font-size: 12px; font-weight: bold;"
                                                            x-text="order.date"></span>
                                                    </div>
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <p
                                                            style="margin: 0; font-size: 14px; font-weight: bold;">
                                                            <span x-text="order.cusName"></span>
                                                        </p>
                                                        -
                                                        <span style="font-size: 14px; font-weight: bold;"
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
                                                                            style="font-size: 14px; font-weight: bold;"
                                                                            x-text="item.qty"></span>
                                                                        <span
                                                                            style="font-size: 14px; font-weight: bold;"
                                                                            x-text="item.name"></span>
                                                                    </div>
                                                                    <div class="status">
                                                                        <span :class="{
                                                                        'status-pill': true,
                                                                        'status-ready': item.status === 'ready',
                                                                        'status-processing': item.status === 'processing',
                                                                        'status-pending': item.status === 'pending',
                                                                        'status-completed': item.status === 'completed',
                                                                        'status-canceled': item.status === 'canceled'
                                                                        }" style="font-size: 14px; text-transform: capitalize;"
                                                                            x-text="item.status"></span>
                                                                    </div>
                                                                </div>
                                                                <ul>
                                                                    <template
                                                                        x-for="variation in item.variations"
                                                                        :key="variation">
                                                                        <li style="margin-left: 12px;">
                                                                            <span x-text="variation"></span>
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
                                                        <template x-if="item.status === 'processing'">
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
                                                        <template x-if="item.status === 'completed'">
                                                            <button class="ready-btn">
                                                                Completed
                                                            </button>
                                                        </template>
                                                        <button @click="markItemAsCanceled(item)"
                                                            class="cancel-btn">Cancellation</button>
                                                    </div>

                                                </div>
                                            </template>
                                        </template>
                                    </template>
                                </div>
                            </div>

                            <div class="ready" x-show="currentStatus === 'ready'">
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="order in orders">
                                        <template x-for="item in order.items" :key="item.line_id">
                                            <template x-if="item.status.toLowerCase() === 'ready'">
                                                <div :style="{
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
                                                        <span style="font-size: 14px; font-weight: bold;"
                                                            x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                                    </div>
                                                    <div
                                                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 3px;">
                                                        <span style="font-size: 12px; font-weight: bold;"
                                                            x-text="order.date"></span>
                                                    </div>
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <p
                                                            style="margin: 0; font-size: 18px; font-weight: bold;">
                                                            <span x-text="order.cusName"></span>
                                                        </p>
                                                        -
                                                        <span style="font-size: 14px; font-weight: bold;"
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
                                                                            x-text="item.name"></span>
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
                                                                        <li style="margin-left: 15px;">
                                                                            <span x-text="variation"></span>
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

                            <div class="processing" x-show="currentStatus === 'processing'">
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="order in orders">
                                        <template x-for="item in order.items" :key="item.line_id">
                                            <template x-if="item.status.toLowerCase() === 'processing'">
                                                <div :style="{
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
                                                        <span style="font-size: 14px; font-weight: bold;"
                                                            x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                                    </div>
                                                    <div
                                                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 3px;">
                                                        <span style="font-size: 12px; font-weight: bold;"
                                                            x-text="order.date"></span>
                                                    </div>
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <p
                                                            style="margin: 0; font-size: 18px; font-weight: bold;">
                                                            <span x-text="order.cusName"></span>
                                                        </p>
                                                        -
                                                        <span style="font-size: 14px; font-weight: bold;"
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
                                                                            x-text="item.name"></span>
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
                                                                        <li style="margin-left: 15px;">
                                                                            <span x-text="variation"></span>
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

                            <div class="pending" x-show="currentStatus === 'pending'">
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="order in orders">
                                        <template x-for="item in order.items" :key="item.line_id">
                                            <template x-if="item.status.toLowerCase() === 'pending'">
                                                <div :style="{
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
                                                        <span style="font-size: 14px; font-weight: bold;"
                                                            x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                                    </div>
                                                    <div
                                                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 3px;">
                                                <span style="font-size: 12px; font-weight: bold;"
                                                    x-text="order.date"></span>
                                            </div>
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <p
                                                            style="margin: 0; font-size: 18px; font-weight: bold;">
                                                            <span x-text="order.cusName"></span>
                                                        </p>
                                                        -
                                                        <span style="font-size: 14px; font-weight: bold;"
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
                                                                            x-text="item.name"></span>
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
                                                                        <li style="margin-left: 15px;">
                                                                            <span x-text="variation"></span>
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

                            <div class="completed" x-show="currentStatus === 'completed'">
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="order in orders">
                                        <template x-for="item in order.items" :key="item.line_id">
                                            <template x-if="item.status.toLowerCase() === 'completed'">
                                                <div :style="{
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
                                                        <span style="font-size: 14px; font-weight: bold;"
                                                            x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                                    </div>
                                                    <div
                                                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 3px;">
                                                <span style="font-size: 12px; font-weight: bold;"
                                                    x-text="order.date"></span>
                                            </div>
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <p
                                                            style="margin: 0; font-size: 18px; font-weight: bold;">
                                                            <span x-text="order.cusName"></span>
                                                        </p>
                                                        -
                                                        <span style="font-size: 14px; font-weight: bold;"
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
                                                                            x-text="item.name"></span>
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
                                                                        <li style="margin-left: 15px;">
                                                                            <span x-text="variation"></span>
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

                            <div class="canceled" x-show="currentStatus === 'canceled'">
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="order in orders">
                                        <template x-for="item in order.items" :key="item.line_id">
                                            <template x-if="item.status.toLowerCase() === 'canceled'">
                                                <div :style="{
                                                    'border': '1px solid var(--primary-border-color)',
                                                    'padding': '10px',
                                                    'border-radius': '5px',
                                                    'background-color': getOrderBackgroundColor(order.status)
                                                }">
                                                    <div
                                                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                                                        <p
                                                            style="margin: 0; font-size: 14px; font-weight: bold;">
                                                            <span x-text="order.invoiceNo"></span>
                                                        </p>
                                                        <span style="font-size: 14px; font-weight: bold;"
                                                            x-text="`Due ${calculateRemainingTime(order)}`"></span>
                                                    </div>
                                                    <div
                                                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 3px;">
                                                <span style="font-size: 12px; font-weight: bold;"
                                                    x-text="order.date"></span>
                                            </div>
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <p
                                                            style="margin: 0; font-size: 14px; font-weight: bold;">
                                                            <span x-text="order.cusName"></span>
                                                        </p>
                                                        -
                                                        <span style="font-size: 14px; font-weight: bold;"
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
                                                                            x-text="item.name"></span>
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
                                                                        <li style="margin-left: 15px;">
                                                                            <span x-text="variation"></span>
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
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<link rel="stylesheet" type="text/css" media="screen" href="{{asset('asset/css/pos.css')}}"/>
<script type="text/javascript">
    document.addEventListener("alpine:init", () => {
        Alpine.data("menu", () => ({
            orders: <?php echo $orders ?>,
            markItemAsReady(item) {
                item.status = "ready";
                this.updateStatus(item, "ready");
            },
            markItemAsProcessing(item) {
                item.status = "processing";
                this.updateStatus(item, "processing");
            },
            markItemAsPending(item) {
                item.status = "pending";
                this.updateStatus(item, "pending")
            },
            markItemAsCompleted(item) {
                item.status = "Completed";
                this.updateStatus(item, "completed")
            },

            markItemAsCanceled(item) {
                item.status = "canceled";
                this.updateStatus(item, "canceled")
            },

            updateStatus(item, status) {
                const href = "{{ action('KitchenController@update') }}";
                $.ajax({
                    async: false,
                    method: "POST",
                    url: href,
                    dataType: "json",
                    data: {
                        'line_id': item.line_id,
                        'status': status
                    },
                    success: function (result) {
                    }
                });
                return newInvoice;
            },

            currentStatus: "all",

            // This method returns the background color based on order status
            getOrderBackgroundColor(status) {
                if (status === "ready") {
                    return "#ffff004f"; // Example color for pending status
                } else if (status === "processing") {
                    return "#ffa50040"; // Example color for processing status
                } else if (status === "completed") {
                    return "#00800033"; // Example color for completed status
                } else if (status === "pending") {
                    return "#0000ff24"; // Example color for completed status
                } else if (status === "canceled") {
                    return "#ff000036"; // Example color for completed status
                } else {
                    return "#ffffff"; // Default color if status doesn't match
                }
            },

            showOrders(status) {
                this.currentStatus = status;
            },

            calculateRemainingTime(order) {
                const orderTime = new Date(order.time).getTime();

                if (isNaN(orderTime)) {
                    return "Invalid time"; // Return an error message if the time is invalid
                } else {
                    const now = new Date().getTime();
                    const remainingTime =  now - orderTime;

                    // if (remainingTime <= 0) {
                    // return "Expired";
                    // } else {
                    const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
                    const minutes = Math.floor(
                        (remainingTime % (1000 * 60 * 60)) / (1000 * 60)
                    );

                    return `${minutes}m ${seconds}s`;
                    // }
                }
            },

            filteredOrders() {
                if (this.currentStatus === "ready") {
                    return this.tableData.filter((order) => order.status === "ready");
                } else if (this.currentStatus === "process") {
                    return this.tableData.filter((order) => order.status === "process");
                } else if (this.currentStatus === "completed") {
                    return this.tableData.filter((order) => order.status === "completed");
                } else if (this.currentStatus === "canceled") {
                    return this.tableData.filter((order) => order.status === "canceled");
                } else {
                    return this.tableData;
                }
            },
        }));
    });
</script>
@endsection  