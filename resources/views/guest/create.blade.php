<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('GuestController@store'), 'method' => 'post', 'id' => 'guest_add_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Customer Details</h4>
    </div>

    <div class="modal-body">
      <div class="row">
          <div class="col-md-6" style="border-right:1px solid black;">
          <h4 class="modal-title">Guest Details</h4>
          <br>
              <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">First Name <span class="text-danger">*</span></label>
                        <input type="text" placeholder="First Name" class="form-input" name="first_name" value="" required="required">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">Last Name <span class="text-danger">*</span></label>
                        <input type="text" placeholder="last Name" class="form-input" name="last_name" required="required" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">Father Name</label>
                        <input type="text" placeholder="Father Name" class="form-input" name="father_name" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">Gender</label>
                        <div>
                            <label class="inline-flex">
                                <input type="radio" name="gender" class="form-radio outline-primary" value="Male" >
                                <span>Male</span>
                            </label>
                            <label class="inline-flex">
                                <input type="radio" name="gender" class="form-radio outline-primary"  value="Female">
                                <span>Female</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="ctnSelect1">Phone <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Phone" class="form-input" name="contact_no" required="required" value="">
                        <span class="text-xl text-white-dark">Note : Add prefix without + sign Example: (88)01840997***</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">Occupation</label>
                        <input type="text" placeholder="Occupation" class="form-input" name="occupation" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">Date of Birth <span class="text-danger">*</span></label>
                        <div x-data="form">

                        <input id="basic" x-model="date2" class="form-input flatpickr-input active" name="dob" type="text" readonly="readonly" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">Anniversary</label>
                        <div x-data="form">

                        <input id="annu" x-model="date2" class="form-input flatpickr-input active" name="anniversary" type="text" readonly="readonly" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">Nationality</label>
                        <input type="text" placeholder="Nationality" class="form-input" name="nationality_country"  value="">
                    </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label class="inline-flex">
                          <input name="is_vip" type="checkbox" class="form-checkbox text-success" value="1" >
                          <span>Is VIP</span>
                      </label>
                  </div>
                </div>
          </div>
          <div class="col-md-6">
                <h4 class="modal-title">Contact Details</h4>
                <br>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="contacttype">Contact Type</label>
                        <div class="icon-addon addon-md">
                            <select class="form-input" id="contacttype" name="contact_type">
                                <option selected="" value="">Choose Contact Type</option>
                                <option value="Home">Home</option>
                                <option value="Personal">Personal</option>
                                <option value="Official">Official</option>
                                <option value="Business">Business</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">Email <span class="text-danger">*</span></label>
                        <input type="email" placeholder="Email" class="form-input" name="email" value="" required="required">
                        @error('email')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">Country</label>
                        <input type="text" placeholder="Country" class="form-input" name="country" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">State</label>
                        <input type="text" placeholder="State" class="form-input" name="state" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">City</label>
                        <input type="text" placeholder="City" class="form-input" name="city" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ctnSelect1">Zip Code</label>
                        <input type="text" placeholder="" class="form-input" name="zip_code" value="">
                    </div>
                </div>
          </div>
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">SAVE</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->