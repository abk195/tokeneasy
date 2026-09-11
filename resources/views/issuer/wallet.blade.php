@php
    $currentRoute = (\Request::getRequestUri() == "/issuer/wallet"? true : false);
@endphp
@extends('issuer.layout.base')
@section('content')
<style>
	.introactive
  {
	  background: #232020 !important;
    background: #140749 !important;
	  color: #FFF;
	}
	.introactive .nav-link
  {
	  color: #FFF;
	}
  .currencies-container .currency-balance
  {
    font-size: 20px;
    font-weight: bold;
    font-family: monospace;
    margin: 0;
    text-align: left;
    color: #fff;
  }
  .withdraw-instruction .steps
  {
    line-height: 30px;
  }
  .table-currencies
  {
    border: none !important;
    border-radius: 10px;
  }
  .currency-symbol
  {
    color: #fff;
  }
  .input-group-addon
  {
    padding: 6px 12px;
    font-size: 14px;
  }
  .details-container
  {
    border-radius: 10px;
  }
  .table td
  {
    border:none !important;
  }
  .container {
    max-width: 100%;
  }
</style>
<div class="content-page-inner">
  <!-- Header Banner Start -->
  <div class="header-breadcrumbs">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>Issuer Wallet</h1>
        </div>
        <div class="col-sm-6">
            @include('issuer.layout.breadcrumb',['items' => [
                [
                    'url' => 'issuer/dashboard',
                    'title' => 'Dashboard'
                ],
                [
                    'title' => 'Wallet'
                ],
            ]])
        </div>
      </div>
    </div>
  </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="content">
                    <!-- Start container-fluid -->
                    <div class="container-fluid wizard-border p-2">
                        <section class="container spaceall wallet-full p-0">
                        <!-- Top Details -->

                        <div class="container p-0">

                            <div class="p-3 m-0">
                                <div class="panel-body panel-currencies p-0">

                                    <div class="row m-0">

                                        <!-- Left Side Box-->
                                        {{-- <div class="col-12">
                                            <div class="currencies-container nav nav-tabs">

                                                @foreach($coins as $key => $value)
                                                    @if($value != 'USD')
                                                    <div style="position:relative; width:100%">
                                                        <table class="table table-currencies introactive">
                                                            <tbody>
                                                            <tr class="currency-item" class="nav-item nav-link" href="#eth_deposit" data-toggle="tab">
                                                                <td class="currency-logo"><img src="{{asset('asset/package/images/wallet/'.strtolower($value).'.png')}}" alt=""><span class="currency-symbol">{{$value}}</span></td>
                                                                <td class="currency-balance-col" style="vertical-align: top !important;">
                                                                    <p class="currency-balance"><span class="currency-sign"></span>{{number_format(@$user->$value,6)}} {{$value}}</p>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @elseif($value == 'USD')
                                                    <div style="position:relative; width:100%">
                                                        <a href="#" data-toggle="modal" data-bs-toggle="modal" data-target="#exampleModal" data-bs-target="#exampleModal">
                                                            <table class="table table-currencies introactive">
                                                                <tbody>
                                                                <tr class="currency-item" class="nav-item nav-link">
                                                                    <td class="currency-logo"><img src="{{asset('asset/package/images/wallet/'.strtolower($value).'.png')}}" alt=""><span class="currency-symbol">{{$value}}</span></td>
                                                                    <td class="currency-balance-col" style="vertical-align: top !important; text-align: left !important;" >
                                                                        <p class="currency-balance"><span class="currency-sign"></span>{{number_format(@$user->$value,6)}} {{$value}}</p>
                                                                        Deposit Fiat
                                                                    </td>
                                                                    <!-- <td class="currency-balance-col" style="vertical-align: top !important;">

                                                                    </td> -->
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </a>
                                                    </div>
                                                    @endif
                                                @endforeach
                                                <div style="position:relative; width:100%">
                                                        <a href="#" data-toggle="modal" data-bs-toggle="modal" data-target="#CryptoModal" data-bs-target="#CryptoModal">
                                                            <table class="table table-currencies introactive">
                                                                <tbody>
                                                                <tr class="currency-item" class="nav-item nav-link">
                                                                    <td class="currency-balance-col" style="vertical-align: top !important;">
                                                                        Deposit Crypto
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                 layout                           </table>
                                                        </a>
                                                    </div>
                                            </div>
                                        </div> --}}

                                        <!-- Model start -->
                                        {{-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog df-modal-xl" role="document">
                                                <div class="modal-content" style="">
                                                <div class="modal-header deposit-fiat-header">
                                                    <h4 class="modal-title" id="exampleModalLabel">Deposit Fiat</h4>
                                                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                <form class="deposit-fiat-form" method="post" action="{{ url('/issuer/deposit_fiat') }}" enctype="multipart/layout                                       <option value="{{$value->id}}">{{$value->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="deposit-fiat-box df-box-1">
                                                        <div class="row">
                                                            <div class="df-heading">
                                 layout                               <h4>Bank Details</h4>
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
                                                                </div>
                                  layout                          </div>
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
                                  layout                                  <label class="df-lable" for="propertyLocation">Other Details</label>
                                                                    <input class="form-control" type="text" name="other details">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Send Request</button>
                                                </div>
                                                </form>
                                            </div>
                                            </div>
                                        </div> --}}
                                            <!-- Modal end  -->
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
                                                <form class="deposit-fiat-form" method="post" action="{{ url('issuer/deposit_crypto') }}" enctype="multipart/form-data">
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
                                                                        @foreach($coins as $index => $value)
                                                                        <option value="{{$value}}">{{$value}}</option>
                                                                        @endforeach
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
                                                                    <input class="form-control" type="number" name="amount" step="any">
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
                                                                <input class="form-control" type="text" id="AdminAddress" name="address" readonly>
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
                                                <button id="IssuerDepositFunds" class="btn btn-primary">Deposit</button>
                                            </div>
                                            </div>
                                        </div>
                                        </div> --}}
                                        <!-- End metamask modal -->

                                        <!-- End Left Size Box -->
                                        <div class="content w-100 p-0">
                                            <!-- Start container-fluid -->
                                            <div class="container-fluid p-0">
                                                <!-- Right Size Box -->
                                                <div class="col-sm-12 p-0">

                                                    <div class="details-container tab-content">
                                                        @foreach($coins as $key => $value)
                                                            @if($value != 'USD')
                                                                <h2 class="panel-title mb-3">Current Balance: {{number_format(@$user->$value,3)}} {{$value}}</h2>
                                                            @elseif($value == 'USD')
                                                                <h2 class="panel-title mb-3">Current Balance: {{number_format(@$user->$value,3)}} {{$value}}</h2>
                                                                <p>
                                                                    This is your internal wallet with your current balance {{number_format(@$user->$value,3)}} {{$value}}. Before any purchase you need to deposit funds in the internal wallet. There are multiple ways funds can be deposited to internal wallet.
                                                                </p>
                                                                <div class="tab-pane active wallet-new-container" id="d-flat_deposit">
                                                                    <h2 class="panel-title mb-5">
                                                                        Bank Transfer
                                                                    </h2>

                                                                    <form class="deposit-fiat-form" method="post" action="{{ url('/issuer/deposit_fiat') }}" enctype="multipart/form-data">
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
                                                                                    <input id="BankID" type="hidden" name="bank" value="">
                                                                                    <p>
                                                                                        Name: <b class="" id="BankName"></b>
                                                                                    </p>
                                                                                    <p>
                                                                                        Account Number: <b id="AccountNumber"></b>
                                                                                    </p>
                                                                                    <p>
                                                                                        Account Holder Name:  <b id="AccountHolder"></b>
                                                                                    </p>
                                                                                    <p>
                                                                                        Branch Name:  <b id="BranchName"></b>
                                                                                    </p>
                                                                                    {{-- <p>
                                                                                        IFSC Code: <b class="" id="IFSC_code"></b>
                                                                                    </p> --}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-5">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <div class="form-group">
                                                                                            <label class="df-lable" for="propertyLocation">Bank Account</label>
                                                                                            <select class="form-control" id="select-bank-id" name="bank" required>
                                                                                                <option>Select Bank</option>
                                                                                                @foreach($fields as $index => $value)
                                                                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
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
                                                                                    <input class="form-control" type="number" name="amount" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group">
                                                                                    <label class="df-lable" for="propertyLocation">Screenshot</label>
                                                                                    <input class="form-control" type="file" name="proof" accept="image/*,/pdf" required>
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

                                                                    <!-- End Deposit History -->
                                                                </div>
                                                            @endif
                                                        @endforeach

                                                        <!-- ETH Deposit Tab -->
                                                        <div class="card active wallet-new-container" id="eth_deposit">
                                                            <div class="card-header">
                                                            <h2 class="panel-title mb-5">Manual Deposit</h2>
                                                            <p>
                                                                You can manually deposit stable coin to admin's provided wallet addresses. Please select the coin from dropdown, deposit to admin address in step 2 and provide proof to admin for verification
                                                            </p>
                                                            </div>
                                                            <div class="card-body">
                                                            <!-- withdraw instruction -->
                                                                <section class="withdraw-instruction">
                                                                    <form class="deposit-fiat-form" method="post" action="{{ url('issuer/deposit_crypto') }}" enctype="multipart/form-data">
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
                                                                                        Selected coin :<b class="coin_type" > </b>
                                                                                    </p>
                                                                                    <p>
                                                                                        Coin Blockchain Address :  <b class="coin_address" >
                                                                                            </b>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-5">
                                                                                <div class="form-group">
                                                                                    <label class="df-lable" for="propertyLocation">Select Coin</label>
                                                                                    <select class="form-control" id="SelectCrypto" name="coin" required>
                                                                                        <option value="">Select Coin</option>
                                                                                        {{-- <option value="ETH">ETH</option>
                                                                                        <option value="BNB">BNB</option>
                                                                                        <option value="MATIC">MATIC</option> --}}
                                                                                        <option value="USDT">USDT</option>
                                                                                        <option value="USDC">USDC</option>
                                                                                        <option value="DIE">DIE</option>
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

                                                                                        </p>
                                                                                        <div class="cursor" id="deposit_address_data" title="Click to Copy" onclick="copyToClipboard('.eth-dp-address')">
                                                                                            <i class="far fa-copy"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-5">
                                                                                <!-- QR Code Block -->
                                                                                {{-- <div class="qr-custom-class-wrapper">
                                                                                    <div id="qrcode-ETH" class="qrcode-container img-thumbnail" data-width="180" data-height="180" data-text="{{Setting::get('admin_usdt_address')}}" title="{{Setting::get('admin_usdt_address')}}"> </div>
                                                                                </div> --}}
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
                                                                                    <input class="form-control" type="number" name="amount" step="any" required>
                                                                                    <input type="hidden" value="{{$admin->wallet_address}}" name="admin_address">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group">
                                                                                    <label class="df-lable" for="propertyLocation">Screenshot</label>
                                                                                    <input class="form-control" type="file" name="proof" accept="image/*,.pdf" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row m-0 steps-wrapper">
                                                                            <div class="col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label class="df-lable" for="propertyLocation">Transaction Hash</label>
                                                                                    <input class="form-control" type="text" name="hash" required>
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
                                                        </div>

                                                        <div class="card active wallet-new-container mt-3" id="meta_mask">
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
                                                                                <input type="hidden" id="AdminAddress">
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-5">
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
                                                                            <label class="df-lable" for="propertyLocation">Amount</label>
                                                                            <input class="form-control" type="number" id="DepositAmount" name="amount" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4" style="display: flex;">
                                                                        <button id="IssuerDepositFunds" class="btn btn-primary" style="margin-top: auto;display: block;">Deposit</button>
                                                                    </div>
                                                                </div> --}}

                                                                {{-- <div class="row m-0 steps-wrapper">
                                                                    <div class="col-lg-12">
                                                                        <div class="input-group-addon" style="display:none;" id="DepositDiv">
                                                                            <div class="row m-0">
                                                                                <div class="col-md-4 p-0">
                                                                                    <div class="form-group">
                                                                                    <label class="df-lable" for="propertyLocation">Select Asset</label>
                                                                                        <select class="form-control" id="SelectAssett" name="coin">
                                                                                            <option>Select Asset</option>
                                                                                            <option value="USDC">USDC (ETH)</option>
                                                                                            <option value="USDT">USDT (ETH)</option>
                                                                                            <option value="DIE">DIE (ETH)</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 px-md-3">
                                                                                    <div class="form-group">
                                                                                        <label class="df-lable" for="propertyLocation">Address</label>
                                                                                        <input class="form-control" type="text" id="AdminAddresss" name="address" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 p-0">
                                                                                    <div class="form-group">
                                                                                        <label class="df-lable" for="propertyLocation">Amount</label>
                                                                                        <input class="form-control" type="number" id="DepositAmountt" name="amount" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <button id="IssuerDepositFunds" class="btn btn-primary">Deposit</button>
                                                                        </div>
                                                                    </div>
                                                                </div> --}}

                                                                {{-- <div class="row m-0 steps-wrapper">
                                                                    <div class="col-lg-12">
                                                                        <p>Please do not close this window while transaction is executing</p>
                                                                    </div>
                                                                </div> --}}
                                                            {{-- </section> --}}
                                                            <div class="card-body">
                                                            <h2 class="panel-title mb-5">Deposit History</h2>

                                                            <!-- Deposit History -->
                                                            <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Time</th>
                                                                        <th>Transaction ID</th>
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
                                                                                <a href="{{ $transaction_link }}tx/{{ $eth_history->txn_hash }}" target="_blank">{{substr($eth_history->txn_hash, 0, 8).'****'}}</a>
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
                                                            <h2 class="panel-title">Deposit Fiat History</h2>

                                                            <!-- Deposit History -->
                                                            <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Time</th>
                                                                        <th>Amount</th>
                                                                        <th>Proof</th>
                                                                        <th>Status</th>
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
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                            </div>
                                                        </div>

                                                        {{-- <div class="tab-pane active" id="eth_deposit">

                                                            <h2 class="panel-title">Deposit</h2>

                                                            <!-- withdraw instruction -->
                                                            <section class="withdraw-instruction">
                                                                <div class="row">
                                                                    <div class="col-sm-1">
                                                                        <h4 class="steps">1</h4>
                                                                    </div>

                                                                    <div class="col-sm-11">
                                                                        <p>Welcome to platform wallet, Please follow steps to deposit crypto to your internal wallet.</p>
                                                                    </div>
                                                                </div>

                                                                <hr class="split">

                                                                <div class="row">
                                                                    <div class="col-sm-1">
                                                                        <h4 class="steps">2</h4>
                                                                    </div>
                                                                    <div class="col-sm-11">

                                                                        <!-- QR Code Block -->
                                                                        <div id="qrcode-ETH" class="qrcode-container img-thumbnail" data-width="180" data-height="180" data-text="{{$admin->wallet_address}}" title="{{$admin->wallet_address}}"> </div>
                                                                        <!-- End QR Code Block -->

                                                                        <p class="clearfix">Please paste the address below in your wallet, and fill in the amount you want to deposit, then confirm and send.</p>

                                                                        <div class="input-group col-sm-12 cpyInput">
                                                                            <div class="input-group-addon">
                                                                                <span>Address</span>
                                                                            </div>
                                                                            <div class="form-control form-control-static eth_deposit_address selectable" id="deposit_address">{{$admin->wallet_address}}</div>
                                                                            <div class="input-group-addon cursor" id="deposit_address_data" title="Click to Copy" onclick="copyToClipboard('.eth_deposit_address')">
                                                                                <i class="far fa-copy"></i>
                                                                            </div>
                                                                            <!-- <div class="input-group-addon">
                                                                                <a id="new_address" href="#"> New Address</a>
                                                                            </div> -->
                                                                        </div>
                                                                        <br>
                                                                        <div class="input-group-addon" style="display:none; width:150px;" id="DepositDiv">
                                                                            <button style="width:124px;" type="button" class="btn btn-primary" data-toggle="modal" data-bs-toggle="modal" data-target="#WalletDeposit" data-bs-target="#WalletDeposit">Deposit</button>
                                                                        </div>
                                                                        <br>
                                                                        <p class="text-center">Scan QR code to Pay through mobile terminal wallet.</p>

                                                                    </div>
                                                                </div>

                                                                <hr class="split">

                                                                <div class="row">
                                                                    <div class="col-sm-1">
                                                                        <h4 class="steps">3</h4></div>
                                                                    <div class="col-sm-11">
                                                                        <p>After following the steps please wait for transaction get settle in blockchain and wallet automatically sync</p>
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
                                                            <h2 class="panel-title">Deposit Fiat History</h2>

                                                            <!-- Deposit History -->
                                                            <table class="datatable-full table table-striped table-responsive table-bordered custom-table-style" cellspacing="0" width="100%">
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
                                                                                <a class="approveButton" href="{{url('issuer/cancel_deposit', $value->id)}}">Cancel</a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                            <!-- End Deposit History -->

                                                        </div> --}}
                                                        <!-- End ETH Deposit Tab -->

                                                        <!-- ETH Withdraw Tab -->
                                                        <div class="tab-pane" id="eth_withdraw">


                                                            <h2 class="panel-title">MATIC Withdraw</h2>
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

                                                        </div>
                                                        <!-- End ETH Withdraw Tab -->

                                                        <!-- LTC Deposit Tab -->
                                                        {{--<div class="tab-pane active" id="ltc_deposit">

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

                                                        </div>--}}
                                                        <!-- End LTC Deposit Tab -->

                                                        <!-- LTC Withdraw Tab -->
                                                        {{--<div class="tab-pane" id="ltc_withdraw">


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

                                                        </div>--}}
                                                        <!-- End LTC Withdraw Tab -->

                                                        <!-- BCH Deposit Tab -->
                                                        {{--<div class="tab-pane active" id="bch_deposit">

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

                                                        </div>--}}
                                                        <!-- End BCH Deposit Tab -->

                                                        <!-- BCH Withdraw Tab -->
                                                        {{--<div class="tab-pane" id="bch_withdraw">

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

                                                        </div>--}}
                                                        <!-- BCH LTC Withdraw Tab -->

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <!-- .row -->

                                </div>
                            </div>

                        </div>

                        </section>
                        {{-- <div class="alert-container">
                        <div class="alert">
                            Copied
                        </div>
                        </div> --}}
                    </div>
                </div>
            </div>
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
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.1/ethers.umd.min.js"></script>
<script src="{{ asset('asset/js/metamask.js') }}"></script>

