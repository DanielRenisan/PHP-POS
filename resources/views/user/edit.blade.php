@extends('layouts.app')

@section('title', __( 'Edit User' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Edit User' )</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-body">
            {!! Form::open(['url' => action('ManageUserController@update', [$user->id]), 'method' => 'PUT', 'id' => 'user_edit_form','enctype' => 'multipart/form-data' ]) !!}
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                        {!! Form::label('surname', __( 'Prefix' ) . ':') !!}
                            {!! Form::text('surname', $user->surname, ['class' => 'form-input', 'placeholder' => __( 'Prefix' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                        {!! Form::label('first_name', __( 'First Name' ) . ':*') !!}
                            {!! Form::text('first_name', $user->first_name, ['class' => 'form-input', 'required', 'placeholder' => __( 'First Name' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                        {!! Form::label('last_name', __( 'Last Name' ) . ':') !!}
                            {!! Form::text('last_name', $user->last_name, ['class' => 'form-input', 'placeholder' => __( 'Last Name' ) ]); !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <div class="form-group">
                        {!! Form::label('email', __( 'Email' ) . ':') !!}
                            {!! Form::text('email', $user->email, ['class' => 'form-input', 'placeholder' => __( 'Email' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                        {!! Form::label('role', __( 'Role' ) . ':*') !!}
                            {!! Form::select('role', $roles, $user->roles->first()->id ?? '', ['class' => 'form-input select2']); !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        {!! Form::label('password', __( 'Password' ) . ':') !!}
                            {!! Form::password('password', ['class' => 'form-input', 'placeholder' => __( 'Password' ) ]); !!}
                            <p class="help-block">@lang('Leave Password Blank')</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        {!! Form::label('confirm_password', __( 'Confirm Password' ) . ':') !!}
                            {!! Form::password('confirm_password', ['class' => 'form-input', 'placeholder' => __( 'Confirm Password' ) ]); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">@lang( 'Update' )</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
     </div><!-- /.modal-content -->
</section>
@stop
@section('javascript')
<script type="text/javascript">
  $('form#user_edit_form').validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    email: {
                        email: true
                    },
                    password: {
                        minlength: 5
                    },
                    confirm_password: {
                        equalTo: "#password",
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