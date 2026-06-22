@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li>
                <a href="{{action('Rest\ContactController@index')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">Customers</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Create Employee</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]" x-data="customerList">
                <div class="px-5" x:data="categoryList">
                <form id="employee_edit_form" class="needs-validation" method="POST"
                                                action="{{ route('employee.store') }}">
                                                @csrf
                <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="firstName">First Name *</label>
                            <input id="firstName" type="text" class="form-input" name="first_name"
                                required />
                        </div>
                        <div>
                            <label for="lastName">LastName *</label>
                            <input id="lastName" type="text" class="form-input" name="last_name"
                                required />
                        </div>
                        <div>
                            <label for="mobile">Mobile No *</label>
                            <input type="text" id="mobile" class="form-input" maxlength="10"
                                pattern="\d{10}" name="mobile"
                                title="Please enter exactly 10 digits" required />
                        </div>
                        <div>
                            <label for="phone">Phone</label>
                            <input type="text" id="phone" class="form-input" maxlength="10"
                                pattern="\d{10}" name="phone"
                                title="Please enter exactly 10 digits" />

                        </div>
                       
                        <div>
                            <label for="email">E-mail</label>
                            <input id="email" type="email" class="form-input" name="email" />
                        </div>
                        <div>
                            <label for="positionId">Position *</label>
                            <select class="form-select text-white-dark" name="position_id"
                                id="positionId" required>
                                <option value="" disabled selected>Select Position</option>
                                @foreach ($position as $et)
                                <option name="position_id" class="pro-type" value="{{ $et->id }}" {{
                                    $et->
                                    id == old('position_id') ? 'selected' : '' }}>
                                    {{ $et->name }}</option>
                                @endforeach
                                <span class="text-danger">
                                    @error('position_id')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </select>
                        </div>
                        
                        <div>
                            <label for="username">Username</label>
                            <input id="username" type="text" class="form-input" name="username"/>
                        </div>
                        
                        <div>
                            <label for="password">Password</label>
                            <input id="password" type="password" class="form-input" name="password" oninput="validatePasswords()"/>
                        </div>
                        
                        <div>
                            <label for="confirmPassword">Confirm Password</label>
                            <input id="passwordConfirmation" type="password" class="form-input" name="confirm_password"
                                oninput="validatePasswords()" />
                            <p id="passwordError" class="error"></p>
                        </div>
                        <!-- Error message container -->
                        <div id="iconError" class="text-red-500 hidden" style="color: red;">
                            Please select a valid image for the icon (image format only).
                        </div>
                        <div style="margin-top: 35px">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="form-checkbox" name="status"
                                    checked />
                                <span class=" text-white-dark">Active</span>
                                    </label>
                                </div>
                                <div class=" flex justify-end items-center mt-3">
                                    <a href="#" class="btn btn-outline-danger discard-btn"
                                        >BACK</a>
                                    <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4 discard-btn"
                                        >Create</button>
                        </div>
                    </div>
                </form>                                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    document.addEventListener("alpine:init", () => {
        function validatePasswords() {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('passwordConfirmation').value;
        const passwordError = document.getElementById('passwordError');
        const createButton = document.getElementById('createButton');
        const updateButton = document.getElementById('updateButton');

        if (password !== passwordConfirmation) {
            // Passwords do not match
            passwordError.innerHTML = 'Passwords do not match. Please check again.';
            document.getElementById('password').classList.add('input-error');
            document.getElementById('passwordConfirmation').classList.add('input-error');
            createButton.disabled = true; // Disable the create button
            // updateButton.disabled = true; // Disable the update button
        } else {
            // Passwords match
            passwordError.innerHTML = '';
            document.getElementById('password').classList.remove('input-error');
            document.getElementById('passwordConfirmation').classList.remove('input-error');
            createButton.disabled = false; // Enable the create button
            updateButton.disabled = false; // Enable the update button
        }
    }

    function saveFunction() {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('passwordConfirmation').value;

        if (password !== passwordConfirmation) {
            // Passwords do not match
            alert('Passwords do not match. Please check again.');
            return false; // Exit the function without executing the save logic
        }

        // Passwords match, proceed with the save function
        // Your save function logic goes here
        console.log('Save function executed successfully!');
        return true; // Allow the button click to proceed
    }

    function updateFunction() {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('passwordConfirmation').value;

        // if (password !== passwordConfirmation) {
        //     // Passwords do not match
        //     alert('Passwords do not match. Please check again.');
        //     return false; // Exit the function without executing the update logic
        // }
        console.log('Update function executed successfully!');
        return true; // Allow the button click to proceed
    }
}); 
</script>

@endsection 