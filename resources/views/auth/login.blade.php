<!DOCTYPE html>
<html lang="en">
<head>
    <title>LOGIN - GDS POS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Nunito", sans-serif;
            display: flex;
            height: 100vh;
            background: #ffffff;
        }

        /* MAIN LAYOUT */
        .left-panel, .right-panel {
            flex: 1;
            height: 100vh;
        }

        /* LEFT PANEL DESIGN (Logo + Welcome Section) */
        .left-panel {
            background: radial-gradient(circle at 20% 40%, #1a73e8 0%, #0057d9 40%, #0038a8 100%);
            padding: 70px 50px;
            color: white;
            position: relative;
            overflow: hidden;
            border-radius: 0 50px 50px 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        /* Floating circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, #1e7bff 0%, #0057d9 60%, #0038a8 100%);
            box-shadow: inset 0 -25px 50px rgba(0,0,0,0.25);
        }

        .circle1 { width: 300px; height: 300px; top: -40px; left: -60px; }
        .circle2 { width: 250px; height: 250px; bottom: -40px; left: -40px; }
        .circle3 { width: 180px; height: 180px; bottom: 40px; left: 250px; }

        .left-logo {
            width: 180px;
            margin: 0 auto 30px auto;
            z-index: 10;
        }

        .left-title {
            font-size: 40px;
            font-weight: 600;
            z-index: 10;
            margin-bottom: 12px;
        }

        .left-subtitle {
            font-size: 15px;
            max-width: 380px;
            margin: 0 auto;
            line-height: 1.6;
            opacity: 0.9;
            z-index: 10;
        }

        /* RIGHT PANEL (Original Form) */
        .right-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #ffffff;
        }

        .form-wrapper {
            width: 380px;
        }

        .login100-form-title {
            display: block;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
        }

        .login100-form-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #0038a8;
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin-top: 15px;
            cursor: pointer;
        }

        .login100-form-footer {
            display: block;
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            body { flex-direction: column; height: auto; }
            .left-panel { display: none; }
            .right-panel { padding: 20px; }
            .form-wrapper { width: 100%; }
        }
    </style>
</head>

<body>

    <!-- LEFT PANEL WITH LOGO -->
    <div class="left-panel">
        <div class="circle circle1"></div>
        <div class="circle circle2"></div>
        <div class="circle circle3"></div>

        @php 
            $bussiness = App\Models\Business::first();
        @endphp

        <img class="left-logo" 
             src="{{ isset($bussiness) && isset($bussiness->logo) 
                    ? url('storage/business_logos/' . $bussiness->logo) 
                    : asset('img/codegs.png') }}">

        <h1 class="left-title">WELCOME</h1>

		<span class="left-title">
			{{ isset($bussiness) && isset($bussiness->name) ? $bussiness->name : 'Loopdigi' }}
		</span>
        <!-- <p class="left-subtitle">
            Your headline name goes here.<br>
            Add any intro text you want below it.
        </p> -->
    </div>

    <!-- RIGHT PANEL WITH ORIGINAL FORM -->
    <div class="right-panel">

        <div class="form-wrapper">
            <form class="login100-form validate-form" method="POST" action="{{ route('login.post') }}">
                {{ csrf_field() }}

                <span class="login100-form-title">Login</span>

                <!-- Username -->
                <div class="form-group">
                    <label>Username</label>
                    <input class="form-input {{ $errors->has('username') ? 'is-invalid' : '' }}"
                           id="username" type="text" name="username"
                           value="{{ old('username') }}" required autofocus
                           placeholder="Enter Username">
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-input @error('password') is-invalid @enderror"
                           type="password" name="password" id="password"
                           required placeholder="Enter Password">
                </div>

                <button type="submit" class="login100-form-btn">Login</button>

                <span class="login100-form-footer">
                    © {{date('Y')}} <a href="https://loopdigius.com/" target="_blank">Loopdigi</a> (+94768271573)
                </span>
            </form>
        </div>

    </div>

</body>
</html>
