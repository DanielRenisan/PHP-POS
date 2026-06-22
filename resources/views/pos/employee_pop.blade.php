<div x-show.transition.duration.500ms="employeePopup"
                    :class="{ 'popup-hidden': !employeePopup, 'popup-visible': employeePopup }"
                    class="popup" @click.away="employeePopup = null">
    <div>
        <div>
            <h6 style="
            background: skyblue;
            color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 20px;" class="menu-title">Waiter</h6>
        </div>
        <div style="margin: 35px 0;">
            <!-- search  -->
            <div class="m-0" @click.outside="search = false">
                <!-- <form style="margin: 0 !important;"
                    class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0"
                    @submit.prevent="handleStockSearchInput">
                    <div class="relative">
                        <input type="text"
                            class="peer border-colored form-input bg-gray-100 placeholder:tracking-widest ltr:pl-9 ltr:pr-9 rtl:pl-9 rtl:pr-9 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                            placeholder="Employee Name / Mobile No " />
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
                </form> -->
            </div>
            <select class="form-select mt-3" name="employee" id="employee" x-model="selectedEmployee" @change="setEmployee($event.target.value)">
                <!-- <option value="all">All Waiters</option> -->
                @foreach($employees as $employee)
                    <option value="{{$employee->id}}" {{$employee->id == auth()->user()->id ? 'selected' : '' }}>{{$employee->first_name}}</option>
                @endforeach
            </select>
        </div>
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger"
                @click="employeePopup = null">Close</button>
        </div>
    </div>
</div>