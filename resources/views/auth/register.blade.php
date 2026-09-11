@extends('layout.auth')

@section('content')
    <!-- <section class="login-section">
        <div class="container">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="login-box col-md-6 mb-4 mt-4">
                    @include('common.notify')
                    <div id="regCheck" class="card-body">
                        <h5>WHY DO I NEED TO REGISTER?</h5>
                        <hr>
                        <div class="country-input1">
                            <p>I am a resident of:</p>
                            <div class="form-group">
                                <select class="reg-sel form-control" id="country_id" name="country_id" required="">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $value)
    <option value="{{ $value->id }}">{{ $value->countryname }}</option>
    @endforeach
                                </select>
                                <span class="errors" id="country_id_error"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="register-content">
                                    <div class="checkbox">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="list-1" name="list1" required="">
                                            <label class="custom-control-label" for="list-1">By accessing the Dashboard you are agreeing to be bound by the terms of service, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. </label>
                                            <span class="errors" id="list1_error"></span>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="list-2"  name="list2" required="">
                                            <label class="custom-control-label" for="list-2">In no event shall the company or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials.</label>
                                            <span class="errors" id="list2_error"></span>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="list-3"   name="list3" required="">
                                            <label class="custom-control-label" for="list-3">I understand the risks of being part of the investment opportunity  and agree to comply with the securities laws enforced by countries enforcement agencies.</label>
                                            <span class="errors" id="list3_error"></span>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="list-4"  name="list4" required="">
                                            <label class="custom-control-label" for="list-4">You may be required to pay mining or gas fees for transfers of security tokens on the Ethereum Network. I agree that I am olely responsible for all mining fees as well as all other costs and expenses incurred with respect to obtaining or using security tokens on the Ethereum Network.</label>
                                            <span class="errors" id="list4_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 text-center mt-5">
                                <a class="btn btn-theme-dark regCheckBtn" role="button">REGISTER</a>
                            </div>
                            <div class="text-center col-xl-12 mt-3">
                                <a href="{{ url('/login') }}" class="link">Back to Login</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="regFrom" class="col-md-6  login-box d-none">
                    <h2 class="text-center">Investor</h2>
                        <div>
                            <label>{{ __('Name') }}</label>

                            <div class="input-group">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
    @endif
                            </div>
                        </div>

                        <div>
                            <label>{{ __('E-Mail Address') }}</label>

                            <div class="input-group">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
    @endif
                            </div>
                        </div>

                        <div>
                            <label>{{ __('Password') }}</label>

                            <div class="input-group">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
    @endif
                            </div>
                        </div>

                        <div>
                            <label>{{ __('Confirm Password') }}</label>
                            <div class="input-group">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        {{-- <div>
                        <label>Type</label>
                        <div class="input-group">
                            <select class="form-control" name="main_type" required>
                                <option value="">Select Type</option>
                                <option value="1">Investor</option>
                                <option value="2">Issuer</option>
                            </select>
                        </div>
                    </div> --}}

                        <div class="check mt-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="regTerms" required="">
                                <label class="custom-control-label" for="regTerms">I have read and accept <a href="#">Privacy Policy</a> and <a href="#">Consent Notice</a></label>
                            </div>
                        </div>


                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-theme-dark">{{ __('Register') }}</button>
                        </div>

                        <div class="text-center ">
                            <a href="{{ url('/login') }}" class="link">Back to Login</a>
                        </div>

                </div>
            </form>
        </div>
    </section> -->

    <div class="form-holder">
        <div class="form-content">
            <div class="form-items">
                @include('common.notify')
                <h3>Register as investor.</h3>
                <p>Please enter following details to register as Investor in the platform. A verification email will be send to your email address</p>
                <div class="page-links">
                    <a href="#" class="active">Investor Register</a>
                </div>

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6 mt-6">
                    <input class="form-control" type="text" name="name" placeholder="Full Name"
                        value="{{ old('name') }}" required autocomplete="off">
                    </div>
                    <div class="mb-6">
                        <input class="form-control" id="email_address" type="email" name="email"
                            placeholder="xyz@gmail.com" value="{{ old('email') }}" required autocomplete="off">
                        <span id="email_error" style="display:none;color:red;">Enter valid e-mail</span>
                    </div>
                    <div class="mb-6">
                        <input class="form-control passwordField" type="password" name="password" placeholder="Password"
                            id="password" required autocomplete="off">
                    </div>
                    <div class="mb-6">
                        <input class="form-control passwordField" type="password" name="password_confirmation"
                            placeholder="Confirm Password" id="confirm_password" required autocomplete="off">
                    </div>

                    <div class="mb-6">
                    <select class="reg-sel form-control" id="country_id" name="country_id" required="">
                        <option value="">Select Country</option>
                        @foreach ($countries as $value)
                            <option value="{{ $value->id }}"
                                {{ old('country_id') == $value->id ? 'selected' : null }}>{{ $value->countryname }}
                            </option>
                        @endforeach
                    </select>
                    </div>
                    <div class="mb-6">
                    <select name="account_type" class="form-control" id="" required>
                        <option value="">Select Account Type</option>
                        <option value="individual" {{ old('account_type') == 'individual' ? 'selected' : null }}>
                            Individual</option>
                        <option value="company" {{ old('account_type') == 'company' ? 'selected' : null }}>Company
                        </option>
                    </select>
                    </div>
                    <div class="mb-6">
                    <label for="" class="col-form-label">Investor Identification Document (Max- 5MB)</label>
                    <input class="form-control" type="file" name="issuer_pros_doc" id="issuer_pros_doc"
                        placeholder="Issuer Identification Document" accept=".pdf" required>
                    <span id="doc_error" style="display:none;color:red;">File should be maximum 5 MB </span>
                    </div>
                    <div class="mb-6">
                    <label for="" class="col-form-label">Address Proof (Max- 5MB)</label>
                    <input class="form-control" type="file" name="issuer_kyc_doc" id="issuer_kyc_doc"
                        placeholder="KYC Document" accept=".pdf" required>
                    <span id="doc_error" style="display:none;color:red;">File should be maximum 5 MB </span>
                    </div>
                    <div class="mb-6">
                    <input type="hidden" value="1" name="user_type" id="user_type">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="register-content">
                                <div class="checkbox">
                                    <div class="form-check custom-control custom-checkbox">
                                        <input type="checkbox" class="form-check-input custom-control-input" id="list-1"
                                            name="list1" >
                                        <label class="form-check-label custom-control-label" for="list-1">By accessing the Dashboard you
                                            are agreeing to be bound by the terms of service, all applicable laws and
                                            regulations, and agree that you are responsible for compliance with any
                                            applicable local laws. </label>
                                        <span class="errors" id="list1_error"></span>
                                    </div>
                                    <div class="form-check custom-control custom-checkbox">
                                        <input type="checkbox" class="form-check-input custom-control-input" id="list-2"
                                            name="list2" >
                                        <label class="form-check-label custom-control-label" for="list-2">In no event shall the company
                                            or its suppliers be liable for any damages (including, without limitation,
                                            damages for loss of data or profit, or due to business interruption) arising out
                                            of the use or inability to use the materials.</label>
                                        <span class="errors" id="list2_error"></span>
                                    </div>
                                    <div class="form-check custom-control custom-checkbox">
                                        <input type="checkbox" class="form-check-input custom-control-input" id="list-3"
                                            name="list3" >
                                        <label class="form-check-label custom-control-label" for="list-3">I understand the risks of being
                                            part of the investment opportunity and agree to comply with the securities laws
                                            enforced by countries enforcement agencies.</label>
                                        <span class="errors" id="list3_error"></span>
                                    </div>
                                    <div class="form-check custom-control custom-checkbox">
                                        <input type="checkbox" class="form-check-input custom-control-input" id="list-4"
                                            name="list4" >
                                        <label class="form-check-label custom-control-label" for="list-4">You may be required to pay
                                            mining or gas fees for transfers of security tokens on the EVM Network. I
                                            agree that I am olely responsible for all mining fees as well as all other costs
                                            and expenses incurred with respect to obtaining or using security tokens on the
                                            EVM Network.</label>
                                        <span class="errors" id="list4_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="form-button mb-3">
                        <button id="submit" type="submit" class="ibtn btn btn-primary registerButton">Register</button>
                    </div>
                    <p>Already have an account? <a href="{{ url('/login') }}"> Log-in</a></p>
                </form>
                <!-- Button trigger modal -->
                <?php /*
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-bs-toggle="modal" data-target="#exampleModalCenter" data-bs-target="#exampleModalCenter">
                            I'am a Borrower
                            </button>


                              <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-bs-toggle="modal" data-target="#investor" data-bs-target="#investor">
                            I'am a Investor
                            </button>


                                              <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-bs-toggle="modal" data-target="#broker" data-bs-target="#broker">
                            I'am a Broker
                            </button>
                            */
                ?>

                @include('layout.links')

            </div>
        </div>
    </div>





    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Borrower</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Registering as a borrower allows you to submit new loan applications,track their status,and
                    participate in each of your projects through our amazing set of tools.You can also persue our
                    past projects to get an idea on how it all works.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="investor" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Investor</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    By becoming an investor,you can view comprehensive information on all our oppurtunities,invest
                    fully online,track your portfolio performance via real-time dashboards,manage your earnings and
                    much more.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="broker" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Broker</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Registering as a borrower allows you to submit new loan applications,track their status,and
                    participate in each of your projects through our amazing set of tools.You can also persue our
                    past projects to get an idea on how it all works.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="{{ asset('/js/parsley.js') }}"></script>

