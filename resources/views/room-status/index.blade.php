@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6   no-print" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Room Status</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
<style>
.hotel-image {
    display: flex;
    overflow: hidden;
    height: 312px;
    border-radius: 0.25rem;
}
.scroll-bar {
    height: 284px;
    -webkit-overflow-scrolling: touch;
    overflow: hidden;
}

.overlay-black {
    background-color: #070807ab;
    border-radius: 0.25rem;
    top: 14px;
    align-items: center;
    height: 284px;
    display: grid;
    width: 90%;
    overflow: auto;
}
.position-absolute {
    position: absolute!important;
}
.text-white {
    color: #fff!important;
}
.text-center {
    text-align: center!important;
}
.pxx-4 {
    padding-left: 1.5rem!important;
}
.pby-3 {
    padding-bottom: 1rem!important;
}
.col-xl-3 {
    max-width: 25%;
}
.mb-3, .my-3 {
    margin-bottom: 1rem!important;
}
.mb-2 {
    margin-bottom: 1rem!important;
}
.col-sm-6 {
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}
.col-lg-4 {
    width: 100%;
    float: left;
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}

</style>
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-semibold dark:text-white-light">Room Status</h3>
                </div>
                <form action="{{action('RoomStatusController@index')}}" method="GET">
                    <div class="grid grid-cols-3 gap-4 pt-5">
                        <div class="col-md-2">
                            <select name="status" placeholder="status" class="form-input">
                            <option disabled selected>Status</option>
                            <option value="0" {{request()->get('status') && request()->get('status') == 0 ? 'selected' : ''}}>Available</option>
                            <option value="1" {{request()->get('status') && request()->get('status') == 1 ? 'selected' : ''}}>Booked</option>
                            <option value="2" {{request()->get('status') && request()->get('status') == 2 ? 'selected' : ''}}>Checkin</option>
                            <option value="3" {{request()->get('status') && request()->get('status') == 3 ? 'selected' : ''}}>Blocked</option>
                            <option value="4" {{request()->get('status') && request()->get('status') == 4 ? 'selected' : ''}}>Reserve</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="floor" placeholder="status" class="form-input">
                            <option disabled selected>floor</option>
                            @foreach($floors as $val)
                            <option value="{{$val->id}}" {{request()->get('floor') && request()->get('floor') == $val->id ? 'selected' : ''}}>{{$val->name}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">GET</button>
                        </div>    
                    </div>
                </form>
                <div class="my-4 h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                <div class="mb-5">
                    <div class="grid grid-cols-4 gap-4 pt-5">
                        @foreach($rooms as $room)
                            @php
                            $floor = App\Models\Floor::find($room->floor_id);
                            $title = 'Avaialable';
                            $back_class = '#478778';
                            if($room->status == 1)
                            {
                                $back_class = '#3b82f6 var(--tw-gradient-from-position)';
                                $title = 'Booked';
                            }
                            if($room->status == 2)
                            {
                                $back_class = '#22d3ee var(--tw-gradient-to-position)';
                                $title = 'Checkin';
                            }
                            if($room->status == 3)
                            {
                                $back_class = 'black';
                                $title = 'Blocked';
                            }
                            if($room->status == 4)
                            {
                                $back_class = 'red';
                                $title = 'Reserved';
                            }
                            @endphp
                            <div class="col-lg-4 mb-3">
                                <div class="position-relative d-flex justify-content-center">
                                    <div class="hotel-image">
                                        <img src="{{asset('img/back.jpg')}}" class="image-inner" alt="">
                                    </div>
                                    <div class="scroll-bar overlay-black pxx-4 pby-3 text-center text-white position-absolute">
                                        @if(!in_array($room->status, [1,2]))   
                                        <div>
                                            <label class="inline-flex">
                                                <input type="checkbox" data-value="{{$room->room_id}}" class="peer form-checkbox text-success block-check-box" {{$room->status == 3 ? 'checked' : ''}}>
                                                <span class="peer-checked:text-success">Block</span>
                                            </label>
                                        </div>
                                        @endif
                                        <h2 class="fs-21 mt-3 font-weight-bold">{{$floor->name}}</h2>
                                        <h3 class="fs-21 mt-3 font-weight-bold">Room No. {{$room->room_id}}</h3>
                                        <p class="mb-1">Room Type :{{$room->room_type}} </p>
                                        <button type="button" class="btn mb-2 font-weight-bold" style="background:{{$back_class}};margin-right:12px;">{{$title}}</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{$rooms->links("pagination::bootstrap-4")}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')

    <script type="text/javascript">
        $(document).ready( function(){
            $(document).on('click', 'input.block-check-box', function() {
                var url = "{{ action('CancellationController@block') }}";
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: {
                        'room_no' : $(this).data('value')
                    },
                    success: function (result) {
                        window.location.reload();
                    }
                });
            });
        });
    </script>
@endsection
