@extends('layout.app')

@section('content')
<style>

    .container {
        padding: 0px;
    }
    .panel-body {
        padding: 12px;
    }

    .currency-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .mb-3 {
        margin-bottom: 32px !important;
    }
    .mb-5 {
        margin-bottom: 50px !important;
    }
    .panel-currencies {
        padding: 20px !important;
        border: 1px solid #e0e0e0;
        border-radius: 16px;
        background: #fff;
    }
</style>
<!-- Breadcrumb -->
<div class="page-content">

      <!-- Header Banner Start -->
      <div class="header-breadcrumbs">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h1>Withdraw</h1></div>
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
                                    <span>Withdraw</span>
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
            <section class="container spaceall wallet-full">
                <!-- Top Details -->
                <div class="container">
                    <!-- Left Side Box-->
                    <div class="col-12">
                        @foreach($coins as $coin)
                        <div class="currencies-container">
                            {{-- <table class="table table-currencies introactive">
                                <tbody>
                                    <tr class="currency-item" class="nav-item nav-link" href="#eth_deposit" data-toggle="tab">
                                        <td class="currency-logo"><img src="{{asset('asset/package/images/wallet/'.strtolower($coin).'.png')}}" alt=""><span class="currency-symbol">{{$coin}}</span></td>
                                        <td class="currency-balance-col">
                                            <p class="currency-balance"><span class="currency-sign"></span>{{ number_format($user->$coin,6) }} {{$coin}}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> --}}
                            <h2 class="currency-title mb-3">Current Balance: {{number_format(@$user->$coin,6)}} {{$coin}}</h2>

                        </div>
                        @endforeach
                        <div class="currencies-container">
                            {{-- <a href="#" data-toggle="modal" data-bs-toggle="modal" class="btn btn-primary" style="margin-bottom: 20px;" data-target="#exampleModal" data-bs-target="#exampleModal">

                                Withdraw Shares
                            </a> --}}
                        </div>
                    </div>
                    <!-- End Left Size Box -->
                    <div class="panel panel-currencies">
                        <div class="">
                            <div class="row m-0">
                                <div class="col-12">
                                    <div class="details-container tab-content">
                                        <!-- ETH WITHDRAWAL Tab -->
                                        <div class="tab-pane active show" id="withdraw_share">

                                            <h2 class="panel-title mb-5">Withdraw Share</h2>
                                            <form class="deposit-fiat-form" method="post" action="{{ url('/withdraw_share') }}" enctype="multipart/form-data">
						@csrf
                                                <div class="deposit-fiat-box mb-3">
                                                    <div class="row">
                                                        <div class="df-heading">
                                                            <h4>Select Share</h4>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="df-lable" for="propertyLocation">Select Share</label>
                                                                <select class="form-control" id="SelectShare" name="id">
                                                                    <option>Select Share</option>
                                                                    @foreach($user_tokens as $index => $value)
                                                                    <option value="{{$value->usercontract->property_id}}" >{{$value->usercontract->tokenname}} ({{$value->usercontract->tokensymbol}})</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="deposit-fiat-box df-box-1 mb-3">
                                                    <div class="row">
                                                        <div class="df-heading">
                                                            <h4>Share Details</h4>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group bk-form-group">
                                                                        <label class="df-lable" for="propertyName">Available balance</label>
                                                                        <span class="bk-m-colon">:</span>
                                                                        <input id="BankID" type="hidden" name="bank" value="">
                                                                        <p class="df-value" id="BankName">---</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group bk-form-group">
                                                                        <label class="df-lable" for="propertyLocation">Token Value ($)</label>
                                                                        <span class="bk-m-colon">:</span>
                                                                        <p class="df-value" id="IFSC_code">---</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group bk-form-group">
                                                                        <label class="df-lable" for="propertyLocation">Symbol</label>
                                                                        <span class="bk-m-colon">:</span>
                                                                        <p class="df-value" id="AccountNumber">---</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group bk-form-group">
                                                                        <label class="df-lable" for="propertyLocation">Chain</label>
                                                                        <span class="bk-m-colon">:</span>
                                                                        <p class="df-value" id="BranchName">---</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group bk-form-group">
                                                                        <label class="df-lable" for="propertyLocation">Contract Address</label>
                                                                        <span class="bk-m-colon">:</span>
                                                                        <p class="df-value" id="ContractAddress">---</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="deposit-fiat-box mb-3">
                                                    <div class="row">
                                                        <div class="df-heading">
                                                            <h4>Withdraw</h4>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="df-lable" for="propertyLocation">Amount</label>
                                                                <input id="Amount" class="form-control" type="number" name="amount" placeholder="Enter Amount">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="df-lable" for="propertyLocation">Wallet address</label>
                                                                {{-- <input id="WalletAddress" class="form-control" type="text" name="address" placeholder="Enter your wallet address"> --}}
                                                                <select class="form-control" name="address" id="SelectWalletAddress">
                                                                    <option value="">Select address</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6" id="AddAnotherAccount" style="display:none;">
                                                            <div class="form-group">
                                                                <label class="df-lable" for="propertyLocation">Enter your address</label>
                                                                <input class="form-control" type="text" name="wallet_address" placeholder="Enter Address">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" id="SubmitText" class="btn btn-primary mb-3">Send Request</button>
                                            </form>
                                        </div>
                                        <!-- End ETH WITHDRAWAL Tab -->

                                    </div>
                                </div>

                                <!-- Right Size Box -->
                                <div class="col-12">

                                    <div class="details-container tab-content">
                                        <!-- ETH WITHDRAWAL Tab -->
                                        <div class="tab-pane active show" id="btc_deposit">

                                            <h2 class="panel-title">WITHDRAW</h2>
                                            <br>
                                            <!-- withdraw instruction -->
                                            <section class="withdraw-instruction">

                                                <div class="row m-0">
                                                    <div class="col-sm-1">
                                                        <h4 class="steps">1</h4>
                                                    </div>
                                                    <div class="col-sm-11">
                                                        <form id="sendCoinForm" action="{{ url('sendETH') }}" method="post">
                                                            @csrf
                                                            <div class="">
                                                                <div class="form-group">
                                                                    <label for="amount">Select Chain</label>
                                                                    <select class="form-control non-negative" id="coin" name="chain" required>
                                                                        <option value="">Select Chain</option>
                                                                        @foreach($coins as $coin)
                                                                        <option value="{{$coin}}">{{$coin}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="amount">Enter Amount</label>
                                                                    <input type="number" class="form-control non-negative" id="amount" name="amount" placeholder="Enter Amount" value="" required min="0" step="any">
                                                                </div>
                                                                <div class="form-group" id="ETH_Address">
                                                                    <label for="address">Enter Address</label>
                                                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address">
                                                                </div>
                                                                <div class="form-group FiatWithdraw" style="display:none;">
                                                                    <label for="amount">Enter Account Number</label>
                                                                    <input type="text" class="form-control" id="account" name="account" placeholder="Enter Account">
                                                                </div>
                                                                <div class="form-group FiatWithdraws" style="display:none;">
                                                                    <label for="amount">Enter IFSC Code</label>
                                                                    <input type="text" class="form-control" id="ifsc" name="ifsc_code" placeholder="Enter IFSC Code">
                                                                </div>
                                                                <div class="form-group FiatWithdraw" style="display:none;">
                                                                    <label for="amount">Enter Bank Name</label>
                                                                    <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Enter Bank Name">
                                                                </div>
                                                                <div class="form-group FiatWithdraw" style="display:none;">
                                                                    <label for="amount">Enter Account Holder Name</label>
                                                                    <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Enter Account Holder Name">
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="submit" class="btn btn-primary sectionHide" value="Send">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <hr class="split">

                                                <div class="row">
                                                    <div class="col-sm-1">
                                                        <h4 class="steps">2</h4>
                                                    </div>
                                                    <div class="col-sm-11">
                                                        <p>Once you complete sending, you can check the status of your new withdrawal below.</p>
                                                    </div>
                                                </div>

                                            </section>

                                            <!-- End withdraw instruction -->

                                            <br>
                                            <h2 class="panel-title">Withdrawal History</h2>

                                            <!-- Deposit History -->
                                            <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Time</th>
                                                        <th>Coin</th>
                                                        <th>Transaction ID</th>
                                                        <th>To Address</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Status Info</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($history as $eth_history)
                                                    <tr>
                                                        <td>{{$eth_history->created_at}}</td>
                                                        <td>{{$eth_history->coin}}</td>
                                                        @if($eth_history->coin == "ETH")
                                                        <td>
                                                            <a href="https://sepolia.etherscan.io/tx/{{ $eth_history->tx_hash }}" target="_blank">{{substr($eth_history->tx_hash, 0, 8).'****'}}</a>
                                                        </td>
                                                        <td>
                                                            <a href="https://sepolia.etherscan.io/address/{{ $eth_history->receiver }}" target="_blank">{{substr($eth_history->receiver, 0, 8).'****'}}</a>
                                                        </td>
                                                        @elseif($eth_history->coin == "BNB")
                                                        <td>
                                                            <a href="https://testnet.bscscan.com/tx/{{ $eth_history->tx_hash }}" target="_blank">{{substr($eth_history->tx_hash, 0, 8).'****'}}</a>
                                                        </td>
                                                        <td>
                                                            <a href="https://testnet.bscscan.com/address/{{ $eth_history->receiver }}" target="_blank">{{substr($eth_history->receiver, 0, 8).'****'}}</a>
                                                        </td>
                                                        @else
                                                        <td>
                                                            <a href="https://amoy.polygonscan.com/tx/{{ $eth_history->tx_hash }}" target="_blank">{{substr($eth_history->tx_hash, 0, 8).'****'}}</a>
                                                        </td>
                                                        <td>
                                                            <a href="https://amoy.polygonscan.com/address/{{ $eth_history->receiver }}" target="_blank">{{substr($eth_history->receiver, 0, 8).'****'}}</a>
                                                        </td>
                                                        @endif
                                                        <td>{{digitround($eth_history->amount)}}</td>
                                                        <td>{{strtoupper($eth_history->status)}}</td>
                                                        <td><i class="fa fa-info" rel="tooltip" title="{{ $eth_history->reason }}" id="tip_{{ $eth_history->id}}"></i></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <!-- End Deposit History -->

                                            <!-- Fiat History -->
                                            <br>
                                            <h2 class="panel-title">USD Withdrawal History</h2>

                                            <!-- Deposit History -->
                                            <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Time</th>
                                                        <th>Coin</th>
                                                        <th>To Address</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($fiat_history as $usd_history)
                                                    <tr>
                                                        <td>{{$usd_history->created_at}}</td>
                                                        <td>{{$usd_history->coin}}</td>
                                                        <td>
                                                            <?php $details = json_decode($usd_history->receiver);
                                                            ?>
                                                            Bank Name : {{$details[2]}},<br>
                                                            Account : {{$details[0]}}
                                                        </td>
                                                        <td>{{$usd_history->amount}}</td>
                                                        <td>{{strtoupper($usd_history->status)}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <!-- End History -->

                                        </div>
                                        <!-- End ETH WITHDRAWAL Tab -->

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


                    <!-- Model start -->
                    {{-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog df-modal-xl" role="document">
                            <div class="modal-content" style="">
                            <div class="modal-header deposit-fiat-header">
                                <h4 class="modal-title" id="exampleModalLabel">Withdraw Share</h4>
                                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <form class="deposit-fiat-form" method="post" action="{{ url('/withdraw_share') }}" enctype="multipart/form-data">
                            @csrf()
                            <div class="deposit-fiat-box">
                                    <div class="row">
                                        <div class="df-heading">
                                            <h4>Select Share</h4>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="df-lable" for="propertyLocation">Select Share</label>
                                                <select class="form-control" id="SelectShare" name="id">
                                                    <option>Select Share</option>
                                                    @foreach($user_tokens as $index => $value)
                                                    <option value="{{$value->usercontract->property_id}}" >{{$value->usercontract->tokenname}} ({{$value->usercontract->tokensymbol}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="deposit-fiat-box df-box-1">
                                    <div class="row">
                                        <div class="df-heading">
                                            <h4>Share Details</h4>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bk-form-group">
                                                        <label class="df-lable" for="propertyName">Available balance</label>
                                                        <span class="bk-m-colon">:</span>
                                                        <input id="BankID" type="hidden" name="bank" value="">
                                                        <p class="df-value" id="BankName">---</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group bk-form-group">
                                                        <label class="df-lable" for="propertyLocation">Token Value ($)</label>
                                                        <span class="bk-m-colon">:</span>
                                                        <p class="df-value" id="IFSC_code">---</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bk-form-group">
                                                        <label class="df-lable" for="propertyLocation">Symbol</label>
                                                        <span class="bk-m-colon">:</span>
                                                        <p class="df-value" id="AccountNumber">---</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group bk-form-group">
                                                        <label class="df-lable" for="propertyLocation">Chain</label>
                                                        <span class="bk-m-colon">:</span>
                                                        <p class="df-value" id="BranchName">---</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bk-form-group">
                                                        <label class="df-lable" for="propertyLocation">Contract Address</label>
                                                        <span class="bk-m-colon">:</span>
                                                        <p class="df-value" id="ContractAddress">---</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="deposit-fiat-box">
                                    <div class="row">
                                        <div class="df-heading">
                                            <h4>Withdraw</h4>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="df-lable" for="propertyLocation">Amount</label>
                                                <input id="Amount" class="form-control" type="number" name="amount" placeholder="Enter Amount">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="df-lable" for="propertyLocation">Wallet address</label>
                                                <select class="form-control" name="address" id="SelectWalletAddress">
                                                    <option value="">Select address</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="AddAnotherAccount" style="display:none;">
                                            <div class="form-group">
                                                <label class="df-lable" for="propertyLocation">Enter your address</label>
                                                <input class="form-control" type="text" name="wallet_address" placeholder="Enter Address">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                                <button type="submit" id="SubmitText" class="btn btn-primary">Send Request</button>
                            </div>
                            </form>
                            </div>
                        </div>
                    </div> --}}
                    <!-- Modal end  -->

    <div class="alert-container">

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

<script type="text/javascript" src="{{asset('js/jquery.qrcode.js')}}"></script>
<script type="text/javascript" src="{{asset('js/qrcode.js')}}"></script>
<script>

    $(document).ready(function(){
        $('#SubmitText').prop('disabled', true);
    })

    $('#SelectWalletAddress').change(function(){
        address = $(this).val();
        property = $('#SelectShare').val();
        if(property != 'Select Share'){
            $.ajax({
            headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url : "/check_whitelist/"+property+"/"+address,
                type : "GET",
            }).done(function(res){
                console.log(res);
                if(res == 'Verified'){
                    $('#SubmitText').text('Withdraw')
                    $('#SubmitText').prop('disabled', false);
                }else if(res == 'Unverified'){
                    $('#SubmitText').text('Send Request');
                    $('#SubmitText').prop('disabled', false);
                }
            })
        }else{
            alert('Please choose property')
        }
    });

    $('#SelectWalletAddress').on('change', function(){
        if($(this).val() == 'another_account'){
            $('#AddAnotherAccount').show();
        }else{
            $('#AddAnotherAccount').hide();
        }
    })




    $('#SelectShare').change(async function () {
        id = $(this).val();
        try {
            await $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/get_propert_details/" + id,
                type: "GET",
            }).then(function(result){
                console.log(result);
                if(result.status == 'success'){
                    $('#BankName').text(result.user_token.token_acquire);
                    $('#AccountNumber').text(result.user_token.usercontract.tokensymbol);
                    $('#IFSC_code').text(result.user_token.usercontract.tokenvalue);
                    $('#BranchName').text(result.user_token.usercontract.coin);
                    $('#ContractAddress').text(result.user_token.usercontract.contract_address);

                    const $select = $('#SelectWalletAddress');
                    CheckInvest = result.check_invest;
                    if(CheckInvest.length > 0){
                        $select.empty();
                        $select.append('<option value="">Select an account</option>');

                        $.each(result.check_invest, function(index, value){
                            const option = `<option value="${value.wallet_address}">${value.wallet_address}</option>`;
                            $select.append(option);
                        });
                        $select.append('<option value="another_account">Add another Address</option>')
                    }else{
                        $select.append('<option value="another_account">Add another Address</option>')
                    }
                }
            }).catch((err)=>{
                alert('Something went wrong');
            });
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    });
</script>
<script type="text/javascript">

    $('#coin').on('change', function(){
        coin = $(this).val();
        if(coin == 'USD'){
            $('#ETH_Address').hide();
            $('.FiatWithdraw').show();
        }else{
            $('#ETH_Address').show();
            $('.FiatWithdraw').hide();
        }
    })

    $('#generateOTP').click(function(e){
        $.ajax({
                   url: "{{url('/generate/withdrawOTP')}}?type=ETH",
                   type: "GET",
               }).done(function(response){
                   $('.sectionShow').hide();
                   $('.sectionHide').show();
                   alert(response.success.msg);
        }).fail(function(jqXhr,status){
        });
    });

    {{--    $('#generateOTP').click(function(e){--}}
    {{--        $('#generateOTP').attr("disabled", true);--}}
    {{--        $('alert').hide();--}}
    {{--        e.preventDefault();--}}
    {{--        $.ajaxSetup({--}}
    {{--            headers: {--}}
    {{--              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
    {{--            }--}}
    {{--        });--}}
    {{--        $.ajax({--}}
    {{--            url: "{{url('/generate/ethwithdraw')}}" ,--}}
    {{--            type: "POST",--}}
    {{--            data: $('#sendCoinForm').serialize(),--}}
    {{--            success: function(response, textStatus, xhr) {--}}
    {{--                $('.alert-danger').hide();--}}
    {{--                $('.alert-danger').html('');--}}
    {{--                $('#withdrawotpsection').show();--}}
    {{--                $('#generateOTP').hide();--}}
    {{--                $('#sendToken').show();--}}
    {{--                $('#sendCoinForm').attr('method','POST');--}}
    {{--                $('#sendCoinForm').attr('action',"{{ url('/sendETH') }}");--}}
    {{--                $('.alert-success').show();--}}
    {{--                $('.alert-success').html('');--}}
    {{--                $('.alert-success').html('<p><i class="fa fa-check"></i>'+response.success.msg+'</p>');--}}
    {{--            },--}}
    {{--            error: function (jqXHR, textStatus, errorThrown) {--}}
    {{--                $('.alert-danger').html('');--}}

    {{--                console.log(jqXHR);--}}

    {{--                console.log(jqXHR.status);--}}
    {{--                console.log(textStatus);--}}

    {{--                console.log(errorThrown);--}}
    {{--                if(jqXHR.status == 400){--}}
    {{--                    var errors = $.parseJSON(jqXHR.responseText);--}}
    {{--                    console.log(errors);--}}
    {{--                    console.log(errors.error);--}}
    {{--                    console.log(errors.error.msg);--}}


    {{--                    $('#alert-danger').html(errors.error.msg);--}}
    {{--                    $('#generateOTP').attr("disabled", false);--}}
    {{--                }else{--}}
    {{--                    var errors = $.parseJSON(jqXHR.responseText);--}}
    {{--                    console.log(errors);--}}
    {{--                    console.log(errors.errors);--}}
    {{--                    var errorString = '<ul>';--}}
    {{--                    $.each(errors.errors, function( key, value) {--}}
    {{--                        errorString += '<li>' + value + '</li>';--}}
    {{--                    });--}}
    {{--                    errorString += '</ul>';--}}
    {{--                    // alert(errorString);--}}
    {{--                    $('#alert-danger').append(errorString);--}}
    {{--                    $('#alert-danger').show()--}}
    {{--                    $('#generateOTP').attr("disabled", false);--}}
    {{--                }--}}
    {{--            }--}}
    {{--        });--}}
	{{--});--}}

</script>

<script>
    $(document).on('keypress', '.non-negative', function(e) {

        if ($(this).hasClass("only-integer")) {
            if (e.which == 46) {
                return false;
            }
        }

        if (isNaN(this.value + String.fromCharCode(event.keyCode)) || (e.which == 32)) {
            return false;
        }
    });
</script>
@endsection
