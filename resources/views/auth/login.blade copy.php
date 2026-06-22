<!DOCTYPE html>
<html lang="en">
<head>
	<title>LOGIN - GDS POS</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="{{asset('css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/main-login.css')}}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
                <form class="login100-form validate-form" method="POST" action="{{ route('login.post') }}">
                {{ csrf_field() }}
					@php 
					$bussiness = App\Models\Business::first();
					@endphp
                    <span class="login100-form-title">
					<img src="{{ isset($bussiness) && isset($bussiness->logo) ? url( 'storage/business_logos/' . $bussiness->logo)  : asset('img/codegs.png')}}" width="200px"/>
					</span>
					<span class="login100-form-title p-b-30">
					{{ isset($bussiness) && isset($bussiness->name) ? $bussiness->name : 'Loopdigi' }}
					</span>
					<div class="form-group">
                        <label for="username">Username</label>
                        <input class="form-input {{ $errors->has('username') ? 'is-invalid' : '' }}"
                            id="username" type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-input @error('password') is-invalid @enderror"
                            type="password" name="password" id="password" required placeholder="Enter Password">
                    </div>
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button type="submit" class="login100-form-btn">
								Login
							</button>
						</div>
					</div>
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<span class="login100-form-footer p-b-15" style="text-decoration:none;font-size: 10px;">
							© {{date('Y')}} <a href="https://loopdigius.com/" target="_blank" style="text-decoration:none;font-size: 10px;">Loopdigi</a> (+94768271573)
							</span>
						</div>
					</div>
				</form>
			</div>	
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
	<script src="{{asset('js/login-main.js')}}"></script>
    <script src="{{ asset('js/app-main.js') }}" defer></script>

</body>
</html>

