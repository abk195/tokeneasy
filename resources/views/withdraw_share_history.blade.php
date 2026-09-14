@extends('layout.app')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-content">
        <div class="pro-breadcrumbs">
            <div class="container">
                <a href="{{ url('/dashboard') }}" class="pro-breadcrumbs-item">Home</a>
                <span>/</span>
                <a href="#" class="pro-breadcrumbs-item">External Withdraw Share History</a>
            </div>
        </div>
        <!-- End Breadcrumb -->
        <!-- Property Head Starts -->
        <div class="property-head grey-bg pt30">
            <div class="container">
                <div class="property-head-btm row">
                    <div class="col-md-12">
                        <h2 class="pro-head-tit">External Withdraw Share History</h2>
                        <p class="pro-head-txt">Hello, {{auth()->user()->name}}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Property Head Ends -->

        <!-- Property Tab Starts -->
        <div class="property-tab">
            
            <div class="pro-tab-wrap">
                <div class="container">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active" style="display: none"><a href="#my_earnings" role="tab" data-toggle="tab" data-bs-toggle="tab"></a></li>
                    </ul>
                </div>
            </div>
            <!-- Tab panes -->
            <div class="pro-content-tab-wrap">
                <div class="section">
                    <div class="tab-content">
                        <!-- Dashboard Tab Starts -->
                        
                        <!-- Dashboard Tab Ends -->
                        <!-- my Earnings Tab Starts -->
                        <div role="tabpanel" class="tab-pane active show" id="my_earnings">
                            <!--Table -->
                            <section class="container table-property">
                            <h5 class="mb-1">Select History Type</h5>
                            <select class="form-control" name="type" id="walletType" style="width:20%;">
                                <option value="{{ url('/trade_history') }}" 
                                    {{ request()->segment(1) === 'trade_history' ? 'selected' : '' }}>
                                    Trade History
                                </option>
                                <option value="{{ url('/external_withdraw') }}" 
                                    {{ request()->segment(1) === 'external_withdraw' ? 'selected' : '' }}>
                                    External Withdraw History
                                </option>
                            </select></br>
                            
                                <table class="datatable-full table table-striped table-bordered custom-table-style"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Date</th>
                                            <th>Token Name</th>
                                            <th>No of Token</th>
                                            <th>Address</th>
                                            <th>Hash</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($withdraw_shares as $index => $value)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$value->created_at}}</td>
                                        <td>{{$value->token_name}}</td>
                                        <td>{{$value->amount}} {{$value->symbol}}</td>
                                        <td>{{$value->address}}</td>
                                        <td>{{substr($value->trx_hash, 0, 8). '****' . substr($value->trx_hash, -8)}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </section>
                            <!-- End Table -->

                        </div>
                        <!-- Tax Tab Ends -->
                        </div>
                        <!-- Trades Tab Ends -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Property Tab Ends -->
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#walletType').on('change', function() {
            const selectedValue = $(this).val();
            if (selectedValue) {
                window.location.href = selectedValue; // Redirect to the selected URL
            }
        });
    });
</script>

@section('scripts')
    
@endsection
