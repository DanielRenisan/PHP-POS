@extends('layouts.app')

@section('title', __( 'Add User' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Add User' )</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-body">
            {!! Form::open(['url' => action('ManageUserController@store'), 'method' => 'post', 'id' => 'user_form', 'enctype' => 'multipart/form-data']) !!}
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                        {!! Form::label('surname', __( 'Prefix' ) . ':') !!}
                            {!! Form::text('surname', null, ['class' => 'form-input', 'placeholder' => __( 'Prefix' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                        {!! Form::label('first_name', __( 'First Name' ) . ':*') !!}
                            {!! Form::text('first_name', null, ['class' => 'form-input', 'required', 'placeholder' => __( 'First Name' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                        {!! Form::label('last_name', __( 'Last Name' ) . ':') !!}
                            {!! Form::text('last_name', null, ['class' => 'form-input', 'placeholder' => __( 'Last Name' ) ]); !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <div class="form-group">
                        {!! Form::label('email', __( 'Email' ) . ':') !!}
                            {!! Form::text('email', null, ['class' => 'form-input', 'placeholder' => __( 'Email' ) ]); !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                        {!! Form::label('role', __( 'Role' ) . ':*') !!}
                            {!! Form::select('role', $roles, null, ['class' => 'form-input select2']); !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        {!! Form::label('username', __( 'Username' ) . ':*') !!}
                            {!! Form::text('username', null, ['class' => 'form-input', 'required', 'placeholder' => __( 'Username' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        {!! Form::label('password', __( 'Password' ) . ':*') !!}
                            {!! Form::password('password', ['class' => 'form-input', 'required', 'placeholder' => __( 'Password' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        {!! Form::label('confirm_password', __( 'Confirm Password' ) . ':*') !!}
                            {!! Form::password('confirm_password', ['class' => 'form-input', 'required', 'placeholder' => __( 'Confirm Password' ) ]); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</section>
  @stop
@section('javascript')
<script type="text/javascript">
  $('form#user_form').validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    email: {
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    confirm_password: {
                        equalTo: "#password"
                    },
                    username: {
                        required: true,
                        minlength: 5,
                        remote: {
                            url: "/register/check-username",
                            type: "post",
                            data: {
                                username: function() {
                                    return $( "#username" ).val();
                                }
                            }
                        }
                    }
                },
                messages: {
                    password: {
                        minlength: 'Password should be minimum 5 characters',
                    },
                    confirm_password: {
                        equalTo: 'Should be same as password'
                    },
                    username: {
                        remote: 'Invalid username or User already exist'
                    }
                }
            });
</script>
@endsection
