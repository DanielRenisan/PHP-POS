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
                <a href="{{action('Rest\ContactController@index_s')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">Suppliers</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Edit Supplier</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]" x-data="customerList">
                <div class="px-5" x:data="categoryList">
                    <form enctype="multipart/form-data" id="customer_add_form" class="needs-validation" method="POST" action="{{ route('contact.store_s') }}">
                    @csrf
                    <input name="id" type="hidden" value="{{isset($contact) ? $contact->id : ''}}">
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <label for="code">Short Code</label>
                            <input id="create_code" type="text" class="form-input" name="code"  value="{{isset($contact) ? $contact->code : ''}}"/>
                        </div>
                        <div>
                            <label for="firstName">First Name</label>
                            <input id="firstName" type="text" class="form-input" name="first_name" value="{{isset($contact) ? $contact->first_name : ''}}"
                                required />
                        </div>
                        <div>
                            <label for="lastName">LastName</label>
                            <input id="lastName" type="text" class="form-input" name="last_name" value="{{isset($contact) ? $contact->last_name : ''}}"/>
                        </div>
                        <div>
                            <label for="businessName">Business Name</label>
                            <input id="businessName" type="text" class="form-input" value="{{isset($contact) ? $contact->business_name : ''}}"
                                name="business_name" required />
                        </div>
                        <div>
                            <label for="address1">Address 1</label>
                            <input id="address1" type="text" class="form-input" value="{{isset($contact) ? $contact->address_one : ''}}"
                                name="address_one" />
                        </div>
                        <div>
                            <label for="address2">Address 2</label>
                            <input id="address2" type="text" class="form-input" value="{{isset($contact) ? $contact->address_two : ''}}"
                                name="address_two" />
                        </div>
                        <div>
                            <label for="city">City</label>
                            <input id="city" type="text" class="form-input" name="city" value="{{isset($contact) ? $contact->city : ''}}"/>
                        </div>
                        <div>
                            <label for="state">State</label>
                            <input id="state" type="text" class="form-input" name="state" value="{{isset($contact) ? $contact->state : ''}}"/>
                        </div>
                        <div>
                            <label for="zipcode">Zip Code</label>
                            <input id="zipcode" type="text" class="form-input" name="zip_code" value="{{isset($contact) ? $contact->zip_code : ''}}"/>
                        </div>
                        <div>
                            <label for="country">Country</label>
                            <input id="country" type="text" class="form-input" name="country" value="{{isset($contact) ? $contact->country : ''}}"/>
                        </div>

                        <!-- <div>
                            <label for="eventType">Event Type </label>
                            <select class="form-select text-white-dark" name="event_type"
                                x-model="itemToEdit.name" id="eventType">
                                <option value="" selected>Select Table</option>
                                @foreach ($eventType as $et)
                                <option name="event_type" class="pro-type" value="{{ $et->id }}" {{isset($contact) && $contact->event_type == $et->id ? 'selected' : ''}}>
                                    {{ $et->name }}</option>
                                @endforeach
                            </select>
                        </div> -->
                        <!-- <div>
                            <label for="eventDate">Event Date</label>
                            <input id="eventDate" type="date" class="form-input" value="{{isset($contact) ? $contact->event_date : ''}}"
                                name="event_date" />
                        </div> -->
                        <div>
                            <label for="mobileNo">Mobile Number</label>
                            <input type="text" id="mobileNo" class="form-input" maxlength="10" value="{{isset($contact) ? $contact->mobile_no : ''}}"
                                pattern="\d{10}" name="mobile_no"
                                title="Please enter exactly 10 digits" required />
                        </div>
                        <div>
                            <label for="telephoneNo">Telephone Number</label>
                            <input type="text" id="telephoneNo" class="form-input" maxlength="10" value="{{isset($contact) ? $contact->telephone_no : ''}}"
                                pattern="\d{10}" name="telephone_no"
                                title="Please enter exactly 10 digits" required />
                        </div>
                        <div>
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-input" name="email" value="{{isset($contact) ? $contact->email : ''}}"/>
                        </div>
                        <div>
                            <label for="taxNo">Tax Number</label>
                            <input id="taxNo" type="text" class="form-input" name="tax_no" value="{{isset($contact) ? $contact->tax_no : ''}}"/>
                        </div>

                        <div>
                            <label for="openBalance">Open Balance</label>
                            <input id="openBalance" type="number" class="form-input" value="{{isset($contact) ? $contact->open_balance : ''}}"
                                name="open_balance" />
                        </div>
                        <div>
                            <label for="paymentSettleDays">Payment Settle Days</label>
                            <input id="paymentSettleDays" type="text" class="form-input" value="{{isset($contact) ? $contact->payment_settle_days : ''}}"
                                name="payment_settle_days" />
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
                        <div style="margin-top:11px">
                            <label for="status">Status</label>
                            <input id="status" type="checkbox" class="form-checkbox"
                                name="status" checked/>
                            <span>Active</span>
                        </div>
                    </div>
                    <div class="mb-6 flex flex-wrap items-center justify-center gap-4 lg:justify-end">
                        <a href="{{action('Rest\ContactController@index')}}" class="btn btn-outline-danger"
                                >Discard</a>
                        <button type="submit" class="btn btn-primary" 
                                >Updated</button>
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
            searchText: "",
            viewItem: {},
            itemToEdit: {},
            selectedCusType: 'default',
            selectedHotelOption: '',
            get codeInitialLetter() {
                const allCustomers = JSON.parse(JSON.stringify(this.items));

                if(allCustomers.length > 0) {
                    return allCustomers.find(item =>
                        item.code_initial_letter === 'SU'
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
        }));
    });
</script>

@endsection 