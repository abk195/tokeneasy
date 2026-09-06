@extends('issuer.layout.base')
@section('content')
<!-- Start Page Content here -->
<div class="content-page-inner">

    <!-- Header Banner Start -->
    <div class="header-breadcrumbs">
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h1>Tokens Requested</h1></div>
            <div class="col-sm-6">
                @include('issuer.layout.breadcrumb',['items' => [
                    [
                        'url' => 'issuer/dashboard',
                        'title' => 'Dashboard'
                    ],
                    [
                        'title' => 'Tokens Requested'
                    ]
                ]])
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
                            <div class="alert alert-info">
                                <strong>Note:</strong> Tokens are deployed as soon as they are issued &mdash; no admin
                                approval is needed. Anything listed here did not finish deploying. The most common
                                cause is the deployment wallet having no balance for gas fees.
                            </div>
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
                                        <th>Token Supply</th>
                                        {{-- <th>Status</th> --}}
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tokens as $key => $item)
                                            <tr style="pointer-events: none">
                                                <td>{{ @$key + 1}}</td>
                                                {{--
                                                <td>{{ @$item->tokenname }}</td>
                                                <td>{{ @$item->tokensymbol }}</td>
                                                <td>{{ @$item->tokenvalue}}</td>
                                                <td>{{ @$item->tokensupply }}</td>
                                                <td>{{ (!is_null($item->contract_address)) ? @$item->contract_address : 0 }}</td>
                                                <td>{{ ($item->status == 1) ? 'Success' : 'Pending' }}</td>
                                                --}}
                                                <td>{{ @$item->coin }}</td>
                                                <td>{{ @$item->name }}</td>
                                                <td>{{ @$item->symbol }}</td>
                                                <td>{{ @$item->usdvalue }}</td>
                                                <td>{{ @$item->supply }}</td>
                                                {{-- @if(@$item->status=='pending')
                                                <td style="color:orange">Awaiting For Approval</td>
                                                @elseif(@$item->status=='live')
                                                <td style="color:green">Approved</td>
                                                @else
                                                <td style="color:red">Approval Denied</td>
 --}}
                                                {{-- @endif --}}
                                                <td style="pointer-events: all;">
                                                    @if ($item->status != 'block')
                                                        <a href="{{ route('token.edit', $item->property_id) }}"
                                                            class="btn btn-primary">Edit</a>
                                                    @else
                                                        <div class="text-danger">Rejected by Admin</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    </table>
                                </div>
                                </div>
                            </div>
                            <!-- end row -->


                        </div>
                        <!-- end container-fluid -->

                        <!-- Footer Start -->
                        <!-- <footer class="footer">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                    <ul class="social">
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    </ul>
                                        <p>Copyright © 2021 {{ $project_name }}. All rights reserved.</p>
                                    </div>
                                </div>
                            </div>
                        </footer> -->
                        <!-- end Footer -->
                    </div>
                    <!-- end content -->
                </div>
            </div>
        </div>

        <!-- Footer Start -->
    </div>
    <!-- END content-page -->
@endsection
