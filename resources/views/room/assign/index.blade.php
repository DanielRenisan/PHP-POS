@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Assign Room</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            {!! Form::open(['url' => action('RoomAssignController@assign'), 'method' => 'post', 'id' => 'add_form' , 'files' => true]) !!}
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-semibold dark:text-white-light">Assign Room</h3>
                </div>
                <div class="my-4 h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                <div class="mb-5">
                    <input type="hidden" name="room_type" value="{{$room->room_type}}">
                    @foreach(App\Models\Floor::get() as $floor)
                    @php
                        $check = App\Models\RoomAssign::where('floor_id', $floor->id)->pluck('room_id')->toArray();
                        $plan = App\Models\FloorPlan::where('floor_id', $floor->id)->first();
                    @endphp
                        <div class="grid grid-cols-1 gap-6 pt-5">
                            <div class="panel mt-10 text-center md:mt-20">
                                <h3 class="mb-2 text-xl font-bold dark:text-white md:text-2xl">{{$floor->name ?? ''}}</h3>
                            </div>
                            @if(isset($plan))
                                @php
                                    $start = $plan->start_room_no;
                                    $end = $plan->start_room_no + $plan->no_of_rooms;
                                @endphp
                                <div class="grid-cols-6 gap-9 pt-5">
                                    @for($i = $start;$i <= $end - 1; $i++)
                                        @php
                                            $assign = App\Models\RoomAssign::where('floor_id', $floor->id)->where('room_id', $i)->first();
                                        @endphp
                                        <div style="width: 16.66666667%;float: left;position: relative;line-height:3.0rem;
                                            min-height: 3px;padding-right: 15px;padding-left: 15px;">
                                            <div class="checkbox">
                                                <label>
                                                    {!! Form::checkbox('rooms[' . $floor->id. '][]', $i, in_array($i, $check), 
                                                    [ 'class' => 'input-icheck', (isset($assign) && $assign->room_type == $room->room_type) ||  !isset($assign)? '' : 'disabled' ,'data-toggle' =>"tooltip", 'data-original-title' => isset($assign) ? $assign->room_type : 'Not Assign']); !!} {{ __( 'Room'.$i ) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                                <br>
                            @endif
                        </div>
                        <br>
                    @endforeach
                </div>
            </div>
            <div class="panel">
                <button type="submit" class="btn btn-primary">SAVE</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready( function(){
       
            var room_table = $('#room_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/rooms',
                columnDefs: [{
                    "targets": 10,
                    "orderable": false,
                    "searchable": false
                }]
            });
            $(document).on('click', '.delete_room_button', function (e) {
                e.preventDefault();
                swal({
                    title: LANG.sure,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).attr('data-href');
                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            success: function (result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    if (typeof room_table !== 'undefined') {
                                        room_table.ajax.reload();
                                    }

                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
	        });

            $(document).on('click', 'button.edit_room_button', function () {

                $("div.room_modal").load($(this).data('href'), function () {

                    $(this).modal('show');

                    $('form#room_edit_form').submit(function (e) {
                        e.preventDefault();
                        var data = $(this).serialize();

                        $.ajax({
                            method: "POST",
                            url: $(this).attr("action"),
                            dataType: "json",
                            data: data,
                            success: function (result) {
                                if (result.success == true) {
                                    $('div.room_modal').modal('hide');
                                    toastr.success(result.msg);
                                    room_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    });
                });
            });
        });
    </script>
@endsection