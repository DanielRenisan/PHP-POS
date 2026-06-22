<div x-show.transition.duration.500ms="roomPopup"
    :class="{ 'popup-hidden': !roomPopup, 'popup-visible': roomPopup }" class="popup"
    @click.away="roomPopup = null" style="width: 90%; overflow: hidden;">
    <div>
        <div>
            <h6 style="
            background: skyblue;
            color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 20px;" class="menu-title">Room Details</h6>
            <div style="margin-top: 35px;">
                <!-- search  -->
                <div class="m-0" @click.outside="search = false">
                    <form style="margin: 0 !important;"
                        class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0"
                        @submit.prevent="search = false">
                        <div class="relative">
                            <input type="text"
                                class="peer border-colored form-input bg-gray-100 placeholder:tracking-widest ltr:pl-9 ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                                placeholder="Room No / Name " x-model="searchRoom"/>
                            <button type="button"
                                class="absolute inset-0 h-9 w-9 appearance-none peer-focus:text-primary ltr:right-auto rtl:left-auto btn-hover">
                                <svg class="mx-auto" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11.5" cy="11.5" r="9.5"
                                        stroke="currentColor" stroke-width="1.5"
                                        opacity="0.5" />
                                    <path d="M18.5 18.5L22 22" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <div class="mt-3 dine-buttons" style="
                max-height: 220px;
                height: auto;
                overflow: auto;
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                justify-content: center;
                align-items: center;">
                    <template x-for="(button, index) in filteredRooms" :key="button.id">
                        <button style="background-color: rgb(192, 219, 226);"
                            type="button" class="dine-in-buttons"
                            :class="{ 'selected': selectedRoomInButtons === button.id }"
                            @click="toggleRoomSelected(button.id)">
                            <span style="font-weight: bold;"
                                x-text="button.label"></span>
                            <!-- Checkbox for including tax -->
                            <!-- <div>
                                <label style="margin: 0;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                font-size: 12px;
                                gap: 2px;">
                                    <input type="checkbox" value="1" name="is_include" id="is_include">
                                    Include Hotel Bill
                                </label>
                            </div> -->
                            <!--<div>-->
                            <!--                                    <label class="checkbox-label">-->
                            <!--                                        <input class="checkbox-round" type="checkbox" value="1" name="is_include" id="is_include">-->
                            <!--                                        Include Hotel Bill-->
                            <!--                                    </label>-->
                            <!--                                </div>-->
                        </button>
                    </template>
                </div>
            </div>
            <hr>
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger"
                @click="roomPopup = null">Close</button>
        </div>
    </div>
</div>