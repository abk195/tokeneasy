@extends('layout.app')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-content">
        <div class="pro-breadcrumbs">
            <div class="container">
                <a href="{{ url('/dashboard') }}" class="pro-breadcrumbs-item">Home</a>
                <span>/</span>
                <a href="#" class="pro-breadcrumbs-item">Invest</a>
            </div>
        </div>
        <!-- End Breadcrumb -->
        <!-- Property Head Starts -->
        <div class="property-head grey-bg pt30">
            <div class="container">
                <div class="property-head-btm row">
                    <div class="col-md-12">
                        <h2 class="pro-head-tit">Invest</h2>
                        <p class="pro-head-txt">{{ @$property->propertyName }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Property Head Ends -->

        <!-- Property Tab Starts -->
        <div class="property-tab">
            <!-- Tab panes -->
            <div class="pro-content-tab-wrap p40">

                <div class="container">
                    <div class="tab-content">
                        {{-- @include('common.notify') --}}
                        <!-- Identity Tab Starts -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="wizard wizard1">
                                    <form role="form" action="{{ route('investstore') }}" method="POST"
                                        enctype="multipart/form-data" id="identity-form">
                                        @csrf()
                                        <div class="tab-content">
                                            <div class="tab-pane active" role="tabpanel" id="step1">
                                                <input type="hidden" id="token_id" name="token_id"
                                                    value="{{ @$property->userContract->id }}">
                                                <input type="hidden" id="total_token_value" name="total_token_value">
                                                <div class="row">
                                                    <div class="col-xs-12 form-group">
                                                        <a href="{{ @img($property->investor) }}" target="_blank"
                                                            rel="noopener noreferrer">View Offering Memorandum</a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6">
                                                        <input type="hidden" name="stripe_token" id="stripeTokenVal">
                                                        <div class="form-group">
                                                            <label class="control-label">Token name</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Enter Token Name" name="tokenname"
                                                                value="{{ @$property->userContract->tokenname }}"
                                                                readonly />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Token value</label>
                                                            <input type="number" class="form-control"
                                                                placeholder="Enter Token Value" name="tokenvalue"
                                                                value="{{ @$property->userContract->tokenvalue }}"
                                                                step="any" readonly />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Token Symbol</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Enter Token Symbol" name="tokensymbol"
                                                                value="{{ @$property->userContract->tokensymbol }}"
                                                                readonly />
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <label for="" class="col-form-label">Token Image</label>
                                                            <img src="{{ img(@$property->userContract->token_image) }}"
                                                                width="200px" class="thumbnail" />
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label">Pay By</label>
                                                            <select name="payby" class="form-control payby"
                                                                id="paymentType" onchange="changePayment(this.value)">
                                                                <option value="ETH">ETH</option>
                                                                <option value="stripe">Stripe</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group" style="display: none">
                                                            <label class="control-label">Token Dividend</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Token Dividend" name="tokendivident"
                                                                value="{{ @$property->dividend }}" readonly />
                                                        </div>

                                                    </div>
                                                    <div class="col-xs-12 col-sm-6">

                                                        <div class="form-group">
                                                            <label class="control-label">No of Token</label>
                                                            <input type="number" class="form-control no-of-token"
                                                                placeholder="No of Token" name="nooftoken" value=""
                                                                min="10" required />
                                                        </div>
                                                        <div class="form-group" style="display: none">
                                                            <label class="control-label">Bonus Token Dividend</label>
                                                            <input type="number" readonly class="form-control"
                                                                id="bonus_token" placeholder="Bonus Token Dividend"
                                                                name="bonustokendivident" value="0" />
                                                        </div>
                                                        <div class="form-group " style="display: none">
                                                            <label class="control-label">Total Token</label>
                                                            <input type="text" class="form-control" name="totaltoken"
                                                                id="total_token" placeholder="Total Token" value="0"
                                                                readonly />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Pay Value</label>
                                                            <input type="text" class="form-control" name="payvalue"
                                                                placeholder="Pay Value" id="amount" value=""
                                                                required readonly />
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-xs-6 text-muted">
                                                        Fee: Please add
                                                        {{ setting('admin_commission', 1.5) }}% to your token
                                                        purchase to cover administrative costs. Failure to do so will render
                                                        your token purchase null and void until the administrative fee is
                                                        paid.
                                                    </div>
                                                    <div class="col-xs-6 text-muted">By clicking the 'Buy Token' button I
                                                        confirm that I have thoroughly read and I am of sound mind to
                                                        comprehend the terms and conditions of this token purchase
                                                        agreement.
                                                    </div> -->
                                                    <div class="col-xs-12">
                                                        <ul class="list-inline pull-right">
                                                            <li>
                                                                <button type="button" onclick="checkPayment()"
                                                                    class=" btn1 btn2 next-step">Buy Token</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="clearfix"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Identity Tab Ends -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Property Tab Ends -->




    <!-- Modal -->
    <div class="modal fade" id="stripePaymentModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="stripePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stripePaymentModalLabel">Fiat Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default credit-card-box">
                                <div class="panel-heading display-table">
                                    <div class="row display-tr">
                                        <h3 class="panel-title display-td text-center">Payment Details</h3>
                                        <div class="display-td">
                                            {{-- <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png"> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">

                                    @if (Session::has('success'))
                                        <div class="alert alert-success text-center">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            <p>{{ Session::get('success') }}</p>
                                        </div>
                                    @endif

                                    <form role="form" action="{{ route('stripe.post') }}" method="post"
                                        class="require-validation" data-cc-on-file="false"
                                        data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
                                        @csrf

                                        <div class='form-row row'>
                                            <div class='col-xs-12 form-group required'>
                                                <label class='control-label'>Name on Card</label> <input
                                                    class='form-control' size='4' type='text' maxlength="20"
                                                    required>
                                            </div>
                                        </div>

                                        <div class='form-row row'>
                                            <div class='col-xs-12 form-group card required' style="border: 0">
                                                <label class='control-label'>Card Number</label>
                                                <input autocomplete='off' class='form-control card-number' type="number"
                                                    maxlength="16" minlength="16" type='text' required>
                                            </div>
                                        </div>

                                        <div class='form-row row'>
                                            <div class='col-xs-12 col-md-4 form-group cvc required'>
                                                <label class='control-label'>CVC</label> <input autocomplete='off'
                                                    class='form-control card-cvc' placeholder='ex. 311' size='4'
                                                    maxlength="3" minlength="3" type='text' required>
                                            </div>
                                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                                <label class='control-label'>Expiration Month</label> <input
                                                    type="number" class='form-control card-expiry-month'
                                                    placeholder='MM' size='2' maxlength="2" minlength="2"
                                                    type='text'required>
                                            </div>
                                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                                <label class='control-label'>Expiration Year</label> <input type="number"
                                                    class='form-control card-expiry-year' placeholder='YYYY'
                                                    size='4' minlength="4" maxlength="4" type='text'
                                                    required>
                                            </div>
                                        </div>

                                        <div class='form-row row'>
                                            <div class='col-md-12 error form-group hide'>
                                                <div class='alert-danger alert'>Please correct the errors and try
                                                    again.</div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <button class="btn btn-primary btn-lg btn-block stripeSubmit"
                                                    type="submit"></button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Understood</button> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript">
        $(function() {
            var $form = $(".require-validation");
            $('form.require-validation').bind('submit', function(e) {
                var $form = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'
                    ].join(', '),
                    $inputs = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid = true;
                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('hide');
                        e.preventDefault();
                    }
                });

                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }

            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $('#stripeTokenVal').val(token);
                    // $form.get(0).submit();
                    $('#identity-form').submit();
                }
            }

        });
    </script>
    <script type="text/javascript">
        var totalSupply = "{{ $contract->tokenbalance }}";
        var no_of_token = $('.no-of-token').val();
        var min_inves = parseInt("{{ $property->initialInvestment }}");

        function checkPayment() {
            var no_of_token = $('.no-of-token').val();
            if (parseInt(no_of_token) > parseInt(totalSupply)) {
                alert("No. of Token must be less than are equal to " + totalSupply)
                return;
            }
            if (!parseInt(no_of_token) || parseInt(no_of_token) < min_inves) {
                alert("Number of tokens should be greater than or equal " + min_inves)
                return false;
            }
            let val = $('#paymentType').val();
            if (val == 'stripe') {
                $('#stripePaymentModal').modal('show');
            } else {
                $('#identity-form').submit();
            }
        }

        function changePayment(val) {
            let no_of_token = $('.no-of-token').val();
            // console.log('no_of_token', no_of_token)
            if (parseInt(no_of_token) > parseInt(totalSupply)) {
                // alert("No. of Token must be less than are equal to " + totalSupply)
                return;
            }
            if (!no_of_token || no_of_token < min_inves) {
                // console.log("if")
                alert('Number of tokens should be greater than or equal to ' + min_inves)
                return;
            }
            if (val == 'stripe') {
                $('#stripePaymentModal').modal('show');
            }
        }

        $(document).on("ready", function() {
            $(".payby, .no-of-token").on("change", function() {
                var token_id = $('#token_id').val();
                var no_of_token = $('.no-of-token').val();
                var payment_id = $('.payby').val();

                if (parseInt(no_of_token) > parseInt(totalSupply)) {
                    alert("No. of Token must be lesser than or equal to " + totalSupply)
                    return;
                }
                $.ajax({
                    url: "{{ url('/gettokeninvestvalue') }}",
                    type: "POST",
                    data: {
                        'token_id': token_id,
                        'no_of_token': no_of_token,
                        'payment_id': payment_id,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        // console.log('result', result)
                        if (result.status == 1) {
                            $('#amount').val(result.token_equ_value);
                            $('#bonus_token').val(result.bonus_token);
                            $('#total_token').val(result.total_token);
                            $('.stripeSubmit').text('Pay $' + result.token_equ_value);
                            $('#total_token_value').val(result.token_equ_value)
                            // console.log(result.token_equ_value)
                        } else {
                            $('#amount').val(0);
                        }
                    }
                });
            });
        });
    </script>
@endsection
