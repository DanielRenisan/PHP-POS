<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('LocationController@store'), 'method' => 'post', 'id' => 'business_location_add_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'Add Business Location' )</h4>
    </div>

    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    {!! Form::label('name', __( 'Location Name' ) . ':*') !!}
                    {!! Form::text('name', null, ['class' => 'form-input', 'required', 'placeholder' => __( 'Location Name' ) ]); !!}
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('landmark', __( 'Landmark' ) . ':') !!}
                    {!! Form::text('landmark', null, ['class' => 'form-input', 'placeholder' => __( 'Landmark' ) ]); !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('city', __( 'business.city' ) . ':*') !!}
                    {!! Form::text('city', null, ['class' => 'form-input', 'placeholder' => __( 'business.city'), 'required' ]); !!}
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('zip_code', __( 'Zip Code' ) . ':') !!}
                    {!! Form::text('zip_code', null, ['class' => 'form-input', 'placeholder' => __( 'Zip Code') ]); !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('state', __( 'State' ) . ':*') !!}
                    {!! Form::text('state', null, ['class' => 'form-input', 'placeholder' => __( 'State'), 'required']); !!}
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('country', __( 'Country' ) . ':*') !!}
                    {!! Form::text('country', null, ['class' => 'form-input', 'placeholder' => __( 'Country'), 'required' ]); !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('mobile', __( 'Mobile' ) . ':') !!}
                    {!! Form::text('mobile', null, ['class' => 'form-input', 'placeholder' => __( 'Mobile')]); !!}
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('tin_number','Tin Number' . ':*') !!}
                    {!! Form::text('tin_number', null, ['class' => 'form-input', 'required',
                    'placeholder' => 'Tin Number']); !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('reg_doc_no','Registration Document Number' . ':*') !!}
                    {!! Form::text('reg_doc_no', null, ['class' => 'form-input', 'required',
                    'placeholder' => 'Registration Document Number']); !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('fax_no','Fax Number' . ':*') !!}
                    {!! Form::text('fax_no', null, ['class' => 'form-input', 'required',
                    'placeholder' => 'Fax Number']); !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('alternate_number', __( 'Alternate Number' ) . ':') !!}
                    {!! Form::text('alternate_number', null, ['class' => 'form-input', 'placeholder' => __( 'Alternate_number')]); !!}
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('email', __( 'Email' ) . ':') !!}
                    {!! Form::email('email', null, ['class' => 'form-input', 'placeholder' => __( 'Email')]); !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">CREATE</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->