@extends('layout.app')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-content">

        <div class="header-breadcrumbs">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6"><h1>Open Trades</h1></div>
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
                                        <span>Open Trades</span>
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
                                                    <th>S.No</th>
                                                    <th>Property Name</th>
                                                    <th>No of Token</th>
                                                    <th>Total Token Value</th>
                                                    <th>Buy</th>
                                                    <th>Buy Value</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($trades as $index => $value)
                                            <tr>
                                                <td>{{$index+1}}</td>
                                                <td>{{$value->property->propertyName}}</td>
                                                <td>{{$value->no_of_tokens}}</td>
                                                <td>$ {{$value->value_of_tokens}}</td>
                                                <td>{{$value->buy}}</td>
                                                <td>{{$value->buy_value}} {{$value->buy}}</td>
                                                <td>{{$value->status}}</td>
                                                <td>
                                                    <a href="{{ url('/cancel_trade', $value->id) }}" class="btn btn-danger">Cancel</a>
                                                </td>
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
        {{-- </div> --}}

    </div>
    <!-- Property Tab Ends -->
@endsection


@section('scripts')

@endsection
