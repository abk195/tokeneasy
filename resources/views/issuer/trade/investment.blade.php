@extends('issuer.layout.base')
@section('content')
    <!-- Start Page Content here -->
    <div class="content-page-inner">

        <!-- Header Banner Start -->
        <div class="header-breadcrumbs">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1>Trades</h1>
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
                                        </svg> <span>Trades</span></a></li>
                            </ul>
                        </div>
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
                        <div class="container-fluid wizard-border">
                            <!-- start  -->
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <div>
                                        <table id="example1" class="table table-bordered">
                                        <thead>
                                                <tr>
                                                    <th>S.N0</th>
                                                    <th>Chain</th>
                                                    <th>Token Name</th>
                                                    <th>Token Symbol</th>
                                                    <th>Token Value</th>
                                                    <th>Available Tokens</th>
                                                    <th>Contract Address</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($user_contract as $key => $item)
                                                    <tr style="pointer-events: none">
                                                        <td>{{ @$key + 1 }}</td>
                                                        <td>{{ @$item->coin }}</td>
                                                        <td>{{ @$item->tokenname }}<br></td>
                                                        <td>{{ @$item->tokensymbol }}</td>
                                                        <td>{{ @$item->tokenvalue }}</td>
                                                        <td>{{ @$item->tokenbalance }}</td>
                                                        @if($item->coin == 'ETH')
                                                        <td style="pointer-events: all">
                                                            <a href="https://sepolia.etherscan.io/token/{{@$item->contract_address}}" target="_blank">{{@$item->contract_address}}</a>
                                                        </td>
                                                        @elseif($item->coin == 'BNB')
                                                        <td style="pointer-events: all">
                                                                <a href="https://testnet.bscscan.com/token/{{@$item->contract_address}}" target="_blank">{{@$item->contract_address}}</a>
                                                            </td>
                                                        @else
                                                        <td style="pointer-events: all">
                                                                <a href="https://amoy.polygonscan.com/token/{{@$item->contract_address}}" target="_blank">{{@$item->contract_address}}</a>
                                                            </td>
                                                        @endif
                                                        <td>{{ @$item->status == 1 ? 'Success' : 'Pending' }}</td>
                                                        <td style="pointer-events: all;">
                                                            <a class="btn btn-primary" onclick="TriggerModel('{{@$item->property->propertyName}}', '{{@$item->property->id}}', '{{@$item->tokenvalue}}', '{{@$item->id}}')">Sell</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end content -->
                </div>
            </div>
        </div>


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

    </div>
    <!-- END content-page -->

<!-- Model start -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content" style="width:190%; margin-left:-40%;">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Trade</h5>
        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
    <form method="post" action="{{ url('issuer/place_trade') }}" enctype="multipart/form-data">
    @csrf()
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <input type="hidden" name="property_id" id="PropertyID" value="">
                    <input type="hidden" name="token_value" id="TokenValue" value="">
                    <input type="hidden" name="contract_id" id="CantractID" value="">
                    <label for="propertyName">Property Name</label>
                    <input class="form-control" type="text" name="property_name" id="PropertyName" readonly>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="propertyLocation">No of Tokens</label>
                    <input class="form-control" type="text" name="no_of_tokens" id="NoOfTokens">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="propertyLocation">Token Value</label>
                    <input class="form-control" type="text" name="total_token_value" id="TotalTokenValue" readonly>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="propertyLocation">Buy</label>
                    <select class="form-control" name="buy" id="SelectedCoin">
                        <option>Select Buy type</option>
                        @foreach($coins as $coin)
                        <option value="{{$coin}}">{{$coin}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="propertyLocation">Get value</label>
                    <input class="form-control" type="number" step="any" name="get_value" id="GetValue">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Place Order</button>
    </div>
    </form>
    </div>
</div>
</div>
<!-- Modal end  -->
@endsection
@section('scripts')
    <script>
        function TriggerModel(name, id, value, contract_id) {
            $('#PropertyID').val(id);
            $('#TokenValue').val(value);
            $('#PropertyName').val(name);
            $('#CantractID').val(contract_id);
            $('#exampleModal').modal('show');
        }

        $('#NoOfTokens').on('keyup', function(){
            count = $(this).val()
            value = $('#TokenValue').val();
            total_value = count * value;
            $('#TotalTokenValue').val(total_value);
        });

        $('#SelectedCoin').on('change', function(){
            coin = $(this).val();
            alert(coin);
            $.ajax({
                url: '/issuer/get_live_balance/' + coin,
                type: "GET",
            }).done(function(res){
                console.log(res)
                tokenValue = $('#TotalTokenValue').val();
                sellValue = tokenValue / res;
                $('#GetValue').val(sellValue.toFixed(5));
            })
        });
    </script>
@endsection