@section('scripts')
    <script>
        $('input[type=file]').on('change', function(e) {
                // console.log(this.files[0].size)
                $(this).next('span').remove()
                if (this.files[0].size > 4194304) {
                    $(this).after("<span class='text-danger'>* Maximum file size 4MB! * </span>")
                    this.value = "";
                };
        })

        $('#email_address').on('keyup', function() {
            console.log('sdks')
            var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.value);
            if (!re) {
                $('#email_error').show();
            } else {
                $('#email_error').hide();
            }
        })
    </script>
    <!-- <script type="text/javascript">
        $('.regCheckBtn').click(function() {

            var country = $('#country_id').val();

            var list1 = $("#list-1").prop('checked');
            var list2 = $("#list-2").prop('checked');
            var list3 = $("#list-3").prop('checked');
            var list4 = $("#list-4").prop('checked');

            $(".errors").html('');

            if (country != "" && list1 == true && list2 == true && list3 == true && list4 == true) {
                $("#regFrom").removeClass('d-none').addClass("d-block");

                $("#regCheck").addClass('d-none').removeClass("d-block");
            } else {

                if (country == "") {
                    $("#country_id_error").html('This field is required');
                }
                if (list1 == false) {
                    $("#list1_error").html('This field is required');
                }
                if (list2 == false) {
                    $("#list2_error").html('This field is required');
                }
                if (list3 == false) {
                    $("#list3_error").html('This field is required');
                }
                if (list4 == false) {
                    $("#list4_error").html('This field is required');
                }

            }
        });
    </script> -->
@endsection
