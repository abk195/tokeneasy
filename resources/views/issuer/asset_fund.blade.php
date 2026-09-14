@extends('issuer.layout.base')
@section('content')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script> --}}
    <style>
        #landmark_remove_2,
        #landmark_remove_3,
        #landmark_remove_4,
        #landmark_remove_5,
        #landmark_remove_6,
        #landmark_remove_7,
        #landmark_remove_8 {
            margin-top: 38px !important;
        }

        .error {
            color: red;
        }

        .section-title, h5 {
            margin: 0;
            font-size: 24px;
            line-height: 1.6;
            color: #000000;
            margin-bottom: 16px;
        }
        .section-desc {
            margin: 0;
            font-size: 16px;
            line-height: 1.6;
            color: #000000;
            margin-bottom: 24px;
        }
        .col-form-label {
            padding-top: 0 !important;
        }
        textarea.form-control {
            height: 180px !important;
        }
        input::file-selector-button {
            font-weight: bold;
            color: #707f94;
            padding: 0.5em;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            background: #e2e8f0;
            font-weight: 500;
            cursor: pointer;
        }

        input[type="file"] {
            padding: 0 !important;
            border: none !important;
        }

        .form-control {
            border: 2px solid #8a99af;
            border-radius: 6px;
        }
        .form-control:focus {
            border: 2px solid #8a99af !important;
        }
        label {
            color: #323947 !important;
        }

        .tooltip-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .custom-tooltip {
            position: absolute;
            bottom: 100%;
            left: var(--mouse-x, 50%);
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
            z-index: 1000;
            margin-bottom: 5px;
            pointer-events: none;
        }

        .custom-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: var(--arrow-x, 50%);
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #333;
        }

        .tooltip-wrapper:hover .custom-tooltip {
            opacity: 1;
            visibility: visible;
        }
    </style>

    <div class="content-page-inner">

        <!-- Header Banner Start -->
        <div class="header-breadcrumbs">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1>@lang('admin.tokencreate')</h1>
                    </div>
                    <div class="col-sm-6">
                        @include('issuer.layout.breadcrumb',['items' => [
                            [
                                'url' => 'issuer/dashboard',
                                'title' => 'Dashboard'
                            ],
                            [
                                'title' => 'Asset Fund'
                            ],
                        ]])
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Banner Start -->


        <div class="content">
            <!-- Start container-fluid -->
            <div class="container-fluid">
                <!-- start  -->
                <h5>@lang('admin.fund.create_fund')</h5>
                @if(!$isKeystoreAvailable)
                    <div class="alert alert-danger" role="alert">
                        <strong>No keystore file is set in the platform.</strong><br>
                        Keystores are the encrypted blockchain private key that you set in the platform.<br>
                        Please click the <strong>“Manage Keystore”</strong> option on the left-side menu
                        or <a href="{{ route('keystore') }}" class="alert-link text-decoration-underline">click here</a> to manage your keystore.
                    </div>
                 @endif
                <form class="form-horizontal" id="property-create" action="{{ route('propertyStore') }}" method="POST"
                    enctype="multipart/form-data" role="form">
                    @csrf
                    <input type="hidden" name="token_type" value="2">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="propertyName">Enter RWA Asset Name<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="propertyName" required=""
                                    value="{{ old('propertyName') }}" id="propertyName"
                                    placeholder="Enter RWA Asset Name" maxlength="320">
                                <div class="custom-tooltip">The official name of the real-world asset being tokenized.</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="propertyLogo">@lang('admin.fund.fund_logo')<span class="text-danger">*</span>&nbsp;<i
                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                        title="Only allows png/jpeg/jpg format">&#xf05a;</i></label>
                                <input class="form-control" type="file" name="propertyLogo"
                                    value="{{ old('propertyLogo') }}" accept="image/png,image/jpeg,image/jpg" required
                                    id="propertyLogo" placeholder="@lang('admin.fund.fund_logo')">

                                <div class="custom-tooltip">Upload an image representing the asset for display in the marketplace.</div>
                            </div>
                        </div>

                        <div class="form-group col-md-4 mb-4">
                            <div class="form-group">
                                <label for="property_state">Project Status<span class="text-danger">*</span></label>
                                <input class="form-control" name="property_state" id="property_state" value="live"
                                    required readonly />
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="show_property">Show RWA Token Details<span class="text-danger">*</span></label>
                                <select class="form-control" name="show_property" id="show_property" required>
                                    <option value="">Select</option>
                                    <option value="yes">yes</option>
                                    <option value="no">no</option>
                                </select>
                                <div class="custom-tooltip">Display the token details (name, symbol, decimals, etc.) associated with the asset.</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="" class="col-form-label">@lang('admin.coin.tokenvalue')
                                    ({{ Setting::get('default_currency') }}) <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" name="token_value" required
                                    value="{{ old('token_value') }}" id="tokenValue" placeholder="Eg: 1"
                                    step="any" min="0">
                                <div class="custom-tooltip">The price of a single token in USD.</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="totalDealSize">@lang('admin.totaldealsize')<span class="text-danger">*</span></label>
                                <input class="form-control" type="number" name="totalDealSize" required
                                    id="totalDealSize" value="{{ old('totalDealSize') }}"
                                    placeholder="@lang('admin.placeholders.total_deal_size')" min="1" step="any">
                                    {{-- <div class="d-flex align-items-center">
                                        <p style="font-size: 16px; font-weight: 700; color: #000000">Token Supply <span class="text-danger">*</span> <small>is equal to total Sq meter</small></p style="font-size: 16px; font-weight: 500; color: #000000">
                                        <h1 style="font-size: 24px; font-weight: 700; color: #000000">{{ old('totalDealSize') }}</h1>
                                    </div> --}}
                                    <div class="custom-tooltip">
                                        The full capital required for the asset offering.
                                    </div>
                            </div>

                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="expectedIrr">Expected Annual Return (%)<span class="text-danger"></span></label>
                                <input class="form-control" type="number" name="expectedIrr"  id="expectedIrr"
                                    value="{{ old('expectedIrr') }}" placeholder="Enter Expected Annual Return"
                                    min="1" step="any">
                                <div class="custom-tooltip">The projected yearly return (%) investors can expect from this asset.</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="fundedMembers">@lang('admin.fund.fundedMembers')<span class="text-danger"> </span></label>
                                <input class="form-control" type="number" name="fundedMembers"
                                value="{{ old('fundedMembers', 0) }}" id="fundedMembers"
                                placeholder="Enter total Funded Members" min="0">
                                <div class="custom-tooltip">Total number of investors already committed or backing the asset.</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="initialInvestment">Minimum Investment ($)<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="number" name="initialInvestment" required
                                    value="{{ old('initialInvestment') }}" id="initialInvestment"
                                    placeholder="@lang('admin.enter') @lang('admin.fund.min_investment')" min="0.1" step="any">

                                <div class="custom-tooltip">The minimum dollar amount an investor must contribute to participate.</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="holdingPeriod">Minimum Holding Period (Years)<span
                                        class="text-danger">*</span></label>
                                <select class="form-control" name="holdingPeriod" required id="holdingPeriod">
                                    <option value="">Select Holding Period</option>
                                    @for($i=0; $i<=35; $i++)
                                    <option value="{{ $i }}" {{ $i == 0 ? 'selected' : '' }}>{{ $i }}</option>
                                        {{-- <option value="{{$i}}">{{$i}}</option> --}}
                                    @endfor
                                </select>
                                <div class="custom-tooltip">The minimum number of years investors are required to hold the token before selling.</div>
                            </div>


                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.coin.tokensupply') <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="number" name="token_supply"
                                    value="{{ old('token_supply') }}" min="1000" required id="tokenSupply"
                                    placeholder="1000 or above" readonly>
                            </div>
                        </div>
                    </div>
                    <h5>@lang('admin.inputs.overview_details')</h5>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="propertyOverview">@lang('admin.fund.detail_overview')<span class="text-danger">*</span></label>
                                <textarea class="form-control" type="text" name="propertyOverview" value="{{ old('propertyOverview') }}"
                                    required id="propertyOverview" placeholder="@lang('admin.enter') @lang('admin.fund.detail_overview')" maxlength="10000"></textarea>

                                <div class="custom-tooltip">A descriptive summary of the asset, including purpose, type, and strategy.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="propertyHighlights">@lang('admin.fund.detail_highlights')<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" type="text" name="propertyHighlights" value="{{ old('propertyHighlights') }}"
                                    required id="propertyHighlights" placeholder="@lang('admin.enter') @lang('admin.fund.detail_highlights')" maxlength="10000"></textarea>

                                <div class="custom-tooltip">A descriptive summary of the key selling points or unique features of the asset.</div>
                            </div>
                        </div>
                    </div>
                    <h5>@lang('admin.doc/reports')</h5>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.labels.investordos')<span
                                        class="text-danger"></span>&nbsp;<i style="font-size:14px;cursor: pointer;"
                                        class="fa" data-toggle="tooltip"
                                        title="Only allows pdf format">&#xf05a;</i></label>
                                <input class="form-control" type="file" name="investor" accept="application/pdf"
                                     id="propertyInvestor">
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.labels.titlereport')<span
                                        class="text-danger"></span>&nbsp;<i style="font-size:14px;cursor: pointer;"
                                        class="fa" data-toggle="tooltip"
                                        title="Only allows pdf format">&#xf05a;</i></label>
                                <input class="form-control" type="file" name="titlereport" accept="application/pdf"
                                     id="propertyTitlereport">
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="" class="col-form-label">Reports&nbsp;<i
                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                        title="Only allows pdf format">&#xf05a;</i></label>
                                <input class="form-control" type="file" name="termsheet" accept="application/pdf"
                                    id="termsheet">
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="" class="col-form-label">Share Certificate<span
                                class="text-danger"></span>&nbsp;<i
                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                        title="Only allows pdf format">&#xf05a;</i></label>
                                <input class="form-control" type="file" accept="application/pdf"
                                    name="propertyUpdatesDoc" id="propertyUpdatesDoc">
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="" class="col-form-label">Brochure <span
                                        class="text-danger"></span>&nbsp;<i style="font-size:14px;cursor: pointer;"
                                        class="fa" data-toggle="tooltip"
                                        title="Only allows pdf format">&#xf05a;</i></label>
                                <input class="form-control" type="file" name="brochure" accept="application/pdf"
                                     id="brochure">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-4 d-none">
                            <div class="form-group">
                                <label for="" class="col-form-label">Management Team</label>
                                <input class="form-control" type="file" accept="application/pdf"
                                    name="propertyManagementTeam" id="propertyManagementTeam">

                                    <textarea class="form-control" type="text" name="ManagementTeamDescription" id="propertyOverview"
                                    placeholder="Enter Management Team Description" maxlength="10000"></textarea>


                            </div>
                        </div>
                    </div>
                    <h5>Token Details</h5>
                    <div class="row">

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="tokenChain" class="col-form-label">Select Chain<span class="text-danger">*</span></label>
                                <select class="form-control allowAlphaSpace" name="token_chain" id="tokenChain" required>
                                    <option value="">Select Chain</option>
                                    @foreach($blockchains as $blockchain)
                                        <option value="{{ $blockchain->id }}">{{ $blockchain->blockchain_name }}</option>
                                    @endforeach
                                </select>
                                <span id="tokenChainError" class="text-danger" style="display: none;">* Please select a Token Chain</span>
                                <div class="custom-tooltip">Choose the blockchain network on which the token will be deployed.</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="" class="col-form-label">@lang('admin.coin.tokenname')<span
                                        class="text-danger">*</span></label>
                                <input class="form-control allowAlphaSpace" type="text" name="token_name"
                                    value="{{ old('token_name') }}" required id="tokenName"
                                    placeholder="@lang('admin.coin.tokenname')"
                                    data-parsley-required-message="Please enter Token Name" maxlength="100">

                                <div class="custom-tooltip">The full name of the token (e.g., “Maple Grove Equity Token”).</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="" class="col-form-label">@lang('admin.coin.tokensymbol')<span
                                        class="text-danger">*</span><i style="font-size:14px;cursor: pointer;"
                                        class="fa" data-toggle="tooltip"
                                        title="Only 4 characters are allowed ">&#xf05a;</i></label>
                                <input class="form-control" type="text" name="token_symbol"
                                    value="{{ old('token_symbol') }}" required id="tokenSymbol" placeholder="Eg: ABCD"
                                    maxlength="4"  minlength="1">

                                <div class="custom-tooltip">The abbreviated ticker symbol (e.g., MGT).</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="" class="col-form-label">@lang('admin.coin.tokendecimal') <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" name="token_decimal" required id="tokenDecimal">
                                    <option value="">Select Decimal</option>
                                    @for($i=1; $i<=18; $i++)
                                        <option value="{{ $i }}" {{ $i == 18 ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>

                                <div class="custom-tooltip">The number of decimal places the token supports.</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.token_to_be_issued') <span
                                        class="text-danger">*</span></label>
                                <br>
                                <input type="radio" name="tokentype" class="tokentype" value="ERC20" checked>
                                @lang('admin.erc20_utility')<br>
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="" class="col-form-label">@lang('admin.coin.tokenimage')<span
                                        class="text-danger">*</span> &nbsp;<i style="font-size:14px;cursor: pointer;"
                                        class="fa" data-toggle="tooltip"
                                        title="Only allows png/jpeg format">&#xf05a;</i></label>
                                <input class="form-control" type="file" name="token_image"
                                    accept="image/png,image/jpeg, image/jpg" required id="tokenImage">

                                <div class="custom-tooltip">An icon or image representing the token.</div>
                            </div>
                        </div>

                        {{-- <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper">
                                <label for="" class="col-form-label">@lang('admin.coin.tokensupply') <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="number" name="token_supply"
                                    value="{{ old('token_supply') }}" min="1000" required id="tokenSupply"
                                    placeholder="1000 or above" readonly>

                            <div class="custom-tooltip">The total fixed supply of tokens for the asset.</div>
                            </div>
                            <label style="color: blue">* Note: Only whole numbers allowed</label> --}}
                            {{-- <div class="d-flex align-items-center">
                                <p style="font-size: 16px; font-weight: 700; color: #000000">Token Supply <span class="text-danger">*</span> <small>is equal to total Sq meter</small></p style="font-size: 16px; font-weight: 500; color: #000000">
                                <h1 style="font-size: 24px; font-weight: 700; color: #000000">{{ old('token_supply') }}</h1>
                            </div> --}}

                        {{-- </div> --}}

                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="keystoreSelect" class="col-form-label">
                                    @lang('admin.keystore') <span class="text-danger">*</span>
                                </label>

                                <select class="form-control" name="keystore_id" id="keystoreSelect" required data-url="{{ url('api/balance')}}">
                                    <option value="">-- Select a Keystore --</option>
                                    @foreach($keystores as $id => $keystore)
                                        <option value="{{ $keystore->id }}" data-public-address="{{ $keystore->public_address }}">
                                            {{ $keystore->title }}
                                        </option>
                                    @endforeach

                                </select>
                                <div id="keystoreError" class="text-danger mt-2" style="display: none;">* Insufficient balance</div>
                            </div>

                        </div>




                    </div>
                    <!-- end row -->
                    {{-- The Wallet Custody section is withdrawn: internal custody is no longer
                         offered, so every asset is external-wallet only and enable_internal_wallet
                         is always submitted as 0. The original section is kept here for reference.
                    <h5 class="mb-3">Wallet Custody</h5>
                    <p>
                        You can enable internal wallet functionality for the issuer. If enabled, investors will have a choice to receive purchased tokens in an internal wallet or an external wallet.
                        The difference: if an investor chooses the internal wallet, tokens are not moved on the blockchain. Instead, they remain in the company’s blockchain wallet, while the platform's database reserves those tokens for the investor.
                        Internal wallets are helpful for investors who are not familiar with blockchain or crypto wallets.
                        If the investor opts for an external wallet, they must set up and manage their own crypto (blockchain) wallet.
                    </p>
                    <div class="row form-group">

                        <div class="col-md-4 mb-4">
                            <div class="form-group tooltip-wrapper position-relative">
                                <label class="col-form-label">
                                    Enable Internal Wallet <span class="text-danger">*</span>
                                </label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="enable_internal_wallet" id="enableWalletYes" class="form-check-input" value="1" checked>
                                    <label class="form-check-label" for="enableWalletYes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="enable_internal_wallet" id="enableWalletNo" class="form-check-input" value="0">
                                    <label class="form-check-label" for="enableWalletNo">No</label>
                                </div>


                            </div>
                        </div>
                    </div>
                    --}}
                    <input type="hidden" name="enable_internal_wallet" value="0">


                    <h5 class="mb-3">Management Team</h5>
                    <div class="row form-group">
                        <div class="col-md-12 mb-4">
                            <label for="" class="col-form-label mb-3">Management Team Description (Max 10000
                                Characters)</label>
                            <textarea class="form-control" type="text" name="ManagementTeamDescription" id="propertyOverview"
                                placeholder="Enter Management Team Description" maxlength="10000"></textarea>
                        </div>
                    </div>

                    <div class="row next-btn" style="padding:25px 0px;">
                        <div class="col-sm-12">
                            <div class="form-group text-left mb-0">
                                <input type="submit" class="btn btn-primary waves-effect waves-light mr-1"
                                    value="Create Token">
                                {{-- <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">Previous</button>
                                <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">Create Token</button> --}}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- end container-fluid -->
        @endsection
        <!-- Start Page Content here -->
        @section('scripts')

            <script src="{{ asset('main/assets/js/validate.min.js') }}"></script>
            <script src="{{ asset('main/assets/js/additional-method.js') }}"></script>
            <script type="text/javascript">
                $(document).ready(function() {

                    var n = 2;
                    $("#add_comparables").click(function(e) {
                        e.preventDefault();
                        var html = '<div id="comparables_block_' + n +
                            '" class="form-group row"><div class="form-group col-md-3"><label for="" class="col-form-label">Property Address</label><input class="form-control" type="text" name="comparables[' +
                            n + '][property]" required id="property_' + n +
                            '" placeholder="Property Address"></div><div class="form-group col-md-3"><label for="" class="col-form-label">Sale Date</label><input class="form-control" type="text" name="comparables[' +
                            n + '][type]" required id="type_' + n +
                            '" placeholder="Sale Date"></div><div class="form-group col-md-3"><label for="" class="col-form-label">Location</label><input class="form-control" type="text" name="comparables[' +
                            n + '][location]" required id="location_' + n +
                            '" placeholder="Location"></div><div class="form-group col-md-3"><label for="" class="col-form-label">Year of Build</label><input class="form-control" type="number" name="comparables[' +
                            n + '][distanaccept="image/png,image/jpeg"ce]" required id="distance_' + n +
                            '" placeholder="Year of Build" min="0" step="any"></div><div class="form-group col-md-3"><label for="" class="col-form-label">Total Sft</label><input class="form-control" type="number" name="comparables[' +
                            n + '][rent]" required id="rent_' + n +
                            '" placeholder="Total Sft" min="0" step="any"></div><div class="form-group col-md-3"><label for="" class="col-form-label">Sale Price</label><input class="form-control" type="number" name="comparables[' +
                            n + '][saleprice]" required id="saleprice_' + n +
                            '" placeholder="Sale Price" min="0" step="any"></div><div class="col-md-3"><div class="form-group"><label for="propertyLogo">Property Logo</label><input class="form-control" type="file" name="propertyVideo" accept="image/png,image/jpeg"  required id="propertyLogo" placeholder="Enter Property Logo"></div></div><div class="col-md-3"><div class="form-group"><label for="propertyLogo">Map</label><input class="form-control" type="file" name="map" accept=".pdf"  required id="propertyLogo" placeholder="Enter Property Logo"></div></div><div class="col-md-3"><div class="form-group"><label for="propertyLogo">Comparables Details</label><input class="form-control" type="file" name="comparabledetails" accept=".pdf"  required id="propertyLogo" placeholder="Enter Property Logo"></div></div><div class="form-group col-md-3"><button type="button" class="btn btn-danger landmark_remove" id="landmark_remove_' +
                            n + '" onclick="comparables_remove(' + n + ');" >X</button></div></div>';
                        $("#comparables_section").append(html);
                        n = n + 1;
                    });

                    $("#add_landmark").click(function(e) {
                        e.preventDefault();
                        var html = '<div id="landmark_block_' + n +
                            '" class="form-group row"><div class="form-group col-md-3"><label for="" class="col-form-label">Landmark Name</label><input class="form-control" type="text" name="landmarks[' +
                            n + '][landmarkName]" required id="landmarkName_' + n +
                            '" placeholder="Enter landmark name"></div><div class="form-group col-md-3"><label for="" class="col-form-label">Landmark Distance</label><input class="form-control" type="number" name="landmarks[' +
                            n + '][landmarkDist]" required id="landmarkDist_' + n +
                            '" placeholder="Enter landmark distance" min="0" step="any"></div><div class="form-group col-md-3"><button type="button" class="btn btn-danger landmark_remove" id="landmark_remove_' +
                            n + '" onclick="landmark_remove(' + n + ');" >X</button></div></div>';
                        $("#landmark_section").append(html);
                        n = n + 1;
                    });
                });

                function landmark_remove(n) {
                    $("#landmark_block_" + n).remove();
                }

                function comparables_remove(n) {
                    $("#comparables_block_" + n).remove();
                }

                $(document).ready(function() {
                    addManagementMember(0);
                    addUpdates(0);
                });

                $('#AddMember').click(function(e) {
                    var index = parseInt($('#divManagementMembers').attr('data-id'));
                    addManagementMember(index + 1);
                });

                function removeMember(index) {
                    $('#MemberBlock_' + index).remove();
                }

                function addManagementMember(index) {
                    var removeBtn =
                        '<button type="button" class="btn btn-danger" style="margin-top: 34px;" onclick="removeMember(' + index +
                        ');">X</button>';
                    var temp = '<div class="row" id="MemberBlock_' + index + '">' +
                        '	<div class="col-md-3 form-group">' +
                        '		<label for="" class="col-form-label">Member Name</label>' +
                        '		<input class="form-control" type="text" name="member[' + index + '][name]" id="MemberName_' + index +
                        '" placeholder="Enter Member Name">' +
                        '	</div>' +
                        '	<div class="col-md-3 form-group">' +
                        '		<label for="" class="col-form-label">Member Position</label>' +
                        '		<input class="form-control" type="text" name="member[' + index + '][position]"  id="MemberName_' +
                        index + '" placeholder="Enter Member Position">' +
                        '	</div>' +
                        '	<div class="col-md-3 form-group">' +
                        '		<label for="" class="col-form-label">Member Image&nbsp;<i style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip" title="Only allows png/jpeg format">&#xf05a;</i></label>' +
                        '		<input class="form-control" type="file" name="member[' + index + '][pic]" id="MemberPic_' + index +
                        '" accept="image/png,image/jpeg" placeholder="Select Member Picture">' +
                        '	</div>' +
                        '	<div class="col-md-3 form-group">' +
                        '		<label for="" class="col-form-label">Member Description' +
                        '		<input class="form-control" type="text" name="member[' + index +
                        '][description]" id="MemberDescription_' + index + '" placeholder="Enter Description">' +
                        '	</div>' +
                        '	<div class="col-md-1 form-group">' +
                        '       ' + ((index > 0) ? removeBtn : '') +
                        '   </div>' +
                        '</div>';
                    $('#divManagementMembers').attr('data-id', index).append(temp);
                }

                $("#Addupdates").on("click", function() {
                    var index = parseInt($('#updates').attr('data-id'));
                    addUpdates(index + 1);
                });

                function removeUpdates(index) {
                    $('#updates_' + index).remove();
                }

                function addUpdates(index) {
                    var removeBtn =
                        '<button type="button" class="btn btn-danger" style="margin-top: 34px;" onclick="removeUpdates(' + index +
                        ');">X</button>';
                    var temp = '<div class="row" id="updates_' + index + '">' +
                        '<div class="col-md-3">' +
                        '<div class="form-group">' +
                        '<label for="" class="col-form-label">Update Date</label>' +
                        '<input class="form-control" type="date" name="updates[' + index +
                        '][date]" id="date" placeholder="Date" >' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-3">' +
                        '<div class="form-group">' +
                        '<label for="" class="col-form-label">Update Description</label>' +
                        '<input class="form-control" type="text" name="updates[' + index +
                        '][description]" id="date" placeholder="Please enter Description" >' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-1">' +
                        '' + ((index > 0) ? removeBtn : '') +
                        '<div>' +
                        '<div>';
                    $("#updates").attr('data-id', index).append(temp);
                }
            </script>
          <script>
            $('#tokenValue').on('keyup',function(){
              var totalDealSize=$('#totalDealSize').val();
                var tokenvalue=$('#tokenValue').val();
                if(totalDealSize==0||totalDealSize==''){
                    $('#tokenSupply').val(0);

                }else if(tokenvalue==0||tokenvalue==''){
                    $('#tokenSupply').val(0);

                }
                var supply=totalDealSize/tokenvalue;
                $('#tokenSupply').val(supply);

            });
            </script>
             <script>
                $('#totalDealSize').on('keyup',function(){
                  var totalDealSize=$('#totalDealSize').val();
                  console.log(totalDealSize);
                    var tokenvalue=$('#tokenValue').val();
                    console.log(tokenvalue);
                    if(totalDealSize==0||totalDealSize==''){
                        $('#tokenSupply').val(0);
                        // $('#totalDealSize').val(0);

                    }else if(tokenvalue==0||tokenvalue==''){
                        $('#tokenSupply').val(0);



                    }
                    var supply=Math.ceil(totalDealSize / tokenvalue);
                    $('#tokenSupply').val(supply);

                });
                </script>
            <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
            <script>
                // $(document).ready(function() {
                //     $("input[type=text], input[type=radio], input[type=file], textarea, select, input[type=number]").blur(
                //         function() {
                //             if ($(this).val() != "") {
                //                 Cookies.set('token_asset_' + $(this).attr('name'), $(this).val(), {
                //                     expires: 1
                //                 });
                //                 console.log($(this).val());
                //                 console.log($(this).attr('name'));
                //             }
                //         });

                //     $("input[type=text], textarea, select, input[type=radio],  input[type=number]").each(function() {

                //         var cookval = Cookies.get('token_asset_' + $(this).attr('name'));
                //         if ($(this).attr('type') == "radio") {
                //             var inpname = $(this).attr('name');
                //             $("input[name=" + inpname + "][value=" + cookval + "]").prop("checked", true);
                //         } else {
                //             $(this).val(cookval);
                //         }
                //     });
                // });
            </script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#property-create").validate({
                        rules: {
                            "propertyName": {
                                required: true,
                                maxlength: 320,
                            },
                            "propertyLocation": {
                                required: true,
                                maxlength: 320,
                            },
                            "propertyLogo": {
                                required: false,
                                accept: 'image/jpeg, image/png, image/jpg',
                            },
                            "propertyType": {
                                required: true,
                            },
                            "property_state": {
                                required: true
                            },
                            "totalDealSize": {
                                required: true,
                                number: true,
                                min: 1,
                                max: 100000000000,
                            },
                            "expectedIrr": {
                                // required: true,
                                // number: true,
                                // min: 1,
                                // max: 100,
                            },
                            "PropertyedMembers": {
                                required: true,
                                number: false,
                                min: 1,
                            },
                            "initialInvestment": {
                                required: true,
                                number: true,
                                min: 1,
                                max: 10000000000,
                            },
                            "holdingPeriod": {
                                required: true,
                                number: true,
                                min: 1,
                                max: 100
                            },
                            "total_sft": {
                                required: true,
                                number: true,
                                min: 0,
                                max: 100000000000,
                            },
                            "propertyOverview": {
                                required: true,
                                maxlength: 10000
                            },
                            "propertyLocationOverview": {
                                required: true,
                                maxlength: 10000
                            },
                            "comparables[1][property]": {
                                maxlength: 150
                            },

                            "comparables[1][location]": {
                                maxlength: 100
                            },

                            "comparables[1][rent]": {
                                max: 100000000000,
                            },
                            "comparables[1][saleprice]": {
                                max: 100000000000,
                            },

                            "propertyEquityMultiple": {
                                required: false,
                                number: true,
                                min: 0,
                            },

                            "propertyHighlights": {
                                required: true,
                            },
                            "show_property": {
                                required: true,
                            },
                            "locality": {
                                required: true,
                                maxlength: 600,
                            },
                            "yearOfConstruction": {
                                required: true,
                                number: true,
                                min: 0,
                                maxlength: 4,
                            },
                            "storeys": {
                                required: true,
                                max: 100000000000,
                            },
                            "propertyParking": {
                                required: true,
                                min: 1,
                                max: 100000000000,
                            },
                            "floorforSale": {
                                required: true,
                                number: true,
                                min: 0,
                                max: 100000000000,
                            },
                            "propertyTotalBuildingArea": {
                                required: false,
                                number: true,
                                min: 0,
                                max: 100000000000,
                            },
                            "propertyDetailsHighlights": {
                                required: false,
                                maxlength: 10000
                            },
                            "floorplan": {
                                required: false,
                                accept: 'pdf',
                            },
                            "airport": {
                                maxlength: 100
                            },
                            "hospitals": {
                                maxlength: 100
                            },
                            "fire_services": {
                                maxlength: 100,
                            },
                            "industrial": {
                                maxlength: 100,
                            },
                            "investor": {
                                required: false,
                                accept: 'pdf',
                            },
                            "titlereport": {
                                required: false,
                                accept: 'pdf',
                            },
                            "termsheet": {
                                required: false,
                                accept: 'pdf',
                            },
                            "propertyimages": {
                                required: true,
                                accept: 'image/jpeg, image/png, image/jpg, image',
                            },
                            "management": {
                                required: true,
                            },
                            "updates": {
                                required: true,
                            },
                            "propertylogoimage": {
                                required: true,
                                accept: 'image/jpeg, image/png, image/jpg, image',
                            },
                            "propertyVideo": {
                                required: true,
                                accept: 'video/mp4,video/x-m4v',
                            },
                            "propertyManagementTeam": {
                                required: false,
                                accept: 'pdf',
                            },
                            "propertyUpdatesDoc": {
                                required: false,
                                accept: 'pdf',
                            },
                            "brochure": {
                                required: false,
                                accept: 'pdf',
                            },
                            "map": {
                                required: false,
                                accept: 'pdf',
                            },
                            "comparabledetails": {
                                required: false,
                                accept: 'pdf',
                            },
                            "token_name": {
                                required: true,
                                maxlength: 100
                            },
                            "token_symbol": {
                                required: true,
                                minlength: 2,
                                maxlength: 4,
                            },
                            "token_value": {
                                required: true,
                                number: true,
                                max: 100000000,
                            },
                            "token_supply": {
                                required: true,
                                number: true,
                                min: 1000,
                                max: 100000000000,
                            },
                            "token_decimal": {
                                required: true,
                                // digits: true,
                                min:1,
                                max:18,
                                maxlength: 2,
                            },
                            "ManagementTeamDescription": {
                                maxlength: 10000
                            },
                            "member[0][name]": {
                                maxlength: 50
                            },
                            "member[0][position]": {
                                maxlength: 50
                            },
                            "member[0][description]": {
                                required:false,
                                maxlength: 50
                            }
                        },
                        messages: {
                            "propertyName": {
                                required: "Please Enter Asset Name",
                            },
                            "propertyLocation": {
                                required: "Please Enter Property Location",
                            },
                            "propertyLogo": {
                                required: "Please Choose Asset Logo",
                                accept: "Please Upload Valid format (jpeg|png|jpg|bmp)",
                            },
                            "propertyVideo": {
                                required: "Please Choose Asset Logo",
                                accept: "Please Upload Valid format (mp4|x-m4v)",
                            },
                            "management": {
                                required: "Please Upload Management",
                            },
                            "updates": {
                                required: "Please Upload Updates",
                            },
                            "propertylogoimage": {
                                required: "Please Choose Asset Logo",
                                accept: "Please Upload Valid format (jpeg|png|jpg|bmp)",
                            },
                            "propertyimages": {
                                required: "Please Choose Property Logo",
                                accept: "Please Upload Valid format (jpeg|png|jpg|bmp)",
                            },
                            "totalDealSize": {
                                required: "Please Enter Total Deal Size",
                            },
                            "expectedIrr": {
                                // required: "Please Enter Expected Annual Return",
                            },
                            "fundedMembers": {
                               // required: "Please Enter Funded Members",
                            },
                            "initialInvestment": {
                                required: "Please Enter Minimum Investment",
                            },
                            "propertyEquityMultiple": {
                                required: "Please Enter Property Equity Multiple",
                            },
                            "holdingPeriod": {
                                required: "Please Enter Holding Period",
                            },
                            "total_sft": {
                                required: "Please Enter Total SFT",
                            },
                            "propertyOverview": {
                                required: "Please Enter Asset Overview",
                            },
                            "propertyHighlights": {
                                required: "Please Enter Asset Highlights",
                            },
                            "propertyLocationOverview": {
                                required: "Please Enter Location Overview",
                            },
                            "locality": {
                                required: "Please Enter Locality",
                            },
                            "yearOfConstruction": {
                                required: "Please Enter Year of Construction",
                            },
                            "propertyParking": {
                                required: "Please Enter Parking",
                            },
                            "floorforSale": {
                                required: "Please Enter Floor for Sale",
                            },
                            "propertyTotalBuildingArea": {
                                required: "Please Enter Total Building Area",
                            },
                            "propertyDetailsHighlights": {
                                required: "Please Enter Details Highlights",
                            },
                            "floorplan": {
                                required: "Please Choose Floor Plan",
                                accept: "Please Upload Valid format (pdf)",
                            },
                            "investor": {
                                required: "Please Choose Prospectus/OM",
                                accept: "Please Upload Valid format (pdf)",
                            },
                            "titlereport": {
                                required: "Please Choose Report",
                                accept: "Please Upload Valid format (pdf)",
                            },
                            "termsheet": {
                                required: "Please Choose Reports",
                                accept: "Please Upload Valid format (pdf)",
                            },
                            "comparabledetails": {
                                required: "Please Choose Term Sheet",
                                accept: "Please Upload Valid format (pdf)",
                            },
                            "propertyManagementTeam": {
                                required: "Please Choose Management Team Report",
                                accept: "Please Upload Valid format (pdf)",
                            },
                            "brochure": {
                                required: "Please Choose Brochure Document",
                                accept: "Please Upload Valid format (pdf)",
                            },
                            "propertyUpdatesDoc": {
                                required: "Please Choose share certificate",
                                accept: "Please Upload Valid format (pdf)",
                            },
                            "map": {
                                required: "Please Choose Term Sheet",
                                accept: "Please Upload Valid format (pdf)",
                            },
                            "token_value": {
                                required: "Please Enter Valid Token Value",
                            },
                            "token_image": {
                                required: "Please Choose Token Image",
                            },
                            "token_decimal": {
                                required: "Please Enter Valid Token Decimal",
                            },
                            "token_supply": {
                                required: "Please Enter Valid Token Supply",
                            },
                            "token_name": {
                                required: "Please Enter Valid Token Name",
                            },
                            "token_symbol": {
                                required: "Please Enter Valid Token Symbol",
                            },
                        },
                        submitHandler: function(form) {
                            form.submit();
                        }
                    });
                });
            </script>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const keystoreSelect = document.getElementById("keystoreSelect");
                    const tokenChainSelect = document.getElementById("tokenChain");
                    const tokenChainError = document.getElementById("tokenChainError");
                    const keystoreError = document.getElementById("keystoreError");

                    if (!keystoreSelect || !tokenChainSelect) return;

                    function checkBalance() {
                        // Hide previous errors
                        tokenChainError.style.display = "none";
                        keystoreError.style.display = "none";

                        if (!tokenChainSelect.value) {
                            tokenChainError.style.display = "inline";
                            return; // Stop if no chain selected
                        }

                        const selectedOption = keystoreSelect.options[keystoreSelect.selectedIndex];
                        const publicAddress = selectedOption.getAttribute("data-public-address");
                        const apiBaseUrl = keystoreSelect.getAttribute("data-url");

                        if (!publicAddress) {
                            // No keystore selected or no data attribute, don't call API
                            return;
                        }

                        fetch(`${apiBaseUrl}/${encodeURIComponent(publicAddress)}?chain=${tokenChainSelect.value}`)
                            .then(response => response.json())
                            .then(data => {
                                if (!data.status || data.balance <= 0) {
                                    keystoreError.textContent = "Insufficient Balance, Select another file";
                                    keystoreError.style.display = "block";

                                    // Unselect keystore (reset to first placeholder option)
                                    keystoreSelect.selectedIndex = 0;
                                }
                            })
                            .catch(error => {
                                console.error("Error fetching balance:", error);
                                keystoreError.textContent = "Error checking balance.";
                                keystoreError.style.display = "block";
                            });
                    }

                    keystoreSelect.addEventListener("change", function () {
                        checkBalance();
                    });

                    tokenChainSelect.addEventListener("change", function () {
                        if (tokenChainSelect.value !== '') {
                            tokenChainError.style.display = "none";
                        }
                        checkBalance();
                    });
                });
            </script>
        @endsection
