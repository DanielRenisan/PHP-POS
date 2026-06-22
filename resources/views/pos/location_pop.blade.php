<div x-show.transition.duration.500ms="locationPopup"
    :class="{ 'popup-hidden': !locationPopup, 'popup-visible': locationPopup }"
    class="popup" @keydown.enter="updateSelectedProductQty"
    @click.away="locationPopup = null">
    <div>
        <div>
            <h6 style="
            background: skyblue;
            color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 20px;" class="menu-title">Location</h6>
        </div>
        @if (is_null($default_location))
        <div style="margin: 35px 0;">
            <select class="form-select mt-3" name="location" id="location"
                x-model="selectedLocation" @change="setLocation($event.target.value)"  x-on:change="onLocationChange">
                <option selected value="All">Select Location</option>
                @foreach($business_locations as $location_id => $location)
                    <option value="{{$location_id}}" {{$location_id == $default_location ? 'selected' : ''}}>{{$location}}</option>
                @endforeach
            </select>
        </div>
        @endif
        @if (is_null($default_location))
        <div style="margin: 35px 0;">
            <select class="form-select mt-3" name="department" id="department"
                x-model="selectedDepartment" @change="setDepartment($event.target.value)"   x-on:change="onDepartmentChange">
                <option selected value=" ">Select Department</option>
            </select>
        </div>
        @else
        <div style="margin: 35px 0;">
            <select class="form-select mt-3" name="department" id="department"
                x-model="selectedDepartment" @change="setDepartment($event.target.value)"    x-on:change="onDepartmentChange">
                <option selected value="all">All</option>
                @foreach($departments as $department)
                <option value="{{$department->id}}">{{$department->name}}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div
            style="display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-top: 20px;">
            <button type="button" class="btn btn-danger"
                @click="locationPopup = null">Close</button>
        </div>
    </div>
</div>