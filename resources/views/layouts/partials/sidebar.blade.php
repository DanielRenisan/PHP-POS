@inject('request', 'Illuminate\Http\Request')
@php
    
@endphp


<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="">
                <a href="{{action('Auth\LoginController@dashboard')}}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard

                        </span>
                </a>
            </li>
            @if(auth()->user()->can('user.view') ||
            auth()->user()->can('user.create') ||
            auth()->user()->can('role.view') ||
            auth()->user()->can('role.create'))
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span class="title">HR Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(auth()->user()->can('user.view') || auth()->user()->can('user.create')) 
                        <li class=""><a href="{{ action('ManageUserController@index') }}"><i class="fa fa-list"></i>Users</a></li>
                    @endif
                    @if(auth()->user()->can('role.view') || auth()->user()->can('role.create'))   
                        <li class=""><a href="{{ action('RoleController@index') }}"><i class="fa fa-list"></i>Role</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if(auth()->user()->can('customer.view') ||
            auth()->user()->can('customer.create') ||
            auth()->user()->can('wakeup.view') ||
            auth()->user()->can('wakeup.create') ||
            auth()->user()->can('supplier.view') ||
            auth()->user()->can('supplier.create'))
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-address-book"></i>
                    <span class="title">Customer</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(auth()->user()->can('customer.view') || auth()->user()->can('customer.create')) 
                        <li class=""><a href="{{ action('CustomerController@index') }}"><i class="fa fa-list"></i>Customer List</a></li>
                        <li class=""><a href="{{ action('GuestController@index') }}"><i class="fa fa-list"></i>Guest List</a></li>
                    @endif
                    @if(auth()->user()->can('wakeup.view') || auth()->user()->can('wakeup.create'))   
                        <li class=""><a href="{{ action('WakeUpController@index') }}"><i class="fa fa-list"></i>Wake Up Call List</a></li>
                    @endif
                    @if(auth()->user()->can('supplier.view') || auth()->user()->can('supplier.create')) 
                        <li class=""><a href="{{ action('SupplierController@index') }}"><i class="fa fa-list"></i>Supplier</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if(auth()->user()->can('room-facility.view') ||
            auth()->user()->can('room-facility.create') ||
            auth()->user()->can('room-details.view') ||
            auth()->user()->can('room-details.create') ||
            auth()->user()->can('room-size.view') ||
            auth()->user()->can('room-size.create')||
            auth()->user()->can('room-type.view') ||
            auth()->user()->can('room-type.create'))
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-th-large"></i>
                    <span class="title">Room Facilities</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(auth()->user()->can('room-facility.view') || auth()->user()->can('room-facility.create')) 
                        <li class=""><a href="{{ action('RoomFacilityController@index') }}"><i class="fa fa-list"></i>Facility List</a></li>
                    @endif
                    @if(auth()->user()->can('room-details.view') || auth()->user()->can('room-details.create'))   
                        <li class=""><a href="{{ action('RoomDetailController@index') }}"><i class="fa fa-list"></i>Facility Details</a></li>
                    @endif
                    @if(auth()->user()->can('room-size.view') || auth()->user()->can('room-size.create')) 
                        <li class=""><a href="{{ action('RoomSizeController@index') }}"><i class="fa fa-list"></i>Room Size</a></li>
                    @endif
                    @if(auth()->user()->can('room-type.view') || auth()->user()->can('room-type.create')) 
                        <li class=""><a href="{{ action('RoomTypeController@index') }}"><i class="fa fa-list"></i>Room Type</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if(auth()->user()->can('bed.view') ||
            auth()->user()->can('bed.create') ||
            auth()->user()->can('booking-type.view') ||
            auth()->user()->can('booking-type.create') ||
            auth()->user()->can('complementary.view') ||
            auth()->user()->can('complementary.create')||
            auth()->user()->can('floor-plan.view') ||
            auth()->user()->can('floor-plan.create')||
            auth()->user()->can('booking-source.view') ||
            auth()->user()->can('booking-source.create')||
            auth()->user()->can('room.view') ||
            auth()->user()->can('room.create'))
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-scribd"></i>
                    <span class="title">Room Settings</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(auth()->user()->can('bed.view') || auth()->user()->can('bed.create')) 
                        <li class=""><a href="{{ action('BedController@index') }}"><i class="fa fa-list"></i>Bed List</a></li>
                    @endif
                    @if(auth()->user()->can('booking-type.view') || auth()->user()->can('booking-type.create'))   
                        <li class=""><a href="{{ action('BookingTypeController@index') }}"><i class="fa fa-list"></i>Booking Type List</a></li>
                    @endif
                    @if(auth()->user()->can('complementary.view') || auth()->user()->can('complementary.create')) 
                        <li class=""><a href="{{ action('ComplementaryController@index') }}"><i class="fa fa-list"></i>Complementary</a></li>
                    @endif
                    @if(auth()->user()->can('floor-plan.view') || auth()->user()->can('floor-plan.create')) 
                        <li class=""><a href="{{ action('FloorPlaneController@index') }}"><i class="fa fa-list"></i>Floor Plan List</a></li>
                    @endif
                    @if(auth()->user()->can('booking-source.view') || auth()->user()->can('booking-source.create')) 
                        <li class=""><a href="{{ action('BookingSourceController@index') }}"><i class="fa fa-list"></i>Booking Source</a></li>
                    @endif
                    @if(auth()->user()->can('room.view') || auth()->user()->can('room.create')) 
                        <li class=""><a href="{{ action('RoomController@index') }}"><i class="fa fa-list"></i>Room Assign</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if(auth()->user()->can('booking.view') ||
            auth()->user()->can('booking.create') ||
            auth()->user()->can('checkin.view') ||
            auth()->user()->can('checkin.create') ||
            auth()->user()->can('checkout.create') ||
            auth()->user()->can('checkout.view'))
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-sliders"></i>
                    <span class="title">Room Reservation</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(auth()->user()->can('booking.view') || auth()->user()->can('booking.create')) 
                        <li class=""><a href="{{ action('BookingController@index') }}"><i class="fa fa-list"></i>Booking</a></li>
                    @endif
                    @if(auth()->user()->can('checkin.view') || auth()->user()->can('checkin.create'))   
                        <li class=""><a href="{{ action('CheckinController@index') }}"><i class="fa fa-list"></i>Checkin</a></li>
                    @endif
                    @if(auth()->user()->can('checkout.create')) 
                        <li class=""><a href="{{ action('CheckoutController@index') }}"><i class="fa fa-list"></i>Checkout</a></li>
                    @endif
                    @if(auth()->user()->can('checkout.view')) 
                        <li class=""><a href="{{ action('CheckoutController@list') }}"><i class="fa fa-list"></i>Checkout List</a></li>
                    @endif
                    <li class=""><a href="{{ action('RoomStatusController@index') }}"><i class="fa fa-list"></i>Room Status</a></li>
                </ul>
            </li>
            @endif
            @if(auth()->user()->can('purchase.view') ||
            auth()->user()->can('purchase.create'))
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-database"></i>
                    <span class="title">Purchase</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create')) 
                        <li class=""><a href="{{ action('TransactionController@index') }}"><i class="fa fa-list"></i>Purchase List</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if(auth()->user()->can('expense.view') ||
            auth()->user()->can('expense.create') ||
            auth()->user()->can('category.view') ||
            auth()->user()->can('category.create'))
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i>
                    <span class="title">Expense</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if(auth()->user()->can('expense.view') || auth()->user()->can('expense.create')) 
                        <li class=""><a href="{{ action('ExpenseController@index') }}"><i class="fa fa-list"></i>Expense List</a></li>
                    @endif
                    @if(auth()->user()->can('category.view') || auth()->user()->can('category.create')) 
                        <li class=""><a href="{{ action('CategoryController@index') }}"><i class="fa fa-list"></i>Category</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if(auth()->user()->can('setting.update'))
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span class="title">Setting</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=""><a href="{{ action('BussinessController@index') }}"><i class="fa fa-list"></i>Business Setting</a></li>
                </ul>
            </li>
            @endif
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-briefcase"></i>
                    <span class="title">Accounts</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=""><a href="{{ action('AccountController@index') }}"><i class="fa fa-list"></i>Initial Balance</a></li>
                    <li class=""><a href="{{ action('AccountController@cashFlow') }}"><i class="fa fa-list"></i>Cash Flow</a></li>
                </ul>
            </li>
        </ul>

        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
