<div x-show.transition.duration.500ms="addCustomerPop"
    :class="{ 'popup-hidden': !addCustomerPop, 'popup-visible': addCustomerPop }" class="popup"
    @click.away="addCustomerPop = null" style="width: 40%; overflow: hidden;top:20% !important;">
    <div>
        <div>
            <h6 style="
            background: skyblue;
            color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 20px;" class="menu-title">Add Customer</h6>
            <div style="margin-top: 35px;">
                <!-- search  -->
                <div class="m-0" @click.outside="search = false">
                    <form style="margin: 0 !important;"
                        class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0"
                        
                        @submit.prevent="addCustomer">
                            <div class="grid grid-cols-2 gap-2">
                            <div>
                                        <label for="Name">Name</label>
                                        <input class="form-input" type="text" name="quick_name" id="quick_name" required
                                            placeholder="Customer Name"
                                            x-model="newCustomer.name">
                                    </div>
                                    <div>
                                        <label for="Mobile No">Mobile No</label>
                                        <input class="form-input" type="number" name="quick_mobile" id="quick_mobile" required
                                            placeholder="Customer Mobile No"
                                            x-model="newCustomer.mobileNo">
                                    </div>
                            </div>
                            <div class=" flex justify-end items-center mt-3">
                                <button type="button" class="btn btn-outline-danger discard-btn"
                                    @click="addCustomerPop = false">Discard</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4 discard-btn">Create</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
</script>