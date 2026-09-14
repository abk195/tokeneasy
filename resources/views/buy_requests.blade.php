@php
    $statusColors = [
        'inProgress' => 'orange',
        'inReview'   => 'blue',
        'success'    => 'green',
        'reject'     => 'red',
        'failed'    => 'red'
    ];
@endphp

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


                    <!-- Tab panes -->
                    <div class="pro-content-tab-wrap">
                        <div class="section">
                            <div class="tab-content">

                                <!-- my Earnings Tab Starts -->
                                <div role="tabpanel" class="tab-pane active show" id="my_earnings">
                                    <!--Table -->
                                    <section class="container table-property">

                                        <table class="datatable-full table table-striped table-bordered custom-table-style"
                                            cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Asset Name</th>
                                                    <th>Issuer Name</th>
                                                    <th>Tokens purchase</th>
                                                    <th>Total Amount</th>
                                                    <th>Status</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($requests))
                                                    @foreach($requests as $index => $value)
                                                    <tr>
                                                        <td>{{$value->property ? $value->property->propertyName : 'N/A'}}</td>
                                                        <td>{{$value->property ? $value->property->getIssuerDetails()->name : 'N/A'}}</td>
                                                        <td>{{$value->token_acquire}}</td>
                                                        <td>{{ number_format((float)($value->commission ?? 0) + (float)($value->deal_amount ?? 0), 2) }}</td>
                                                        <td style="color: {{ $statusColors[$value->status] ?? 'gray' }};">
                                                            {{ ucfirst($value->status) }}
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                @endif
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
