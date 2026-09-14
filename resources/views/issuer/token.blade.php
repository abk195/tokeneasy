@extends('issuer.layout.base')
@section('content')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
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

        /* Multi-step form styles */
        .form-step {
            display: none !important;
        }

        .form-step.active {
            display: block !important;
        }

        /* Ensure no other CSS interferes */
        .form-step:not(.active) {
            display: none !important;
            visibility: hidden !important;
        }

        .form-step.active {
            display: block !important;
            visibility: visible !important;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding: 20px 0;
        }

        /* Error styling for validation */
        .form-control.error {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        /* Ensure proper spacing */
        .form-step {
            margin-bottom: 20px;
        }

        /* Add visual feedback for active step */
        .form-step.active {
            border: 2px solid #3b82f6;
            padding: 20px;
            border-radius: 8px;
            background-color: #f8fafc;
        }

        /* Modal styles for token creation */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
            background-color: #f8f9fa;
            border-radius: 12px 12px 0 0;
        }

        .modal-title {
            font-weight: 600;
            color: #333;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
        }

        .progress-bar {
            background-color: #007bff;
            border-radius: 4px;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        #contractAddressLink {
            word-break: break-all;
            text-decoration: none;
        }

        #contractAddressLink:hover {
            text-decoration: underline;
        }

        .fa-check-circle {
            color: #28a745;
        }

        /* Prevent modal closing styles */
        #tokenCreationModal {
            pointer-events: auto !important;
        }

        #tokenCreationModal .modal-backdrop {
            pointer-events: none !important;
        }

        #tokenCreationModal .modal-content {
            pointer-events: auto !important;
        }

        /* Disable close button if it exists */
        #tokenCreationModal .close,
        #tokenCreationModal .btn-close {
            display: none !important;
            pointer-events: none !important;
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
                                'title' => 'user.dashboard'
                            ],
                            [
                                'title' => 'admin.tokencreate'
                            ]

                        ]])
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Banner Start -->


        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="content">
                        <!-- Start container-fluid -->
                        <div class="container-fluid p-0">
                            <!-- start  -->
                            <h5 class="section-title">@lang('admin.property.create_property')</h5>
                            <p class="section-desc">
                                Please enter all details of the property and then click Create Property. it will send request to admin for review and approval. Upon admin approval, property will be created and a security token will be deployed in blockchain
                            </p>
                            @if(!$isKeystoreAvailable)
                                <div class="alert alert-danger" role="alert">
                                    <strong>No keystore file is set in the platform.</strong><br>
                                    Keystores are the encrypted blockchain private key that you set in the platform.<br>
                                    Please click the <strong>"Manage Keystore"</strong> option on the left-side menu
                                    or <a href="{{ route('keystore') }}" class="alert-link text-decoration-underline">click here</a> to manage your keystore.
                                </div>
                            @endif

                            <form class="form-horizontal" id="property-create" action="{{ route('propertyStore') }}" method="POST"
                                enctype="multipart/form-data" role="form">
                                @csrf
                                <input type="hidden" name="token_type" value="1">

                                <!-- Step 1: Property Details -->
                                <div class="form-step active" data-step="1">
                                    <div class="row">
                                        <div class="col-sm-12 mb-4">
                                            <div class="form-group">
                                                <label for="propertyName">Property Name<span class="text-danger">*</span></label>
                                                <div class="tooltip-wrapper">
                                                    <input class="form-control" type="text" name="propertyName"
                                                        value="" required=""
                                                        data-parsley-required-message="Please enter Property Name"
                                                        id="propertyName"
                                                        placeholder="Enter Property Name"
                                                        maxlength="320">
                                                    <div class="custom-tooltip">
                                                        The official name or title of the property.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 mb-4">
                                            <div class="form-group ">
                                                <div class="tooltip-wrapper">
                                                    <label for="propertyType">@lang('admin.property.type')<span class="text-danger">*</span></label>
                                                    <select class="form-control" name="propertyType" id="propertyType" required
                                                        data-parsley-required-message="Please choose @lang('admin.property.type')">
                                                        <option value="">Select</option>
                                                        @foreach ($assetType as $value)
                                                            <option value="{{ @$value->type }}">{{ @$value->type }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="custom-tooltip">
                                                        The category of the property (e.g., Residential, Commercial, Industrial).
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-4">
                                            <div class="form-group ">
                                                <div class="tooltip-wrapper">
                                                    <label for="propertyLogo">@lang('admin.property.propertylogo')@if(!$isDemo)<span class="text-danger">*</span>@endif <i
                                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                                        title="Only allows png/jpeg/jpg format">&#xf05a;</i></label>
                                                    <input class="form-control" type="file" name="propertyLogo"
                                                        accept="image/png,image/jpeg,image/jpg" @if(!$isDemo) required @endif
                                                        data-parsley-required-message="Please choose @lang('admin.property.propertylogo')" id="propertyLogo"
                                                        placeholder="@lang('admin.placeholders.property_logo')">
                                                    <div class="custom-tooltip">Upload images showcasing the property.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <div class="form-group tooltip-wrapper">
                                                <label for="property_state">Project Status<span class="text-danger">*</span></label>
                                                <input type="text" value="live" class="form-control" name="property_state"
                                                    id="property_state" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <div class="form-group tooltip-wrapper">
                                                <div class="tooltip-wrapper">
                                                    <label for="propertyLocation">@lang('admin.property.propertylocation')<span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text" name="propertyLocation"
                                                        value="{{ old('propertyLocation') }}" required=""
                                                        data-parsley-required-message="Please enter @lang('admin.property.propertylocation')" id="propertyLocation"
                                                        placeholder="@lang('admin.enter') @lang('admin.property.propertylocation')" maxlength="320">
                                                        <div class="custom-tooltip">The full address or general area of the property.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group tooltip-wrapper">
                                                <label for="expectedIrr">Expected Annual Return (%)<span class="text-danger"></span></label>
                                                <input class="form-control" type="number" name="expectedIrr" id="expectedIrr"
                                                    value="{{ old('expectedIrr') }}" placeholder="Enter Expected Annual Return"
                                                    data-parsley-type="digits"
                                                    data-parsley-required-message="Please Enter Expected Annual Return" min="1"
                                                    step="any">
                                                <div class="custom-tooltip">Projected yearly return from the property, expressed as a percentage.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group tooltip-wrapper">
                                                <label for="holdingPeriod">Minimum Holding Period<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" name="holdingPeriod" id="holdingPeriod" required
                                                    data-parsley-required-message="Enter Minimum Holding Period">
                                                    <option value="0" {{ old('holdingPeriod', '0') == '0' ? 'selected' : '' }}> 0</option>
                                                    <option value="< 1" {{ old('holdingPeriod') == '< 1' ? 'selected' : '' }}>&lt; 1 year</option>
                                                    <option value="2" {{ old('holdingPeriod') == '2' ? 'selected' : '' }}>2 years</option>
                                                    <option value="5" {{ old('holdingPeriod') == '5' ? 'selected' : '' }}>5 years</option>
                                                    <option value="> 5" {{ old('holdingPeriod') == '> 5' ? 'selected' : '' }}>&gt; 5 years</option>
                                                </select>
                                                <div class="custom-tooltip">Minimum time investors must hold the token before selling.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group tooltip-wrapper">
                                                <label for="initialInvestment">@lang('admin.inputs.min_investment') ($)<span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" type="number" name="initialInvestment"
                                                    value="{{ old('initialInvestment') }}" required data-parsley-type="digits"
                                                    data-parsley-required-message="Please enter @lang('admin.inputs.min_investment')" id="initialInvestment"
                                                    placeholder="@lang('admin.placeholders.min_investment')" min="0.1" step="any"
                                                    onkeyup="calculateTokenValue()">
                                                    <div class="custom-tooltip">The smallest investment allowed for this offering.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group tooltip-wrapper">
                                                <label for="" class="col-form-label">Area Type</label>
                                            <select name="area_type" id="area_type" class="form-control">
                                                <option value="Sq ft">Square Feet</option>
                                                <option value="acres">Acres</option>
                                                <option value="hectares">Hectares</option>
                                                <option value="Sq meter">Square Meters</option>
                                                <option value="Sq yards">Square Yards</option>
                                                <option value="Sq miles">Square Miles</option>
                                            </select>
                                            </div>
                                            <div class="custom-tooltip">Total land or building area of the property.</div>
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <div class="form-group tooltip-wrapper">
                                                <label for="" class="col-form-label">@lang('admin.coin.tokenvalue')
                                                    ({{ Setting::get('default_currency') }}) <span class="text-danger">*</span></label>
                                                <input class="form-control" type="number" name="token_value"
                                                    value="{{ old('token_value') }}" id="tokenValue" placeholder="Token Value"
                                                    step="any" min="1" onkeyup="calculateTokenValue()">
                                                    <div class="custom-tooltip">Price per token in USD for this property.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="totalDealSize">@lang('admin.totaldealsize')<span class="text-danger">*</span></label>
                                                <input class="form-control" type="number" name="totalDealSize"
                                                    value="" required data-parsley-type="digits"
                                                    id="totalDealSize"
                                                    placeholder="@lang('admin.placeholders.total_deal_size')" min="1" step="any" onkeyup="calculateTokenValue()">
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="total_sft">Total Dealsize<span class="text-danger">*</span></label>
                                                <input class="form-control" type="number" name="total_sft" value="{{ old('total_sft') }}" data-parsley-type="digits" id="total_sft_input" placeholder="Enter Total Deal Value and Token value" step="any" min="1" max="999999999" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <h5>@lang('admin.inputs.overview_details')</h5>
                                            <div class="form-group tooltip-wrapper">
                                                <label for="propertyOverview">@lang('admin.inputs.detail_overview') ( Max 10000 Characters )<span
                                                        class="text-danger"></span></label>
                                                <textarea class="form-control" type="text" name="propertyOverview" required
                                                    data-parsley-required-message="Please enter @lang('admin.inputs.detail_overview')" id="propertyOverview"
                                                    placeholder="@lang('admin.placeholders.detail_overview')" maxlength="10000"></textarea>
                                                <div class="custom-tooltip">A short overview describing the property.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <h5>@lang('admin.location_details')</h5>
                                            <div class="form-group tooltip-wrapper">
                                                <label for="">@lang('admin.inputs.location_overview') ( Max 10000 Characters ) <span class="text-danger">
                                                        </span> </label>
                                                <textarea class="form-control" type="text" name="propertyLocationOverview" id="propertyLocationOverview"
                                                    placeholder="Enter Property Location Overview" required data-parsley-required-message="This field is required!"
                                                    maxlength="10000"></textarea>
                                                <div class="custom-tooltip">A brief summary of the property's location and its benefits.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="mb-3">Management Team</h5>
                                    <div class="row form-group">
                                        <div class="col-md-12 mb-4">
                                            <label for="" class="col-form-label mb-3">Management Team Description (Max 10000
                                                Characters)</label>
                                            <textarea class="form-control" type="text" name="ManagementTeamDescription" id="managementTeamDescription"
                                                placeholder="Enter Management Team Description" maxlength="10000"></textarea>
                                        </div>
                                    </div>

                                    <!-- Step 1 Navigation -->
                                    <div class="navigation-buttons">
                                        <div></div>
                                        <button type="button" class="btn btn-primary" onclick="nextStep(1)">Next</button>
                                    </div>
                                </div>

                                <!-- Step 2: Documents/Reports & Property Location -->
                                <div class="form-step" data-step="2">
                                    <h5>@lang('admin.doc/reports')</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">@lang('admin.labels.investordos')<span
                                                        class="text-danger"></span>&nbsp;&nbsp;<i style="font-size:14px;cursor: pointer;"
                                                        class="fa" data-toggle="tooltip"
                                                        title="Only allows pdf format">&#xf05a;</i></label>
                                                <input class="form-control" type="file" name="investor"
                                                    value="{{ old('investor') }}" accept="application/pdf"
                                                    id="propertyInvestor" data-parsley-required-message="Please choose Prospectus">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">Reports&nbsp;&nbsp;<i
                                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                                        title="Only allows pdf format">&#xf05a;</i></label>
                                                <input class="form-control" type="file" name="titlereport"
                                                    value="{{ old('titlereport') }}" accept="application/pdf"
                                                    id="propertyTitlereport">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">Agreements&nbsp;&nbsp;<i
                                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                                        title="Only allows pdf format">&#xf05a;</i></label>
                                                <input class="form-control" type="file" name="termsheet" accept="application/pdf"
                                                    id="termsheet">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">Share Certificate<span
                                                class="text-danger"></span>&nbsp;&nbsp;<i
                                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                                        title="Only allows pdf format">&#xf05a;</i></label>
                                                <input class="form-control" type="file" accept="application/pdf"
                                                    name="propertyUpdatesDoc" id="propertyUpdatesDoc" data-parsley-required-message="Please choose Share certificate">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">Brochure <span
                                                        class="text-danger"></span>&nbsp;&nbsp;<i style="font-size:14px;cursor: pointer;"
                                                        class="fa" data-toggle="tooltip"
                                                        title="Only allows pdf format">&#xf05a;</i></label>
                                                <input class="form-control" type="file" name="brochure" accept="application/pdf"
                                                    id="brochure"  data-parsley-required-message="Please choose Brochure">
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="mt-5">@lang('admin.property_details')</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="show_property">Show Property Details<span class="text-danger">*</span></label>
                                                <select class="form-control" name="show_property" id="show_property" required>
                                                    <option value="">Select</option>
                                                    <option value="yes">yes</option>
                                                    <option value="no">no</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">@lang('admin.inputs.locality')<span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="locality" id="locality"
                                                    value="{{ old('locality') }}" placeholder="@lang('admin.placeholders.locality')" required
                                                    maxlength="600" data-parsley-required-message="Please enter locality">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">@lang('admin.inputs.year_of_build') <span
                                                        class="text-danger"> *</span></label>
                                                <select class="form-control" name="yearOfConstruction" id="yearofbuild" required
                                                    data-parsley-required-message="Please choose @lang('admin.inputs.yearofbuild')">
                                                    <option value="">Select</option>
                                                    @for ($i = 1990; $i <= date('Y'); $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">@lang('admin.inputs.details_highlight') ( Max 10000 Characters
                                                    )</label>
                                                <textarea class="form-control" type="text" name="propertyDetailsHighlights"
                                                    value="{{ old('propertyDetailsHighlights') }}" id="propertyDetailsHighlights" placeholder="@lang('admin.placeholders.details_highlight')"
                                                    data-parsley-required-message="Please enter Property Highlights" maxlength="10000"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">@lang('admin.placeholders.propertyimage')&nbsp;&nbsp;<i
                                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                                        title="Only allows png/jpeg format">&#xf05a;</i></label>
                                                <input class="form-control" type="file" name="propertyimages"
                                                    accept="image/png,image/jpeg,image/jpg" id="propertyimages"
                                                    @if(!$isDemo) data-parsley-required-message="Please Upload Property Images" @endif>
                                                <p>You can add more images while editing this token</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="airport">@lang('admin.property.airport')</label>
                                                <input type="text" class="form-control" type="text" name="airport"
                                                    value="{{ old('airport') }}"
                                                    data-parsley-required-message="Please enter @lang('admin.property.airport')" id="airport"
                                                    placeholder="Nearest Airport" maxlength="100">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="hospitals">@lang('admin.property.hospitals')</label>
                                                <input class="form-control" type="text" name="hospitals"
                                                    value="{{ old('hospitals') }}"
                                                    data-parsley-required-message="Please enter @lang('admin.property.hospitals')" id="hospitals"
                                                    placeholder="Nearest Hospital" maxlength="100">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="fire_services">@lang('admin.property.fire_services')</label>
                                                <input class="form-control" type="text" name="fire_services" id="fire_services"
                                                    value="{{ old('fire_services') }}" placeholder="Nearest Fire Service"
                                                    maxlength="100">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="industrial">@lang('admin.property.industrial')</label>
                                                <input class="form-control" type="text" name="industrial" id="industrial"
                                                    value="{{ old('industrial') }}" placeholder="Nearest Industrial Area"
                                                    maxlength="100">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 2 Navigation -->
                                    <div class="navigation-buttons">
                                        <button type="button" class="btn btn-secondary" onclick="previousStep(2)">Previous</button>
                                        <button type="button" class="btn btn-primary" onclick="nextStep(2)">Next</button>
                                    </div>
                                </div>

                                <!-- Step 3: Token Details -->
                                <div class="form-step" data-step="3">
                                    <h5 class="mb-4">Token Details</h5>
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
                                                <div class="custom-tooltip">Choose the blockchain where the token will be deployed.</div>
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
                                                <div class="custom-tooltip">Full name of the token representing this property.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group tooltip-wrapper">
                                                <label for="" class="col-form-label">@lang('admin.coin.tokensymbol') <span
                                                        class="text-danger">* </span><i style="font-size:14px;cursor: pointer;"
                                                        class="fa" data-toggle="tooltip"
                                                        title="Only 4 characters are allowed ">&#xf05a;</i></label>
                                                <input class="form-control allowAlphaOnly" type="text" name="token_symbol"
                                                    value="{{ old('token_symbol') }}" required id="tokenSymbol" placeholder="Eg: ABCD"
                                                    data-parsley-required-message="Please enter Token Symbol" maxlength="4">
                                                <div class="custom-tooltip">Abbreviated symbol (ticker) for the token.</div>
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
                                            <div class="form-group tooltip-wrapper">
                                                <label for="" class="col-form-label">@lang('admin.token_to_be_issued') <span
                                                        class="text-danger">*</span></label>
                                                <br>
                                                <input type="radio" name="tokentype" class="tokentype" value="ERC20" checked>
                                                @lang('admin.erc20_utility')<br>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group tooltip-wrapper">
                                                <label for="" class="col-form-label">@lang('admin.coin.tokenimage') @if(!$isDemo)<span
                                                        class="text-danger">*</span>@endif &nbsp;&nbsp;<i style="font-size:14px;cursor: pointer;"
                                                        class="fa" data-toggle="tooltip"
                                                        title="Only allows png/jpeg format">&#xf05a;</i></label>
                                                <input class="form-control" type="file" name="token_image" @if(!$isDemo) required @endif id="tokenImage"
                                                    accept="image/png,image/jpeg,image/jpg"
                                                    data-parsley-required-message="Please upload Token Image">
                                                <div class="custom-tooltip">Icon or image to visually represent the token.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group tooltip-wrapper">
                                                <label for="" class="col-form-label">@lang('admin.coin.tokensupply') <span
                                                        class="text-danger">*</span> (is equal to total <span id="TokanSupplyVal">Sq ft</span>)</label>
                                                <input class="form-control" type="number" name="token_supply"
                                                    value="" required id="tokenSupply"
                                                    placeholder="Token Supply" readonly >
                                                <div class="custom-tooltip">The total amount of tokens being created for this property.</div>
                                            </div>
                                            <label style="color: blue">* Note: Only whole numbers allowed</label>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="keystoreSelect" class="col-form-label">
                                                    @lang('admin.keystore') <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control" name="keystore_id" id="keystoreSelect" required data-url="{{ url('api/balance')}}">
                                                    <option value="">-- Select a Keystore --</option>
                                                    @foreach($keystores as $keystore)
                                                        <option value="{{ $keystore->id }}" data-public-address="{{ $keystore->public_address }}">
                                                            {{ $keystore->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div id="keystoreError" class="text-danger mt-2" style="display: none;">* Insufficient balance</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- The Wallet Custody section is withdrawn: internal custody is no longer
                                         offered, so every asset is external-wallet only and enable_internal_wallet
                                         is always submitted as 0. The original section is kept here for reference.
                                    <h5 class="mb-3">Wallet Custody</h5>
                                    <p>
                                        You can enable internal wallet functionality for the issuer. If enabled, investors will have a choice to receive purchased tokens in an internal wallet or an external wallet.
                                        The difference: if an investor chooses the internal wallet, tokens are not moved on the blockchain. Instead, they remain in the company's blockchain wallet, while the platform's database reserves those tokens for the investor.
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
                                                    <input type="radio" name="enable_internal_wallet" id="enableWalletYes" class="form-check-input" value="1">
                                                    <label class="form-check-label" for="enableWalletYes">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" name="enable_internal_wallet" id="enableWalletNo" class="form-check-input" value="0" checked>
                                                    <label class="form-check-label" for="enableWalletNo">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    --}}
                                    <input type="hidden" name="enable_internal_wallet" value="0">

                                    <!-- Step 4 Navigation -->
                                    <div class="navigation-buttons">
                                        <button type="button" class="btn btn-secondary" onclick="previousStep(3)">Previous</button>
                                        <button type="submit" class="btn btn-success">Create Token</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end container-fluid -->
                    @endsection
                    <!-- Start Page Content here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Token Creation Progress Modal -->
        <div class="modal fade" id="tokenCreationModal" tabindex="-1" role="dialog" aria-labelledby="tokenCreationModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tokenCreationModalLabel">Creating Token</h5>
                        <!-- Removed close button to prevent user from closing -->
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="spinner-border text-primary mb-3" role="status" id="creationSpinner">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <h6 id="creationStatus">Initializing token creation...</h6>
                        </div>

                        <div class="progress mb-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 id="creationProgress"
                                 style="width: 0%"
                                 aria-valuenow="0"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                0%
                            </div>
                        </div>

                        <div class="text-center">
                            <small class="text-muted" id="creationDetails">Please wait while we deploy your token to the blockchain...</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Please Wait Modal for Extended Processing -->
        <div class="modal fade" id="pleaseWaitModal" tabindex="-1" role="dialog" aria-labelledby="pleaseWaitModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pleaseWaitModalLabel">Please Wait</h5>
                        <!-- No close button to prevent user from closing -->
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="spinner-border text-warning mb-3" role="status" id="waitSpinner">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <h6 class="text-warning">Processing is taking longer than expected</h6>
                        </div>

                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-info-circle me-2"></i>
                                <div>
                                    <strong>Please wait.</strong> It may take up to 5 minutes to complete the token creation process.
                                    <br><small class="text-muted">Do not close this window or refresh the page.</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <small class="text-muted">The blockchain network may be experiencing high traffic. Your request is still being processed.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Token Success Modal -->
        <div class="modal fade" id="tokenSuccessModal" tabindex="-1" aria-labelledby="tokenSuccessModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tokenSuccessModalLabel">Token Deployed Successfully!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="mb-4">
                            <i class="fa fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h6 class="mb-3">The token has been deployed successfully!</h6>
                        <div class="mb-3">
                            <strong>Contract Address:</strong><br>
                            <a href="#" id="contractAddressLink" target="_blank" class="text-primary text-decoration-none">
                                <span id="contractAddress">0x...</span>
                            </a>
                        </div>
                        <p class="text-muted small">Click the address above to view your token on the blockchain explorer</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-primary px-4 py-2" id="proceedButton">Proceed</button>
                    </div>
                </div>
            </div>
        </div>

        @section('scripts')
            {{-- <script type="text/javascript" src="{{ asset('main/vendor/jquery/jquery-1.12.3.min.js') }}"></script> --}}
            <script src="{{ asset('main/assets/js/validate.min.js') }}"></script>
            <script src="{{ asset('main/assets/js/additional-method.js') }}"></script>
            <script type="text/javascript">
                $(document).ready(function() {

                    var n = 2;
                    $("#add_comparables").click(function(e) {
                        e.preventDefault();
                        var html = '<div id="comparables_block_' + n +
                            '" class="form-group row"><div class="form-group col-md-3"><label for="" class="col-form-label">Property Address</label><input class="form-control" type="text" name="comparables[' +
                            n + '][property]" id="property_' + n +
                            '" placeholder="Property Address"></div><div class="form-group col-md-3"><label for="" class="col-form-label">Sale Date</label><input class="form-control" type="date" name="comparables[' +
                            n + '][type]" id="type_' + n +
                            '" placeholder="Sale Date"></div><div class="form-group col-md-3"><label for="" class="col-form-label">Location</label><input class="form-control" type="text" name="comparables[' +
                            n + '][location]" id="location_' + n +
                            '" placeholder="Location"   data-parsley- -message="Please enter Location"></div><div class="form-group col-md-3"><label for="" class="col-form-label">Year of Build</label><input class="form-control" type="number" name="comparables[' +
                            n + '][distance]" accept="image/png,image/jpeg" id="distance_' + n +
                            '" placeholder="Year of Build" min="0"   data-parsley- -message="Please enter Year of Build"></div><div class="form-group col-md-3"><label for="" class="col-form-label">Total Sft</label><input class="form-control" type="number" name="comparables[' +
                            n + '][rent]" id="rent_' + n +
                            '" placeholder="Total Sft" min="0" step="any"   data-parsley- -message="Please enter Total SFT"></div><div class="form-group col-md-3"><label for="" class="col-form-label">Sale Price</label><input class="form-control" type="number" name="comparables[' +
                            n + '][saleprice]"  id="saleprice_' + n +
                            '" placeholder="Sale Price" min="0" step="any"   data-parsley- -message="Please enter Sale Price"></div><div class="col-md-3"><div class="form-group"><label for="propertyLogo">Property Logo</label><input class="form-control" type="file" name="propertyVideo" accept="image/png,image/jpeg"   id="propertyLogo" placeholder="Enter Property Logo"   data-parsley- -message="Please upload Property Logo"></div></div><div class="col-md-3"><div class="form-group"><label for="propertyLogo">Map</label><input class="form-control" type="file" name="map" accept="application/pdf"   id="propertyLogo" placeholder="Enter Property Logo"   data-parsley- -message="Please choose Map"></div></div><div class="col-md-3"><div class="form-group"><label for="propertyLogo">Comparables Details</label><input class="form-control" type="file" name="comparabledetails" accept="application/pdf"  id="propertyLogo" placeholder="Enter Property Logo"   data-parsley- -message="Please upload Comparable Details"></div></div><div class="form-group col-md-3"><button type="button" class="btn btn-danger landmark_remove" id="landmark_remove_' +
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

                $('#area_type').on('change', function(){
                    value = $(this).val();
                    $('#AreaType').text(value);
                    $('#TokanSupplyVal').text(value);
                    var inputField = document.getElementById('total_sft_input');
                    // inputField.placeholder = "Enter Total " + value;
                })

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
                        '		<input class="form-control" type="text" name="member[' + index + '][name]"  id="MemberName_' + index +
                        '" placeholder="Enter Member Name" >' +
                        '	</div>' +
                        '	<div class="col-md-3 form-group">' +
                        '		<label for="" class="col-form-label">Member Position</label>' +
                        '		<input class="form-control" type="text" name="member[' + index + '][position]"  id="MemberPosition_' +
                        index + '" placeholder="Enter Member Position" >' +
                        '	</div>' +
                        '	<div class="col-md-3 form-group">' +
                        '		<label for="" class="col-form-label">Member Image&nbsp;&nbsp;<i style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip" title="Only allows png/jpeg format">&#xf05a;</i></label>' +
                        '		<input class="form-control" type="file" name="member[' + index + '][pic]"  id="MemberPic_' + index +
                        '" accept="image/png,image/jpeg" placeholder="Select Member Picture" >' +
                        '	</div>' +
                        '	<div class="col-md-3 form-group">' +
                        '		<label for="" class="col-form-label">Member Description</label>' +
                        '		<input class="form-control" type="text" name="member[' + index +
                        '][description]"  id="MemberDescription_' + index + '" placeholder="Enter Description" >' +
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
            <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
            <script>
                // $(document).ready(function () {
                //     $("input[type=text], input[type=radio], input[type=file], textarea, select, input[type=number]").blur(function () {
                //         if ($(this).val() != "") {
                //             Cookies.set('token_' + $(this).attr('name'), $(this).val(), {expires: 1});
                //             console.log($(this).val());
                //             console.log($(this).attr('name'));
                //         }
                //     });

                //     $("input[type=text], textarea, select, input[type=radio],  input[type=number]").each(function () {

                //         var cookval = Cookies.get('token_' + $(this).attr('name'));
                //         if ($(this).attr('type') == "radio") {
                //             var inpname = $(this).attr('name');
                //             $("input[name=" + inpname + "][value=" + cookval + "]").prop("checked", true);
                //         } else {
                //             $(this).val(cookval);
                //         }


                //     });
                // });
            </script>
            <script src="{{ asset('/js/parsley.js') }}"></script>
            <script>
                function showLoader() {
                    console.log('loader')
                    $('body').addClass('pre-loader')
                }

                function hideLoader() {
                    console.log("hide loader")
                    $('body').removeClass('pre-loader')
                }
            </script>
            <script type="text/javascript">
                var canSubmit = [],
                    count = 1;

                function calculateTokenValue() {
                    updateDealSizeAndSupply();
                    var totalDealSize = parseInt($('#totalDealSize').val()) || 0
                    var formButton = $('.createTokenButton')
                    var totalSqt = parseInt($('#total_sft').val()) || 0
                    var minimumField = $('#initialInvestment')
                    var tokenField = $('#tokenValue')
                    var totalSupplyField = $('#tokenSupply')
                    var tokenValue = (totalDealSize / totalSqt).toFixed(4)
                    // console.log(tokenValue, totalDealSize, totalSqt)
                    minimumField.next('span').remove()
                    formButton.attr('disabled', false)
                    // console.log(parseInt(minimumField.val()))
                    if (parseInt(minimumField.val()) > totalDealSize) {
                        formButton.attr('disabled', true)
                        minimumField.after(
                            "<span class='text-danger'>* Minimum Investment should not be greater than Total Deal Size *</span>"
                            )
                    }

                }

                $('#tokenValue').on('keyup',function(){
                    updateDealSizeAndSupply();
                });

                function updateDealSizeAndSupply() {
                    var totalDealSize = parseFloat($('#totalDealSize').val());
                    var tokenValue = parseFloat($('#tokenValue').val());

                    // console.log('Total Deal Size:', totalDealSize, 'Token Value:', tokenValue);

                    var tokenSupply = 0;

                    // Handle empty or invalid input
                    if (isNaN(totalDealSize) || totalDealSize <= 0) {
                        $('#tokenSupply').val(0);
                        $('#total_sft_input').val(0);
                    } else if (isNaN(tokenValue) || tokenValue <= 0) {
                        $('#tokenSupply').val(0);
                        $('#total_sft_input').val(0);
                    } else {
                        // Calculate number of tokens
                        tokenSupply = totalDealSize / tokenValue;
                        $('#tokenSupply').val(tokenSupply);
                        $('#total_sft_input').val(tokenSupply);
                    }
                }

            </script>
             <script>
                $('#total_sft').on('keyup',function(){
                    var sqft=$('#total_sft').val();
                    var supply=$('#tokenSupply').val();
                    console.log(supply);
                    // console.log(sqft!=0);

                        $('#tokenSupply').val(sqft);

                    if(sqft==0){
                        $('#totalDealSize').val(0);
                        $('#tokenSupply').val(0);

                    }
                    var dealsize;
                    var tokenvalue=$('#tokenValue').val();
                    var supply;
                    if(tokenvalue==0 || tokenvalue==''){
                        $('#totalDealSize').val(0);
                        $('#tokenSupply').val(sqft);

                    }
                    dealsize=sqft*tokenvalue;
                    $('#totalDealSize').val(dealsize);
                    $('#tokenSupply').val(sqft);

                });
            </script>

            <script type="text/javascript">
                $(document).ready(function() {
                    // Initialize form validation but don't enable it yet
                    var formValidator = $("#property-create").validate({
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
                                required: {{$isDemo ? 'false' : 'true'}},
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
                                required: false,
                                number: true,
                                min: 1,
                                max: 100,
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

                            },
                            "total_sft": {
                                required: true,
                                number: true,
                                min: 1,
                                max: 100000000000,
                            },
                            "propertyOverview": {
                                required: false,
                                maxlength: 100000
                            },
                            "propertyLocationOverview": {
                                required: false,
                                maxlength: 100000
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
                            "propertyTotalBuildingArea": {
                                required: false,
                                number: true,
                                min: 0,
                                max: 100000000000,
                            },
                            "propertyDetailsHighlights": {
                                required: false,
                                maxlength: 100000
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
                                required: {{$isDemo ? 'false' : 'true'}},
                                accept: 'image/jpeg, image/png, image/jpg',
                            },
                            "management": {
                                required: true,
                            },
                            "updates": {
                                required: true,
                            },
                            "propertylogoimage": {
                                required: false,
                                accept: 'image/jpeg, image/png, image/jpg',
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
                                min: 0.000001
                            },
                            "token_supply": {
                                required: true,
                                number: true,

                                max: 100000000000,
                            },
                            "token_decimal": {
                                required: true,
                                min:1,
                                max:18,
                                // digits: true,
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
                                maxlength: 50
                            }
                        },
                        messages: {
                            "propertyName": {
                                required: "Please Enter Valid Property Name",
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
                            "storeys": {
                                required: "Please Enter Valid Community!",
                            },
                            "propertyLocation": {
                                required: "Please Enter Valid Property Location",
                            },
                            "propertyLogo": {
                                required: "Please Choose Property Logo",
                                accept: "Please Upload Valid format (jpeg|png|jpg)",
                            },
                            "propertyVideo": {
                                required: "Please Choose Property Logo",
                                accept: "Please Upload Valid format (mp4|x-m4v)",
                            },
                            "management": {
                                required: "Please Upload Management",
                            },
                            "updates": {
                                required: "Please Upload Updates",
                            },
                            "propertylogoimage": {
                                required: "Please Choose Property Logo",
                                accept: "Please Upload Valid format (jpeg|png|jpg)",
                            },
                            "propertyimages": {
                                required: "Please Choose Property Image",
                                accept: "Please Upload Valid format (jpeg|png|jpg)",
                            },
                            "totalDealSize": {
                                required: "Please Enter Valid Total Deal Size",
                            },
                            "expectedIrr": {
                                required: "Please Enter Valid Expected Annual Return",
                            },
                            "PropertyedMembers": {
                                required: "Please Enter Valid Propertyed Members",
                            },
                            "initialInvestment": {
                                required: "Please Enter Valid Minimum Investment",
                            },
                            "propertyEquityMultiple": {
                                required: "Please Enter Valid Property Equity Multiple",
                            },
                            "holdingPeriod": {
                                required: "Please Enter Valid Holding Period",
                            },
                            "total_sft": {
                                required: "Please Enter Valid Total Square feet",
                            },
                            "propertyOverview": {
                                required: "Please Enter Valid Property Overview",
                            },
                            "propertyHighlights": {
                                required: "Please Enter Valid Property Highlights",
                            },
                            "propertyLocationOverview": {
                                required: "Please Enter Valid Location Overview",
                            },
                            "locality": {
                                required: "Please Enter Valid Location",
                            },
                            "yearOfConstruction": {
                                required: "Please Enter Valid Year of Build!",
                            },
                            "propertyParking": {
                                required: "Please Enter Valid Number of Bedrooms",
                            },
                            "floorforSale": {
                                required: "Please Enter Valid Number of Bathrooms",
                            },
                            "propertyTotalBuildingArea": {
                                required: "Please Enter Valid Total Building Area",
                            },
                            "propertyDetailsHighlights": {
                                required: "Please Enter Valid Details Highlights",
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
                        },
                        submitHandler: function(form) {
                            // Only validate on final step (step 4)
                            const currentStep = document.querySelector('.form-step.active');
                            const currentStepNumber = currentStep ? parseInt(currentStep.getAttribute('data-step')) : 1;

                            if (currentStepNumber !== 3) {
                                console.log('Form submission blocked - not on final step');
                                return false;
                            }

                            // Validate all steps before submitting
                            for (let i = 1; i <= 3; i++) {
                                if (!validateStep(i)) {
                                    console.log(`Step ${i} validation failed, moving to step ${i}`);
                                    // Show the step that failed validation
                                    document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
                                    document.querySelector(`[data-step="${i}"]`).classList.add('active');
                                    updateStepIndicator(i);
                                    return false;
                                }
                            }

                            console.log('All steps validated, submitting form');
                            form.submit();
                        }
                    });

                    // Disable automatic validation on form submission
                    $("#property-create").off('submit').on('submit', function(e) {
                        e.preventDefault();
                        formValidator.form();
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
                                console.log(data.status, data.balance,data.balance <= 0);
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

            <script>
                const tooltipWrapper = document.querySelector('.tooltip-wrapper');
                const tooltip = document.querySelector('.custom-tooltip');
                const input = document.querySelector('#propertyName');

                input.addEventListener('mousemove', function(e) {
                    const rect = tooltipWrapper.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const percentage = (x / rect.width) * 100;

                    // Constrain the tooltip to stay within reasonable bounds
                    const constrainedPercentage = Math.max(15, Math.min(85, percentage));

                    tooltipWrapper.style.setProperty('--mouse-x', constrainedPercentage + '%');
                    tooltipWrapper.style.setProperty('--arrow-x', constrainedPercentage + '%');
                });
            </script>

            <script>
                // Auto-fill form with backend mock data on window load
                window.addEventListener('load', function() {
                    // Mock data from backend - make it optional
                    const mockData = @json($mockData ?? null);

                    if(!mockData){
                        return;
                    }

                    // Helper function to safely set element value
                    function safeSetValue(elementId, value) {
                        const element = document.getElementById(elementId);
                        if (element) {
                            element.value = value;
                        } else {
                            console.warn(`Element with ID '${elementId}' not found`);
                        }
                    }

                    // Property Basic Information
                    safeSetValue('propertyName', mockData.propertyName);
                    safeSetValue('propertyType', mockData.propertyType);
                    safeSetValue('propertyLocation', mockData.propertyLocation);
                    safeSetValue('expectedIrr', mockData.expectedIrr);
                    safeSetValue('holdingPeriod', mockData.holdingPeriod);
                    safeSetValue('initialInvestment', mockData.initialInvestment);
                    safeSetValue('area_type', mockData.areaType);
                    safeSetValue('tokenValue', mockData.tokenValue);
                    safeSetValue('totalDealSize', mockData.totalDealSize);
                    safeSetValue('total_sft_input', mockData.totalSqft);

                    // Property Details
                    safeSetValue('show_property', mockData.showProperty);
                    safeSetValue('locality', mockData.locality);
                    safeSetValue('yearofbuild', mockData.yearOfConstruction);
                    safeSetValue('propertyDetailsHighlights', mockData.propertyDetailsHighlights);

                    // Property Overview
                    safeSetValue('propertyOverview', mockData.propertyOverview);

                    // Location Overview
                    safeSetValue('propertyLocationOverview', mockData.propertyLocationOverview);

                    // Token Details
                    safeSetValue('tokenChain', '1'); // Assuming first blockchain option
                    safeSetValue('tokenName', mockData.tokenName);
                    safeSetValue('tokenSymbol', mockData.tokenSymbol);
                    safeSetValue('tokenDecimal', mockData.tokenDecimal);
                    safeSetValue('tokenSupply', mockData.tokenSupply);

                    // Keystore Selection (select first available keystore)
                    const keystoreSelect = document.getElementById('keystoreSelect');
                    if (keystoreSelect && keystoreSelect.options.length > 1) {
                        keystoreSelect.selectedIndex = 1; // Select first actual keystore (skip placeholder)
                    }

                    // Management Team Description
                    safeSetValue('managementTeamDescription', mockData.managementTeamDescription);

                    // Enable Internal Wallet
                    const enableWalletNo = document.getElementById('enableWalletNo');
                    if (enableWalletNo) {
                        enableWalletNo.checked = true;
                    }

                    // Nearby amenities
                    safeSetValue('airport', mockData.airport);
                    safeSetValue('hospitals', mockData.hospitals);
                    safeSetValue('fire_services', mockData.fireServices);
                    safeSetValue('industrial', mockData.industrial);

                    // Update area type display
                    const areaTypeElement = document.getElementById('AreaType');
                    const tokanSupplyValElement = document.getElementById('TokanSupplyVal');
                    if (areaTypeElement) areaTypeElement.textContent = mockData.areaType;
                    if (tokanSupplyValElement) tokanSupplyValElement.textContent = mockData.areaType;

                    // Trigger calculations
                    setTimeout(function() {
                        if (typeof calculateTokenValue === 'function') {
                            calculateTokenValue();
                        }
                        if (typeof updateDealSizeAndSupply === 'function') {
                            updateDealSizeAndSupply();
                        }
                    }, 100);
                });
            </script>

            <script>
                // Multi-step form navigation functions
                function nextStep(currentStep) {
                    // Validate current step
                    if (validateStep(currentStep)) {
                        // Update to next step
                        updateStepIndicator(currentStep + 1);
                    }
                }

                function previousStep(currentStep) {
                    // Update to previous step
                    updateStepIndicator(currentStep - 1);
                }

                function updateStepIndicator(activeStep) {
                    // Update form step visibility
                    const allFormSteps = document.querySelectorAll('.form-step');

                    allFormSteps.forEach(step => {
                        step.classList.remove('active');
                    });

                    const activeFormStep = document.querySelector(`.form-step[data-step="${activeStep}"]`);
                    if (activeFormStep) {
                        activeFormStep.classList.add('active');
                        // Force a reflow to ensure the display change takes effect
                        activeFormStep.offsetHeight;
                    }
                }

                function validateStep(step) {
                    const currentStepElement = document.querySelector(`[data-step="${step}"]`);
                    if (!currentStepElement) {
                        return false;
                    }

                    // Get all required fields in the current step
                    const requiredFields = currentStepElement.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        const fieldName = field.name;
                        const fieldValue = field.value.trim();

                        // Check if field is empty
                        if (!fieldValue) {
                            field.classList.add('error');
                            isValid = false;

                            // Show error message
                            const errorElement = field.parentNode.querySelector('.error-message');
                            if (!errorElement) {
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'error-message text-danger mt-1';
                                errorDiv.textContent = 'This field is required.';
                                field.parentNode.appendChild(errorDiv);
                            }
                        } else {
                            field.classList.remove('error');

                            // Remove error message
                            const errorElement = field.parentNode.querySelector('.error-message');
                            if (errorElement) {
                                errorElement.remove();
                            }
                        }
                    });

                    return isValid;
                }

                // Initialize step indicator on page load
                document.addEventListener('DOMContentLoaded', function() {
                    updateStepIndicator(1);
                });
            </script>

            <script>
                // Token creation progress handling
                let progressInterval;
                let currentProgress = 0;
                let isCreating = false;
                let progressCompleted = false;
                let pleaseWaitTimeout;
                let pleaseWaitModalShown = false;

                // Override form submission to show progress modal
                $(document).ready(function() {
                    $('#property-create').on('submit', function(e) {
                        e.preventDefault();

                        if (isCreating) return false;

                        // Validate all steps before submitting
                        for (let i = 1; i <= 3; i++) {
                            if (!validateStep(i)) {
                                console.log(`Step ${i} validation failed, moving to step ${i}`);
                                document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
                                document.querySelector(`[data-step="${i}"]`).classList.add('active');
                                updateStepIndicator(i);
                                return false;
                            }
                        }

                        // Start token creation process
                        startTokenCreation();
                    });
                });

                function startTokenCreation() {
                    isCreating = true;
                    currentProgress = 0;
                    progressCompleted = false;
                    pleaseWaitModalShown = false;

                    // Show progress modal with Bootstrap 5
                    const progressModal = new bootstrap.Modal(document.getElementById('tokenCreationModal'), {
                        backdrop: 'static',
                        keyboard: false
                    });
                    progressModal.show();

                    // Start progress simulation
                    startProgressSimulation();

                    // Set timeout for "Please wait" modal (show after 2 minutes)
                    pleaseWaitTimeout = setTimeout(() => {
                        if (isCreating && !progressCompleted) {
                            showPleaseWaitModal();
                        }
                    }, 120000); // 2 minutes

                    // Submit form via AJAX
                    submitFormWithProgress();
                }

                function showPleaseWaitModal() {
                    if (pleaseWaitModalShown) return;

                    pleaseWaitModalShown = true;

                    // Hide progress modal
                    const progressModal = bootstrap.Modal.getInstance(document.getElementById('tokenCreationModal'));
                    if (progressModal) {
                        progressModal.hide();
                    }

                    // Show please wait modal
                    const pleaseWaitModal = new bootstrap.Modal(document.getElementById('pleaseWaitModal'), {
                        backdrop: 'static',
                        keyboard: false
                    });
                    pleaseWaitModal.show();
                }

                function startProgressSimulation() {
                    const progressBar = document.getElementById('creationProgress');
                    const progressText = progressBar.querySelector('span') || progressBar;
                    const statusText = document.getElementById('creationStatus');
                    const detailsText = document.getElementById('creationDetails');

                    const steps = [
                        { progress: 10, status: 'Validating form data...', details: 'Checking all required fields and file uploads' },
                        { progress: 25, status: 'Preparing token parameters...', details: 'Setting up token name, symbol, and supply' },
                        { progress: 40, status: 'Connecting to blockchain...', details: 'Establishing connection to the selected blockchain network' },
                        { progress: 60, status: 'Deploying smart contract...', details: 'Creating and deploying the token contract' },
                        { progress: 80, status: 'Confirming transaction...', details: 'Waiting for blockchain confirmation' },
                        { progress: 95, status: 'Finalizing deployment...', details: 'Completing token setup and verification' },
                        { progress: 100, status: 'Token deployed successfully!', details: 'Your token is now live on the blockchain' }
                    ];

                    let currentStep = 0;

                    progressInterval = setInterval(() => {
                        if (currentStep < steps.length) {
                            const step = steps[currentStep];
                            currentProgress = step.progress;

                            progressBar.style.width = currentProgress + '%';
                            progressBar.setAttribute('aria-valuenow', currentProgress);
                            progressText.textContent = currentProgress + '%';

                            statusText.textContent = step.status;
                            detailsText.textContent = step.details;

                            currentStep++;
                        } else {
                            clearInterval(progressInterval);
                            progressCompleted = true;
                        }
                    }, 2000); // Change step every 2 seconds
                }

                function submitFormWithProgress() {
                    const form = document.getElementById('property-create');
                    const formData = new FormData(form);

                    // Add CSRF token
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                    // Calculate total time for progress simulation (7 steps * 2 seconds = 14 seconds)
                    const totalProgressTime = 14000; // 14 seconds in milliseconds

                    $.ajax({
                        url: form.action,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        timeout: 300000, // 5 minutes timeout
                        success: function(response) {
                            // Clear the please wait timeout
                            if (pleaseWaitTimeout) {
                                clearTimeout(pleaseWaitTimeout);
                            }

                            // Wait for progress simulation to complete before showing success
                            setTimeout(() => {
                                clearInterval(progressInterval);
                                progressCompleted = true;
                                showSuccessModal(response);
                            }, totalProgressTime);
                        },
                        error: function(xhr, status, error) {
                            // Clear the please wait timeout
                            if (pleaseWaitTimeout) {
                                clearTimeout(pleaseWaitTimeout);
                            }

                            clearInterval(progressInterval);
                            progressCompleted = true;
                            let errorMessage = 'An error occurred while creating the token.';

                            // Handle timeout specifically
                            if (status === 'timeout') {
                                errorMessage = 'The request timed out. Please try again. The blockchain network may be experiencing high traffic.';
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    errorMessage = response.message || errorMessage;
                                } catch (e) {
                                    errorMessage = 'Server error occurred.';
                                }
                            }

                            showErrorModal({ message: errorMessage });
                        }
                    });
                }

                function showSuccessModal(response) {
                    // Only show success if progress is completed
                    if (!progressCompleted) {
                        setTimeout(() => showSuccessModal(response), 500);
                        return;
                    }

                    // Hide progress modal
                    const progressModal = bootstrap.Modal.getInstance(document.getElementById('tokenCreationModal'));
                    if (progressModal) {
                        progressModal.hide();
                    }

                    // Hide please wait modal if it's shown
                    const pleaseWaitModal = bootstrap.Modal.getInstance(document.getElementById('pleaseWaitModal'));
                    if (pleaseWaitModal) {
                        pleaseWaitModal.hide();
                    }

                    // Set contract address from response - only use real data
                    const contractAddress = response.contract_address;
                    const blockchainExplorer = response.blockchain_explorer;

                    if (contractAddress) {
                        document.getElementById('contractAddress').textContent = contractAddress;
                        if (blockchainExplorer) {
                            document.getElementById('contractAddressLink').href = blockchainExplorer + contractAddress;
                        }
                    } else {
                        // Hide contract address section if not available
                        document.getElementById('contractAddressLink').parentElement.style.display = 'none';
                    }

                    // Store redirect URL from backend response
                    window.redirectUrl = response.redirect_url || '{{ route("marketplace") }}';

                    // Show success modal
                    const successModal = new bootstrap.Modal(document.getElementById('tokenSuccessModal'));
                    successModal.show();
                }

                function showErrorModal(error) {
                    // Hide progress modal
                    const progressModal = bootstrap.Modal.getInstance(document.getElementById('tokenCreationModal'));
                    if (progressModal) {
                        progressModal.hide();
                    }

                    // Hide please wait modal if it's shown
                    const pleaseWaitModal = bootstrap.Modal.getInstance(document.getElementById('pleaseWaitModal'));
                    if (pleaseWaitModal) {
                        pleaseWaitModal.hide();
                    }

                    // Show error modal instead of alert for better UX
                    const errorMessage = error.message || 'An unknown error occurred while creating the token.';

                    // Create error modal if it doesn't exist
                    let errorModal = document.getElementById('tokenErrorModal');
                    if (!errorModal) {
                        const modalHTML = `
                            <div class="modal fade" id="tokenErrorModal" tabindex="-1" aria-labelledby="tokenErrorModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger" id="tokenErrorModalLabel">Token Creation Failed</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <div class="mb-4">
                                                <i class="fa fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                                            </div>
                                            <h6 class="mb-3">Token creation was unsuccessful</h6>
                                            <p class="text-muted" id="errorMessageText">${errorMessage}</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        document.body.insertAdjacentHTML('beforeend', modalHTML);
                        errorModal = document.getElementById('tokenErrorModal');
                    } else {
                        // Update existing error message
                        document.getElementById('errorMessageText').textContent = errorMessage;
                    }

                    // Show error modal
                    const errorModalInstance = new bootstrap.Modal(errorModal);
                    errorModalInstance.show();

                    isCreating = false;
                }

                // Handle proceed button click
                $(document).ready(function() {
                    $('#proceedButton').on('click', function() {
                        const successModal = bootstrap.Modal.getInstance(document.getElementById('tokenSuccessModal'));
                        if (successModal) {
                            successModal.hide();
                        }
                        isCreating = false;
                        progressCompleted = false;
                        pleaseWaitModalShown = false;

                        // Clear any pending timeouts
                        if (pleaseWaitTimeout) {
                            clearTimeout(pleaseWaitTimeout);
                        }

                        // Redirect using URL from backend response
                        window.location.href = window.redirectUrl || '{{ route("marketplace") }}';
                    });
                });

                // Handle modal close events
                $(document).ready(function() {
                    const tokenCreationModal = document.getElementById('tokenCreationModal');
                    const pleaseWaitModal = document.getElementById('pleaseWaitModal');

                    // Handle progress modal events
                    tokenCreationModal.addEventListener('hidden.bs.modal', function() {
                        if (isCreating) {
                            clearInterval(progressInterval);
                            isCreating = false;
                            progressCompleted = false;
                            pleaseWaitModalShown = false;
                        }
                    });

                    // Prevent progress modal from being closed by any means
                    tokenCreationModal.addEventListener('hide.bs.modal', function(e) {
                        if (isCreating) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                    });

                    // Handle please wait modal events
                    pleaseWaitModal.addEventListener('hidden.bs.modal', function() {
                        if (isCreating) {
                            isCreating = false;
                            progressCompleted = false;
                            pleaseWaitModalShown = false;
                        }
                    });

                    // Prevent please wait modal from being closed by any means
                    pleaseWaitModal.addEventListener('hide.bs.modal', function(e) {
                        if (isCreating) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                    });

                    // Prevent ESC key from closing modals
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && isCreating) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                    });

                    // Prevent clicking outside modals from closing them
                    tokenCreationModal.addEventListener('click', function(e) {
                        if (e.target === this && isCreating) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                    });

                    pleaseWaitModal.addEventListener('click', function(e) {
                        if (e.target === this && isCreating) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                    });
                });
            </script>
        @endsection
