@extends('layout.app')

@section('content')

    <style>
        .container {
            width: 100% !important;
        }
    </style>
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="header-breadcrumbs">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6"><h1>Investments</h1></div>
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
                                        <span>Investments</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="content">
            <!-- Start container-fluid -->
            <div class="container-fluid wizard-border">
                <!-- Property Tab Starts -->
                <div class="property-tab">

                    {{-- <div class="pro-tab-wrap">
                        <div class="container">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active" style="display: none"><a href="#my_earnings" role="tab" data-toggle="tab"></a></li>
                            </ul>
                        </div>
                    </div> --}}
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

                                        <table class="datatable-full table table-striped table-bordered custom-table-style"
                                            cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Property</th>
                                                    <th>Purchase Dt</th>
                                                    <th>Token Price</th>
                                                    <th>No of Token</th>
                                                    <th>Total Token Value</th>
                                                    <th>Transaction Status</th>
                                                </tr>
                                            </thead>

                                            @foreach ($user_tokens as $token)
                                                <tr>
                                                    <td>{{ @$token->usercontract->property->propertyName }}</td>
                                                    <td>{{ @date('Y-m-d: H:m:s', strtotime($token->created_at)) }}</td>
                                                    <td>{{ @$token->usercontract->tokenvalue }}$</td>
                                                    <td>{{ @$token->number_of_token }} {{ @$token->tokenSymbol }}</td>
                                                    <td>{{ number_format(@$token->number_of_token * @$token->usercontract->tokenvalue, 6, '.', '') }}$</td>
                                                    <td style="color:green">Success</td>
                                                </tr>
                                            @endforeach
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
        {{-- </div> --}}
    </div>
    <!-- Property Tab Ends -->
@endsection
