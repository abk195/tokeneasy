@php
$currentRoute = \Request::getRequestUri() == '/issuer/security' ? true : false;
@endphp
@extends('issuer.layout.base')
@section('content')
    <style>
        .introactive {
            background: #232020 !important;
            background: #140749 !important;
            color: #FFF;
        }

        .introactive .nav-link {
            color: #FFF;
        }

        .currencies-container .currency-balance {
            font-size: 20px;
            font-weight: bold;
            font-family: monospace;
            margin: 0;
            text-align: left;
            color: #fff;
        }

        .withdraw-instruction .steps {
            line-height: 30px;
        }

        .table-currencies {
            border: none !important;
            border-radius: 10px;
        }

        .currency-symbol {
            color: #fff;
        }

        .input-group-addon {
            padding: 6px 12px;
            font-size: 14px;
        }

        .details-container {
            border-radius: 10px;
        }

        .table td {
            border: none !important;
        }
        .container {
            max-width: 100% !important;
        }
    </style>
    <div class="content-page-inner">
        <!-- Header Banner Start -->
        <div class="header-breadcrumbs">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1>Issuer Security</h1>
                    </div>
                    <div class="col-sm-6">
                        <div class="breadcrumb-four" style="text-align: right;">
                            <ul class="breadcrumb">
                                <li><a href="{{ url('issuer/dashboard') }}"><svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-box">
                                            <path
                                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                            </path>
                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                        </svg> <span>Dashboard</span></a></li>
                                <li class="active"><a href=""><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-cpu">
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
                                        </svg> <span>Wallet</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="content">
            <div class="container-fluid d-inline-block border p-2 rounded me-3 m-2" style="border: 1px solid #e0e0e0;">
                <div class="tab-pane p-3" id="profile-b2" >
                    <h4 class="panel-title">Change Password</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <form method="POST" action="{{ route('change.password') }}" id="password-update-form">
                                @csrf
                                <div class="form-group">
                                    <label for="current_password">Current Password <span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" required class="form-control" id="current_password">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="password">New Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" required class="form-control passwordField" id="password">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" required class="form-control passwordField" id="confirm_password">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group text-left mb-0">
                                        <button type="submit" class="btn btn-primary mt-3 profileUpdateButton registerButton">
                                            Update Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- Start container-fluid -->
            <div class="container-fluid d-inline-block border p-2 rounded me-3 m-2" style="border: 1px solid #e0e0e0;">
                <section class="container spaceall wallet-full">
                    <!-- Top Details -->

                    <div class="container">
                        <div class="alert alert-danger" style="display: none;">

                        </div>
                        <div class="alert alert-success" style="display: none;">

                        </div>
                        <div class="">
                            <div class="">

                                <div class="row">

                                    <!-- Left Side Box-->

                                    <!-- End Left Size Box -->

                                    <!-- Right Size Box -->
                                    <div class="col-sm-12">

                                        <div class="details-container tab-content">

                                            <!-- USD Deposit Tab -->
                                            {{-- <div class="tab-pane" id="usd_deposit">

                                    <h2 class="panel-title">USD Deposit</h2>
                                    <!-- Help Block -->
                                    <div class="help-block">
                                        To deposit via Bank transfer, please follow these steps:
                                        <ul>
                                            <li>1. Submit the form to get the identification code.</li>
                                            <li>2. Transfer the money to exchange's bank account. Please make sure your referral code was written on the form you fill in. </li>
                                            <li>3. Your deposit will be confirmed as soon as the money is received.</li>
                                        </ul>
                                        <span class="text-danger">Attention: The name of your bank account must be the same as your account name on our site, otherwise your deposit may fail.</span>
                                    </div>
                                    <!-- End Help Block -->

                                    <!-- USD Deposit Form -->
                                    <form id="usd_deposit" class="form form-horizontal transfer-form">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td rowspan="3" class="v-col">From</td>
                                                    <td>
                                                        <label class="optional control-label">Your Name</label>
                                                        <p class="form-control-static">Coinage Admin</p>
                                                    </td>
                                                    <td rowspan="3" class="v-col">To</td>
                                                    <td>
                                                        <label class="optional control-label">Name</label>
                                                        <p class="form-control-static">Demo Servicos Digitais</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="select required control-label">Deposit Account</label>
                                                        <select id="fund_source" class="select required form-control" required="">
                                                            <option value="?" selected="selected" label="">Deposit Account
                                                            </option>
                                                            <option value="0" label="
                                                            en.banks.icbc#****2345">en.banks.icbc#****2345</option>
                                                            <option value="1" label=" en.banks.Testb9787aa7#****1822">en.banks.Testb9787aa7#****1822
                                                            </option>
                                                            <option value="2" label=" en.banks.Test0e68cb32#****c6cc">en.banks.Test0e68cb32#****c6cc
                                                            </option>
                                                            <option value="3" label="22511893758413163656090cf7bdcc2a">22511893758413163656090cf7bdcc2a
                                                            </option>
                                                            <option value="4" label=" en.banks.Test0ac1abbd#****f944"> en.banks.Test0ac1abbd#****f944
                                                            </option>
                                                            <option value="5" label="f8d0e1cc4a3eaff5e8659eff75c5bd57">f8d0e1cc4a3eaff5e8659eff75c5bd57
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <label class="optional control-label">Account</label>
                                                        <p class="form-control-static">37027-9</p>
                                                    </td>
                                                </tr>
                                                <tr class="last-row">
                                                    <td>
                                                        <label class="optional control-label">Deposit Amount</label>
                                                        <input class="form-control" id="deposit_sum" name="deposit[sum]" placeholder="At least 10 Real" type="number" min="100" required="">
                                                    </td>
                                                    <td class="row">
                                                        <span class="col-md-6"><label class="optional control-label">Bank Name</label><p class="form-control-static">Caixa Econômica Federal</p></span>
                                                        <span class="col-md-6"><label class="optional control-label">Account where created</label><p class="form-control-static">0448</p></span>
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="form-group" align="center">
                                            <button type="button" class="btn1 btn2">Submit</button>
                                        </div>
                                    </form>
                                     <!-- End USD Deposit Form -->

                                    <br>


                                    <h2 class="panel-title">Deposit History</h2>

                                    <!-- Deposit History -->
                                    <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Identification Code</th>
                                                <th>Time</th>
                                                <th>From</th>
                                                <th>Amount</th>
                                                <th>State/Action</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>32550</td>
                                            <td>2019-03-18 18:26</td>
                                            <td>icbc @ 5678902345</td>
                                            <td>100.0</td>
                                            <td data-order="1000"><a class="btn btn-info btn-xs badge-black" href="#"><i class="far fa-edit"></i> Submit</a> <a href="#" class="btn btn-danger btn-xs badge-orange"><i class="fas fa-ban"></i> Cancel</a></td>
                                        </tr>
                                        <tr>
                                            <td>32547</td>
                                            <td>2019-03-05 11:46</td>
                                            <td>icbc @ 5678902345</td>
                                            <td>123.0 </td>
                                            <td data-order="1000"><a class="btn btn-info btn-xs badge-black" href="#"><i class="far fa-edit"></i> Submit</a> <a href="#" class="btn btn-danger btn-xs badge-orange"><i class="fas fa-ban"></i> Cancel</a></td>
                                        </tr>
                                        <tr>
                                            <td>32546</td>
                                            <td>2019-02-22 12:43 </td>
                                            <td>icbc @ 5678902345</td>
                                            <td>1000.0</td>
                                            <td data-order="1000"><a class="btn btn-info btn-xs badge-black" href="#"><i class="far fa-edit"></i> Submit</a> <a href="#" class="btn btn-danger btn-xs badge-orange"><i class="fas fa-ban"></i> Cancel</a></td>
                                        </tr>
                                    </table>
                                     <!-- End Deposit History -->
                                </div> --}}
                                            <!-- End USD Deposit Tab -->

                                            <!-- USD Withdraw Tab -->
                                            {{-- <div class="tab-pane" id="usd_withdraw">

                                    <h2 class="panel-title">USD Withdraw</h2>
                                     <!-- Help Block -->
                                    <div class="help-block">
                                        Select the bank to cash withdrawal amount and enter the account number and complete submission.
                                        <br> Your bank account and name must be consistent with the real-name authentication name.
                                        <br>
                                        <span class="text-danger"><strong>Working Hours: 9:00 - 18:00</strong> </span>
                                    </div>
                                     <!-- End Help Block -->

                                      <!-- USD Withdraws Form -->
                                    <form id="usd_withdraws" class="form form-horizontal">
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label class="optional control-label">Account Name</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">Coinage Admin</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label>Withdraw Address</label>
                                            </div>
                                            <div class="col-sm-9">
                                              <select id="fund_source" class="select required form-control" name="fund_source" required="">
                                                    <option value="?" selected="selected" label="">Withdraw Address</option>
                                                    <option value="0" label=" en.banks.icbc#****2345"> en.banks.icbc#****2345</option>
                                                    <option value="1" label=" en.banks.Testb9787aa7#****1822"> en.banks.Testb9787aa7#****1822</option>
                                                    <option value="2" label=" en.banks.Test0e68cb32#****c6cc"> en.banks.Test0e68cb32#****c6cc</option>
                                                    <option value="3" label="22511893758413163656090cf7bdcc2a (Teste9d9e43b)">22511893758413163656090cf7bdcc2a (Teste9d9e43b)</option>
                                                    <option value="4" label=" en.banks.Test0ac1abbd#****f944"> en.banks.Test0ac1abbd#****f944</option>
                                                    <option value="5" label="f8d0e1cc4a3eaff5e8659eff75c5bd57 (Test27097aa4)">f8d0e1cc4a3eaff5e8659eff75c5bd57 (Test27097aa4)</option>
                                                </select>
                                             </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label class="optional control-label">Balance</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><span class="currency-balance" id="withdraw_balance">1000.0</span></p>
                                            </div>
                                        </div>

                                        <div class="form-group required">
                                            <div class="col-sm-3">
                                                <label class="decimal required control-label" for="withdraw_sum">Withdraw Amount</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input class="numeric decimal required form-control" id="withdraw_sum" min="100" placeholder="At least 100" step="any" type="number" value="0.0">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-success btn1 highbtn" id="withdraw_all_btn" type="button">Withdraw all</button>
                                                    </div>
                                                </div>
                                                <span><a class="btn1 btn2 newaccount_btn" data-toggle="modal" data-bs-toggle="modal" data-target="#enquirypopup" data-bs-target="#enquirypopup">Add New Account</a></span>
                                            </div>
                                       </div>

                                        <div class="two-factor-auth-container form-group string required">
                                                <div class="col-sm-12">
                                                    <div class="input-group" style="width:100%;">
                                                        <div class="input-group-btn">
                                                            <button class="btn btn-default dropdown-toggle highbtn" data-toggle="dropdown" href="javascript:;" style="cursor:pointer; ">
                                                                <span class="switch-name">Google Authenticator</span>
                                                                <span class="caret" style="margin-left:5px;"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a data-type="app" href="javascript:;" class="ng-binding">Google Authenticator</a></li>
                                                                <li><a data-type="sms" href="javascript:;" class="ng-binding">SMS Verification Messages</a></li>
                                                            </ul>
                                                        </div>
                                                        <input class="two_factor_auth_type" name="two_factor" type="hidden" value="app">
                                                        <input class="string required form-control" id="two_factor_otp" name="two_factor" placeholder="6-digit password">
                                                        <div class="input-group-btn send-code-button hide">
                                                            <button class="btn btn-primary" data-alt-name="Resend in COUNT seconds" data-orig-name="Send Code" name="commit" type="submit" value="send_code">Send Code</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <div class="form-group" align="center">
                                            <button type="button" class="btn1 btn2">Submit</button>
                                        </div>
                                    </form>
                                    <!-- End USD Withdraws Form -->

                                    <br>
                                    <h2 class="panel-title">Withdraw History</h2>

                                    <!-- Withdraw History -->
                                    <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Number</th>
                                                <th>Time</th>
                                                <th>Withdraw Account</th>
                                                <th>Amount</th>
                                                <th>Fee</th>
                                                <th>State/Action</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td colspan="6"><span class="help-block text-center">There is no history data</span></td>
                                        </tr>
                                    </table>
                                     <!-- End Withdraw History -->
                                </div> --}}
                                            <!-- End USD Withdraw Tab -->

                                            <!-- BTC Deposit Tab -->
                                            <div class="tab-pane @if (!$currentRoute) active @endif"
                                                id="btc_deposit">

                                                <h2 class="panel-title">BTC Deposit</h2>

                                                <!-- withdraw instruction -->
                                                <section class="withdraw-instruction">
                                                    <div class="row">
                                                        <div class="col-sm-1">
                                                            <h4 class="steps">1</h4>
                                                        </div>

                                                        <div class="col-sm-11">
                                                            <p> Please use your common wallet services, local wallet, mobile
                                                                terminal or online wallet, select a payment and send. </p>
                                                        </div>
                                                    </div>

                                                    <hr class="split">

                                                    <div class="row">
                                                        <div class="col-sm-1">
                                                            <h4 class="steps">2</h4>
                                                        </div>
                                                        <div class="col-sm-11">

                                                            <!-- QR Code Block -->
                                                            <div id="qrcode-BTC" class="qrcode-container img-thumbnail"
                                                                data-width="180" data-height="180"
                                                                data-text="{{ @$user->btc_address }}"
                                                                title="{{ @$user->btc_address }}"></div>
                                                            <!-- End QR Code Block -->

                                                            <p class="clearfix">Please paste the address below in your
                                                                wallet, and fill in the amount you want to deposit, then
                                                                confirm and send.</p>

                                                            <div class="input-group col-sm-12 cpyInput">
                                                                <div class="input-group-addon">
                                                                    <span>Address</span>
                                                                </div>
                                                                <div class="form-control form-control-static btc_deposit_address selectable"
                                                                    id="deposit_address">{{ @$user->btc_address }}</div>
                                                                <div class="input-group-addon cursor"
                                                                    id="deposit_address_data" title="Click to Copy"
                                                                    onclick="copyToClipboard('.btc_deposit_address')">
                                                                    <i class="far fa-copy"></i>
                                                                </div>
                                                                <!-- <div class="input-group-addon">
                                                            <a id="new_address" href="#"> New Address</a>
                                                        </div> -->
                                                            </div>

                                                            <br>Scan QR code to Pay through mobile terminal wallet.

                                                        </div>
                                                    </div>

                                                    <hr class="split">

                                                    <div class="row">
                                                        <div class="col-sm-1">
                                                            <h4 class="steps">3</h4>
                                                        </div>
                                                        <div class="col-sm-11">
                                                            <p>Once you complete sending, you can check the status of your
                                                                new deposit below.</p>
                                                        </div>
                                                    </div>

                                                </section>

                                                <!-- End withdraw instruction -->

                                                <br>
                                                <h2 class="panel-title">Deposit History</h2>

                                                <!-- Deposit History -->
                                                <table
                                                    class="datatable-full table table-striped table-bordered custom-table-style"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Time</th>
                                                            <th>Transaction ID </th>
                                                            <th>Amount</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>

                                                </table>
                                                <!-- End Deposit History -->

                                            </div>
                                            <!-- End BTC Deposit Tab -->

                                            <!-- BTC Withdraw Tab -->
                                            <div class="tab-pane" id="btc_withdraw">

                                                <h2 class="panel-title">BTC Withdraw</h2>
                                                <p class="help-block">
                                                    Please fill in the address and amount, then submit the form. It will be
                                                    confirmed in 10 minutes
                                                </p>

                                                <!-- BTC Withdraw Form -->
                                                <form id="btc_withdraw" class="form form-horizontal">

                                                    <div class="form-group">
                                                        <div class="col-sm-3">
                                                            <label class="select required control-label"
                                                                for="withdraw_fund_source">Address</label>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <p class="form-control-static"><span
                                                                    class="btc-address">{{ @$user->btc_address }}</span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-sm-3">
                                                            <label class="optional control-label">Balance</label>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <p class="form-control-static"><span class="currency-balance"
                                                                    id="withdraw_balance">{{ @$user->BTC }}</span></p>
                                                        </div>
                                                    </div>

                                                    <div class="form-group required">
                                                        <div class="col-sm-3">
                                                            <label class="decimal required control-label"
                                                                for="withdraw_sum">Amount</label>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <div class="input-group">
                                                                <input class="numeric decimal required form-control"
                                                                    id="withdraw_sum" min="0" name="withdraw[sum]"
                                                                    placeholder="At least 0.001" step="any"
                                                                    type="number" value="0.0">
                                                                <div class="input-group-btn">
                                                                    <button
                                                                        class="btn btn-success btn1 highbtn withdraw-btn"
                                                                        id="withdraw_all_btn" type="button">Withdraw
                                                                        all</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="two-factor-auth-container form-group string required">

                                                        <div>
                                                            <div class="col-sm-12">
                                                                <div class="input-group" style="width:100%;">
                                                                    <div class="input-group-btn">
                                                                        <button
                                                                            class="btn btn-default dropdown-toggle highbtn"
                                                                            data-toggle="dropdown"
                                                                            style="cursor:pointer; ">
                                                                            <span class="switch-name">Google
                                                                                Authenticator</span>
                                                                        </button>
                                                                    </div>
                                                                    <input class="two_factor_auth_type" type="hidden"
                                                                        value="app">
                                                                    <input class="string required form-control"
                                                                        id="two_factor_otp" name="two_factor"
                                                                        placeholder="6-digit password">
                                                                </div>
                                                            </div>
                                                            <span class="help-block app col-sm-12">Google Authenticator
                                                                will re-generate a new password every thirty seconds, please
                                                                input timely.</span>
                                                            <span
                                                                class="help-block sms col-sm-12 col-sm-offset-6 hide">We'll
                                                                send a text message to you phone with verify code.</span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" align="center">
                                                        <button type="button" class="btn1 btn2">Submit</button>
                                                    </div>

                                                </form>
                                                <!-- End BTC Withdraw Form -->

                                                <br>
                                                <h2 class="panel-title">Withdraw History</h2>

                                                <!-- BTC Withdraw History -->
                                                <table
                                                    class="datatable-full table table-striped table-bordered custom-table-style"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Number</th>
                                                            <th>Time</th>
                                                            <th>Address</th>
                                                            <th>Actual Amount</th>
                                                            <th>Fee</th>
                                                            <th>State/Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>2018-09-17 18:09</td>
                                                        <td>
                                                            <a href="#" target="_blank">2N6PMCpWMxhk9gLzUAyG...</a>
                                                        </td>
                                                        <td>0.0177</td>
                                                        <td>0.0001</td>
                                                        <td>Done
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- End BTC Withdraw History -->

                                            </div>
                                            <!-- End BTC Withdraw Tab -->

                                            <!-- ETH Deposit Tab -->

                                            <div class="tab-pane @if ($currentRoute) active @endif"
                                                id="eth_deposit">
                                                @if ($user->g2f_status == 0)
                                                    <h2 class="panel-title">Security</h2>

                                                    <!-- withdraw instruction -->
                                                    <section class="withdraw-instruction">
                                                        <div class="row">
                                                            <div class="col-sm-1">
                                                                <h4 class="steps">1</h4>
                                                            </div>

                                                            <div class="col-sm-11">
                                                                <p> To enable 2FA, you'll need to have a 2FA auth app
                                                                    installed on your phone or tablet(examples include
                                                                    Google Authenticator, Duo Mobile, and Authy). </p>
                                                            </div>
                                                        </div>

                                                        <hr class="split">

                                                        <div class="row">
                                                            <div class="col-sm-1">
                                                                <h4 class="steps">2</h4>
                                                            </div>
                                                            <div class="col-sm-11">

                                                                <!-- QR Code Block -->
                                                                <div id="qrcode-ETH"
                                                                    class="qrcode-container img-thumbnail"
                                                                    data-width="180" data-height="180">
                                                                    {!! $image !!}
                                                                </div>
                                                                <!-- End QR Code Block -->

                                                                <p class="clearfix">Generate a code from your
                                                                    newly-activated 2FA app to confirm that you're all set
                                                                    up.</p>

                                                                <div class="input-group col-sm-12 cpyInput">
                                                                    <div class="input-group-addon">
                                                                        <span>Address</span>
                                                                    </div>
                                                                    <div class="form-control form-control-static eth_deposit_address selectable"
                                                                        id="deposit_address">{{ @$secret }}</div>
                                                                    <div class="input-group-addon cursor"
                                                                        id="deposit_address_data" title="Click to Copy"
                                                                        onclick="copyToClipboard('.eth_deposit_address')">
                                                                        <i class="far fa-copy"></i>
                                                                    </div>
                                                                    <!-- <div class="input-group-addon">
                                                            <a id="new_address" href="#"> New Address</a>
                                                        </div> -->
                                                                </div>

                                                                <br>
                                                                <p class="text-center">Scan QR code .</p>

                                                            </div>
                                                        </div>

                                                        <hr class="split">

                                                        <div class="row">
                                                            <div class="col-sm-1">
                                                                <h4 class="steps">3</h4>
                                                            </div>
                                                            <div class="col-sm-11">
                                                                <div class="form-group">
                                                                    <label for="sendamt">Enter Google 2FA Code</label>
                                                                    <input type="text" class="form-control"
                                                                        id="sendamt"
                                                                        placeholder="Enter the generated 2FA passcode"
                                                                        maxlength="6"
                                                                        onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;">
                                                                </div>
                                                                <div class="enabl2fa" style="text-align: center;">
                                                                    <button type="submit" class="btn btn-primary"
                                                                        onclick="myfuncgoogleotp();">Enable</button>
                                                                    <p style="display: none;" id="otp_msg"></p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </section>
                                                @else
                                                    <div class="tab-pane active" id="btc_deposit">

                                                        <h2 class="panel-title">Disable factor Authentication</h2>

                                                        <!-- withdraw instruction -->
                                                        <section class="withdraw-instruction">


                                                            <hr class="split">

                                                            <div class="row">
                                                                <div class="col-sm-11">
                                                                    <form method=get
                                                                        action="{{ url('issuer/2fa/disable') }}"
                                                                        enctype="multipart/form-data">
                                                                        {{ csrf_field() }}
                                                                        <div class="row">
                                                                            <div class="col-lg-6 col-md-6 col-sm-12 ">
                                                                                <div class="form-group">
                                                                                    <label for="sendamt">Enter Google 2FA
                                                                                        Code</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="otp" aria-describedby=""
                                                                                        placeholder="Enter 6 digits 2FA Code"
                                                                                        name="otp" maxlength="6"
                                                                                        onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;"
                                                                                        required>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-12 col-sm-12 ">
                                                                                <div style="text-align: start;">
                                                                                    <button type="submit"
                                                                                        class="btn btn-secondary"
                                                                                        {{-- style="margin-left: -464px;" --}}
                                                                                        >Disable
                                                                                        2FA</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>

                                                        </section>
                                                @endif
                                                <!-- End withdraw instruction -->

                                                <br>

                                                <!-- End Deposit History -->

                                            </div>
                                            <!-- End ETH Deposit Tab -->


                                            <!-- End ETH Withdraw Tab -->

                                            <!-- LTC Deposit Tab -->
                                            {{-- <div class="tab-pane active" id="ltc_deposit">

                                    <h2 class="panel-title">LTC Deposit</h2>

                                    <!-- withdraw instruction -->
                                    <section class="withdraw-instruction">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <h4 class="steps">1</h4>
                                            </div>

                                            <div class="col-sm-11">
                                                <p> Please use your common wallet services, local wallet, mobile terminal or online wallet, select a payment and send. </p>
                                            </div>
                                        </div>

                                        <hr class="split">

                                        <div class="row">
                                            <div class="col-sm-1">
                                                <h4 class="steps">2</h4>
                                            </div>
                                            <div class="col-sm-11">

                                                <!-- QR Code Block -->
                                                <div id="qrcode" class="qrcode-container img-thumbnail" data-width="180" data-height="180" data-text="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc" title="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc">
                                                    <canvas width="180" height="180" style="display: none;"></canvas><img alt="Scan me!" src="data:image/png;base64,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" style="display: block;">
                                                </div>
                                                 <!-- End QR Code Block -->

                                                <p class="clearfix">Please paste the address below in your wallet, and fill in the amount you want to deposit, then confirm and send.</p>

                                                <div class="input-group col-sm-12 cpyInput">
                                                    <div class="input-group-addon">
                                                        <span>Address</span>
                                                    </div>
                                                    <div class="form-control form-control-static" id="deposit_address">2N21yUseHps6BtVe193P6nSUexEHhnr3BRc
                                                    </div>
                                                    <div class="input-group-addon" id="deposit_address_data" title="Click to Copy">
                                                        <i class="far fa-copy"></i>
                                                    </div>
                                                    <div class="input-group-addon">
                                                        <a id="new_address" href="#"> New Address</a>
                                                    </div>
                                                </div>

                                                <br>Scanning QR code to Pay for In the mobile terminal wallet.

                                            </div>
                                        </div>

                                        <hr class="split">

                                        <div class="row">
                                            <div class="col-sm-1">
                                                <h4 class="steps">3</h4></div>
                                            <div class="col-sm-11">
                                                <p>Once you complete sending, you can check the status of your new deposit below.</p>
                                            </div>
                                        </div>

                                    </section>

                                    <!-- End withdraw instruction -->

                                    <br>
                                    <h2 class="panel-title">Deposit History</h2>

                                    <!-- Deposit History -->
                                    <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Time</th>
                                                <th>Transaction ID</th>
                                                <th>Amount</th>
                                                <th>Confirmations</th>
                                                <th>State/Action</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>2018-09-11 15:00</td>
                                            <td>
                                                <a href="#" target="_blank">b35c4f8babcf5b845d2f24398ec01f</a>
                                            </td>
                                            <td>0.0279</td>
                                            <td>6</td>
                                            <td>Accepted</td>
                                        </tr>
                                    </table>
                                     <!-- End Deposit History -->

                                </div> --}}
                                            <!-- End LTC Deposit Tab -->

                                            <!-- LTC Withdraw Tab -->
                                            {{-- <div class="tab-pane" id="ltc_withdraw">


                                    <h2 class="panel-title">LTC Withdraw</h2>
                                    <p class="help-block">
                                        Please fill in the address and amount, then submit the form. It will be confirmed in 10 minutes
                                    </p>

                                    <!-- BTC Withdraw Form -->
                                    <form id="btc_withdraw" class="form form-horizontal">

                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label class="select required control-label" for="withdraw_fund_source">Label</label>
                                            </div>
                                            <div class="col-sm-9">

                                                <select id="fund_source" class="select required form-control" required="">
                                                    <option value="0" selected="selected" label="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Mine)">2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Mine)</option>
                                                    <option value="1" label="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Friend)">2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Friend)</option>
                                                    <option value="2" label="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Mom)">2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Mom)</option>
                                                    <option value="3" label="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Fr)">2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Fr)</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label class="optional control-label">Balance</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><span class="currency-balance" id="withdraw_balance">1006.432740704</span></p>
                                            </div>
                                        </div>

                                        <div class="form-group required">
                                            <div class="col-sm-3">
                                                <label class="decimal required control-label" for="withdraw_sum">Amount</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input class="numeric decimal required form-control" id="withdraw_sum" min="0" name="withdraw[sum]" placeholder="At least 0.001" step="any" type="number" value="0.0">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-success btn1 highbtn" id="withdraw_all_btn" type="button">Withdraw all</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="two-factor-auth-container form-group string required">

                                            <div>
                                                <div class="col-sm-12">
                                                    <div class="input-group" style="width:100%;">
                                                        <div class="input-group-btn">
                                                            <button class="btn btn-default dropdown-toggle highbtn" data-toggle="dropdown" style="cursor:pointer; ">
                                                                <span class="switch-name">Google Authenticator</span>
                                                                <span class="caret" style="margin-left:5px;"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a data-type="app">Google Authenticator</a></li>
                                                                <li><a data-type="sms">SMS Verification Messages</a></li>
                                                            </ul>
                                                        </div>
                                                        <input class="two_factor_auth_type" type="hidden" value="app">
                                                        <input class="string required form-control" id="two_factor_otp" name="two_factor" placeholder="6-digit password">
                                                        <div class="input-group-btn send-code-button">
                                                            <button class="btn btn-primary" data-alt-name="Resend in COUNT seconds" data-orig-name="Send Code" name="commit" type="submit" value="send_code">Send Code</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="help-block app col-sm-12">Google Authenticator will re-generate a new password every thirty seconds, please input timely.</span>
                                                <span class="help-block sms col-sm-12 col-sm-offset-6 hide">We'll send a text message to you phone with verify code.</span>
                                            </div>
                                        </div>

                                        <div class="form-group" align="center">
                                            <button type="button" class="btn1 btn2">Submit</button>
                                        </div>

                                    </form>
                                    <!-- End BTC Withdraw Form -->

                                    <br>
                                    <h2 class="panel-title">Withdraw History</h2>

                                    <!-- BTC Withdraw History -->
                                    <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Number</th>
                                                <th>Time</th>
                                                <th>Address</th>
                                                <th>Actual Amount</th>
                                                <th>Fee</th>
                                                <th>State/Action</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>1</td>
                                            <td>2018-09-17 18:09</td>
                                            <td>
                                                <a href="#" target="_blank">2N6PMCpWMxhk9gLzUAyG...</a>
                                            </td>
                                            <td>0.0177</td>
                                            <td>0.0001</td>
                                            <td>Done
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End BTC Withdraw History -->

                                </div> --}}
                                            <!-- End LTC Withdraw Tab -->

                                            <!-- BCH Deposit Tab -->
                                            {{-- <div class="tab-pane active" id="bch_deposit">

                                    <h2 class="panel-title">BCH Deposit</h2>

                                    <!-- withdraw instruction -->
                                    <section class="withdraw-instruction">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <h4 class="steps">1</h4>
                                            </div>

                                            <div class="col-sm-11">
                                                <p> Please use your common wallet services, local wallet, mobile terminal or online wallet, select a payment and send. </p>
                                            </div>
                                        </div>

                                        <hr class="split">

                                        <div class="row">
                                            <div class="col-sm-1">
                                                <h4 class="steps">2</h4>
                                            </div>
                                            <div class="col-sm-11">

                                                <!-- QR Code Block -->
                                                <div id="qrcode" class="qrcode-container img-thumbnail" data-width="180" data-height="180" data-text="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc" title="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc">
                                                    <canvas width="180" height="180" style="display: none;"></canvas><img alt="Scan me!" src="data:image/png;base64,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" style="display: block;">
                                                </div>
                                                 <!-- End QR Code Block -->

                                                <p class="clearfix">Please paste the address below in your wallet, and fill in the amount you want to deposit, then confirm and send.</p>

                                                <div class="input-group col-sm-12 cpyInput">
                                                    <div class="input-group-addon">
                                                        <span>Address</span>
                                                    </div>
                                                    <div class="form-control form-control-static" id="deposit_address">2N21yUseHps6BtVe193P6nSUexEHhnr3BRc
                                                    </div>
                                                    <div class="input-group-addon" id="deposit_address_data" title="Click to Copy">
                                                        <i class="far fa-copy"></i>
                                                    </div>
                                                    <div class="input-group-addon">
                                                        <a id="new_address" href="#"> New Address</a>
                                                    </div>
                                                </div>

                                                <br>Scanning QR code to Pay for In the mobile terminal wallet.

                                            </div>
                                        </div>

                                        <hr class="split">

                                        <div class="row">
                                            <div class="col-sm-1">
                                                <h4 class="steps">3</h4></div>
                                            <div class="col-sm-11">
                                                <p>Once you complete sending, you can check the status of your new deposit below.</p>
                                            </div>
                                        </div>

                                    </section>

                                    <!-- End withdraw instruction -->

                                    <br>
                                    <h2 class="panel-title">Deposit History</h2>

                                    <!-- Deposit History -->
                                    <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Time</th>
                                                <th>Transaction ID</th>
                                                <th>Amount</th>
                                                <th>Confirmations</th>
                                                <th>State/Action</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>2018-09-11 15:00</td>
                                            <td>
                                                <a href="#" target="_blank">b35c4f8babcf5b845d2f24398ec01f</a>
                                            </td>
                                            <td>0.0279</td>
                                            <td>6</td>
                                            <td>Accepted</td>
                                        </tr>
                                    </table>
                                     <!-- End Deposit History -->

                                </div> --}}
                                            <!-- End BCH Deposit Tab -->

                                            <!-- BCH Withdraw Tab -->
                                            {{-- <div class="tab-pane" id="bch_withdraw">

                                    <h2 class="panel-title">BCH Withdraw</h2>
                                    <p class="help-block">
                                        Please fill in the address and amount, then submit the form. It will be confirmed in 10 minutes
                                    </p>

                                    <!-- BTC Withdraw Form -->
                                    <form id="btc_withdraw" class="form form-horizontal">

                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label class="select required control-label" for="withdraw_fund_source">Label</label>
                                            </div>
                                            <div class="col-sm-9">

                                                <select id="fund_source" class="select required form-control" required="">
                                                    <option value="0" selected="selected" label="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Mine)">2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Mine)</option>
                                                    <option value="1" label="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Friend)">2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Friend)</option>
                                                    <option value="2" label="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Mom)">2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Mom)</option>
                                                    <option value="3" label="2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Fr)">2N21yUseHps6BtVe193P6nSUexEHhnr3BRc (Fr)</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label class="optional control-label">Balance</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><span class="currency-balance" id="withdraw_balance">1006.432740704</span></p>
                                            </div>
                                        </div>

                                        <div class="form-group required">
                                            <div class="col-sm-3">
                                                <label class="decimal required control-label" for="withdraw_sum">Amount</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input class="numeric decimal required form-control" id="withdraw_sum" min="0" name="withdraw[sum]" placeholder="At least 0.001" step="any" type="number" value="0.0">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-success btn1 highbtn" id="withdraw_all_btn" type="button">Withdraw all</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="two-factor-auth-container form-group string required">

                                            <div>
                                                <div class="col-sm-12">
                                                    <div class="input-group" style="width:100%;">
                                                        <div class="input-group-btn">
                                                            <button class="btn btn-default dropdown-toggle highbtn" data-toggle="dropdown" style="cursor:pointer; ">
                                                                <span class="switch-name">Google Authenticator</span>
                                                                <span class="caret" style="margin-left:5px;"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a data-type="app">Google Authenticator</a></li>
                                                                <li><a data-type="sms">SMS Verification Messages</a></li>
                                                            </ul>
                                                        </div>
                                                        <input class="two_factor_auth_type" type="hidden" value="app">
                                                        <input class="string required form-control" id="two_factor_otp" name="two_factor" placeholder="6-digit password">
                                                        <div class="input-group-btn send-code-button">
                                                            <button class="btn btn-primary" data-alt-name="Resend in COUNT seconds" data-orig-name="Send Code" name="commit" type="submit" value="send_code">Send Code</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="help-block app col-sm-12">Google Authenticator will re-generate a new password every thirty seconds, please input timely.</span>
                                                <span class="help-block sms col-sm-12 col-sm-offset-6 hide">We'll send a text message to you phone with verify code.</span>
                                            </div>
                                        </div>

                                        <div class="form-group" align="center">
                                            <button type="button" class="btn1 btn2">Submit</button>
                                        </div>

                                    </form>
                                    <!-- End BTC Withdraw Form -->

                                    <br>
                                    <h2 class="panel-title">Withdraw History</h2>

                                    <!-- BTC Withdraw History -->
                                    <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Number</th>
                                                <th>Time</th>
                                                <th>Address</th>
                                                <th>Actual Amount</th>
                                                <th>Fee</th>
                                                <th>State/Action</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>1</td>
                                            <td>2018-09-17 18:09</td>
                                            <td>
                                                <a href="#" target="_blank">2N6PMCpWMxhk9gLzUAyG...</a>
                                            </td>
                                            <td>0.0177</td>
                                            <td>0.0001</td>
                                            <td>Done
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End BTC Withdraw History -->

                                </div> --}}
                                            <!-- BCH LTC Withdraw Tab -->

                                        </div>
                                    </div>

                                </div>
                                <!-- .row -->

                            </div>
                        </div>

                    </div>

                </section>
            </div>

          
        </div>
    @endsection

    @section('styles')
        <style type="text/css">
            .introactive {
                background: #232020 !important;
                background: #140749 !important;
                color: #FFF;
            }

            .introactive .nav-link {
                color: #FFF;
            }



            div#deposit_address {
                font-size: 13px;
                font-weight: 500;
                padding: 11px;
                color: #000;
            }

            .custom-table-style thead tr th {
                vertical-align: middle;
                font-size: 12px;
                padding: 8px !important;
            }

            .custom-table-style tbody tr td {
                vertical-align: middle;
                font-size: 12px;
                padding: 8px !important;
                letter-spacing: .1px;
            }

            .send-code-button button.btn.btn-primary {
                padding: 10px 20px;
                background: #5f56e0;
                border: 1px solid #5f56e0;
            }

            .selectable {
                -webkit-touch-callout: all;
                /* iOS Safari */
                -webkit-user-select: all;
                /* Safari */
                -khtml-user-select: all;
                /* Konqueror HTML */
                -moz-user-select: all;
                /* Firefox */
                -ms-user-select: all;
                /* Internet Explorer/Edge */
                user-select: all;
                /* Chrome and Opera */

            }

            .alert-container {
                position: fixed;
                bottom: 5px;
                left: 11%;
                width: 32%;
                margin: 0 25% 0 25%;
                background-color: #e0afaf;
                z-index: 1;
            }

            .alert {
                text-align: center;
                padding: 17px 0 20px 0;
                margin: 0 25% 0 25%;
                height: 54px;
                font-size: 20px;
            }
        </style>
    @endsection

    @section('scripts')
        <script type="text/javascript" src="{{ asset('js/jquery.qrcode.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/qrcode.js') }}"></script>
        <script>
            $(document).ready(function() {
                $(".table-currencies").click(function() {
                    $('.table-currencies').removeClass('introactive');
                    $(this).addClass('introactive');
                });
            });
            $(document).on('click', '.profileUpdateButton', function (e) {
                e.preventDefault();

                const currentPassword = $("#current_password").val();
                const newPassword = $("#password").val();
                const confirmPassword = $("#confirm_password").val();

                // Basic frontend validation
                if (newPassword.length < 6) {
                    toastr.error("New password must be at least 6 characters.");
                    return;
                }

                if (newPassword !== confirmPassword) {
                    toastr.error("New password and confirm password do not match.");
                    return;
                }

                $.post("{{ route('change.password') }}", {
                    _token: "{{ csrf_token() }}",
                    current_password: currentPassword,
                    password: newPassword,
                    password_confirmation: confirmPassword
                }).then((response) => {
                    if (response.status) {
                        toastr.success(response.message);
                        $("#current_password").val('');
                        $("#password").val('');
                        $("#confirm_password").val('');
                    } else {
                        toastr.error(response.message);
                    }
                }).catch((xhr) => {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Laravel validation errors
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            toastr.error(errors[key][0]);
                        }
                    } else {
                        toastr.error("An unexpected error occurred.");
                    }
                });
            });

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
        </script>
        <script type="text/javascript">
            var btc_address = $('#qrcode-BTC').data('text');
            $('#qrcode-BTC').qrcode(btc_address);
            var eth_address = $('#qrcode-ETH').data('text');
            $('#qrcode-ETH').qrcode(eth_address);


            var alert = $(".alert-container");
            alert.hide();

            function copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).text()).select();
                document.execCommand("copy");
                $temp.remove();
                alert.slideDown();
                window.setTimeout(function() {
                    alert.slideUp();
                }, 2500);
            }

            function myfuncgoogleotp() {
                $(".alert-danger").hide();
                $(".alert-success").hide();

                var otp = $('#sendamt').val();
                if (otp == "") {
                    $(".alert-danger").html("Please enter valid 6 digits OTP.");
                    $(".alert-danger").show().delay(5000).fadeOut();
                }

                $.ajax({
                    url: "{{ url('issuer/g2fotpcheckenablenew') }}",
                    type: "POST",
                    data: {
                        "_token": '{{ csrf_token() }}',
                        'totp': otp
                    }
                }).done(function(response) {

                    if (response.status == 1) {
                        $(".alert-success").html(response.message);
                        $(".alert-success").show().delay(5000).fadeOut();
                        // location.reload();

                        $('.enabl2fa').hide();
                        // $(".alert-success").html(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 3000);

                    } else {
                        $(".alert-danger").html(response.message);
                        $(".alert-danger").show().delay(5000).fadeOut();
                    }


                }).fail(function(key, status) {
                    // $('.alert-danger').show();
                    // $('.alert-danger').append('<p>'+status+'</p>');
                });

            }
        </script>
    @endsection