<!-- Include SweetAlert CSS and JS via CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript" src="{{asset('js/jquery.qrcode.js')}}"></script>
<script type="text/javascript" src="{{asset('js/qrcode.js')}}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

<script>
    $("#SelectAsset").on("change", function(){
        asset = $(this).val();
        console.log(asset);
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
 $(document).ready(function(){
    $(".table-currencies").click(function() {
       $('.table-currencies').removeClass('introactive');
      $(this).addClass('introactive');
    });
});
</script>
<script type="text/javascript">
    var btc_address = $('#qrcode-BTC').data('text');
    $('#qrcode-BTC').qrcode(btc_address);
    var eth_address = $('#qrcode-ETH').data('text');
    $('#qrcode-ETH').qrcode(eth_address);


    var alert = $(".alert-container");
    alert.hide();

</script>
<script>
    $('#select-bank-id').on('change', function(){
        id = $(this).val()
        $.ajax({
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                url : "/issuer/get_bank_details/"+id,
                type : "GET",
            }).done(async function(res){
               if(res){
                console.log(res)
                    $('#BankName').text(res.name);
                    $('#BankID').val(res.id);
                    $('#IFSC_code').text(res.ifsc_code);
                    $('#AccountNumber').text(res.account_number);
                    $('#BranchName').text(res.branch_name);
                    $('#AccountHolder').text(res.account_name);
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
    $("#SelectCrypto").on('change', function(){
        console.log('comming');
        coin = $(this).val();
        if(coin == 'USDT'){
            $('.DepositAddress').text("{{Setting::get('admin_usdt_address')}}");
            $("#qrcode-ETH").attr("data-text", "{{Setting::get('admin_usdt_address')}}");
            $("#qrcode-ETH").attr("title", "{{Setting::get('admin_usdt_address')}}");
            $('.coin_address').text("{{Setting::get('usdt_address')}}");

        }else if(coin == 'USDC'){
            $('.DepositAddress').text("{{Setting::get('admin_usdc_address')}}");
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
