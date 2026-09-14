@extends('layout.app')

@section('content') 
<!-- Breadcrumb -->
<div class="page-content">
    <div class="pro-breadcrumbs">
        <div class="container">
            <a href="{{url('/dashboard')}}" class="pro-breadcrumbs-item">Home</a>
            <span>/</span>
            <a href="#" class="pro-breadcrumbs-item">Buy Token </a>
        </div>
    </div>
    <!-- End Breadcrumb -->
    <!-- Property Head Starts -->
    <div class="property-head grey-bg pt30">
        <div class="container">
            <div class="property-head-btm row">
                <div class="col-md-12">
                    <h2 class="pro-head-tit">Buy Token</h2>
                    <p class="pro-head-txt">Hello, {{@ $user->name}}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Property Head Ends -->

    <section class="container spaceall wallet-full">
        <!-- Top Details -->
        {{-- @include('common.notify') --}}
        <div class="container">

            <div class="panel panel-default">
                <div class="panel-body panel-currencies">

                    <div class="row">

                        <!-- Left Side Box-->
                        <div class="col-xs-4">
                            <div class="currencies-container nav nav-tabs">
                               
                                <!-- End Left Side Widget -->

                                <!-- Left Side Widget -->
                                <table class="table table-currencies introactive">
                                    <tbody>
                                        <tr class="currency-item" class="nav-item nav-link" href="#eth_deposit"   data-toggle="tab" data-bs-toggle="tab">

                                            <td class="currency-balance-col">
                                                <p class="currency-balance"><span class="currency-sign"></span>Step 1:- Choose a payment option like {{env('BASE_COIN')}} to buy your {{Setting::get('token_symbol','GMC')}} Token.</p>
                                            </td>
                                           
                                        </tr>
                                        
                                    </tbody>
                                </table>

                                <table class="table table-currencies introactive">
                                    <tbody>
                                        <tr class="currency-item" class="nav-item nav-link" href="#eth_deposit"   data-toggle="tab" data-bs-toggle="tab">

                                            <td class="currency-balance-col">
                                                <p class="currency-balance"><span class="currency-sign"></span>Step 2:- Specify your value of {{Setting::get('token_symbol','GMC')}} to buy through selected payment type.</p>
                                            </td>
                                           
                                        </tr>
                                        
                                    </tbody>
                                </table>

                                <table class="table table-currencies introactive">
                                    <tbody>
                                        <tr class="currency-item" class="nav-item nav-link" href="#eth_deposit"   data-toggle="tab" data-bs-toggle="tab">

                                            <td class="currency-balance-col">
                                                <p class="currency-balance"><span class="currency-sign"></span>Step 3:- Scan the QR-code or copy the address given below to buy {{Setting::get('token_symbol','GMC')}} through selected cryptocurrency.  </p>
                                            </td>
                                           
                                        </tr>
                                        
                                    </tbody>
                                </table>

                                <table class="table table-currencies introactive">
                                    <tbody>
                                        <tr class="currency-item" class="nav-item nav-link" href="#eth_deposit"   data-toggle="tab" data-bs-toggle="tab">

                                            <td class="currency-balance-col">
                                                <p class="currency-balance"><span class="currency-sign"></span>Step 4:- Please note that the address of {{env('BASE_COIN')}} is unique and it can be used only for one transaction.</p>
                                            </td>
                                           
                                        </tr>
                                        
                                    </tbody>
                                </table>
                                <!-- End Left Side Widget -->
                            </div>
                        </div>
                        <!-- End Left Size Box -->

                        <!-- Right Size Box -->
                        <div class="col-xs-8">

                            <div class="details-container tab-content">
                                <!-- ETH WITHDRAWAL Tab -->
                                <div class="tab-pane active show" id="btc_deposit">

                                    <h2 class="panel-title">BUY {{Setting::get('token_name','GMC')}}</h2>
                                    <!-- withdraw instruction -->
                                    <section class="withdraw-instruction">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <h4 class="steps">1</h4>
                                            </div>
                                            <label for="amount" style="padding:0px 13px">Payment Method</label>
                                                <div class="col-sm-11" style="padding:18px">
                                                
                                                    <div class="selector">
                                                        <div class="selecotr-item">
                                                            <input type="radio" id="buy" name="selector" class="selector-item_radio" checked>
                                                            <label for="{{strtolower(env('BASE_COIN'))}}" class="selector-item_label">{{env('BASE_COIN')}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <hr class="split">

                                        <div class="row">
                                            <div class="col-sm-1">
                                                <h4 class="steps">2</h4>
                                            </div>
                                            <div class="col-sm-11">
                                                <div class="form-group">
								                	<label for="quantity">Quantity</label>
								                	<input type="text" class="form-control" id="quantity" name="quantity">									
								                </div>
					                			<div class="form-group">
					                				<label for="total_payment">Total {{env('BASE_COIN')}}</label>
					                				<input type="text" class="form-control" id="total_payment" name="total_payment" value="" readonly>									
					                			</div>
                                            </div>
                                        </div>

                                        <hr class="split">

                                        <div class="row">
                                            <div class="col-sm-1">
                                                <h4 class="steps">3</h4>
                                            </div>
                                            <div class="col-sm-11">

                                                <!-- QR Code Block -->
                                                <div id="qrcode-ETH" class="qrcode-container img-thumbnail" data-width="180" data-height="180" data-text="{{@Auth::user()->payment_address}}" title="{{@Auth::user()->payment_address}}"> </div>
                                                 <!-- End QR Code Block -->

                                                <p class="clearfix">Scan QR code to get the payment address</p>

                                                <div class="input-group col-sm-12 cpyInput">
                                                    <div class="input-group-addon">
                                                        <span>Address</span>
                                                    </div>
                                                    <div class="form-control form-control-static eth_deposit_address selectable" id="deposit_address">{{@Auth::user()->payment_address}}</div>
                                                    <div class="input-group-addon cursor" id="deposit_address_data" title="Click to Copy" onclick="copyToClipboard('.eth_deposit_address')">
                                                        <i class="far fa-copy"></i>
                                                    </div>
                                                    <!-- <div class="input-group-addon">
                                                        <a id="new_address" href="#"> New Address</a>
                                                    </div> -->
                                                </div>


                                            </div>
                                        </div>

                                        <hr class="split">

                                        <div class="row">
                                            <div class="col-sm-1">
                                                <h4 class="steps">4</h4>
                                            </div>
                                            <div class="col-sm-11">
                                                <label>Once the funds are transferred , click the confirmation button to complete the purchase</label>
					                			<div class="form-group" style="margin:12px 200px">
					                				<button class="btn btn-success" onclick="checkPayment()">Confirm Payment</button>									
					                			</div>
                                            </div>
                                        </div>

                                    </section>

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
  *{
    margin:0;
    padding:0;
    box-sizing:border-box;
}
html,body{
    width:100%;
    height:100%;
}
:root{
    --white:#fff;
    --smoke-white:#f1f3f5;
    --blue:#4169e1;
}

.selector{
    position:relative;
    width:100%;
    background-color:var(--smoke-white);
    height:80px;
    padding:8px;
    display:flex;
    justify-content:space-around;
    align-items:center;
    /* border-radius:9999px; */
    box-shadow:0 0 16px rgba(0,0,0,.2);
}
.selecotr-item{
    position:relative;
    flex-basis:calc(70% / 3);
    height:100%;
    display:flex;
    justify-content:center;
    align-items:center;
}
.selector-item_radio{
    appearance:none;
    display:none;
}
.selector-item_label{
    position:relative;
    height:80%;
    width:100%;
    text-align:center;
    border-radius:9999px;
    line-height:400%;
    font-weight:900;
    transition-duration:.5s;
    transition-property:transform, color, box-shadow;
    transform:none;
}
.selector-item_radio:checked + .selector-item_label{
    background-color:#140749;
    color:var(--white);
    box-shadow:0 0 4px rgba(0,0,0,.5),0 2px 4px rgba(0,0,0,.5);
    transform:translateY(-2px);
}
@media (max-width:480px) {
	.selector{
		width: 90%;
	}
}
</style>
@endsection

@section('scripts')

<script type="text/javascript" src="{{asset('js/jquery.qrcode.js')}}"></script>
<script type="text/javascript" src="{{asset('js/qrcode.js')}}"></script>
<script type="text/javascript">
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

</script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        var eth_address = $('#qrcode-ETH').data('text');
    $('#qrcode-ETH').qrcode(eth_address);  
    function copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select(); 
      document.execCommand("copy");
      $temp.remove();  
      Swal.success('Copied')
    }
</script>

<script>
        var quantity,total_payment;
        var token_value = "{{Setting::get('token_value')}}";
        var payment = "{{strtolower(env('BASE_COIN'))}}";
        $("#quantity, #total_payment").change(function(){
            quantity = $('#quantity').val();
            total_payment = $('#total_payment').val();
            url = "{{ env('APP_URL') }}";
            $.get(url+"/coinprice?&type="+payment, function(data, status) {
                var live_price = data.last;
                console.log(live_price);
                if(quantity){
                    var total = live_price / (token_value * quantity);
                    $('#total_payment').val(total);
                }
            });
        });
    function checkPayment(){
        $.ajax({
              url: "{{env('COIN_URL')}}"+"/api?apikey=G5BZ6XU4VVT4C7VHBH9KA6J8UJU1DW2GEV&module=account&action=txlist&address={{@$user->payment_address}}&page=1&offset=1&sort=desc",
              type: "GET",                  
            })
            .done(function(response){
                console.log(response);
                Swal.fire(
                    'Transaction Completed',
                    'Tokens will be received to your wallet shortly',
                    'success'
                )
            })
            .error(function(jqXhr,status){
                if(jqXhr.status === 422) {
                   
                }
            })
    }
        
</script>

@endsection
