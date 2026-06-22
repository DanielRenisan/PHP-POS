@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6 no-print no-print" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Password Update</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
        <form id="password_edit_form" class="needs-validation" method="POST"
                                                action="{{ action('ChangePasswordController@update') }}">
                                                @csrf
            <div>
                <div>
                    <div style="padding: 30px;">
                        <div class="mb-5 grid grid-cols-1 gap-5">
                                <div class="p-5">
                                    <div class="panel">
                                        <div class="grid grid-cols-1 gap-5">
                                            <div>
                                                <label for="password">Current Password</label>
                                                <input id="current_password" type="password" class="form-input"
                                                    name="current_password" required/>
                                            </div>
                                            <div>
                                                <label for="password">Password</label>
                                                <input id="password" type="password" class="form-input"
                                                    name="password" required/>
                                            </div>

                                            <div>
                                                <label for="confirmPassword">Confirm Password</label>
                                                <input id="confirmPassword" type="password" class="form-input"
                                                        name="confirm_password" required/>
                                                <p 
                                                    style="color: red;">Passwords do not match.</p>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-4 gap-5">
                                        <div>
                                        </div>
                                        <div>
                                        </div>
                                        <div>
                                        </div>
                                        <div>
                                        <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4 discard-btn" id="password-btn"
                                        >CHANGE</button>
                                        </div>
                                        
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
    $("form#password_edit_form").submit(function (e) {
			e.preventDefault();
		}).validate({
			submitHandler: function (form) {
				var data = $(form).serialize();

				$.ajax({
					method: "POST",
					url: $(form).attr("action"),
					dataType: "json",
					data: data,
					success: function (result) {
						if (result.success == true) {
							toastr.success(result.msg);
							$('#current_password').val('');
                            $('#password').val('');
                            $('#confirmPassword').val('');
						} else {
							toastr.error(result.msg);
                            $('#current_password').val('');
                            $('#password').val('');
                            $('#confirmPassword').val('');

						}
					}
				});
			}
		});
</script>

@endsection