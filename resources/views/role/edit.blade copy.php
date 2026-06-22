@extends('layouts.app_rest')
@section('title', __('Edit Role'))

@section('content')
<style>
.raw {
  width: 100%;
  line-height:3;
  margin-right: -15px;
    margin-left: -15px;
}
</style>
<style>
    ::-webkit-scrollbar {
        display: none;
    }

    .mt-100 {
        margin-top: 100px
    }

    .multibody {
        background: #ffffff;
        background: -webkit-linear-gradient(to right, #ffffff, #ffffff);
        background: linear-gradient(to right, #ffffff, #ffffff);
        color: #514B64;
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
              <span>Role Edit</span>
            </a>
          </li>
      </ul>
      <div class="grid grid-cols-1 gap-4 pt-5">
        <div class="panel">
      {!! Form::open(['url' => action('RoleController@update', $role->id), 'method' => 'PUT', 'id' => 'role_add_form' ]) !!}
        <div class="mb-5">
          <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('role_id', __( 'Role' ) . ':*') !!}
                {!! Form::select('role_id', $roles ,$role->id, ['class' => 'form-select', 'required', 'placeholder' => __( 'Name' ), 'disabled' ]); !!}
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
                    {!! Form::checkbox('permissions[]', 'user.view', in_array('user.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'User View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'user.create', in_array('user.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'User Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'user.update', in_array('user.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'User Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'user.delete', in_array('user.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'User Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class="check_group">
            <label style="padding-left: 15px;">@lang( 'Role Management' )</label>
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
                    {!! Form::checkbox('permissions[]', 'role.view', in_array('role.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Role View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'role.create', in_array('role.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Role Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'role.update', in_array('role.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Role Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'role.delete', in_array('role.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'employee-type.view', in_array('employee-type.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Emp Type View' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'employee-type.create', in_array('employee-type.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Emp Type Create' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'employee-type.update', in_array('employee-type.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Emp Type Update' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'employee-type.delete', in_array('employee-type.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'attendance-type.view', in_array('attendance-type.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Attendance Type View' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'attendance-type.create', in_array('attendance-type.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Attendance Type Create' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'attendance-type.update', in_array('attendance-type.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Attendance Type Update' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'attendance-type.delete', in_array('attendance-type.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'position.view', in_array('position.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Position View' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'position.create', in_array('position.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Position Create' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'position.update', in_array('position.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Position Update' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'position.delete', in_array('position.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'em-destination.view', in_array('em-destination.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Designation View' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'em-destination.create', in_array('em-destination.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Designation Create' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'em-destination.update', in_array('em-destination.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Designation Update' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'em-destination.delete', in_array('em-destination.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'department.view', in_array('department.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Department View' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'department.create', in_array('department.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Department Create' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'department.update', in_array('department.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Department Update' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'department.delete', in_array('department.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Department Delete' ) }}
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
                    {!! Form::checkbox('permissions[]', 'contact.view', in_array('contact.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Customer View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'contact.create', in_array('contact.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'contact.update', in_array('contact.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'contact.delete', in_array('contact.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'supplier.view', in_array('supplier.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Supplier View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'supplier.create', in_array('supplier.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Supplier Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'supplier.update', in_array('supplier.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Supplier Update' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class="check_group">
            <label style="padding-left: 15px;">@lang( 'Customer Type' )</label>
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
                    {!! Form::checkbox('permissions[]', 'customer-type.view', in_array('customer-type.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Type View' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'customer-type.create', in_array('customer-type.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Type Create' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'customer-type.update', in_array('customer-type.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Type Update' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'customer-type.delete', in_array('customer-type.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Type Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class="check_group">
            <label style="padding-left: 15px;">@lang( 'Customer Group' )</label>
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
                    {!! Form::checkbox('permissions[]', 'customer-group.view', in_array('customer-group.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Group View' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'customer-group.create', in_array('customer-group.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Group Create' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'customer-group.update', in_array('customer-group.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Group Update' ) }}
                  </label>
                </div>
              </div>
              <div>
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'customer-group.delete', in_array('customer-group.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Customer Group Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>   
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Bed management' )</label>
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
                  {!! Form::checkbox('permissions[]', 'bed.view', in_array('bed.view', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Beds View' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-3">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'bed.create', in_array('bed.create', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Bed Create' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-3">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'bed.update', in_array('bed.update', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Bed Update' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-3">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'bed.delete', in_array('bed.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'booking.view', in_array('booking.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Booking View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'booking.create', in_array('booking.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Booking Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'booking.update', in_array('booking.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Booking Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'booking.delete', in_array('booking.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Booking Delete' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'booking.checkin', in_array('booking.checkin', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Checkin' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'checkin.expense', in_array('checkin.expense', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Room Expense' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'booking.cancel', in_array('booking.cancel', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'booking-source.view', in_array('booking-source.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Source View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'booking-source.create', in_array('booking-source.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Source Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'booking-source.update', in_array('booking-source.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Source Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'booking-source.delete', in_array('booking-source.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'booking-type.view', in_array('booking-type.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Type View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'booking-type.create', in_array('booking-type.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Type Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'booking-type.update', in_array('booking-type.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Type Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'booking-type.delete', in_array('booking-type.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'checkin.view', in_array('checkin.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Check-in View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'checkin.create', in_array('checkin.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Check-in Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'checkin.update', in_array('checkin.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Check-in Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'checkin.delete', in_array('checkin.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'category.view', in_array('category.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Category View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'category.create', in_array('category.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Category Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'category.update', in_array('category.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Category Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'category.delete', in_array('category.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'complementary.view', in_array('complementary.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Complementary View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'complementary.create', in_array('complementary.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Complementary Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'complementary.update', in_array('complementary.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Complementary Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'complementary.delete', in_array('complementary.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Complementary Delete' ) }}
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
                    {!! Form::checkbox('permissions[]', 'expense.view', in_array('expense.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Expense View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'expense.create', in_array('expense.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Expense Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'expense.update', in_array('expense.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Expense Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'expense.delete', in_array('expense.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'floor.view', in_array('floor.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Floor View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'floor.create', in_array('floor.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Floor Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'floor.update', in_array('floor.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Floor Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'floor.delete', in_array('floor.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'floor-plan.view', in_array('floor-plan.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Plan View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'floor-plan.create', in_array('floor-plan.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Plan Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'floor-plan.update', in_array('floor-plan.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Plan Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'floor-plan.delete', in_array('floor-plan.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'room.view', in_array('room.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Room View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room.create', in_array('room.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Room Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room.update', in_array('room.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Room Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room.delete', in_array('room.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Room Delete' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room.assign', in_array('room.assign', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'room-details.view', in_array('room-details.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Room Details View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-details.create', in_array('room-details.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Room Details Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-details.update', in_array('room-details.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Room Details Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-details.delete', in_array('room-details.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'room-facility.view', in_array('room-facility.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Facility View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-facility.create', in_array('room-facility.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Facility Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-facility.update', in_array('room-facility.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Facility Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-facility.delete', in_array('room-facility.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'room-size.view', in_array('room-size.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Size View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-size.create', in_array('room-size.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Size Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-size.update', in_array('room-size.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Size Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-size.delete', in_array('room-size.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'purchase.view', in_array('purchase.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase.create', in_array('purchase.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase.update', in_array('purchase.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase.delete', in_array('purchase.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase Delete' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase-payment.create', in_array('purchase-payment.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase Due Payment' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Purchase Return' )</label>
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
                    {!! Form::checkbox('permissions[]', 'purchase-return.view', in_array('purchase-return.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase Return View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase-return.create', in_array('purchase-return.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase Return Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase-return.cancel', in_array('purchase-return.cancel', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase Return Cancel' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Purchase Wastage' )</label>
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
                    {!! Form::checkbox('permissions[]', 'purchase-wastage.view', in_array('purchase-wastage.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Wastage View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase-wastage.create', in_array('purchase-wastage.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Wastage Create' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Kitchen' )</label>
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
                    {!! Form::checkbox('permissions[]', 'kitchen.view', in_array('kitchen.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'BOT' )</label>
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
                    {!! Form::checkbox('permissions[]', 'bot.view', in_array('bot.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Sale Management' )</label>
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
                    {!! Form::checkbox('permissions[]', 'order.list', in_array('order.list', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Order List' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'sale.view', in_array('sale.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Invoice List' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'bot.view', in_array('sale.cancel', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Invoice Cancel' ) }}
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
                    {!! Form::checkbox('permissions[]', 'wakeup.view', in_array('wakeup.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Call View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'wakeup.create', in_array('wakeup.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Call Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'wakeup.update', in_array('wakeup.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Call Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'wakeup.delete', in_array('wakeup.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'room-type.view', in_array('room-type.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Room Type View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-type.create', in_array('room-type.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Room Type Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-type.update', in_array('room-type.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Room Type Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'room-type.delete', in_array('room-type.delete', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'setting.update', in_array('setting.update', $role_permissions), 
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
                    {!! Form::checkbox('permissions[]', 'checkout.view', in_array('checkout.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'checkout.create', in_array('checkout.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Product' )</label>
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
                    {!! Form::checkbox('permissions[]', 'product.view', in_array('product.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'product.create', in_array('product.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'product.update', in_array('product.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'product.delete', in_array('product.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'open-stock.create', in_array('open-stock.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Add Open Stock' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Product Variant' )</label>
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
                    {!! Form::checkbox('permissions[]', 'product-variation.view', in_array('product-variation.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'product-variation.create', in_array('product-variation.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'product-variation.update', in_array('product-variation.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'product-variation.delete', in_array('product-variation.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Type' )</label>
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
                    {!! Form::checkbox('permissions[]', 'type.view', in_array('type.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'type.create', in_array('type.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'type.update', in_array('type.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'type.delete', in_array('type.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Product Category' )</label>
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
                    {!! Form::checkbox('permissions[]', 'product-category.view', in_array('product-category.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'product-category.create', in_array('product-category.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'product-category.update', in_array('product-category.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'product-category.delete', in_array('product-category.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Cousine' )</label>
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
                    {!! Form::checkbox('permissions[]', 'cousine.view', in_array('cousine.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'cousine.create', in_array('cousine.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'cousine.update', in_array('cousine.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'cousine.delete', in_array('cousine.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Menu' )</label>
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
                    {!! Form::checkbox('permissions[]', 'menu.view', in_array('menu.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'menu.create', in_array('menu.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'menu.update', in_array('menu.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'menu.delete', in_array('menu.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Drink Type' )</label>
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
                    {!! Form::checkbox('permissions[]', 'drink-type.view', in_array('drink-type.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'drink-type.create', in_array('drink-type.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'drink-type.update', in_array('drink-type.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'drink-type.delete', in_array('drink-type.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Brand' )</label>
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
                    {!! Form::checkbox('permissions[]', 'brand.view', in_array('brand.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'brand.create', in_array('brand.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'brand.update', in_array('brand.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'brand.delete', in_array('brand.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Units' )</label>
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
                    {!! Form::checkbox('permissions[]', 'unit.view', in_array('unit.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'unit.create', in_array('unit.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'unit.update', in_array('unit.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'unit.delete', in_array('unit.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Table' )</label>
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
                    {!! Form::checkbox('permissions[]', 'table.view', in_array('table.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'table.create', in_array('table.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'table.update', in_array('table.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'table.delete', in_array('table.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Table Booking' )</label>
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
                    {!! Form::checkbox('permissions[]', 'table-booking.view', in_array('table-booking.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'table-booking.create', in_array('table-booking.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'table-booking.update', in_array('table-booking.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'table-booking.delete', in_array('table-booking.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
          <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
            <label style="padding-left: 15px;">@lang( 'Tax' )</label>
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
                    {!! Form::checkbox('permissions[]', 'tax.view', in_array('tax.view', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'tax.create', in_array('tax.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'tax.update', in_array('tax.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'tax.delete', in_array('tax.delete', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete' ) }}
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
                    {!! Form::checkbox('permissions[]', 'business-location.index', in_array('business-location.index', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'business-location.create', in_array('business-location.create', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'business-location.update', in_array('business-location.update', $role_permissions), 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'business-location.delete', in_array('business-location.delete', $role_permissions), 
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
                        {!! Form::checkbox('permissions[]', 'access_all_locations', in_array('access_all_locations', $role_permissions), 
                      [ 'class' => 'input-icheck']); !!} {{ __( 'All Locations' ) }} 
                      </label>
                  </div>
                </div>
                @foreach($locations as $location)
                  <div>
                    <div class="checkbox">
                      <label>
                        {!! Form::checkbox('location_permissions[]', 'locations.' . $location->id, in_array('locations.' . $location->id, $role_permissions), 
                        [ 'class' => 'input-icheck']); !!} {{ $location->name }}
                      </label>
                    </div>
                  </div>
                @endforeach
            </div>
        </div>
        <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
        <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
          <label style="padding-left: 15px;">@lang( 'Petty Cash' )</label>
          <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">  
            <div>
            <div class="col-md-3">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'petty-cash.update', in_array('petty-cash.update', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Create' ) }}
                </label>
              </div>
            </div>
            </div>
          </div>
        </div>
        <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
        <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
          <label style="padding-left: 15px;">@lang( 'Reports' )</label>
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
                  {!! Form::checkbox('permissions[]', 'sale-report.view', in_array('sale-report.view', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Sale Report' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-3">
              <!-- <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'register-report.view', in_array('register-report.view', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Register Report' ) }}
                </label>
              </div> -->
            </div>
            <div class="col-md-3">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'purchase-report.view', in_array('purchase-report.view', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Purchase Report' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-3">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'sale-detail-report.view', in_array('sale-detail-report.view', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Sale Detail Report' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-3">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'payment-detail-report.view', in_array('payment-detail-report.view', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Payment Receive Report' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-3">
              <!-- <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'loaction-report.view', in_array('loaction-report.view', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Location Balance Report' ) }}
                </label>
              </div> -->
            </div>
            <div class="col-md-3">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'sale-cancel-report.view', in_array('sale-cancel-report.view', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Sale Cancel Report' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-3">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'sale-profit-report.view', in_array('sale-profit-report.view', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'Sale Profit Report' ) }}
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
        <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
          <label style="padding-left: 15px;">@lang( 'Pos Dashboard' )</label>
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
                  {!! Form::checkbox('permissions[]', 'pos.dashboard', in_array('pos.dashboard', $role_permissions), 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'POS' ) }}
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="h-px border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
        <div style="display: flex; align-items: center; gap: 20px; padding: 20px 0;" class=" check_group">
          <label style="padding-left: 15px;">@lang( 'User wise Access' )</label>
          <div class="checkboxes" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
            <div class="col-md-3">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'all-users', in_array('all-users', $role_permissions), 
                  [ 'class' => 'input-icheck', 'id' => 'all_user_input']); !!} {{ __( 'All Users' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-3">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'specific-user', in_array('specific-user', $role_permissions), 
                  [ 'class' => 'input-icheck', 'id' => 'specific_user_input']); !!} {{ __( 'Specific Users' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-6 specific-users-div" style="display:{{in_array('specific-user', $role_permissions) ? '' :'none'}}">
            <div class="multibody">
                                                <select class="form-select text-white-dark" name="employees[]"
                                                    style="font-size: 14px; font-weight: bold;line-height:1.25rem;" id="choices-multiple-remove-button"
                                                    placeholder="Select Employee" multiple>
                                                    @foreach($employees as $employee)
                    <option value="{{$employee->id}}" {{ in_array($employee->id, $accesses) ? 'selected' : '' }}>{{$employee->first_name}}</option>
                @endforeach
                                                </select>
                                            </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right">UPDATE</button>
          </div>
        </div>
      </div>              
      {!! Form::close() !!}
    </div>
  </div>

</section>
<!-- /.content -->
@endsection
@section('javascript')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"></script>
<script>
  $(document).ready(function() {
    $(document).on('ifChecked', 'input#specific_user_input', function(){
      $('input#all_user_input').parent('div').attr('aria-checked', false);
      $('input#all_user_input').parent('div').removeClass('checked'); 
      $('.specific-users-div').show();

    });
    $(document).on('ifChecked', 'input#all_user_input', function(){
      $('input#specific_user_input').parent('div').attr('aria-checked', false);
      $('input#specific_user_input').parent('div').removeClass('checked'); 
      $('.specific-users-div').hide();
    });

    var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
        removeItemButton: true,
        maxItemCount:5,
        searchResultLimit:5,
        renderChoiceLimit:5
    });
  });
  </script>
@endsection