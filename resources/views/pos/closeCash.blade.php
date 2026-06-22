<div x-show.transition.duration.500ms="closePopUp"
    :class="{ 'popup-hidden': !closePopUp, 'popup-visible': closePopUp }" class="popup"
    @click.away="closePopUp = null" style="width: 80%;max-height: 650px !important;">
    @php 
        $cash =  App\Models\CashRegister::where('user_id', auth()->user()->id)->where('status','open')
                        ->first();
        @endphp
        <!-- modal -->
        @if(isset($cash))
        <div>
            <div>
                <h6 style="
                background: #4361ee;
                color: white;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                padding: 10px 20px;" class="menu-title">Opening Balance ( {{ \Carbon::createFromFormat('Y-m-d H:i:s', $cash->created_at)->format('jS M, Y h:i A') }} - {{ \Carbon::now()->format('jS M, Y h:i A') }})</h6>
                <div style="margin-top: 25px;">
                    <!-- search  -->
                    <div class="m-0" @click.outside="search = false">
                        <form id="cash_register_form" 
                                class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0" method="POST"
                            action="{{ action('CashRegisterController@postCloseRegister') }}">
                            @csrf
                            <div class="grid grid-cols-1 gap-4">
                                <table class="table">
                                    <tr>
                                        <th></th>
                                        <th style="text-align:center;">Credit</th>
                                        <th style="text-align:center;">Debit</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            Opening Balance:
                                        </td>
                                        <td  style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="opening_balance"></span>
                                        </td>
                                        <td style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true">0.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Cash Payment
                                        </th>
                                        <td  style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="cre_cash_payment"></span>
                                        </td>
                                        <td style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="deb_cash_payment"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Cheque Payment:
                                        </th>
                                        <td  style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="cre_cheque_payment"></span>
                                        </td>
                                        <td style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="deb_cheque_payment"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Credit Payment
                                        </th>
                                        <td  style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="cre_credit_payment"></span>
                                        </td>
                                        <td style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="deb_credit_payment"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Card Payment
                                        </th>
                                        <td  style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="cre_card_payment"></span>
                                        </td>
                                        <td style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="deb_card_payment"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Card Payment
                                        </th>
                                        <td  style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="cre_bank_transfer_payment"></span>
                                        </td>
                                        <td style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="deb_bank_transfer_payment"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Other Payment
                                        </th>
                                        <td  style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="cre_other_payment"></span>
                                        </td>
                                        <td style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="deb_other_payment"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Total Sale</strong>
                                        </th>
                                        <td  style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true" id="total_sale"></span>
                                        </td>
                                        <td style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true">0.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <strong>Total Purchase</strong>
                                        </th>
                                        <td  style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true">0.00</span>
                                        </td>
                                        <td style="text-align:right;">
                                            <span class="display_currency" data-currency_symbol="true"  id="total_purchase">0.00</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="grid grid-cols-4 gap-4">
                                <div>
                                    {!! Form::label('closing_amount', __( 'Total Cash' ) . ':*') !!}
                                    {!! Form::text('closing_amount', null, ['class' => 'form-input input_number closing-amount', 'required', 'placeholder' => __( 'Total Cash' ) ]); !!}
                                </div>
                                <div>
                                    {!! Form::label('total_card_slips', __( 'Total Card Slips' ) . ':*') !!}
                                    {!! Form::number('total_card_slips', null, ['class' => 'form-input', 'required', 'placeholder' => __( 'Total Card Slips' ), 'min' => 0 ]); !!}
                                </div> 
                                <div>
                                    {!! Form::label('total_cheques', __( 'Total cheques' ) . ':*') !!}
                                    {!! Form::number('total_cheques', null, ['class' => 'form-input', 'required', 'placeholder' => __( 'Total cheques' ), 'min' => 0 ]); !!}
                                </div>
                                <div>
                                    {!! Form::label('grand_total', 'Grand Total:') !!}
                                    {!! Form::text('grand_total', null, ['class' => 'form-input input_number', 'id' => 'closing_grand_total' ]); !!}

                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    {!! Form::label('closing_note', __( 'Closing Note:' ) . ':') !!}
                                    {!! Form::textarea('closing_note', null, ['class' => 'form-input', 'placeholder' => __( 'Closing Note' ), 'rows' => 3 ]); !!}
                                </div>
                            </div>  
                            <div class="flex justify-end items-center">
                                <button type="button" class="btn btn-outline-danger discard-btn"
                                    @click="closePopUp = false">Discard</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4 discard-btn">Close Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
</div>