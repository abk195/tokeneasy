@extends('layout.app')

@section('content')
<!-- Breadcrumb -->
<div class="page-content">
    <div class="header-breadcrumbs">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h1>Wallet</h1></div>
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
                                    <span>Wallet</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="container spaceall wallet-full">
        <!-- Top Details -->
        <div class="">
            <div class="p-0">

                <div class="row m-0">
                    <!--Fiat  Model start -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog df-modal-xl" role="document">
                        <div class="modal-content" style="">
                        <div class="modal-header deposit-fiat-header">
                            <h4 class="modal-title" id="exampleModalLabel">Deposit Fiat</h4>
                            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <form class="deposit-fiat-form" method="post" action="{{ url('/deposit_fiat') }}" enctype="multipart/form-data">
                        @csrf()
                        <div class="deposit-fiat-box">
                                <div class="row">
                                    <div class="df-heading">
                                        <h4>Select Bank</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Select Bank</label>
                                            <select class="form-control" id="SelectBank" name="bank">
                                                <option>Select Bank</option>
                                                @foreach($fields as $index => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="deposit-fiat-box df-box-1">
                                <div class="row">
                                    <div class="df-heading">
                                        <h4>Bank Details</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group bk-form-group">
                                                    <label class="df-lable" for="propertyName">Name</label>
                                                    <span class="bk-m-colon">:</span>
                                                    <input id="BankID" type="hidden" name="bank" value="">
                                                    <p class="df-value" id="BankName">---</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group bk-form-group">
                                                    <label class="df-lable" for="propertyLocation">IFSC Code</label>
                                                    <span class="bk-m-colon">:</span>
                                                    <p class="df-value" id="IFSC_code">---</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group bk-form-group">
                                                    <label class="df-lable" for="propertyLocation">Account Number</label>
                                                    <span class="bk-m-colon">:</span>
                                                    <p class="df-value" id="AccountNumber">---</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group bk-form-group">
                                                    <label class="df-lable" for="propertyLocation">Branch Name</label>
                                                    <span class="bk-m-colon">:</span>
                                                    <p class="df-value" id="BranchName">---</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group bk-form-group">
                                                    <label class="df-lable" for="propertyLocation">Account Holder Name</label>
                                                    <span class="bk-m-colon">:</span>
                                                    <p class="df-value" id="AccountHolder">---</p>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-6">
                                                <div class="form-group bk-form-group">
                                                    <label class="df-lable" for="propertyLocation">Test</label>
                                                    <span class="bk-m-colon">:</span>
                                                    <p class="df-value">---</p>
                                                </div>
                                            </div> --}}
                                        </div>
                                        {{-- <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group bk-form-group">
                                                    <label class="df-lable" for="propertyName">Testtt</label>
                                                    <span class="bk-m-colon">:</span>
                                                    <p class="df-value">---</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group bk-form-group">
                                                    <label class="df-lable" for="propertyLocation">Testt</label>
                                                    <span class="bk-m-colon">:</span>
                                                    <p class="df-value">---</p>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="deposit-fiat-box">
                                <div class="row">
                                    <div class="df-heading">
                                        <h4>Proof</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Amount</label>
                                            <input class="form-control" type="number" name="amount">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Screenshot</label>
                                            <input class="form-control" type="file" name="proof">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Other Details</label>
                                            <input class="form-control" type="text" name="other details">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        </form>
                        </div>
                    </div>
                    </div>
                    <!-- Modal end  -->

                    <!-- Repay shares Modal -->

                    <div class="modal fade" id="RepayShare" tabindex="-1" role="dialog" aria-labelledby="RepayShareLabel" aria-hidden="true">
                    <div class="modal-dialog df-modal-xl" role="document">
                        <div class="modal-content" style="">
                        <div class="modal-header deposit-fiat-header">
                            <h4 class="modal-title" id="RepayShareLabel">Repay Share</h4>
                            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <form class="deposit-fiat-form" method="post" action="{{ url('/repay_share') }}" enctype="multipart/form-data">
                        @csrf()
                        <div class="deposit-fiat-box">
                                <div class="row">
                                    <div class="df-heading">
                                        <h4>Select Share</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select class="form-control" id="selectshare" name="share">
                                                <option>Select Share</option>
                                                @foreach($user_tokens as $index => $value)
                                                <option value="{{$value->usercontract->property_id}}" data-contract="{{$value->usercontract->contract_address}}" data-decimal="{{$value->usercontract->decimal}}">{{$value->usercontract->tokenname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="deposit-fiat-box">
                                <div class="row">
                                    <div class="df-heading">
                                        <h4>Repay</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Issuer Address</label>
                                            <input class="form-control" type="text" name="issuer_address" id="IssuerAddress" readonly>
                                            <input type="hidden" value="" name="id">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Amount</label>
                                            <input class="form-control" type="text" name="hash">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        </form>
                        </div>
                    </div>
                    </div>

                    <!-- Repay shares modal End -->

                    <!-- Crypto deposit Modal -->

                    <div class="modal fade" id="CryptoModal" tabindex="-1" role="dialog" aria-labelledby="CryptoModalLabel" aria-hidden="true">
                    <div class="modal-dialog df-modal-xl" role="document">
                        <div class="modal-content" style="">
                        <div class="modal-header deposit-fiat-header">
                            <h4 class="modal-title" id="CryptoModalLabel">Deposit Crypto</h4>
                            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <form class="deposit-fiat-form" method="post" action="{{ url('/deposit_crypto') }}" enctype="multipart/form-data">
                        @csrf()
                        <div class="deposit-fiat-box">
                                <div class="row">
                                    <div class="df-heading">
                                        <h4>Select Coin</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Select Coin</label>
                                            <select class="form-control" id="SelectBank" name="coin">
                                                <option>Select Coin</option>
                                                <option value="USDC">USDC (ETH)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="deposit-fiat-box">
                                <div class="row">
                                    <div class="df-heading">
                                        <h4>Proof</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Amount</label>
                                            <input class="form-control" type="number" name="amount">
                                            <input type="hidden" value="{{$admin->wallet_address}}" name="admin_address">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Screenshot</label>
                                            <input class="form-control" type="file" name="proof">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Transaction Hash</label>
                                            <input class="form-control" type="text" name="hash">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        </form>
                        </div>
                    </div>
                    </div>

                    <!-- End crypto modal -->

                    <!-- Deposit using metamask modal -->
                    {{-- <div class="modal fade" id="WalletDeposit" tabindex="-1" role="dialog" aria-labelledby="WalletDepositLabel" aria-hidden="true">
                    <div class="modal-dialog df-modal-xl" role="document">
                        <div class="modal-content" style="">
                        <div class="modal-header deposit-fiat-header">
                            <h4 class="modal-title" id="WalletDepositLabel">Deposit USDC</h4>
                            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="deposit-fiat-box">
                                <div class="row">
                                    <div class="df-heading">
                                        <h4>Deposit</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Select Asset</label>
                                            <select class="form-control" id="SelectAsset" name="coin">
                                                <option>Select Asset</option>
                                                <option value="USDC">USDC (ETH)</option>
                                                <option value="USDT">USDT (ETH)</option>
                                                <option value="DIE">DIE (ETH)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Address</label>
                                            <input class="form-control" type="hidden" id="AdminAddress" name="address" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="df-lable" for="propertyLocation">Amount</label>
                                            <input class="form-control" type="number" id="DepositAmount" name="amount" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                            <button id="DepositFunds" class="btn btn-primary">Deposit</button>
                        </div>
                        </div>
                    </div>
                    </div> --}}
                    <!-- End metamask modal -->

                    <div class="content">
                        <!-- Start container-fluid -->
                        <div class="container-fluid wizard-border">
                            <div class="col-xs-12 p-0">
                                <div class="details-container tab-content">
                                    @foreach($coins as $coin)
                                        <h2 class="panel-title mb-5">Current Balance: {{number_format(@$user->$coin,6)}} {{$coin}}</h2>
                                        <p>
                                            This is your internal wallet with your current balance {{number_format(@$user->$coin,6)}} {{$coin}}. Before any purchase you need to deposit funds in the internal wallet. There are multiple ways funds can be deposited to internal wallet.
                                        </p>
                                        <!-- withdraw instruction -->
                                        @if($coin == 'USD')
                                        <div class="tab-pane active show wallet-new-container" id="eth_deposit">
                                            <section class="withdraw-instruction">
                                                <h2 class="inner-withdraw-instruction-title">Deposit Fiat through bank transfer</h2>
                                                <form class="deposit-fiat-form" method="post" action="{{ url('/deposit_fiat') }}" enctype="multipart/form-data">
                                                    @csrf()
                                                    <div class="row m-0 steps-wrapper">
                                                        <div class="col-lg-1">
                                                            <h4>Step 1</h4>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="grp-element">
                                                                <p>
                                                                    Select Bank and get deposit details.
                                                                </p>
                                                                <p>
                                                                    Name: <b class="" id="bank-name"></b>
                                                                </p>
                                                                <p>
                                                                    Account Number: <b id="append-account-number"></b>
                                                                </p>
                                                                <p>
                                                                    Account Holder Name:  <b id="append-account-holder"></b>
                                                                </p>
                                                                <p>
                                                                    Branch Name:  <b id="append-branch-name"></b>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label class="df-lable" for="propertyLocation">Select Bank</label>
                                                                    <select class="form-control" id="select-bank" name="bank">
                                                                        <option>Select Bank</option>
                                                                        @foreach($fields as $index => $value)
                                                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row m-0 steps-wrapper">
                                                        <div class="col-lg-1">
                                                            <h4>Step 2</h4>
                                                        </div>
                                                        <div class="col-lg-11">
                                                            <div class="grp-element">
                                                                <p>
                                                                    Deposit your funds to selected bank account (above) and provide deposit details below
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row m-0 steps-wrapper">
                                                        <div class="col-lg-1">
                                                            <h4>Step 3</h4>
                                                        </div>
                                                        <div class="col-lg-11">
                                                            <div class="grp-element">
                                                                <p>
                                                                    Provide deposit proof to admin for approval
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row m-0 steps-wrapper">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="df-lable" for="propertyLocation">Amount</label>
                                                                <input class="form-control" type="number" name="amount">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="df-lable" for="propertyLocation">Screenshot</label>
                                                                <input class="form-control" type="file" name="proof">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row m-0 steps-wrapper">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label class="df-lable" for="propertyLocation">Other Details</label>
                                                                <input class="form-control" type="text" name="other details">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <p>
                                                                Next please wait for admin to approve deposit and your balance will be updated
                                                            </p>
                                                            <p>
                                                                Here is your past fiat deposit history and deposit status
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="submit-btn-wrapper">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                                <!-- End ETH Deposit Tab -->
                                            </section>
                                        </div>
                                        @endif
                                    @endforeach

                                    <div class="tab-pane active show wallet-new-container" id="eth_deposit">
                                        <h2 class="panel-title mb-5">Manual Deposit</h2>
                                        <p>
                                            You can manually deposit stable coin to admin's provided wallet addresses. Please select the coin from dropdown, deposit to admin address in step 2 and provide proof to admin for verification
                                        </p>
                                        <!-- withdraw instruction -->
                                        <section class="withdraw-instruction">
                                            <form class="deposit-fiat-form" method="post" action="{{ url('/deposit_crypto') }}" enctype="multipart/form-data">
                                                @csrf()
                                                <div class="row m-0 steps-wrapper">
                                                    <div class="col-lg-1">
                                                        <h4>Step 1</h4>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="grp-element">
                                                            <p>
                                                                Select blockchain stablecoin
                                                            </p>
                                                            <p>
                                                                Selected coin <b class="coin_type" > </b>
                                                            </p>
                                                            <p>
                                                                Coin Blockchain Address: <b class="coin_address" >
                                                                    </b>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <div class="form-group">
                                                            {{-- <label class="df-lable" for="propertyLocation">Select Coin</label> --}}
                                                            <select class="form-control" id="DepositCrypto" name="coin">
                                                                <option value="">Select Coin</option>
                                                                <option value="USDT">USDT (ETH)</option>
                                                                <option value="USDC">USDC (ETH)</option>
                                                                <option value="DIE">DIE (ETH)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row m-0 steps-wrapper">
                                                    <div class="col-lg-1">
                                                        <h4>Step 2</h4>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="grp-element">
                                                            <p>
                                                                Please deposit selected stablecoin to following admin address
                                                            </p>
                                                            <div class="copy-address-wrapper">
                                                                <p class="eth-dp-address DepositAddress">
                                                                {{Setting::get('admin_usdt_address')}}
                                                                </p>
                                                                <div class="cursor" id="deposit_address_data" title="Click to Copy" onclick="copyToClipboard('.eth-dp-address')">
                                                                    <i class="far fa-copy"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <!-- QR Code Block -->
                                                        <div class="qr-custom-class-wrapper">
                                                        {{-- <div id="qrcode-ETH" class="qrcode-container img-thumbnail qr_canva" data-width="180" data-height="180" data-text="{{Setting::get('admin_usdt_address')}}" title="{{Setting::get('admin_usdt_address')}}"> </div> --}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row m-0 steps-wrapper">
                                                    <div class="col-lg-1">
                                                        <h4>Step 3</h4>
                                                    </div>
                                                    <div class="col-lg-11">
                                                        <div class="grp-element">
                                                            <p>
                                                                Provide deposit proof to admin for approval.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row m-0 steps-wrapper">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="df-lable" for="propertyLocation">Amount</label>
                                                            <input class="form-control" type="number" name="amount">
                                                            <input type="hidden" value="{{$admin->wallet_address}}" name="admin_address">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="df-lable" for="propertyLocation">Screenshot</label>
                                                            <input class="form-control" type="file" name="proof">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row m-0 steps-wrapper">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label class="df-lable" for="propertyLocation">Transaction Hash</label>
                                                            <input class="form-control" type="text" name="hash">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <p>Next please wait for admin to approve deposit and your balance will be updated</p>
                                                    </div>
                                                </div>
                                                <div class="submit-btn-wrapper">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </section>
                                    </div>
                                    <!-- End ETH Deposit Tab -->

                                    <div class="tab-pane active show wallet-new-container" id="meta_mask">
                                        {{-- <h2 class="panel-title mb-5">
                                            Direct Deposit Crypto using your crypto wallet like MetaMask
                                        </h2>
                                        <p>
                                            You can also directly deposit stablecoin using your crypto wallet like MetaMask. Plase connect your wallet, select the stablecoin, sign transaction in your crypto wallet and wait for some time to get transaction settle and your balance updated in platform.
                                        </p> --}}
                                        <!-- withdraw instruction -->
                                        {{-- <section class="withdraw-instruction"> --}}

                                            {{-- <div class="row m-0 steps-wrapper">
                                                <div class="col-lg-1">
                                                    <h4>Step 1</h4>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="grp-element">
                                                        <p>
                                                            1 Connect your crypto wallet
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5">
                                                    <button class="btn btn-primary" id="WalletConnect">Connect wallet</button>
                                                </div>
                                            </div>


                                            <div class="row m-0 steps-wrapper">
                                                <div class="col-lg-1">
                                                    <h4>Step 2</h4>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="grp-element">
                                                        <p>
                                                            Select the stablecoin
                                                        </p>
                                                        <p>
                                                            Selected coin address <b id="StableCoinAddress"></b>
                                                            <input class="form-control" type="hidden" id="AdminAddress" name="address" readonly>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="df-lable" for="propertyLocation" style="text-align: left; width: 100%; margin-bottom: 12px;">Select Asset</label>
                                                        <select class="form-control" id="SelectAsset" name="coin">
                                                            <option>Select Asset</option>
                                                            <option value="USDC">USDC (ETH)</option>
                                                            <option value="USDT">USDT (ETH)</option>
                                                            <option value="DIE">DIE (ETH)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row m-0 steps-wrapper">
                                                <div class="col-lg-1">
                                                    <h4>Step 3</h4>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="grp-element">
                                                        <p>
                                                            Enter amount and click Deposit
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group" style="margin-bottom: 0;">
                                                        <label class="df-lable" for="propertyLocation" style="text-align: left; width: 100%; margin-bottom: 12px;">Amount</label>
                                                                    <input class="form-control" type="number" id="DepositAmount" name="amount" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4" style="display: flex; margin-top: 32px;">
                                                    <button id="DepositFunds"  style="margin-top: auto;display: block;" class="btn btn-primary">Deposit</button>
                                                </div>
                                            </div> --}}

                                            {{-- <div class="row m-0 steps-wrapper">
                                                <div class="col-lg-12">
                                                    <div class="input-group-addon" style="display:none;" id="DepositDiv">
                                                        <div class="" style="margin-bottom: 24px;width: 100%; text-align: left">
                                                            <h4>Deposit</h4>
                                                        </div>
                                                        <div class="row m-0">

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="df-lable" for="propertyLocation" style="text-align: left; width: 100%; margin-bottom: 12px;">Select Asset</label>
                                                                    <select class="form-control" id="SelectAsset" name="coin">
                                                                        <option>Select Asset</option>
                                                                        <option value="USDC">USDC (ETH)</option>
                                                                        <option value="USDT">USDT (ETH)</option>
                                                                        <option value="DIE">DIE (ETH)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="df-lable" for="propertyLocation" style="text-align: left; width: 100%; margin-bottom: 12px;">Address</label>
                                                                    <input class="form-control" type="text" id="AdminAddress" name="address" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="df-lable" for="propertyLocation" style="text-align: left; width: 100%; margin-bottom: 12px;">Amount</label>
                                                                    <input class="form-control" type="number" id="DepositAmount" name="amount" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button id="DepositFunds" style="display: block;" class="btn btn-primary">Deposit</button>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            {{-- <div class="row m-0 steps-wrapper">
                                                <div class="col-lg-12">
                                                    <p>Please do not close this window while transaction is executing</p>
                                                </div>
                                            </div>
                                        </section> --}}

                                        <h2 class="panel-title">Crypto Manual / Direct deposit history</h2>

                                            <!-- Deposit History -->
                                            <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Time</th>
                                                        <th>Transaction ID</th>
                                                        <th>From Address</th>
                                                        <th>Coin</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($history_eth as $eth_history)
                                                    <tr>
                                                        <td>{{$eth_history->created_at}}</td>
                                                        <td>
                                                            @if($eth_history->type == "MATIC")
                                                                <a href="https://amoy.polygonscan.com/tx/{{ $eth_history->txn_hash }}" target="_blank">{{substr($eth_history->txn_hash, 0, 8).'****'}}</a>
                                                            @elseif($eth_history->type == "BNB")
                                                                <a href="https://testnet.bscscan.com/tx/{{ $eth_history->txn_hash }}" target="_blank">{{substr($eth_history->txn_hash, 0, 8).'****'}}</a>
                                                            @else
                                                                <a href="https://sepolia.etherscan.io/tx/{{ $eth_history->txn_hash }}" target="_blank">{{substr($eth_history->txn_hash, 0, 8).'****'}}</a>
                                                            @endif
                                                        </td>
                                                        <td>{{$eth_history->type}}</td>
                                                        <td>{{digitround($eth_history->amount)}}</td>
                                                        <td>{{strtoupper($eth_history->status)}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <br>
                                            <h2 class="panel-title">Fiat Bank Deposit History</h2>

                                                <!-- Deposit History -->
                                                <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Time</th>
                                                            <th>Amount</th>
                                                            <th>Proof</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($deposit_history as $value)
                                                        <tr>
                                                            <td>{{$value->created_at}}</td>
                                                            <td>{{$value->amount}}</td>
                                                            <td>
                                                                <a target="_blank" href="{{ url('/storage/'.$value->proof) }}">{{$value->proof}}</a>
                                                            </td>
                                                            <td>{{$value->status}}</td>
                                                            <td>
                                                                @if($value->status == 'Pending')
                                                                    <a class="approveButton" href="{{url('/cancel_deposit', $value->id)}}">Cancel</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            <!-- End Deposit History -->
                                    </div>

                                    <!-- ETH Withdraw Tab -->
                                    {{-- <div class="tab-pane" id="eth_withdraw"> --}}


                                        {{-- <h2 class="panel-title">MATIC Withdraw</h2>
                                        <p class="help-block">
                                            Please fill in the address and amount, then submit the form. It will be confirmed in 10 minutes
                                        </p> --}}

                                        <!-- BTC Withdraw Form -->
                                        {{-- <form id="btc_withdraw" class="form form-horizontal">

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

                                        </form> --}}
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
                                    {{-- </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- .row -->

            </div>
        </div>
    </section>
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
.selectable{
    -webkit-touch-callout: all; /* iOS Safari */
    -webkit-user-select: all; /* Safari */
    -khtml-user-select: all; /* Konqueror HTML */
    -moz-user-select: all; /* Firefox */
    -ms-user-select: all; /* Internet Explorer/Edge */
    user-select: all; /* Chrome and Opera */

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
  a.nav-item.nav-link {
    color: #8d8d8d !important;
}
</style>
@endsection

@section('scripts')
<!-- Include SweetAlert CSS and JS via CDN -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.1/ethers.umd.min.js"></script>
<script src="{{ asset('asset/js/metamask.js') }}"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript" src="{{asset('js/jquery.qrcode.js')}}"></script>
<script type="text/javascript" src="{{asset('js/qrcode.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script type="text/javascript">
    var eth_address = $('#qrcode-ETH').data('text');
    console.log(eth_address);
    $('#qrcode-ETH').qrcode(eth_address);

    var alert = $(".alert-container");
    alert.hide();
</script>

<script>
    $("#SelectAsset").on("change", function(){
        asset = $(this).val();
        if(asset == 'USDT'){
            $('#AdminAddress').val("{{Setting::get('admin_usdt_address')}}")
            $("#StableCoinAddress").text("{{Setting::get('usdt_address')}}")
        }else if(asset == 'USDC'){
            $('#AdminAddress').val("{{Setting::get('admin_usdc_address')}}")
            $("#StableCoinAddress").text("{{Setting::get('usdc_address')}}")
        }else{
            $('#AdminAddress').val("{{Setting::get('admin_die_address')}}")
            $("#StableCoinAddress").text("{{Setting::get('die_address')}}")
        }
    });
</script>

<script>
    $("#selectshare").on('change', function(){
        const selectedOption = $(this).find(':selected');
        console.log(selectedOption.attr('data-decimal'));
        property = $(this).val();
        $.ajax({
            headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
            url : "/get_share_detail/"+property,
            type : "GET",
        }).done(function(res){
            if(res.status == 'success'){
                $('#IssuerAddress').val(res.address)
            }else{
                console.log(res);
            }
        });
    })
</script>

<script>
    $('#select-bank').on('change', function(){
        console.log($(this));
        id = $(this).val()
        $.ajax({
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                url : "/get_bank_details/"+id,
                type : "GET",
            }).done(async function(res){
                console.log(res,"res");
                console.log($('#bank-name'));
               if(res){
                    $('#bank-name').text(res.name);
                    $('#append-bank-id').val(res.id);
                    $('#append-ifsc-code').text(res.ifsc_code);
                    $('#append-account-number').text(res.account_number);
                    $('#append-branch-name').text(res.branch_name);
                    $('#append-account-holder').text(res.account_name);
               }
            });
    });
</script>

<script>
    document.querySelectorAll('.approveButton').forEach(function(button) {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default action of the link

            // Show SweetAlert confirmation popup
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to cancel the deposit request?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, navigate to the URL
                    window.location.href = this.href;
                }
            });
        });
    });
</script>
<script>
    $('#DepositCrypto').on('change', function(){
        console.log('comming');
        coin = $(this).val();
        if(coin == 'USDT'){
            $('.DepositAddress').text("{{Setting::get('admin_usdt_address')}}")
            $("#qrcode-ETH").attr("data-text", "{{Setting::get('admin_usdt_address')}}");
            $("#qrcode-ETH").attr("title", "{{Setting::get('admin_usdt_address')}}");
            $('.coin_address').text("{{Setting::get('usdt_address')}}");
        }else if(coin == 'USDC'){
            $('.DepositAddress').text("{{Setting::get('admin_usdc_address')}}")
            $("#qrcode-ETH").attr("data-text", "{{Setting::get('admin_usdc_address')}}");
            $("#qrcode-ETH").attr("title", "{{Setting::get('admin_usdc_address')}}");
            $('.coin_address').text("{{Setting::get('usdc_address')}}");
        }else if(coin == 'BNB'){
            $('.DepositAddress').text("{{Setting::get('admin_address_bnb')}}")
            $("#qrcode-ETH").attr("data-text", "{{Setting::get('admin_address_bnb')}}");
            $("#qrcode-ETH").attr("title", "{{Setting::get('admin_address_bnb')}}");
        }else if(coin == 'MATIC'){
            $('.DepositAddress').text("{{Setting::get('admin_address_matic')}}")
            $("#qrcode-ETH").attr("data-text", "{{Setting::get('admin_address_matic')}}");
            $("#qrcode-ETH").attr("title", "{{Setting::get('admin_address_matic')}}");
        }else if(coin == 'ETH'){
            $('.DepositAddress').text("{{Setting::get('admin_address_eth')}}")
            $("#qrcode-ETH").attr("data-text", "{{Setting::get('admin_address_eth')}}");
            $("#qrcode-ETH").attr("title", "{{Setting::get('admin_address_eth')}}");
        }else{
            $('.DepositAddress').text("{{Setting::get('admin_die_address')}}")
            $("#qrcode-ETH").attr("data-text", "{{Setting::get('admin_die_address')}}");
            $("#qrcode-ETH").attr("title", "{{Setting::get('admin_die_address')}}");
            $('.coin_address').text("{{Setting::get('die_address')}}");
        }
        $('.coin_type').text(coin);
    })
</script>
@endsection
