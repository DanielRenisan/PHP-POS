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
                <span>Create Customer</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]" x-data="customerList">
                <div class="px-5" x:data="categoryList">
                <form enctype="multipart/form-data" id="customer_add_form" class="needs-validation" method="POST" action="{{ route('contact.store') }}">
                @csrf

                    <!-- Radio buttons -->
                    <!-- <div class="customer-types" style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label>
                                <input type="radio" name="cusType" value="default"
                                    x-model="selectedCusType" checked @click="change()">
                                Default
                            </label>
                        </div>
                        <div>
                            <label>
                                <input type="radio" name="cusType" value="hotel"
                                    x-model="selectedCusType"
                                    @click="change()">
                                Hotel
                            </label>
                        </div>
                    </div> -->
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <label for="code">Short Code</label>
                            <input id="create_code" type="text" class="form-input" name="create_code" value="{{isset($contact) ? $contact->code : ''}}"/>
                        </div>
                        <div>
                            <label for="firstName">First Name *</label>
                            <input id="firstName" type="text" class="form-input" name="first_name"
                                required />
                        </div>
                        <div>
                            <label for="lastName">Last Name</label>
                            <input id="lastName" type="text" class="form-input" name="last_name" />
                        </div>
                        <div>
                            <label for="address1">Address 1</label>
                            <input id="address1" type="text" class="form-input"
                                name="address_one" />
                        </div>
                        <div>
                            <label for="address2">Address 2</label>
                            <input id="address2" type="text" class="form-input"
                                name="address_two" />
                        </div>
                        <div>
                            <label for="city">City</label>
                            <input id="city" type="text" class="form-input" name="city" />
                        </div>
                        <div>
                            <label for="state">State</label>
                            <input id="state" type="text" class="form-input" name="state" />
                        </div>
                        <div>
                            <label for="zipcode">Zip Code</label>
                            <input id="zipcode" type="text" class="form-input" name="zip_code" />
                        </div>
                        <div>
                            <label for="country">Country</label>
                            <input id="country" type="text" class="form-input" name="country" />
                        </div>

                        <!-- Hotel options -->
                        <div class="mt-3" x-show="selectedCusType === 'hotel'">
                            <label>Hotel Options:</label>
                            <div class="hotel-options"
                                style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                                <div>
                                    <label>
                                        <input type="radio" name="nationality_type" value="native"
                                            x-model="selectedHotelOption">
                                        Native
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="radio" name="nationality_type"
                                            value="foreigner" x-model="selectedHotelOption">
                                        Foreigner
                                    </label>
                                </div>
                            </div>

                        </div>
                        <!-- Additional fields for Native -->
                        <div
                            x-show="selectedCusType === 'hotel' && selectedHotelOption === 'native'">
                            <div>
                                <label for="professional">Professional</label>
                                <input id="professional" type="text" name="professional"
                                    class="form-input" />
                            </div>
                        </div>

                        <div
                            x-show="selectedCusType === 'hotel' && selectedHotelOption === 'native'">
                            <div>
                                <label for="nationalId">National ID</label>
                                <input id="nationalId" type="text" class="form-input"
                                    name="national_id" />
                            </div>
                        </div>

                        <div
                            x-show="selectedCusType === 'hotel' && selectedHotelOption === 'native'">
                            <div>
                                <label for="policeInfo">Police Info</label>
                                <input id="policeInfo" type="text" class="form-input"
                                    name="police_info" />
                            </div>
                        </div>



                        <div
                            x-show="selectedCusType === 'hotel' && selectedHotelOption === 'foreigner'">
                            <div>
                                <label for="nationality">Nationality</label>
                                <input id="nationality" type="text" name="nationality"
                                    class="form-input" />
                            </div>
                        </div>
                        <div
                            x-show="selectedCusType === 'hotel' && selectedHotelOption === 'foreigner'">
                            <div>
                                <label for="passportNo">Passport Number</label>
                                <input id="passportNo" type="text" class="form-input"
                                    name="passport_no" />
                            </div>
                        </div>
                        <div
                            x-show="selectedCusType === 'hotel' && selectedHotelOption === 'foreigner'">
                            <div>
                                <label for="purposeVisit">Purpose of Visit</label>
                                <input id="purposeVisit" type="text" name="purpose_of_visit"
                                    class="form-input" />
                            </div>
                        </div>
                        <div
                            x-show="selectedCusType === 'hotel' && selectedHotelOption === 'foreigner'">
                            <div>
                                <label for="visaRegNo">Visa/Registration Number</label>
                                <input id="visaRegNo" type="text" class="form-input"
                                    name="visa_no" />
                            </div>
                        </div>

                        <!-- <div>
                            <label for="eventType">Event Type </label>
                            <select class="form-select text-white-dark" name="event_type"
                                x-model="itemToEdit.name" id="eventType">
                                <option value="" disabled selected>Select Type</option>
                                @foreach ($eventType as $et)
                                <option name="event_type" class="pro-type" value="{{ $et->id }}" {{
                                    $et->
                                    id == old('event_type') ? 'selected' : '' }}>
                                    {{ $et->name }}</option>
                                @endforeach
                                <span class="text-danger">
                                    @error('event_type')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </select>
                        </div> -->
                        <!-- <div>
                            <label for="eventDate">Event Date</label>
                            <input id="eventDate" type="date" class="form-input"
                                name="event_date" />
                        </div> -->
                        <div>
                            <label for="mobileNo">Mobile Number *</label>
                            <input type="text" id="mobileNo" class="form-input" maxlength="10"
                                pattern="\d{10}" name="mobile_no" required />
                        </div>
                        <div>
                            <label for="telephoneNo">Telephone Number</label>
                            <input type="text" id="telephoneNo" class="form-input" maxlength="10"
                                pattern="\d{10}" name="telephone_no"
                                title="Please enter exactly 10 digits" />

                        </div>
                        <div>
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-input" name="email" />
                        </div>
                        <div>
                            <label for="taxNo">Tax Number</label>
                            <input id="taxNo" type="text" class="form-input" name="tax_no" />
                        </div>

                        <div>
                            <label for="customerGroup">Customer Group</label>
                            <select class="form-select text-white-dark" name="contact_group"
                                x-model="itemToEdit.name" id="customerGroup">
                                <option value="" disabled selected>Select Group</option>
                                @foreach ($customerGroup as $et)
                                <option name="contact_group" class="pro-type" value="{{ $et->id }}"
                                    {{ $et->
                                    id == old('contact_group') ? 'selected' : '' }}>
                                    {{ $et->name }}</option>
                                @endforeach
                                <span class="text-danger">
                                    @error('contact_group')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </select>
                        </div>
                        <div>
                            <label for="creditPayment">Credit Payment</label>
                            <input id="creditPayment" type="text" class="form-input"
                                name="credit_payment" />
                        </div>
                        <div>
                            <label for="imageUpload">Image Upload</label>
                            <div
                                style="display: flex; align-items: center; gap: 10px; object-fit: cover;">

                                <div x-show="imagePreview">
                                    <img style="width: 50px; height: 40px; margin: 0; border-radius: 5px;"
                                        :src="imagePreview" alt="Image Preview"
                                        style="max-width: 200px; max-height: 200px;" />
                                </div>
                                <input id="imageUpload" type="file" class="form-input" name="image"
                                    accept="image/*" x-on:change="handleImageUpload($event)" />
                            </div>
                        </div>
                        <div style="margin-top: 13px;">
                            <label for="status">Status</label>
                            <input id="status" type="checkbox" class="form-checkbox" checked
                                name="status" />
                            <span>Active</span>
                        </div>
                    </div>
                    <div class="mb-6 flex flex-wrap items-center justify-center gap-4 lg:justify-end">
                    <a href="{{action('Rest\ContactController@index')}}" class="btn btn-outline-danger"
                                >Discard</a>
                            <button type="submit" class="btn btn-primary" @click="generateCode()"
                                >Create</button>
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
            //Customer list
            Alpine.data("customerList", () => ({
            selectedRows: [],
            items:  <?php echo $customer; ?>,
            searchText: "",
            viewItem: {},
            itemToEdit: {},
            selectedCusType: 'default',
            selectedHotelOption: '',
            get codeInitialLetter() {
                const allCustomers = JSON.parse(JSON.stringify(this.items));

                if(allCustomers.length > 0) {
                    return allCustomers.find(item =>
                        item.code_initial_letter === 'CU'
                    ).code_initial_letter
                }

                return "CU";
            },
            change() {
                console.log(11)
                this.selectedCusType= 'hotel';
                this.selectedHotelOption = 'native';

                return this.selectedCusType;
            }, 
            get customerLastId() {
                const allCustomers = JSON.parse(JSON.stringify(this.items));

                if (allCustomers.length > 0) {
                    return  allCustomers[allCustomers.length - 1].id;

                }

                return 0;
            },
            data() {
            return {
                newCustomer: {
                imageUpload: null, // Initialize imageUpload
                // Other properties...
                },
                // Other data properties...
            };
            },

            handleImageUpload(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = (e) => {
                this.imagePreview = e.target.result;
                this.newCustomer.imageUpload = e.target.result; // Ensure this line sets the imageUpload property
            };

            reader.readAsDataURL(file);
            },

            statusChecked: false,
            generateCode() {
                document.getElementById('create_code').value = this.codeInitialLetter + '000' + this.customerLastId;
            },
        }));
    });
</script>

@endsection 