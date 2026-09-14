@extends('issuer.layout.base')
@section('content')

    <style>
        /* .nice-select .list { overflow-y: scroll; height: 200px; } */
        .form-section-div,
        .form-section-div1 {
            display: none;
        }

        .form-section-div.current,
        .form-section-div1.current {
            display: block;
        }
    </style>
    <?php
        $username = explode(" ", $user->name);
        $phone = explode("-", @$user->identity->primary_phone);
    ?>
    <div class="content-page-inner">
        <!-- Header Banner Start -->
        <div class="header-breadcrumbs">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
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
                                        <span>Dashboard</span></a></li>
                                <li class="active"><a href="">
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
                                        <span>Profile</span></a></li>
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

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs tabs-bordered nav-justified">
                                <li class="nav-item">
                                    <a href="#home-b2" data-toggle="tab" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                        <span class="d-block d-sm-none"><i
                                                class="mdi mdi-home-variant-outline font-18"></i></span>
                                        <span class="d-none d-sm-block">Profile Update</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#kyc" data-toggle="tab" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                        <span class="d-block d-sm-none"><i
                                                class="mdi mdi-home-variant-outline font-18"></i></span>
                                        <span class="d-none d-sm-block">KYC</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#profile-b2" data-toggle="tab" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                        <span class="d-block d-sm-none"><i
                                                class="mdi mdi-account-outline font-18"></i></span>
                                        <span class="d-none d-sm-block">Change Password</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active show" id="home-b2">
                                    <form action="{{ url('issuer/profile_update') }}" method="POST"
                                        class="form-validation" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="userName">First Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="fname" parsley-trigger="change"
                                                        required="" placeholder="" class="form-control"
                                                        id="userName" value="{{ $username[0] }}" required
                                                        maxlength="50">
                                                </div>
                                                <div class="form-group">
                                                    <label for="userName">Email<span class="text-danger">*</span></label>
                                                    <input type="text" name="email" parsley-trigger="change"
                                                        placeholder="" class="form-control" id="userName"
                                                        value="{{ @$user->email }}" required readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="userName">Country<span
                                                            class="text-danger">*</span></label>
                                                    <select class="reg-sel form-control" id="country_id"
                                                        name="country_id" required="">
                                                        <option value="">Select Country</option>
                                                        @foreach ($country as $value)
                                                            <option value="{{ $value->id }}"
                                                                @if ($user->country_id == $value->id) selected @endif>
                                                                {{ $value->countryname }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="userName">Last Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="lname" parsley-trigger="change"
                                                        required="" placeholder="" class="form-control"
                                                        id="Email" value="{{ @$username[1] }}" required
                                                        maxlength="50">
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-group" style="width: 80px">
                                                        <label for="country_code">Code<span
                                                                class="text-danger">*</span></label>
                                                        {{-- <select class="reg-sel form-control" id="country_code"
                                                            name="country_code" required="">
                                                            <option value="">Choose Code</option>
                                                            @foreach (\App\CountryCode::all() as $value)
                                                                <option value="{{ $value->dial_code }}"
                                                                    @if (@$user->identity->country_code == $value->dial_code) selected @endif>
                                                                    {{ $value->dial_code }}
                                                                </option>
                                                            @endforeach
                                                        </select> --}}
                                                        <input type="text" name="country_code"
                                                        placeholder="" class="form-control" value="{{$phone[0]}}" id="country_code" disabled>

                                                    </div>
                                                    <div class="form-group w-75 ml-3">
                                                        <label for="userName">Mobile No<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="mobileno" parsley-trigger="change"
                                                            placeholder="" class="form-control" id="mobile"
                                                            onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;"
                                                            value="{{ @$user->identity->phone }}" maxlength="10" required
                                                            minlength="10">
                                                            {{-- <input type="text" name="mobileno"
                                                            placeholder="" class="form-control" id="mobile"
                                                            required
                                                            disabled > --}}
                                                            <span id="message_mobile"></span>

                                                    </div>
                                                </div>
                                               {{--
                                                    <div class="form-group">
                                                        <label for="userName">MATIC Address<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="eth_address" parsley-trigger="change"
                                                            required="" placeholder="" class="form-control"
                                                            id="userName" value="{{ @$user->eth_address }}" required
                                                            disabled>
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <label for="userName">Avatar<span class="text-danger">*</span></label>
                                                        <input type="file" class="filestyle" data-btnClass="btn-primary">
                                                    </div>
                                                --}}
                                            </div>
                                        </div>

                                        <div class="row next-btn" style="padding:25px 0px;">
                                            <div class="col-sm-12">
                                                <div class="form-group text-center mb-0">
                                                    <input class="btn btn-primary waves-effect waves-light mr-1"
                                                        type="submit" value="Update Profile">
                                                    {{-- <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">Update Profile</button> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php $proofs = \App\Document::whereNotIn(
                                    'id',
                                    $kyc_doc
                                        ->where('user_id', auth()->id())
                                        ->pluck('accredited_document_id')
                                        ->toArray()
                                )->get(); ?>
                                <div class="tab-pane" id="kyc">
                                    <div class="row mt-5 justify-content-center g-4">
                                        @if (!empty($user->issuer_kyc_doc))
                                            <div class="col-md-5">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body text-center p-4">
                                                        <h6 class="card-subtitle mb-3 text-muted fw-normal">Address Proof</h6>
                                                        <a href="{{ $user->issuer_kyc_doc }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                            <i class="bi bi-file-earmark-pdf me-2"></i>View Document
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-5">
                                                <div class="card border-0 bg-light h-100">
                                                    <div class="card-body text-center p-4">
                                                        <h6 class="card-subtitle mb-3 text-muted fw-normal">Address Proof</h6>
                                                        <span class="text-muted small">No document uploaded</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if (!empty($user->issuer_pros_doc))
                                            <div class="col-md-5">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body text-center p-4">
                                                        <h6 class="card-subtitle mb-3 text-muted fw-normal">Issuer Identification Document</h6>
                                                        <a href="{{ $user->issuer_pros_doc }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                            <i class="bi bi-file-earmark-pdf me-2"></i>View Document
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-5">
                                                <div class="card border-0 bg-light h-100">
                                                    <div class="card-body text-center p-4">
                                                        <h6 class="card-subtitle mb-3 text-muted fw-normal">Issuer Identification Document</h6>
                                                        <span class="text-muted small">No document uploaded</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>



                                    <form role="form" action="{{ url('/issuer/usercompanydetail') }}" method="POST"
                                        enctype="multipart/form-data" id="company-user1" class="">
                                        @csrf()
                                        <div class="row">
                                            @if (@$user->userCompany->team_size)
                                                <input type="hidden" name="updateKYC" value="1">
                                            @endif
                                        </div>
                                        @if (auth()->user()->account_type == 'company')
                                            <div class="form-section-div1">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Company Name</label>
                                                            <input maxlength="30" type="text" required
                                                                class="form-control" placeholder="Enter Company Name"
                                                                name="company_name"
                                                                value="{{ @$user->userCompany->company_name }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Date of Found</label>
                                                            <input maxlength="20" type="date" required="required"
                                                                class="form-control" placeholder="Date of Found"
                                                                name="company_date"
                                                                value="{{ @$user->userCompany->date_founded }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Head Quarters</label>
                                                            <input maxlength="100" type="text" required="required"
                                                                class="form-control" placeholder="Head Quarters"
                                                                name="headquarters"
                                                                value="{{ @$user->userCompany->headquarters }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Signing Authority</label>
                                                            @if (@$user->userCompany->signing_authority)
                                                                <img src="{{ img(@$user->userCompany->signing_authority) }}"
                                                                    width="50" class="popImage">
                                                            @endif
                                                            <input type="file" class="form-control"
                                                                name="signing_authority" accept="jpeg,jpg,bmp,png"
                                                                @if (!@$user->userCompany->signing_authority) required @endif />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Team Size</label>
                                                            <input maxlength="100" type="number" required="required"
                                                                class="form-control" placeholder="Team Size"
                                                                name="team_size"
                                                                value="{{ @$user->userCompany->team_size }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Home Page</label>
                                                            <input maxlength="50" type="text" required="required"
                                                                class="form-control" placeholder="Home Page"
                                                                name="company_url"
                                                                value="{{ @$user->userCompany->company_url }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Social Channels</label>
                                                            <h5>Please mention all the social links below, separate them
                                                                with newlines</h5>
                                                            <textarea name="social_channels" id="" cols="30" rows="10">{{ @$user->userCompany->social_channels }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-section-div1">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 country_select_drop">
                                                        <div class="form-group">
                                                            <label class="control-label">Certificate of
                                                                Incorporation</label>
                                                            @if (@$user->userCompany->incorporation_certificate)
                                                                <img src="{{ img(@$user->userCompany->incorporation_certificate) }}"
                                                                    width="50" class="popImage">
                                                            @endif
                                                            <input type="file" class="form-control"
                                                                name="incorporation_certificate" accept="jpeg,jpg,bmp,png"
                                                                @if (!@$user->userCompany->incorporation_certificate) required @endif />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Partnership deed</label>
                                                            @if (@$user->userCompany->partnership_deed)
                                                                <img src="{{ img(@$user->userCompany->partnership_deed) }}"
                                                                    width="50" class="popImage">
                                                            @endif
                                                            <input type="file" class="form-control"
                                                                name="partnership_deed" accept="jpeg,jpg,bmp,png"
                                                                @if (!@$user->userCompany->partnership_deed) required @endif />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Trust deed</label>
                                                            @if (@$user->userCompany->trust_deed)
                                                                <img src="{{ img(@$user->userCompany->trust_deed) }}"
                                                                    width="50" class="popImage">
                                                            @endif
                                                            <input type="file" class="form-control" name="trust_deed"
                                                                accept="jpeg,jpg,bmp,png"
                                                                @if (!@$user->userCompany->trust_deed) required @endif />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Certificate from the register
                                                                of
                                                                a societies</label>
                                                            @if (@$user->userCompany->register_socities)
                                                                <img src="{{ img(@$user->userCompany->register_socities) }}"
                                                                    width="50" class="popImage">
                                                            @endif
                                                            <input type="file" class="form-control"
                                                                name="register_socities" accept="jpeg,jpg,bmp,png"
                                                                @if (!@$user->userCompany->register_socities) required @endif />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-section-div1">
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
                                                        id="company_accredited_kyc_upload_id">
                                                        <label>Application Document</label>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="">Front Side</label>
                                                        <input type="file" id="file" name="image" required
                                                            accept="image/*" />
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="">Back Side</label>
                                                        <input type="file" id="file" name="back_image" required
                                                            accept="image/*" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="submit"
                                                            class="btn2 btn-primary btn1 btn2 btn-info-full form-sub1">Submit</button>
                                                    </div>
                                                    {{-- <span class="border-bottom w-100 my-3"></span> --}}
                                                </div>
                                            @endif
                                    </form>

                                    <div class="row mt-5 justify-content-center">
                                        @forelse ($kyc_doc as $key=> $doc)
                                            <span class="border-bottom w-100 mb-5 pt-4"></span>

                                            <div class="col-md-8">
                                                @if (@$doc->url)
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <label for=""
                                                                class="d-block text-center text-bold mb-3">
                                                                <h3>{{ @$doc->document->name }} Front Side</h3>
                                                            </label>
                                                            <a href="{{ @img(@$doc->url) }}" target="_blank"
                                                                class="btn btn-link d-block">View</a>

                                                        </div>
                                                        <div class="ml-5">
                                                            <label for=""
                                                                class="d-block text-center text-bold mb-3">
                                                                <h3>{{ @$doc->document->name }} Back Side</h3>
                                                            </label>
                                                            <a href="{{ @img(@$doc->back_url) }}" target="_blank"
                                                                class="btn btn-link d-block">View</a>

                                                        </div>
                                                        <div class="text-center">
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
                                                    </div>
                                                @endif
                                            </div>
                                            @if ($doc->status !== 'APPROVED')
                                                {{-- <span class="border-bottom w-100 my-3"></span> --}}
                                                <form action="{{ route('update-kyc', $doc->id) }}" method="post"
                                                    id="kyc-edit-form-{{ $key }}" class="d-none kyc-edit-form"
                                                    enctype="multipart/form-data">
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label for="">Front Side</label>
                                                            <input type="file" id="file" name="image" required
                                                                accept="image/*" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="" class="d-block">Back Side</label>
                                                            <input type="file" id="file" name="back_image"
                                                                required accept="image/*" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button type="submit"
                                                                class="btn btn-primary btn-info-full">Update
                                                                {{ @$doc->document->name }}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <span class="border-bottom w-100 my-3"></span>
                                                <div class="row justify-content-center">
                                                    <div class="col-md-6">
                                                        <button type="button"
                                                            class="btn btn-primary btn-info-full kyc-edit-but"
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
                                <div class="clearfix"></div>
                                <div class="col-xs-12 list-inline pull-right">
                                    <ul class="list-inline pull-right">
                                        <li>
                                            <button type="button" class="btn2 prev-step1 btn1 btn2">Previous</button>
                                        </li>
                                        <li>
                                            <button type="button" class=" btn1 btn2 next-step1">Next</button>
                                        </li>
                                        <li>
                                            {{-- <button type="submit"
                                                        class="btn2 btn-primary btn1 btn2 btn-info-full form-sub1">Submit</button> --}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane" id="profile-b2">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <form method="POST" action="{{ route('change.password') }}"
                                            id="passord-update-form">
                                            @csrf
                                            <div class="form-group">
                                                <label for="userName">Current Password<span
                                                        class="text-danger">*</span></label>
                                                <input type="password" name="current_password" required=""
                                                    placeholder="" class="form-control" id="current_password">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label for="userName">New Password<span
                                                            class="text-danger">*</span></label>
                                                    <input type="password" name="password" required="" placeholder=""
                                                        class="form-control passwordField" id="password">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label for="userName">Confirm Password<span
                                                            class="text-danger">*</span></label>
                                                    <input type="password" name="password_confirmation" required=""
                                                        placeholder="" class="form-control passwordField"
                                                        id="confirm_password">

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 form-group text-left mb-0">
                                                    <input class="btn btn-primary mr-1 profileUpdateButton registerButton"
                                                        type="button" style="margin-top: 30px !important;"
                                                        value="Update Password" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- end container-fluid -->
    <!-- Footer Start -->
    <!-- <footer class="footer">
                                                                                                                                                                                                            <div class="container-fluid">
                                                                                                                                                                                                                <div class="row">
                                                                                                                                                                                                                    <div class="col-md-12">
                                                                                                                                                                                                                      <ul class="social">
                                                                                                                                                                                                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                                                                                                                                                                                                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                                                                                                                                                                                                    </ul>
                                                                                                                                                                                                                        <p>Copyright © 2021 {{ $project_name }}. All rights reserved.</p>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                            </div>
                                                                                                                                                                                                        </footer> -->
    <!-- end Footer -->
    </div>
    <!-- end content -->
    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div class="d-flex flex-wrap justify-content-between align-content-center">
                    <ul class="social">
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                    </ul>
                    <p>Copyright © <script>document.write(new Date().getFullYear());</script> {{ $project_name }}. All rights reserved.</p>
                </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
    <!-- END content-page -->
@endsection
@section('scripts')
<script src="https://unpkg.com/libphonenumber-js/bundle/libphonenumber-js.min.js"></script>
    <script>
        $(document).ready(function() {
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

            let searchParams = new URLSearchParams(window.location.search)
            let param = searchParams.get('tab')
            console.log(param);
            let data = $('.nav-tabs a[href="' + param + '"]').tab('show')
            console.log(data)
        });
        $('.nav-tabs a').on('click', function(e) {
            e.preventDefault()
            const url = new URL(window.location.href);
            url.searchParams.set('tab', $(this).attr('href'));
            window.history.pushState(null, null, url);
        })
        $(document).on('click', '.profileUpdateButton', function() {

            $.post("{{ route('change.password') }}", {
                "_token": "{{ csrf_token() }}",
                "current_password": $("#current_password").val(),
                "password": $("#password").val(),
                "password_confirmation": $("#confirm_password").val()
            }).then((response) => {
                if (response.status) {
                    toastr.success(response.message);
                    $("#current_password").val(null);
                    $("#password").val(null);
                    $("#confirm_password").val(null);
                } else {
                    toastr.error(response.message);
                }
            })
        })
    </script>
    <script src="{{ asset('/js/parsley.js') }}"></script>
    <script type="text/javascript">
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
            var password = $('#password').val();
            var confirm_password = $('#confirm_password').val();
            $('#confirm_password').next('span').remove();
            // $('.registerButton').attr('disabled', false);
            if (password != confirm_password) {
                $('.registerButton').attr('disabled', true);
                $('#confirm_password').after(
                    "<span class='text-danger pb-3 d-block'>Password Confirmation does not match</span>")
            }
        })


        // function check() {
        //     var mobile = document.getElementById('mobile');
        //     var message = document.getElementById('message');
        //     var goodColor = "#0C6";
        //     var badColor = "#FF9B37";
        //     if (mobile.value.length > 15) {
        //         console.log('error');
        //         //  mobile.style.backgroundColor = badColor;
        //         message.style.color = badColor;
        //         message.innerHTML = "Maximum limit is 15 digits!"
        //         $('#message').show();
        //     } else {

        //         $('#message').hide();
        //     }
        // }

        $(document).on('change','#country_id',function(){
        var country_id=$('#country_id').val();
        $.ajax({
            type:'POST',
            url:"{{ url('/issuer/get_country_ph_code') }}",
            data: {
                        'country_id': country_id,
                        '_token': '{{ csrf_token() }}'
                    },
            success:function(data){
                if(data.status==1){
                    // alert(data.message);
                    $('#country_code').val(data.message);
                    $('#country_code').show();
                    $('#mobile').attr('disabled',false)
                }
                // alert(data);
            }
        });

        });
        $(document).on('keyup','#mobile',function(){
            var code=$('#country_code').val();
            var number=$('#mobile').val();
            console.log(number);
            var status=new libphonenumber.parsePhoneNumber(String(code+number)).isValid();
            if(status==true){
                $('#message_mobile').attr("style", "color:green;");
                $('#message_mobile').html('Mobile Number is valid *');
            }else{
                $('#message_mobile').attr("style", "color:red;");

                $('#message_mobile').html('Please enter valid Mobile Number *');
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

            // $('.company_accredited_kyc_select').change(function() {
            //     $('.company_accredited_kyc_upload').hide();

            //     var id = $(this).val();
            //     $('#company_accredited_kyc_upload_' + id).show();
            // });
        });
    </script>
@endsection
