@extends('layouts.app_rest')
@section('title', __('Create Role'))

@section('content')
<style>
.raw {
  width: 100%;
  line-height:3;
  margin-right: -15px;
    margin-left: -15px;
}
</style>
<!-- Main content -->
<div class="animate__animated p-6" :class="[$store.app.animation]">
  <div>
      <ul class="flex space-x-2 rtl:space-x-reverse">
          <li>
              <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
          </li>
          <li>
            <a href="{{action('RoleController@index')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1 text-primary hover:underline" >
              Roles
            </a>
          </li>
          <li> 
            <a class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1" >
              <span>Role Create</span>
            </a>
          </li>
      </ul>
      <div class="grid grid-cols-1 gap-4 pt-5">
        <div class="panel">
            {!! Form::open(['url' => action('RoleController@store'), 'method' => 'post', 'id' => 'role_add_form' ]) !!}
              <div class="mb-5">
                  <div class="grid grid-cols-1 gap-4 pt-5">
                    <div class="col-md-4">
                      <div class="form-group">
                        {!! Form::label('role_id', __( 'Position' ) . ':*') !!}
                          {!! Form::select('role_id', $roles ,null, ['class' => 'form-select', 'required', 'placeholder' => __( 'Name' ) ]); !!}
                      </div>
                    </div>
                  </div>
                  <div class="grid grid-cols-1 gap-4 pt-5">
                    <div class="col-md-3">
                      <label>Permissions:</label> 
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'User' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'user.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'User View' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'user.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'User Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'user.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'User Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'user.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'User Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class="check_group">
                    <label style="padding-left: 15px;">@lang( 'Role management' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'role.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Role View' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'role.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Role Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'role.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Role Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'role.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Role Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class="check_group">
                    <label style="padding-left: 15px;">@lang( 'Employee Type' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'employee-type.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Emp Type View' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'employee-type.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Emp Type Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'employee-type.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Emp Type Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'employee-type.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Emp Type Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class="check_group">
                    <label style="padding-left: 15px;">@lang( 'Attendance Type' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'attendance-type.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Attendance Type View' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'attendance-type.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Attendance Type Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'attendance-type.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Attendance Type Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'attendance-type.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Attendance Type Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class="check_group">
                    <label style="padding-left: 15px;">@lang( 'Position' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'position.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Position View' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'position.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Position Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'position.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Position Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'position.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Position Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class="check_group">
                    <label style="padding-left: 15px;">@lang( 'Designation' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'em-destination.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Designation View' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'em-destination.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Designation Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'em-destination.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Designation Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'em-destination.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Designation Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class="check_group">
                    <label style="padding-left: 15px;">@lang( 'Department' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'department.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Department View' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'department.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Department Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'department.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Department Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div>
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'department.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Department Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Bed management' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'bed.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Beds View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'bed.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Bed Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'bed.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Bed Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'bed.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Bed Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Booking' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Booking View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Booking Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Booking Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Booking Delete' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking.checkin', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Checkin' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'checkin.expense', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Expense' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking.cancel', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Cancel' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Booking Source' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking-source.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Source View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking-source.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Source Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking-source.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Source Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking-source.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Source Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Booking Type' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking-type.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Type View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking-type.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Type Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking-type.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Type Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'booking-type.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Type Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Direct Check-in' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'checkin.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Check-in View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'checkin.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Check-in Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'checkin.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Check-in Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'checkin.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Check-in Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Category' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'category.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Category View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'category.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Category Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'category.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Category Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'category.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Category Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Complementary' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'complementary.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Complementary View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'complementary.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Complementary Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'complementary.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Complementary Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'complementary.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Complementary Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Customer' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'customer.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Customer View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'customer.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'customer.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'customer.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Supplier' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'supplier.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Supplier View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'supplier.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Supplier Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'supplier.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Supplier Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'supplier.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Supplier Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Expense' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'expense.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Expense View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'expense.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Expense Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'expense.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Expense Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'expense.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Expense Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Floor Management' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'floor.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Floor View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'floor.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Floor Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'floor.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Floor Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'floor.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Floor Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Floor Plan' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'floor-plan.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Plan View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'floor-plan.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Plan Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'floor-plan.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Plan Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'floor-plan.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Plan Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Room Management' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Delete' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room.assign', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Assign' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Room Details' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-details.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Details View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-details.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Details Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-details.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Details Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-details.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Details Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Room Facility' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-facility.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Facility View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-facility.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Facility Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-facility.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Facility Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-facility.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Facility Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Room Size' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-size.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Size View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-size.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Size Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-size.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Size Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-size.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Size Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Purchase' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'purchase.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'purchase.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'purchase.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'purchase.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Wake up Call' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'wakeup.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Call View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'wakeup.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Call Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'wakeup.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Call Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'wakeup.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Call Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Room Type' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-type.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Type View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-type.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Type Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-type.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Type Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'room-type.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Room Type Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Business Setting' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'setting.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Business Setting' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Checkout' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'checkout.view', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'checkout.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Business Location' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                      <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                            </label>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'business-location.index', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'business-location.create', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'business-location.update', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="checkbox">
                          <label>
                            {!! Form::checkbox('permissions[]', 'business-location.delete', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                  <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
                    <label style="padding-left: 15px;">@lang( 'Location Access' )</label>
                    <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">  
                      <div>
                        <div class="checkbox">
                            <label>
                              {!! Form::checkbox('permissions[]', 'access_all_locations', true, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'All Locations' ) }} 
                            </label>
                        </div>
                      </div>
                      @foreach($locations as $location)
                        <div>
                          <div class="checkbox">
                            <label>
                              {!! Form::checkbox('location_permissions[]', 'locations.' . $location->id, false, 
                              [ 'class' => 'input-icheck']); !!} {{ $location->name }}
                            </label>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                  <div class="raw">
                    <div class="col-md-12">
                      <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>
                  </div>
              </div>
            {!! Form::close() !!}
        </div>
      </div>
  </div>
</div>
<!-- /.content -->
@endsection