@extends('layouts.app')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li>
                <a href="{{action('SupplierController@index')}}" class="text-primary hover:underline">Suppliers</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Create Supplier</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                </div>
                <div class="mb-5">
                {!! Form::open(['url' => action('SupplierController@store'), 'method' => 'post', 
'id' => 'supplier_add_form','class' => 'supplier_form', 'files' => true ]) !!}
<input type="hidden" name="customer_id" value="{{isset($supplier) ? $supplier->id : '' }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">Supplier Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Supplier Name" class="form-input" name="name" value="{{isset($supplier) ? $supplier->name : '' }}" required="required">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">Email <span class="text-danger">*</span></label>
                                <input type="email" placeholder="Email" class="form-input" name="email" required="required" value="{{isset($supplier) ? $supplier->email : '' }}">
                                @error('email')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">Phone <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Phone" class="form-input" name="contact_no" required="required" value="{{isset($supplier) ? $supplier->contact_no : '' }}">
                                <span class="text-xl text-white-dark">Note : Add prefix without + sign Example: (88)01840997***</span>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ctnSelect1">Address</label>
                                <textarea name="address" cols="30" rows="3" autocomplete="off" class="form-input" placeholder="Address">{{isset($supplier) ? $supplier->address : '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary mt-6">{{isset($supplier) ? 'UPDATE' : 'CREATE' }}</button>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('assets/js/alpine-collaspe.min.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/alpine-persist.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-ui.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine-focus.min.js?v=' . $asset_v) }}"></script>
<script defer="" src="{{ asset('assets/js/alpine.min.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">

    document.addEventListener("alpine:init", () => {
        Alpine.data("form", () => ({
            date1: new Date().toISOString().substr(0, 10),
            init() {
                flatpickr(document.getElementById('basic'), {
                    dateFormat: 'Y-m-d',
                    defaultDate: this.date1,
                })
            }
        }));
    });

    $(document).on('click', 'input#nationality', function(){ 
        console.log(11)
        $('div.foreigner-div').hide();
            $('div.native-div').show();
    });
    $(document).on('click', 'input#nationality_two', function(){ 
        $('div.foreigner-div').show();
        $('div.native-div').hide();
    });
    </script>
@endsection