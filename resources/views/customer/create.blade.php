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
                <a href="{{action('CustomerController@index')}}" class="text-primary hover:underline">Customers</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Create Customer</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                </div>
                <div class="mb-5">
                {!! Form::open(['url' => action('CustomerController@store'), 'method' => 'post', 
'id' => 'customer_add_form','class' => 'customer_form', 'files' => true ]) !!}
<input type="hidden" name="customer_id" value="{{isset($customer) ? $customer->id : '' }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">First Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="First Name" class="form-input" name="first_name" value="{{isset($customer) ? $customer->first_name : '' }}" required="required">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">Last Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="last Name" class="form-input" name="last_name" required="required" value="{{isset($customer) ? $customer->last_name : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">Email <span class="text-danger">*</span></label>
                                <input type="email" placeholder="Email" class="form-input" name="email" required="required" value="{{isset($customer) ? $customer->email : '' }}">
                                @error('email')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            `    <label for="ctnSelect1">Phone <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Phone" class="form-input" name="contact_no" required="required" value="{{isset($customer) ? $customer->contact_no : '' }}">
                                <span class="text-xl text-white-dark">Note : Add prefix without + sign Example: (88)01840997***</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">Date of Birth</label>
                                <div x-data="form">

                                <input id="basic" x-model="date1" class="form-input flatpickr-input active" name="dob" type="text" readonly="readonly" value="{{isset($customer) ? $customer->dob : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">Profession</label>
                                <input type="text" placeholder="Profession" class="form-input" name="profession" value="{{isset($customer) ? $customer->profession : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ctnSelect1">Nationality</label>
                                <div>
                                    <label class="inline-flex">
                                        <input type="radio" name="nationality" class="form-radio outline-primary" id="nationality" value="1" {{isset($customer) && $customer->nationality == 1 ? 'checked' : '' }}>
                                        <span>Native</span>
                                    </label>
                                    <label class="inline-flex">
                                        <input type="radio" name="nationality" class="form-radio outline-primary" id="nationality_two"  value="2"  {{isset($customer) && $customer->nationality == 2 ? 'checked' : '' }}>
                                        <span>Foreigner</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="foreigner-div" style="display:{{isset($customer) && $customer->nationality == 2 ? '' : 'none' }}">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ctnSelect1">Nationality</label>
                                    <input type="text" placeholder="Nationality" class="form-input" name="nationality_country"  value="{{isset($customer) ? $customer->nationality_country : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ctnSelect1">Passport No</label>
                                    <input type="text" placeholder="Passport No" class="form-input" name="passport_no" value="{{isset($customer) ? $customer->passport_no : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ctnSelect1">Visa/Reg. No</label>
                                    <input type="text" placeholder="Visa/Reg. No" class="form-input" name="visa_reg_no" value="{{isset($customer) ? $customer->visa_reg_no : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ctnSelect1">Purpose</label>
                                    <div>
                                        <label class="inline-flex">
                                            <input type="radio" name="purpose" class="form-radio outline-primary" value="Tourist" {{isset($customer) && $customer->purpose == "Tourist" ? 'checked' : '' }}>
                                            <span>Tourist</span>
                                        </label>
                                        <label class="inline-flex">
                                            <input type="radio" name="purpose" class="form-radio outline-primary"  value="Business"  {{isset($customer) && $customer->purpose == "Business" ? 'checked' : '' }}>
                                            <span>Business</span>
                                        </label>
                                        <label class="inline-flex">
                                            <input type="radio" name="purpose" class="form-radio outline-primary"  value="Official"  {{isset($customer) && $customer->purpose == "Official" ? 'checked' : '' }}>
                                            <span>Official</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>    
                        <div class="col-md-6 native-div"  style="display:{{isset($customer) && $customer->nationality == 2 ? 'none' : '' }}">
                            <div class="form-group">
                                <label for="ctnSelect1">National ID</label>
                                <input type="text" placeholder="National ID" class="form-input" name="nic" value="{{isset($customer) ? $customer->nic : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6 native-div"  style="display:{{isset($customer) && $customer->nationality == 2 ? 'none' : '' }}">
                            <div class="form-group">
                                <label for="ctnSelect1">Police Info</label>
                                <input type="text" placeholder="Police Info" class="form-input" name="police_info" value="{{isset($customer) ? $customer->police_info : '' }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ctnSelect1">Address</label>
                                <textarea name="address" cols="30" rows="3" autocomplete="off" class="form-input" placeholder="Address">{{isset($customer) ? $customer->address : '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-file-container" data-upload-id="myFirstImage">
                                    <label class="input-container"> Docs/Images
                                        <input accept="*" name='image' aria-label="Choose File" class="input-hidden" id="file-upload-with-preview-myFirstImage" type="file">
                                        <span class="input-visible">Choose file...<span class="browse-button">Browse</span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary mt-6">{{isset($customer) ? 'UPDATE' : 'CREATE' }}</button>
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
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/nice-select2.css?v='.$asset_v) }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/file-upload-with-preview.min.css?v='.$asset_v) }}">
<script src="{{ asset('assets/js/nice-select2.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/file-upload-with-preview.iife.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
    var date = "{{isset($customer) ? $customer->dob : date('Y-m-d')}}";
    document.addEventListener("alpine:init", () => {
        Alpine.data("form", () => ({
            date1: date,    
            init() {
                flatpickr(document.getElementById('basic'), {
                    dateFormat: 'Y-m-d',
                    defaultDate: this.date1,
                })
            }
        }));
    });
document.addEventListener("DOMContentLoaded", function(e) {
        // seachable 
        var options = {
            searchable: true
        };
        NiceSelect.bind(document.getElementById("seachable-select"), options);
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