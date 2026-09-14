@php
    $countryCodes = getCountyCode();
    $username = explode(' ',$user->name);
@endphp
@extends('layout.app')

@section('content')
<!-- Include Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<style>
    /* .nice-select .list { overflow-y: scroll; height: 200px; } */
    .form-section-div,
    .form-section-div1 {
        display: none;
    }

    .parsley-errors-list{
        color: #FF0000;
    }

    .form-section-div.current,
    .form-section-div1.current {
        display: block;
    }

    .d-none {
        display: none;
    }

    .d-block {
        display: block;
    }

    .justify-content-center {
        justify-content: center;
    }

    .m-auto {
        margin: auto;
    }

    .text-center {
        text-align: center;
    }

    .v-none {
        opacity: 0;
    }
</style>

<!-- Breadcrumb -->
<div class="page-content">
    <div class="header-breadcrumbs">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h1>Profile</h1></div>
                <div class="col-sm-6">
                    <div class="breadcrumb-four" style="text-align: right;">
                        <ul class="breadcrumb">
                            <li>
                                <a href="{{url('/dashboard')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                    <span>Home</span>
                                </a>
                            </li>
                            <li class="active">
                                <a href="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu">
                                        <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
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
                                    <span>Profile</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <!-- Start container-fluid -->
        <div class="container-fluid wizard-border">
            <!-- Property Tab Starts -->
            <div class="nav-align-top nav-tabs-shadow">
                <ul class="nav nav-tabs" role="tablist">
                    @if ($user->account_type == 'individual')
                        <li class="active nav-item">
                            <button class="nav-link active"  data-bs-toggle="tab" data-bs-target="#identity" type="button" role="tab" aria-controls="identity" aria-selected="true">
                                Individual
                            </button>
                        </li>
                    @else
                        <li class="active nav-item">
                            <button class="nav-link active"  data-bs-toggle="tab" data-bs-target="#company-user" type="button" role="tab" aria-controls="company-user" aria-selected="true">
                                Business entity
                            </button>
                        </li>
                    @endif
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kyc_tab" aria-controls="kyc_tab" aria-selected="true">
                            KYC
                        </button>
                    </li>
                    <li class="nav-item @if (Session::get('check_url') == '/change/password') active @endif">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#password_tab" aria-controls="password_tab" aria-selected="true">
                            Change password
                        </button>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade @if ($user->account_type == 'individual') active in @endif" id="identity">
                        <div class="row">
                            <div class="col-md-12">
                                <form role="form" action="{{ route('profile.identity') }}" method="POST"
                                    enctype="multipart/form-data" id="identity-form">
                                    @csrf()
                                    <div class="form-section-div">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">First Name <span class="text-danger">
                                                            *</span></label>
                                                    <input maxlength="30" type="text" required class="form-control"
                                                        placeholder="Enter First Name" name="first_name"
                                                        value="{{ @$username[0] }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Last Name <span class="text-danger">
                                                            *</span></label>
                                                    <input maxlength="30" type="text" required class="form-control"
                                                        placeholder="Enter Last Name" name="last_name"
                                                        value="{{ @$username[1] }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Date of Birth <span class="text-danger">*</span></label>
                                                    <input
                                                        type="text"
                                                        id="dob"
                                                        name="dob"
                                                        required
                                                        class="form-control"
                                                        placeholder="Date of Birth"
                                                        value="{{ @$user->identity->dob ? date('Y-m-d', strtotime(@$user->identity->dob)) : '' }}"
                                                    />
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Country of Citizenship <span
                                                            class="text-danger"> *</span></label>
                                                    <input maxlength="100" type="text" required="required"
                                                        class="form-control" placeholder="Citizenship"
                                                        name="citizenship"
                                                        value="{{ @$user->identity->citizenship }}" />
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Postal Code <span
                                                            class="text-danger"> *</span></label>
                                                            <input type="text"
                                                                class="form-control"
                                                                name="postal_code"
                                                                maxlength="20"
                                                                required
                                                                value="{{ @$user->identity->postal_code }}"
                                                                placeholder="Postal Code">
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Primary Phone <span class="text-danger">*</span></label>
                                                    <div class="input-group" style="display: flex; flex-wrap: nowrap; gap: 8px;">
                                                        <select class="form-control" name="primary_country_code" style="flex: 1;">
                                                            @foreach($countryCodes as $country)
                                                                <option value="{{ $country['dial_code'] }}"
                                                                    {{ @$user->identity->primary_country_code == $country['dial_code'] ? 'selected' : '' }}>
                                                                    {{ $country['name'] }} ({{ $country['dial_code'] }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input maxlength="10" type="text" class="form-control"
                                                                placeholder="Enter Primary Phone"
                                                                onkeypress="if (isNaN(this.value + String.fromCharCode(event.keyCode))) return false;"
                                                                name="primary_phone"
                                                                value="{{ @$user->identity->primary_phone }}" required
                                                                style="flex: 1;" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Secondary Phone <span class="text-danger">*</span></label>
                                                    <div class="input-group" style="display: flex; flex-wrap: nowrap; gap: 8px;">
                                                        <select class="form-control" name="secondary_country_code" style="flex: 1;">
                                                            @foreach($countryCodes as $country)
                                                                <option value="{{ $country['dial_code'] }}"
                                                                    {{ @$user->identity->secondary_country_code == $country['dial_code'] ? 'selected' : '' }}>
                                                                    {{ $country['name'] }} ({{ $country['dial_code'] }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input maxlength="10" type="text" class="form-control"
                                                                placeholder="Enter Secondary Phone"
                                                                onkeypress="if (isNaN(this.value + String.fromCharCode(event.keyCode))) return false;"
                                                                name="secondary_phone"
                                                                value="{{ @$user->identity->secondary_phone }}" style="flex: 1;" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-xs-12 col-sm-6">


                                                <div class="form-group">
                                                    <label class="control-label">Address Line 1 <span
                                                            class="text-danger"> *</span></label>
                                                    <input maxlength="200" type="text" class="form-control"
                                                        placeholder="Address 1" name="address_line_1" required
                                                        value="{{ @$user->identity->address_line_1 }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Address Line 2 <span
                                                            class="text-danger"> *</span></label>
                                                    <input maxlength="200" type="text" class="form-control"
                                                        placeholder="Address 2" name="address_line_2" required
                                                        value="{{ @$user->identity->address_line_2 }}" />
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Country <span class="text-danger">
                                                            *</span></label>
                                                    <select required="required" class="form-control country"
                                                        placeholder="Country" name="country_code">
                                                        @foreach (countries() as $country)
                                                            <option value="{{ $country->code }}"
                                                                @if (@$user->identity->country_code == $country->code) selected @endif>
                                                                {{ $country->countryname }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Country of Residence <span
                                                            class="text-danger"> *</span></label>
                                                    <input maxlength="100" type="text" required="required"
                                                        class="form-control allowAlphaOnly" placeholder="Residence"
                                                        name="residence" value="{{ @$user->identity->residence }}" />
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Email <span class="text-danger">
                                                            *</span></label>
                                                    <input maxlength="50" type="text" class="form-control"
                                                        name="email" value="{{ @$user->email }}" readonly />
                                                </div>

                                                <div class="form-group city_select_drop">
                                                    <label class="control-label">City <span class="text-danger">
                                                            *</span></label>
                                                    <select class="form-control city" placeholder="City"
                                                        name="city_id">
                                                        {{-- Cities for the saved country. cities() with no argument lists
                                                             Afghanistan's, and the script below only reloads the list when
                                                             the country changes, so a saved city elsewhere never showed. --}}
                                                        @foreach (cities(@$user->identity->country_code ?: 'AF') as $city)
                                                            <option value="{{ $city->id }}"
                                                                @if (@$user->identity->city_id == $city->id) selected @endif>
                                                                {{ $city->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Province <span class="text-danger">
                                                            *</span></label>
                                                    <input maxlength="100" type="text" required="required"
                                                        class="form-control allowAlphaOnly" placeholder="Province"
                                                        name="province" value="{{ @$user->identity->province }}" />
                                                </div>


                                            </div>



                                        </div>

                                        <div class="col-xs-12 list-inline pull-right">
                                            <ul class="list-inline pull-right">
                                                <li>
                                                    <button type="button"
                                                        class="btn2 btn prev-step btn1 btn2">Previous</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn btn1 btn2 next-step">Next</button>
                                                </li>
                                                {{-- <li>
                                                    <button type="submit"
                                                        class="btn2 btn btn-primary btn1 btn2 btn-info-full form-sub">Submit</button>
                                                </li> --}}
                                            </ul>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group text-left mb-0">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>

                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>


                    <div role="tabpanel" class="tab-pane fade @if ($user->account_type == 'company') active in @endif" id="company-user">
                        <div class="row">
                            <div class="col-md-12">
                                <form role="form" action="{{ url('/usercompanydetail') }}" method="POST"
                                    enctype="multipart/form-data" id="company-user1">
                                    @csrf()
                                    <div class="form-section-div1">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">Company Name <span
                                                            class="text-danger"> *</span></label>
                                                    <input maxlength="30" type="text" required
                                                        class="form-control" placeholder="Enter Company Name"
                                                        name="company_name"
                                                        value="{{ @$user->userCompany->company_name }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Date of Found<span
                                                            class="text-danger"> *</span></label>
                                                    <input maxlength="20" type="date" required="required"
                                                        class="form-control" placeholder="Date of Found"
                                                        name="company_date"
                                                        value="{{ @$user->userCompany->date_founded }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Head Quarters<span
                                                            class="text-danger"> *</span></label>
                                                    <input maxlength="100" type="text" required="required"
                                                        class="form-control" placeholder="Head Quarters"
                                                        name="headquarters"
                                                        value="{{ @$user->userCompany->headquarters }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Signing Authority<span
                                                            class="text-danger"> *</span></label>
                                                    @if (@$user->userCompany->signing_authority)
                                                        {{-- <img src="{{ img(@$user->userCompany->signing_authority) }}"
                                                            width="50" class="popImage"> --}}
                                                        &nbsp;&nbsp;&nbsp;<a
                                                            href="{{ @img(@$user->userCompany->signing_authority) }}"
                                                            target="_blank" rel="noopener noreferrer">View</a>
                                                        <input type="file" class="form-control"
                                                            name="signing_authority"
                                                            accept="image/jpeg,image/jpg,image/png" />
                                                    @else
                                                        <input type="file" class="form-control"
                                                            name="signing_authority"
                                                            accept="image/jpeg,image/jpg,image/png" required />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">Team Size<span class="text-danger">
                                                            *</span></label>
                                                    <input max="1000000" type="number" required="required"
                                                        class="form-control" placeholder="Team Size" name="team_size"
                                                        value="{{ @$user->userCompany->team_size }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Home Page<span class="text-danger">
                                                            *</span></label>
                                                    <input maxlength="50" type="text" required="required"
                                                        class="form-control" placeholder="Home Page"
                                                        name="company_url"
                                                        value="{{ @$user->userCompany->company_url }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Social Channels</label>
                                                    <h5>Please mention all the social links below, separate them with
                                                        newlines</h5>
                                                    <textarea name="social_channels" id="" cols="30" rows="10" maxlength="300">{{ @$user->userCompany->social_channels }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-section-div1">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 country_select_drop">
                                                <div class="form-group">
                                                    <label class="control-label">Certificate of Incorporation<span
                                                            class="text-danger"> *</span></label>
                                                    @if (@$user->userCompany->incorporation_certificate)
                                                        {{-- <img src="{{ img(@$user->userCompany->incorporation_certificate) }}"
                                                            width="50" class="popImage"> --}}
                                                        &nbsp;&nbsp;&nbsp;<a
                                                            href="{{ @img(@$user->userCompany->incorporation_certificate) }}"
                                                            target="_blank" rel="noopener noreferrer">View</a>
                                                        <input type="file" class="form-control"
                                                            name="incorporation_certificate"
                                                            accept="image/jpeg,image/jpg,image/png,application/pdf" />
                                                    @else
                                                        <input type="file" class="form-control"
                                                            name="incorporation_certificate"
                                                            accept="image/jpeg,image/jpg,image/png,application/pdf"
                                                            required />
                                                    @endif

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Partnership deed<span
                                                            class="text-danger"> *</span></label>
                                                    @if (@$user->userCompany->partnership_deed)
                                                        {{-- <img src="{{ img(@$user->userCompany->partnership_deed) }}"
                                                            width="50" class="popImage"> --}}
                                                        &nbsp;&nbsp;&nbsp;<a
                                                            href="{{ @img(@$user->userCompany->partnership_deed) }}"
                                                            target="_blank" rel="noopener noreferrer">View</a>
                                                        <input type="file" class="form-control"
                                                            name="partnership_deed"
                                                            accept="image/jpeg,image/jpg,image/png,application/pdf" />
                                                    @else
                                                        <input type="file" class="form-control"
                                                            name="partnership_deed"
                                                            accept="image/jpeg,image/jpg,image/png,application/pdf"
                                                            required />
                                                    @endif

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Trust deed<span class="text-danger">
                                                            *</span></label>
                                                    @if (@$user->userCompany->trust_deed)
                                                        {{-- <img src="{{ img(@$user->userCompany->trust_deed) }}"
                                                            width="50" class="popImage"> --}}
                                                        &nbsp;&nbsp;&nbsp;<a
                                                            href="{{ @img(@$user->userCompany->trust_deed) }}"
                                                            target="_blank" rel="noopener noreferrer">View</a>
                                                        <input type="file" class="form-control" name="trust_deed"
                                                            accept="image/jpeg,image/jpg,image/png,application/pdf" />
                                                    @else
                                                        <input type="file" class="form-control" name="trust_deed"
                                                            accept="image/jpeg,image/jpg,image/png,application/pdf"
                                                            required />
                                                    @endif

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Certificate from the register of a
                                                        societies<span class="text-danger"> *</span></label>
                                                    @if (@$user->userCompany->register_socities)
                                                        {{-- <img src="{{ img(@$user->userCompany->register_socities) }}"
                                                            width="50" class="popImage"> --}}
                                                        &nbsp;&nbsp;&nbsp;<a
                                                            href="{{ @img(@$user->userCompany->register_socities) }}"
                                                            target="_blank" rel="noopener noreferrer">View</a>
                                                        <input type="file" class="form-control"
                                                            name="register_socities"
                                                            accept="image/jpeg,image/jpg,image/png,application/pdf" />
                                                    @else
                                                        <input type="file" class="form-control"
                                                            name="register_socities"
                                                            accept="image/jpeg,image/jpg,image/png,application/pdf"
                                                            required />
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-xs-12 list-inline pull-right">
                                        <ul class="list-inline pull-right">
                                            <li>
                                                <button type="button"
                                                    class="btn2 prev-step1 btn1 btn2">Previous</button>
                                            </li>
                                            <li>
                                                <button type="button" class=" btn1 btn2 next-step1">Next</button>
                                            </li>
                                            <li>
                                                <button type="submit"
                                                    class="btn2 btn-primary btn1 btn2 btn-info-full form-sub1">Submit</button>
                                            </li>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php $proofs = \App\Document::whereNotIn(
                        'id',
                        $kyc_doc
                            ->where('user_id', auth()->id())
                            ->pluck('accredited_document_id')
                            ->toArray()
                    )->get(); ?>
                    <div role="tabpanel" class="tab-pane fade" id="kyc_tab">
                            <div class="row mt-5 justify-content-center g-4">

                                <!-- Address Proof -->
                                <div class="col-md-5">
                                    <div class="card border border-light shadow-sm h-100">
                                        <div class="card-body text-center p-4">
                                            <div class="mb-3">
                                                <i class="bi bi-geo-alt-fill fs-2 text-primary"></i>
                                            </div>
                                            <h6 class="card-title text-secondary fw-semibold mb-3">Proof of address</h6>
                                            @if (!empty($user->issuer_kyc_doc))
                                                <a href="{{ $user->issuer_kyc_doc }}" target="_blank" class="btn btn-outline-primary btn-sm px-3">
                                                    <i class="bi bi-file-earmark-pdf me-2"></i> View Document
                                                </a>
                                            @else
                                                <p class="text-muted small">No document uploaded</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Issuer Identification Document -->
                                <div class="col-md-5">
                                    <div class="card border border-light shadow-sm h-100">
                                        <div class="card-body text-center p-4">
                                            <div class="mb-3">
                                                <i class="bi bi-person-vcard fs-2 text-success"></i>
                                            </div>
                                            <h6 class="card-title text-secondary fw-semibold mb-3"> Identification Document</h6>
                                            @if (!empty($user->issuer_pros_doc))
                                                <a href="{{ $user->issuer_pros_doc }}" target="_blank" class="btn btn-outline-primary btn-sm px-3">
                                                    <i class="bi bi-file-earmark-pdf me-2"></i> View Document
                                                </a>
                                            @else
                                                <p class="text-muted small">No document uploaded</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>

                        <form role="form" action="{{ route('kyc-upload') }}" method="POST"
                            enctype="multipart/form-data" id="company-">
                            @csrf()
                            <div class="row">
                                @if (@$user->userCompany->team_size)
                                    <input type="hidden" name="updateKYC" value="1">
                                @endif
                            </div>

                            <div class="form-section">
                                @if ($proofs->isNotEmpty())
                                    <div class="row justify-content-between">

                                        <div class="col-xs-6 col-md-6">
                                            <h5>KYC Verification</h5>
                                            <div class="form-group">
                                                <label for="name">Choose Evidence Type</label>
                                                <select class="form-control company_accredited_kyc_select" name="accredited_kyc_select" id="company_accredited_kyc_select" required>
                                                    <option value="">Choose Document</option>
                                                    @forelse ($accredited_documents as $p => $value)
                                                        <option value="{{ $value->id }}" data-image-src="{{ $value->image }}">
                                                            {{ $value->name }}{{ $value->mandatory == '1' ? ' ( Mandatory )' : ' ( Optional )' }}
                                                        </option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-4">
                                            <img id="document-image" src="" alt="Selected Document Image" style="display:none;width:50%;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{-- @foreach ($accredited_documents as $value) --}}
                                        <div class="company_accredited_kyc_upload"
                                            id="company_accredited_kyc_upload_">
                                            <label>KYC Documents</label>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="">Front Side</label>
                                            <input type="file" id="file_front" name="image" required
                                                accept="image/*" />
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Back Side</label>
                                            <input type="file" id="file_back" name="back_image" required
                                                accept="image/*" />
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn2 btn-primary btn1 btn2 btn-info-full">Submit</button>
                                        </div>
                                        <span class="border-bottom w-100 my-3"></span>
                                    </div>
                                @endif
                            </div>
                        </form>

                        <div class="row mt-5 justify-content-center">
                            @forelse ($kyc_doc as $key => $doc)
                                <div class="col-md-12">
                                    <div
                                        style="border-top:1px solid #0a0a0a4d; margin-top: 2rem; padding-bottom: 2rem; display: block">
                                    </div>
                                    @if (@$doc->url)
                                        <div class="row justify-content-between">
                                            <div class="col-md-6" style="text-align: center;">
                                                <label for="" class="d-block text-center text-bold mb-3">
                                                    <h3>{{ @$doc->document->name }} Front Side</h3>
                                                </label>
                                                <a href="{{ @img(@$doc->url) }}" target="_blank"
                                                    class="btn btn-link d-block" style="display: block">View</a>
                                                {{-- <img src="{{ img(@$doc->url) }}" alt=""
                                                            srcset="" style="width: 100%"> --}}
                                            </div>
                                            <div class="col-md-6" style="text-align: center;">
                                                <label for="" class="d-block text-center text-bold mb-3">
                                                    <h3>{{ @$doc->document->name }} Back Side</h3>
                                                </label>
                                                <a href="{{ @img(@$doc->back_url) }}" target="_blank"
                                                    class="btn btn-link d-block" style="display: block">View</a>

                                                {{-- <img src="{{ img(@$doc->back_url) }}"
                                                            alt="" srcset=""
                                                            style="width: 100%"> --}}
                                            </div>

                                        </div>

                                        <div class="mt-4 text-center">
                                            @if ($doc->status == 'PENDING')
                                                <h4 style="color: #fc8019;">
                                                    {{ ucfirst($doc->status) }}</h4>
                                            @endif
                                            @if ($doc->status == 'APPROVED')
                                                <h4 style="color: #008000;">
                                                    {{ ucfirst($doc->status) }}</h4>
                                            @endif
                                            @if ($doc->status == 'ACTIVE')
                                                <h4 style="color: #008000;">
                                                    {{ ucfirst($doc->status) }}</h4>
                                            @endif
                                            @if ($doc->status == 'REJECTED')
                                                <h4 style="color: #FF0000;">
                                                    {{ ucfirst($doc->status) }}</h4>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                @if ($doc->status !== 'APPROVED')
                                    {{-- <span class="border-bottom w-100 my-3"></span> --}}
                                    <form action="{{ route('update-kyc', $doc->id) }}" method="post"
                                        id="kyc-edit-form-{{ $key }}" class="d-none kyc-edit-form"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="row justify-content-center">
                                            <div class="col-md-4 text-center" style="margin-top: 2rem">
                                                <label for="">Front Side</label>
                                                <input type="file" id="file" name="image" required
                                                    accept="image/*" class="m-auto" />
                                            </div>
                                            <div class="col-md-4 text-center" style="margin-top: 2rem">
                                                <label for="" class="d-block">Back Side</label>
                                                <input type="file" id="file" name="back_image" required
                                                    accept="image/*" class="m-auto" />
                                            </div>
                                            <div class="col-md-4" style="margin-top: 2rem">
                                                <button type="submit" class="btn btn-primary btn-info-full">Update
                                                    {{ @$doc->document->name }}</button>
                                            </div>
                                        </div>
                                    </form>
                                    <span class="border-bottom w-100 my-3"></span>
                                    <div class="row justify-content-center">
                                        <div class="col-md-12" style="text-align: center">
                                            <button type="button" class="btn btn-primary btn-info-full kyc-edit-but"
                                                style="margin: 2rem auto auto auto"
                                                id="kyc-edit-btn-{{ $key }}"
                                                onclick="showEditForm('#kyc-edit-btn-{{ $key }}', '#kyc-edit-form-{{ $key }}')">Edit
                                                {{ @$doc->document->name }}</button>
                                        </div>
                                    </div>
                                @endif
                            @empty
                            @endforelse
                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane fade @if (Session::get('check_url') == '/change/password') active in @endif" id="password_tab">
                        <form method="POST" action="{{ route('change.password') }}" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="userName">Current Password<span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" required="" placeholder=""
                                        class="form-control" id="current_password">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="userName">New Password<span class="text-danger">*</span></label>
                                    <input type="password" name="password" required="" placeholder=""
                                        class="form-control passwordField" id="passwordField" minlength="6"
                                        autocomplete="off">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="userName">Confirm Password<span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" required="" placeholder=""
                                        class="form-control passwordField" id="confirm_password" minlength="6">
                                    <span id="password_error" style="display:none;color:red;"> Password Confirmation
                                        does not match</span>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group text-left mb-0">
                                    <input class="btn btn-primary mr-1 profileUpdateButton registerButton" type="button"
                                        style="margin-top: 30px !important;" value="Update Password" />
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<!-- Property Tab Ends -->
@endsection


@section('scripts')
    <script>
        $('#company_accredited_kyc_select').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var imageSrc = selectedOption.data('image-src');
                console.log(imageSrc)
                if (imageSrc) {
                    $('#document-image').attr('src', imageSrc).show();
                } else {
                    $('#document-image').hide();
                }
            });
        $(document).on('click', '.profileUpdateButton', function() {
            console.log($("#current_password").val());
            $.post("{{ route('change.password') }}", {
                "_token": "{{ csrf_token() }}",
                "current_password": $("#current_password").val(),
                "password": $("#passwordField").val(),
                "password_confirmation": $("#confirm_password").val()
            }).then((response) => {
                if (response.status) {
                    toastr.success(response.message);
                    $("#current_password").val(null);
                    $("#passwordField").val(null);
                    $("#confirm_password").val(null);
                } else {
                    toastr.error(response.message);
                }
            })
        })
    </script>
    <script src="{{ asset('/js/parsley.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="text/javascript">
        flatpickr("#dob", {
            dateFormat: "Y-m-d",
            maxDate: new Date(), // Set to today to prevent future dates, but allows ALL months up to today
            allowInput: true,
            defaultDate: "{{ @$user->identity->dob ? date('Y-m-d', strtotime(@$user->identity->dob)) : '' }}"
        });

        $(document).ready(function() {
            let searchParams = new URLSearchParams(window.location.search)
            let param = searchParams.get('tab')
            console.log(param);
            let data = $('.nav-tabs a[href="' + param + '"]').tab('show')
            console.log(data)
        })
        $('.nav-tabs a').on('click', function(e) {
            e.preventDefault()
            const url = new URL(window.location.href);
            url.searchParams.set('tab', $(this).attr('href'));
            window.history.pushState(null, null, url);
        })
        $('.passwordField').on('keyup', function(e) {
            var ev = e || window.event;
            var key = $(this).val();

            var regex = /^(?=(.*[A-Z]){1,})(?=(.*[0-9]){1,})(?=(.*[!@#$%^&*()\-__+.]){1,}).{6,}$/;
            $(this).next('span').remove()
            $('.registerButton').attr('disabled', false);
            if (!regex.test(key)) {
                $('.registerButton').attr('disabled', true);
                $(this).after(
                    "<span class='text-danger pb-3 d-block'>Password must be 6 digits, must contain 1 capital letter, 1 special character, 1 number </span>"
                )
            }
            var password = $('#passwordField').val();
            var confirm_password = $('#confirm_password').val();
            $('#confirm_password').next('span').remove();
            // $('.registerButton').attr('disabled', false);
            if (password != confirm_password) {
                $('.registerButton').attr('disabled', true);
                $('#confirm_password').after(
                    "<span class='text-danger pb-3 d-block'>Password Confirmation does not match</span>")
            }
        })

        function showEditForm(btn, form) {
            $('.kyc-edit-form').removeClass('d-block');
            $('.kyc-edit-form').addClass('d-none');
            $('.kyc-edit-but').removeClass('d-none');
            $('.kyc-edit-but').addClass('d-block');
            $(btn).removeClass('d-block');
            $(btn).addClass('d-none');
            $(form).removeClass('d-none');
        }

        $(function() {
            var $sections = $('.form-section-div');

            function navigateTo(index) {
                // Mark the current section with the class 'current'
                $sections
                    .removeClass('current')
                    .eq(index)
                    .addClass('current');
                // Show only the navigation buttons that make sense for the current section:
                $('.prev-step').toggle(index > 0);
                var atTheEnd = index >= $sections.length - 1;
                $('.next-step').toggle(!atTheEnd);
                $('.form-sub').toggle(atTheEnd);
            }

            function curIndex() {
                // Return the current index by looking at which section has the class 'current'
                return $sections.index($sections.filter('.current'));
            }

            // Previous button is easy, just go back
            $('.prev-step').click(function() {
                navigateTo(curIndex() - 1);
            });

            // Next button goes forward iff current block validates
            $('.next-step').click(function() {
                $('#identity-form').parsley().whenValidate({
                    group: 'block-' + curIndex()
                }).done(function() {
                    navigateTo(curIndex() + 1);
                });
            });

            // Prepare sections by setting the `data-parsley-group` attribute to 'block-0', 'block-1', etc.
            $sections.each(function(index, section) {
                $(section).find(':input').attr('data-parsley-group', 'block-' + index);
            });
            navigateTo(0); // Start at the beginning
        });



        $(function() {
            var $sections = $('.form-section-div1');

            function navigateTo(index) {
                // Mark the current section with the class 'current'
                $sections
                    .removeClass('current')
                    .eq(index)
                    .addClass('current');
                // Show only the navigation buttons that make sense for the current section:
                $('.prev-step1').toggle(index > 0);
                var atTheEnd = index >= $sections.length - 1;
                $('.next-step1').toggle(!atTheEnd);
                $('.form-sub1').toggle(atTheEnd);
            }

            function curIndex() {
                // Return the current index by looking at which section has the class 'current'
                return $sections.index($sections.filter('.current'));
            }

            // Previous button is easy, just go back
            $('.prev-step1').click(function() {
                navigateTo(curIndex() - 1);
            });

            // Next button goes forward iff current block validates
            $('.next-step1').click(function() {
                $('#company-user1').parsley().whenValidate({
                    group: 'block-' + curIndex()
                }).done(function() {
                    navigateTo(curIndex() + 1);
                });
            });

            // Prepare sections by setting the `data-parsley-group` attribute to 'block-0', 'block-1', etc.
            $sections.each(function(index, section) {
                $(section).find(':input').attr('data-parsley-group', 'block-' + index);
            });
            navigateTo(0); // Start at the beginning
        });

        $(document).on("ready", function() {
            $('.accredited_kyc_select').change(function() {
                $('.accredited_kyc_upload').hide();

                var id = $(this).val();
                $('#accredited_kyc_upload_' + id).show();
            });

            $('.company_accredited_kyc_select').change(function() {
                $('.company_accredited_kyc_upload').hide();

                var id = $(this).val();
                $('#company_accredited_kyc_upload_' + id).show();
            });
        })
    </script>
    <script type="text/javascript">
        /* Signature Code */
        function readURL(input) {
            if (input.files && input.files[0]) {

                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.image-upload-wrap').hide();

                    $('.file-upload-image').attr('src', e.target.result);
                    $('.file-upload-content').show();

                    $('.image-title').html(input.files[0].name);
                };

                reader.readAsDataURL(input.files[0]);

            } else {
                removeUpload();
            }
        }

        function removeUpload() {
            $('.file-upload-input').replaceWith($('.file-upload-input').clone());
            $('.file-upload-content').hide();
            $('.image-upload-wrap').show();
        }
        $('.image-upload-wrap').bind('dragover', function() {
            $('.image-upload-wrap').addClass('image-dropping');
        });
        $('.image-upload-wrap').bind('dragleave', function() {
            $('.image-upload-wrap').removeClass('image-dropping');
        });
        /*End Signature Code */

        $(function() {
            $("#map").googleMap({
                zoom: 15, // Initial zoom level (optional)
                coords: [17.438136, 78.395246], // Map center (optional)
                type: "ROADMAP" // Map type (optional)
            });
        })
        var cities = '';
        $('.country').on('change', function() {
            $('.city').html(''); // Clear previous city details
            $.get("{{ url('countrycity') }}/" + $(this).val())
                .done(function(data) {
                    var cities = ''; // Initialize cities variable here
                    $.each(data, function(index, value) {
                        cities += '<option value=' + value.id + '>' + value.name + '</option>';
                    });
                    $('.city').html(cities); // Append new city options
                });
        });
    </script>
    <style>
        form .error,
        .parsley-required,
        .parsley-length,
        .parsley-max,
        .parsley-min {
            color: #ff0000;
        }
    </style>
@endsection
