@extends('issuer.layout.base')
@section('content')
@php
    $isEditable = $property->isEditable;
    $tokenTypeName = '(Property Token)';
    if($property->token_type == 1)
        $tokenTypeName = '(Property Token)';
    elseif($property->token_type == 2)
        $tokenTypeName = '(Asset Token)';
    elseif($property->token_type == 3)
        $tokenTypeName = '(Utility Token)';
@endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    </style>

    <div class="content-page-inner">

        <!-- Header Banner Start -->
        <div class="header-breadcrumbs">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1>Edit Token Details {{$tokenTypeName}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <div class="breadcrumb-four" style="text-align: right;">
                            <ul class="breadcrumb">
                                <li><a href="{{ url('issuer/dashboard') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                                            <path
                                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                            </path>
                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                        </svg>
                                        <span>@lang('user.dashboard')</span></a></li>
                                <li class="active"><a href="javscript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu">
                                            <rect x="4" y="4" width="16" height="16" rx="2"
                                                ry="2"></rect>
                                            <rect x="9" y="9" width="6" height="6"></rect>
                                            <line x1="9" y1="1" x2="9" y2="4"></line>
                                            <line x1="15" y1="1" x2="15" y2="4"></line>
                                            <line x1="9" y1="20" x2="9" y2="23"></line>
                                            <line x1="15" y1="20" x2="15" y2="23"></line>
                                            <line x1="20" y1="9" x2="23" y2="9"></line>
                                            <line x1="20" y1="14" x2="23" y2="14"></line>
                                            <line x1="1" y1="9" x2="4" y2="9"></line>
                                            <line x1="1" y1="14" x2="4" y2="14"></line>
                                        </svg>
                                        <span>Edit Token</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Banner Start -->


        <div class="content">
            <!-- Start container-fluid -->
            <div class="container-fluid wizard-border">
                <!-- start  -->
                {{-- <h5>Edit Token</h5> --}}
                <form class="form-horizontal" id="property-create" action="{{ route('token.update', $property->id) }}"
                    method="POST" enctype="multipart/form-data" role="form">
                    @csrf
                    <input type="hidden" name="token_type" value="{{$property->token_type}}">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="propertyName">Token Name<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="propertyName"
                                    value="{{ old('propertyName', @$property->propertyName) }}" required=""
                                    data-parsley-required-message="Please enter @lang('admin.property.propertyname')" id="propertyName"
                                    placeholder="@lang('admin.enter') @lang('admin.property.propertyname')" maxlength="320">
                            </div>
                        </div>
                        @if($property->token_type == 1)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="propertyLocation">@lang('admin.property.propertylocation')<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="propertyLocation"
                                        value="{{ old('propertyLocation', @$property->propertyLocation) }}" required=""
                                        data-parsley-required-message="Please enter @lang('admin.property.propertylocation')" id="propertyLocation"
                                        placeholder="@lang('admin.enter') @lang('admin.property.propertylocation')" maxlength="320">
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="propertyLogo">Token Type<span class="text-danger">*</span> <i
                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                        title="Only allows png/jpeg/jpg format">&#xf05a;</i></label>
                                <a href="{{ @img(@$property->propertyLogo) }}" target="_blank"
                                    rel="noopener noreferrer">View</a>
                                <input class="form-control" type="file" name="propertyLogo"
                                    accept="image/png,image/jpeg,image/jpg"
                                    data-parsley-required-message="Please choose @lang('admin.property.propertylogo')" id="propertyLogo"
                                    placeholder="@lang('admin.placeholders.property_logo')">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="propertyType">@lang('admin.property.type')<span class="text-danger">*</span></label>
                                <select class="form-control" name="propertyType" id="propertyType" required
                                    data-parsley-required-message="Please choose @lang('admin.property.type')">
                                    <option value="">Select</option>
                                    @foreach ($assetType as $value)
                                        <option value="{{ @$value->type }}"
                                            {{ @$property->propertyType == $value->type ? 'selected' : null }}>
                                            {{ @$value->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="property_state">Project Status<span class="text-danger">*</span></label>
                                <input type="text" value="live" class="form-control" name="property_state"
                                    id="property_state" required readonly>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="totalDealSize">@lang('admin.totaldealsize')<span class="text-danger">*</span></label>
                                <input class="form-control" type="number" name="totalDealSize"
                                    value="{{ old('totalDealSize', @$property->totalDealSize) }}" required
                                    data-parsley-type="digits"
                                    data-parsley-required-message="Please enter @lang('admin.totaldealsize')"  readonly id="totalDealSize"
                                    placeholder="@lang('admin.placeholders.total_deal_size')" min="1" step="any"
                                    onkeyup="calculateTokenValue()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="expectedIrr">Expected Annual Return (%)<span class="text-danger">*</span></label>
                                <input class="form-control" type="number" name="expectedIrr" id="expectedIrr"
                                    value="{{ old('expectedIrr', @$property->expectedIrr) }}"
                                    placeholder="Enter Expected Annual Return" required data-parsley-type="digits"
                                    data-parsley-required-message="Please Enter Expected Annual Return" min="1"
                                    step="any">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="initialInvestment">@lang('admin.inputs.min_investment')<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="number" name="initialInvestment"
                                    value="{{ old('initialInvestment', @$property->initialInvestment) }}" required
                                    data-parsley-type="digits"
                                    data-parsley-required-message="Please enter @lang('admin.inputs.min_investment')" id="initialInvestment"
                                    placeholder="@lang('admin.placeholders.min_investment')" min="10" step="any"
                                    onkeyup="calculateTokenValue()">
                            </div>
                        </div>

                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="propertyEquityMultiple">@lang('admin.inputs.property_equity_multiple')</label>
                                <input class="form-control" type="number" name="propertyEquityMultiple" value="{{old('propertyEquityMultiple', @$property->propertyEquityMultiple)}}" id="propertyEquityMultiple" placeholder="@lang('admin.placeholders.property_equity_multiple')" step="any" data-parsley-type="digits" max="100">
                            </div>
                        </div> --}}

                        <div class="col-md-3">
                            <div class="form-group ">
                                <label for="holdingPeriod">Minimum Holding Period<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="number" name="holdingPeriod"
                                    value="{{ old('holdingPeriod', @$property->holdingPeriod) }}" required
                                    id="holdingPeriod" placeholder="Enter Minimum Holding Period"
                                    data-parsley-type="digits" min="1"
                                    data-parsley-required-message="Please enter @lang('admin.inputs.total_holding_period')" min="0"
                                    step="any">
                            </div>
                        </div>
                       @if($property->token_type == 1)
                        <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_sft">Total Sq Ft<span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="total_sft"
                                        value="{{ old('total_sft', @$property->total_sft) }}" data-parsley-type="digits"
                                        id="total_sft" placeholder="Enter Total Sq Ft" step="any" min="1"
                                        max="999999999" >
                                </div>
                            </div>
                       @endif
                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="total_sft">Divident</label>
                                <input class="form-control" type="number" name="dividend" required data-parsley-type="digits" data-parsley-required-message="Please enter divident" id="total_sft" placeholder="Please enter divident" step="any" min="0" max="999999999">
                            </div>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <h5>@lang('admin.inputs.overview_details')</h5>
                            <div class="form-group">
                                <label for="propertyOverview">@lang('admin.inputs.detail_overview') ( Max 10000 Characters )<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" type="text" name="propertyOverview" required
                                    data-parsley-required-message="Please enter @lang('admin.inputs.detail_overview')" id="propertyOverview"
                                    placeholder="@lang('admin.placeholders.detail_overview')" maxlength="10000">{{ @$property->propertyOverview }}</textarea>
                            </div>
                        </div>
                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="propertyLogo">Insert Video File ( Max: 5MB)<span class="text-danger">*</span></label>&nbsp; <i style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip" title="Only allows mp4 and x-m4v format">&#xf05a;</i>
                                <input class="form-control" type="file" name="propertyVideo" required data-parsley-required-message="Please upload video file" accept="video/mp4,video/x-m4v,video/*" id="propertyLogo" data-parsley-filemaxsize="10" data-parsley-trigger="change" data-parsley-filemimetypes="video/mp4, video/x-m4v" >
                            </div>
                        </div> --}}
                       @if($property->token_type == 1)
                        <div class="col-md-4">
                            <h5>@lang('admin.location_details')</h5>
                            <div class="form-group">
                                <label for="">@lang('admin.inputs.location_overview') ( Max 100000 Characters ) <span class="text-danger">
                                        *</span> </label>
                                <textarea class="form-control" type="text" name="propertyLocationOverview" id="propertyLocationOverview"
                                    placeholder="Enter Property Location Overview" required data-parsley-required-message="This field is required!"
                                    maxlength="100000">{{ @$property->propertyLocationOverview }}</textarea>
                            </div>
                        </div>
                       @endif
                    </div>

                    @if($property->token_type == 1)
                        <h5>@lang('admin.comparables')</h5>
                        <div class="row">
                            <div class="" id="comparables_section">
                                <div id="comparables_block_1" class="form-group row" style="padding:0px 17px">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">@lang('admin.inputs.property_address')</label>
                                            <input class="form-control" type="text" name="comparables[1][property]"
                                                value="{{ @$propertyComparable->property }}" id="property_1"
                                                placeholder="@lang('admin.inputs.property_address')" maxlength="150">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">Sale Date</label>
                                            <input class="form-control" type="date" name="comparables[1][type]"
                                                value="{{ @$propertyComparable->type }}" id="type_1"
                                                placeholder="Sale Date" max="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">Location</label>
                                            <input class="form-control" type="text" name="comparables[1][location]"
                                                value="{{ @$propertyComparable->location }}"
                                                value="{{ old('comparables[, @$property->comparables1][location]') }}"
                                                id="location_1" placeholder="Location" maxlength="300">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="yearofbuild">@lang('admin.inputs.yearofbuild')</label>
                                            <select class="form-control" name="comparables[1][distance]" id="yearofbuild">
                                                <option value="">Select</option>
                                                @for ($i = 1990; $i <= date('Y'); $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor

                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">@lang('admin.inputs.total_sft')</label>
                                            <input class="form-control" type="number" name="comparables[1][rent]"
                                                value="{{ @$propertyComparable->rent }}" id="rent_1"
                                                placeholder="@lang('admin.inputs.total_sft')">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">@lang('admin.inputs.price/sft')</label>
                                            <input class="form-control" type="number" name="comparables[1][saleprice]"
                                                value="{{ @$propertyComparable->saleprice }}" id="saleprice_1"
                                                placeholder="@lang('admin.inputs.price/sft')">
                                        </div>
                                    </div>


                                    {{-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="propertyLogo2">Property Logo ( Max: 5MB )&nbsp;&nbsp;<i
                                                    style="font-size:14px;cursor: pointer;" class="fa"
                                                    data-toggle="tooltip"
                                                    title="Only allows png/jpeg/jpg formats">&#xf05a;</i></label>
                                                    <a href="{{ @img(@$property->propertyLogo) }}"
                                                        target="_blank" rel="noopener noreferrer">View</a>
                                            <input class="form-control" type="file" name="propertylogoimage"
                                                accept="image/png,image/jpeg,image/jpg" id="propertyLogo2"
                                                placeholder="Please Choose Property Logo">
                                        </div>
                                    </div> --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="propertyMap">Map&nbsp;&nbsp;<i style="font-size:14px;cursor: pointer;"
                                                    class="fa" data-toggle="tooltip"
                                                    title="Only allows pdf format">&#xf05a;</i></label>
                                            <a href="{{ @img(@$PropertyComparable->map) }}" target="_blank"
                                                rel="noopener noreferrer">View</a>
                                            <input class="form-control" type="file" name="map"
                                                accept="application/pdf" id="propertyMap" placeholder="Enter Property Logo">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="propertyDetail">Comparables Details&nbsp;&nbsp;<i
                                                    style="font-size:14px;cursor: pointer;" class="fa"
                                                    data-toggle="tooltip" title="Only allows pdf format">&#xf05a;</i></label>
                                            <a href="{{ @img(@$PropertyComparable->comparabledetails) }}" target="_blank"
                                                rel="noopener noreferrer">View</a>
                                            <input class="form-control" type="file" name="comparabledetails"
                                                accept="application/pdf" id="propertyDetail"
                                                placeholder="Enter Property Logo">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {{-- <button type="button" class="btn btn-info" id="add_comparables">+ @lang('admin.addcomparables')</button> --}}
                        </div>
                    @endif
                    <br>
                    <h5>@lang('admin.doc/reports')</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.labels.investordos')<span
                                        class="text-danger">*</span>&nbsp;&nbsp;<i style="font-size:14px;cursor: pointer;"
                                        class="fa" data-toggle="tooltip"
                                        title="Only allows pdf format">&#xf05a;</i></label>
                                <a href="{{ @img(@$property->investor) }}" target="_blank"
                                    rel="noopener noreferrer">View</a>
                                <input class="form-control" type="file" name="investor"
                                    value="{{ old('investor', @$property->investor) }}" accept="application/pdf"
                                    id="propertyInvestor" data-parsley-required-message="Please choose Prospectus">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">Reports&nbsp;&nbsp;<i
                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                        title="Only allows pdf format">&#xf05a;</i></label>
                                <a href="{{ @img(@$property->titlereport) }}" target="_blank"
                                    rel="noopener noreferrer">View</a>
                                <input class="form-control" type="file" name="titlereport"
                                    value="{{ old('titlereport', @$property->titlereport) }}" accept="application/pdf"
                                    id="propertyTitlereport">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">Agreements&nbsp;&nbsp;<i
                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                        title="Only allows pdf format">&#xf05a;</i></label>
                                <a href="{{ @img(@$property->termsheet) }}" target="_blank"
                                    rel="noopener noreferrer">View</a>
                                <input class="form-control" type="file" name="termsheet" accept="application/pdf"
                                    id="termsheet">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">Additional Information&nbsp;&nbsp;<i
                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                        title="Only allows pdf format">&#xf05a;</i></label>
                                <a href="{{ @img(@$property->propertyUpdatedDoc) }}" target="_blank"
                                    rel="noopener noreferrer">View</a>
                                <input class="form-control" type="file" accept="application/pdf"
                                    name="propertyUpdatesDoc" id="propertyUpdatesDoc">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">Brochure <span
                                        class="text-danger">*</span>&nbsp;&nbsp;<i style="font-size:14px;cursor: pointer;"
                                        class="fa" data-toggle="tooltip"
                                        title="Only allows pdf format">&#xf05a;</i></label>
                                <a href="{{ @img(@$property->brochure) }}" target="_blank"
                                    rel="noopener noreferrer">View</a>
                                <input class="form-control" type="file" name="brochure" accept="application/pdf"
                                    id="brochure" required data-parsley-required-message="Please choose Brochure">
                            </div>
                        </div>
                    </div>

                    

                    <div class="row">
                        <div class="col-md-3 d-none">
                            <div class="form-group">
                                <label for="" class="col-form-label">Management Team&nbsp;&nbsp;<i
                                        style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                        title="Only allows pdf format">&#xf05a;</i></label>
                                <a href="{{ @img(@$property->propertyManagementTeam) }}" target="_blank"
                                    rel="noopener noreferrer">View</a>
                                <input class="form-control" type="file" accept="application/pdf"
                                    name="propertyManagementTeam" id="propertyManagementTeam">
                            </div>
                        </div>
                    </div>

                    @if($property->token_type == 1)
                        <h5>@lang('admin.property_details')</h5>
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="show_property">Show Property Details<span class="text-danger">*</span></label>
                                    <select class="form-control" name="show_property" id="show_property" required>
                                        <option value="">Select</option>
                                        <option value="yes" {{ @$property->show_property == 'yes' ? 'selected' : null }}>
                                            yes</option>
                                        <option value="no" {{ @$property->show_property == 'no' ? 'selected' : null }}>no
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="col-form-label">@lang('admin.inputs.locality')<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="locality" id="locality"
                                        value="{{ old('locality', @$property->locality) }}" placeholder="@lang('admin.placeholders.locality')"
                                        required maxlength="600" data-parsley-required-message="Please enter locality">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="col-form-label">@lang('admin.inputs.year_of_build') <span
                                            class="text-danger"> *</span></label>
                                    <select class="form-control" name="yearOfConstruction" id="yearofbuild" required
                                        data-parsley-required-message="Please choose @lang('admin.inputs.yearofbuild')">
                                        <option value="">Select</option>
                                        @for ($i = 1990; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}"
                                                {{ @$property->yearOfConstruction == $i ? 'selected' : null }}>
                                                {{ $i }}</option>
                                        @endfor

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="col-form-label">@lang('admin.inputs.storeys')<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="storeys" required id="storeys"
                                        value="{{ @$property->storeys }}" placeholder="@lang('admin.placeholders.storeys')" min="0">
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="col-form-label">@lang('admin.inputs.parking')<span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="propertyParking"
                                        value="{{ @$property->propertyParking }}" required id="propertyParking"
                                        placeholder="Number of Bedrooms" min="0">
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="col-form-label">Number of Bathrooms <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="floorforSale"
                                        value="{{ @$property->floorforSale }}" required id="floorforSale"
                                        placeholder="Number of Bathrooms" min="0">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="col-form-label">@lang('admin.inputs.total_area')</label>
                                    <input class="form-control" type="number" name="propertyTotalBuildingArea"
                                        id="propertyTotalBuildingArea"
                                        value="{{ old('propertyTotalBuildingArea', @$property->propertyTotalBuildingArea) }}"
                                        placeholder="Enter Total Square Feet" min="0">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="col-form-label">@lang('admin.inputs.details_highlight') ( Max 100000 Characters
                                        )</label>
                                    <textarea class="form-control" type="text" name="propertyDetailsHighlights"
                                        value="{{ old('propertyDetailsHighlights', @$property->propertyDetailsHighlights) }}"
                                        id="propertyDetailsHighlights" placeholder="@lang('admin.placeholders.details_highlight')"
                                        data-parsley-required-message="Please enter Property Highlights" maxlength="100000"></textarea>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="col-form-label">@lang('admin.placeholders.floorplan')&nbsp;&nbsp;<i
                                            style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                            title="Only allows pdf format">&#xf05a;</i></label>
                                    <a href="{{ @img(@$property->floorplan) }}" target="_blank"
                                        rel="noopener noreferrer">View</a>
                                    <input class="form-control" type="file" name="floorplan" accept="application/pdf"
                                        id="propertyFloorPlan">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="airport">@lang('admin.property.airport')</label>
                                    <input type="text" class="form-control" type="text" name="airport"
                                        value="{{ old('airport', @$property->airport) }}"
                                        data-parsley-required-message="Please enter @lang('admin.property.airport')" id="airport"
                                        placeholder="Nearest Airport" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="hospitals">@lang('admin.property.hospitals')</label>
                                    <input class="form-control" type="text" name="hospitals"
                                        value="{{ old('hospitals', @$property->hospitals) }}"
                                        data-parsley-required-message="Please enter @lang('admin.property.hospitals')" id="hospitals"
                                        placeholder="Nearest Hospital" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fire_services">@lang('admin.property.fire_services')</label>
                                    <input class="form-control" type="text" name="fire_services" id="fire_services"
                                        value="{{ old('fire_services', @$property->fire_services) }}"
                                        placeholder="Nearest Fire Service" maxlength="100">
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="slums">@lang('admin.property.slums')</label>
                                    <input class="form-control" type="text" name="slums" id="slums" required data-parsley-required-message="Please enter @lang('admin.property.slums')" placeholder="@lang('admin.property.slums')" maxlength="300">
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="industrial">@lang('admin.property.industrial')</label>
                                    <input class="form-control" type="text" name="industrial" id="industrial"
                                        value="{{ old('industrial', @$property->industrial) }}"
                                        placeholder="Nearest Industrial Area" maxlength="100">
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="railway_tracks">@lang('admin.property.railway_tracks')</label>
                                    <input class="form-control" type="text" name="railway_tracks" required data-parsley-required-message="Please enter @lang('admin.property.railway_tracks')" id="railway_tracks" placeholder="@lang('admin.property.railway_tracks')" maxlength="300">
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="distance_fm_mainroad">@lang('admin.property.distance_fm_mainroad')</label>
                                    <input class="form-control" type="text" name="distance_fm_mainroad" id="distance_fm_mainroad" required data-parsley-required-message="Please enter @lang('admin.property.distance_fm_mainroad')" placeholder="@lang('admin.property.distance_fm_mainroad')" maxlength="300">
                                </div>
                            </div> --}}
                        </div>
                    @endif
                    <h5>Token Details</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.coin.tokenname')<span
                                        class="text-danger"></span></label>
                                <input class="form-control allowAlphaSpace" type="text" name="token_name"
                                    value="{{ old('token_name', @$property->token_name) }}" required id="tokenName"
                                    placeholder="@lang('admin.coin.tokenname')"
                                    data-parsley-required-message="Please enter Token Name" maxlength="100"   
                                    {{ $isEditable ? '' : 'disabled' }}>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.coin.tokensymbol') <span
                                        class="text-danger"></span><i style="font-size:14px;cursor: pointer;"
                                        class="fa" data-toggle="tooltip"
                                        title="Only 4 characters are allowed ">&#xf05a;</i></label>
                                <input class="form-control allowAlphaOnly" type="text" name="token_symbol"
                                    value="{{ old('token_symbol', @$IssuerTokenRequest->symbol) }}" required
                                    id="tokenSymbol" placeholder="Eg: ABCD"
                                    data-parsley-required-message="Please enter Token Symbol" maxlength="4" 
                                    {{ $isEditable ? '' : 'disabled' }}>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.coin.tokenvalue')
                                    ({{ Setting::get('default_currency') }}) <span class="text-danger"></span></label>
                                <input class="form-control" type="number" name="token_value"
                                    value="{{ old('token_value', @$IssuerTokenRequest->usdvalue) }}" id="tokenValue"
                                    placeholder="Token Value" step="any" min="1" 
                                    {{ $isEditable ? '' : 'disabled' }}>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.coin.tokensupply') <span
                                        class="text-danger"></span></label>
                                <input class="form-control" type="number" value="{{ @$IssuerTokenRequest->supply }}"
                                    required id="tokenSupply" name="token_supply" placeholder="Token Supply" 
                                {{ $isEditable ? '' : 'disabled' }}>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.coin.tokendecimal') <span
                                        class="text-danger"></span></label>
                                <input class="form-control" type="number" name="token_decimal"
                                    value="{{ old('token_decimal', @$IssuerTokenRequest->decimal) }}" required
                                    id="tokenDecimal" placeholder="Eg: 12"
                                    data-parsley-required-message="Please enter Token Decimal" maxlength="2"
                                    min="1" max="99"   
                                    {{ $isEditable ? '' : 'disabled' }}>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.token_to_be_issued') <span
                                        class="text-danger"></span></label>
                                <br>
                                <input type="radio" name="tokentype" class="tokentype" value="ERC20" checked>
                                @lang('admin.erc20_utility')<br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="col-form-label">@lang('admin.coin.tokenimage') <span
                                        class="text-danger"></span>&nbsp;&nbsp;<i style="font-size:14px;cursor: pointer;"
                                        class="fa" data-toggle="tooltip"
                                        title="Only allows png/jpeg format">&#xf05a;</i></label>
                                <a href="{{ @img(@$IssuerTokenRequest->token_image) }}" target="_blank"
                                    rel="noopener noreferrer">View</a>
                                <input class="form-control" type="file" name="token_image" id="tokenImage"
                                    accept="image/png,image/jpeg,image/jpg"
                                    data-parsley-required-message="Please upload Token Image" 
                                    {{ $isEditable ? '' : 'disabled' }}>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            @if($isEditable)
                                <div class="form-group">
                                    <label for="tokenChain" class="col-form-label">Select Chain<span class="text-danger">*</span></label>
                                    <select class="form-control allowAlphaSpace" name="token_chain" id="tokenChain" {{ $isEditable ? '' : 'disabled' }}>
                                        <option value="">Select Chain</option>
                                        @foreach($blockchains as $blockchain)
                                            <option 
                                                value="{{ $blockchain->id }}" 
                                                {{ $property->blockchain_id === $blockchain->id ? 'selected' : '' }}
                                            >
                                                {{ $blockchain->blockchain_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span id="tokenChainError" class="text-danger" style="display: none;">* Please select a Token Chain </span>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="tokenChain" class="col-form-label">
                                        Select Chain <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="tokenChain"  {{ $isEditable ? '' : 'disabled' }}
                                       value="{{ $property->blockchain->blockchain_name  }}"
                                        readonly>
                                </div>
                            @endif

                        </div>
                        
                       
                        <div class="col-md-4 mb-4">
                            @if($isEditable)
                                <div class="form-group">
                                    <label for="keystoreSelect" class="col-form-label">
                                        @lang('admin.keystore') <span class="text-danger">*</span>
                                    </label>
                                    
                                    <select class="form-control" name="keystore_id" id="keystoreSelect" required data-url="{{ url('api/balance')}}">
                                        <option value="">-- Select a Keystore --</option>
                                        @foreach($keystores as $id => $keystore)
                                            <option value="{{ $keystore->id }}"
                                                    data-public-address="{{ $keystore->public_address }}"
                                                    {{ $property->keystore_id == $keystore->id ? 'selected' : '' }}>
                                                {{ $keystore->title }}
                                            </option>
                                        @endforeach
                                    
                                    </select>
                                    <div id="keystoreError" class="text-danger mt-2" style="display: none;">* Insufficient balance</div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="keystoreSelect" class="col-form-label">
                                        @lang('admin.keystore') <span class="text-danger">*</span>
                                    </label>
                            
                                    <input type="text" class="form-control" id="keystoreSelect" 
                                           value="{{ $property->keystore->title ?? 'No keystore selected' }}" 
                                           readonly>
                                </div>
                            @endif
                        </div>
                        

                    </div>

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
                                    <input type="radio" name="enable_internal_wallet" id="enableWalletYes" class="form-check-input" value="1"
                                        {{ @$data['property']->enable_internal_wallet == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enableWalletYes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="enable_internal_wallet" id="enableWalletNo" class="form-check-input" value="0"
                                        {{ @$data['property']->enable_internal_wallet == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enableWalletNo">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    --}}
                    <input type="hidden" name="enable_internal_wallet" value="0">
                    


                    <h5>Management Team</h5>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="" class="col-form-label">Management Team Description (Max 100000
                                Characters)</label>
                                <textarea class="form-control" name="ManagementTeamDescription" id="propertyOverview"
                                placeholder="Enter Management Team Description" maxlength="100000"
                                style="width: 100%;" rows="6">{{ @$property->ManagementTeamDescription }}</textarea>
                            
                        </div>
                    </div>
                    {{-- <h6>Management Members</h6> --}}
                    {{-- <div id="divManagementMembers" data-id="0"></div> --}}
                    {{-- <div class="row" id="MemberBlock_">
                        <div class="col-md-3 form-group">
                            <label for="" class="col-form-label">Member Name</label>
                            <input class="form-control" type="text" name="member[0][name]" id="MemberName_"
                                value="{{ @$ManagementMembers->memberName }}" placeholder="Enter Member Name"
                                maxlength="50">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="" class="col-form-label">Member Position</label>
                            <input class="form-control" type="text" name="member[0][position]" id="MemberPosition_"
                                value="{{ @$ManagementMembers->memberPosition }}" placeholder="Enter Member Position"
                                maxlength="50">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="" class="col-form-label">Member Image&nbsp;&nbsp;<i
                                    style="font-size:14px;cursor: pointer;" class="fa" data-toggle="tooltip"
                                    title="Only allows png/jpeg format">&#xf05a;</i></label>
                            <a href="{{ @img(@$ManagementMembers->memberPic) }}" target="_blank"
                                rel="noopener noreferrer">View</a>
                            <input class="form-control" type="file" name="member[0][pic]" id="MemberPic_"
                                accept="image/png,image/jpeg" placeholder="Select Member Picture">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="" class="col-form-label">Member Description</label>
                            <input class="form-control" type="text" name="member[0][description]"
                                value="{{ @$ManagementMembers->memberDescription }}" id="MemberDescription_0"
                                maxlength="300" placeholder="Enter Description">
                        </div>

                    </div> --}}
                    {{-- <button type="button" class="btn btn-info" id="AddMember">+ Add Member</button> --}}
                    <!-- end row -->

                    {{-- <h5>Updates</h5>
                    <div id="updates" data-id="0"></div> --}}
                    {{-- <button type="button" class="btn btn-info" id="Addupdates">+ Add Updates</button> --}}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">
                                    @lang('admin.placeholders.propertyimage')
                                    &nbsp;&nbsp;
                                    <i class="fa" style="font-size:14px; cursor: pointer;" data-toggle="tooltip" title="Only png/jpeg allowed">&#xf05a;</i>
                                </label>
                            
                                @if(!empty($propertyImages) && $propertyImages->count())
                                    <div class="mb-3">
                                        <p><strong>Existing Images:</strong></p>
                                        <div class="row" id="image-container">
                                            @foreach($propertyImages as $image)
                                                <div class="col-md-3 text-center mb-2 image-box" id="image-{{ $image->id }}">
                                                    <a href="{{ @img($image->image) }}" target="_blank" rel="noopener noreferrer">
                                                        <img src="{{ @img($image->image) }}" class="img-fluid" style="max-height: 120px;" />
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-image" 
                                                            data-id="{{ $image->id }}">
                                                        <i class="fa fa-times"></i> Remove
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div> 
                        </div>
                        <div class="col-md-3">
                            {{-- Upload new images --}}
                            <input class="form-control" type="file" name="propertyimages[]" id="propertyimages" accept="image/png,image/jpeg,image/jpg" multiple data-parsley-required-message="Please Upload Property Images">
                            
                            {{-- Placeholder for removed image IDs --}}
                            <input type="hidden" name="removed_image_ids" id="removed_image_ids" />
                        </div>
                    </div>

                    <div class="row next-btn" style="padding:25px 0px;">
                        <div class="col-sm-12">
                            <div class="form-group text-center mb-0">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light mr-1 createTokenButton">Update
                                    Token</button>
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
                 document.addEventListener('DOMContentLoaded', function () {
                    const keystoreSelect = document.getElementById('keystoreSelect');
                    const tokenChainSelect = document.getElementById("tokenChain");
                    const tokenChainError = document.getElementById("tokenChainError");
                    const keystoreError = document.getElementById("keystoreError");

                    keystoreSelect.addEventListener('change', function () {
                        const selectedOption = this.options[this.selectedIndex];
                        const selectedValue = this.value;
                        const publicAddress = selectedOption.getAttribute('data-public-address');

                        console.log('Selected Keystore ID:', selectedValue);
                        console.log('Public Address:', publicAddress);


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
                            console.log(selectedOption,publicAddress,apiBaseUrl);
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

                        checkBalance()
                    });
                });
            </script>
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
                    console.log(parseInt(minimumField.val()))
                    if (parseInt(minimumField.val()) > totalDealSize) {
                        formButton.attr('disabled', true)
                        minimumField.after(
                            "<span class='text-danger'>* Minimum Investment should not be greater than Total Deal Size *</span>"
                        )
                    }
                    // $('.cal-mismatch').remove()
                    // if(tokenValue <= 0){
                    //     formButton.attr('disabled', true);
                    //     $('#total_sft').after("<span class='text-danger cal-mismatch'>* Total Sq.ft is too High *</span>")
                    // }
                    // tokenField.val(tokenValue)
                    // totalSupplyField.val(parseInt(totalSqt))
                }
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
                                required: false,
                                maxlength: 320,
                            },
                            "propertyLogo": {
                                required: false,
                                accept: 'image/jpeg, image/png, image/jpg',
                            },
                            "propertyType": {
                                required: false,
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
                                required: true,
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
                                number: true,
                                min: 1,
                                max: 100
                            },
                            "total_sft": {
                                required: false,
                                number: true,
                                min: 1,
                                max: 100000000000,
                            },
                            "propertyOverview": {
                                required: true,
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
                                required: false,
                                maxlength: 600,
                            },
                            "yearOfConstruction": {
                                required: false,
                                number: true,
                                min: 0,
                                maxlength: 4,
                            },
                            "storeys": {
                                required: false,
                                max: 100000000000,
                            },
                            "propertyParking": {
                                required: false,
                                min: 1,
                                max: 100000000000,
                            },
                            "floorforSale": {
                                required: false,
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
                                maxlength: 300
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
                                required: false,
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
                                accept: 'image/jpeg, image/png, image/jpg, image',
                            },
                            "propertyVideo": {
                                required: false,
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
                                minlength: 3,
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
                                // min: 1000,
                                max: 100000000000,
                            },
                            "token_decimal": {
                                required: true,
                                // digits: true,
                                maxlength: 2,
                            },
                            "ManagementTeamDescription": {
                                maxlength: 100000
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
                                required: "Please Choose Updates Report",
                                accept: "Please Upload Valid format (pdf)",
                            },
                            "map": {
                                required: "Please Choose Term Sheet",
                                accept: "Please Upload Valid format (pdf)",
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
                    const removedImageIds = [];
            
                    document.querySelectorAll('.remove-image').forEach(button => {
                        button.addEventListener('click', function () {
                            const imageId = this.dataset.id;
            
                            if (confirm('Are you sure you want to remove this image?')) {
                                removedImageIds.push(imageId);
                                document.getElementById(`image-${imageId}`).remove();
                                document.getElementById('removed_image_ids').value = removedImageIds.join(',');

                                $('.createTokenButton').click();
                            }
                        });
                    });

                    document.getElementById('propertyimages').addEventListener('change', function () {
                        $('.createTokenButton').click();
                    });
                });
            </script>
            
        @endsection
