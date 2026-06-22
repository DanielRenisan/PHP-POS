<div x-show.transition.duration.500ms="cashPopUp"
    :class="{ 'popup-hidden': !cashPopUp, 'popup-visible': cashPopUp }" class="popup"
    @click.away="cashPopUp = null" style="width: 30%; overflow: hidden;">
    <div>
        <div>
            <h6 style="
            background: #4361ee;
            color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 20px;" class="menu-title">Opening Balance</h6>
            <div style="margin-top: 35px;">
                <!-- search  -->
                <div class="m-0" @click.outside="search = false">
                    <form style="margin: 0 !important;"
                        class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0"
                        method="POST"
                                        action="{{ action('CashRegisterController@store') }}">
                                        @csrf
                                        <div>
                                    <label for="name">Amount</label>
                                    <input id="amount" type="number" class="form-input" name="amount" required />
                                </div>
                            <div class=" flex justify-end items-center mt-3">
                                <button type="button" class="btn btn-outline-danger discard-btn"
                                    @click="cashPopUp = false">Discard</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4 discard-btn">Update</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>