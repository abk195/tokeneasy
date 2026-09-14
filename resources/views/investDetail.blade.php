@extends('layout.app')

@section('content')
<!-- Breadcrumb -->
<div class="page-content">
    <div class="pro-breadcrumbs">
        <div class="container">
            <a href="{{url('/dashboard')}}" class="pro-breadcrumbs-item">Home</a>
            <span>/</span>
            <a href="#" class="pro-breadcrumbs-item">Prestige Polygon </a>
        </div>
    </div>
    <!-- End Breadcrumb -->
    <!-- Property Head Starts -->
    <div class="property-head grey-bg pt30">
        <div class="container">
            <div class="property-head-btm row">
                <div class="col-md-12">
                    <h2 class="pro-head-tit">Prestige Polygon </h2>
                    <p class="pro-head-txt">Hello, User</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Property Head Ends -->

    <section class="container">
        <!-- Top Details -->
        <div class="top-details-sec">
            <div class="row">

                <div class="col-xl-4 col-md-4 mb-4">
                    <div class="card border-0 text-light card-shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="homepage_single">

                                        <span class="bg-success text-center wb-icon-box bg_shedo_light_green"><i class="flaticon-bank" aria-hidden="true"></i></span>
                                        <div class="homepage_fl_right">
                                            <h4 class="mt-0">Net Investmnt</h4>
                                            <h3>6000$</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4 mb-4">
                    <div class="card border-0 text-light card-shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="homepage_single">

                                        <span class="bg-info text-center wb-icon-box bg_shedo_light"> <i class="flaticon-adjust" aria-hidden="true"></i> </span>
                                        <div class="homepage_fl_right">
                                            <h4 class="mt-0">Dividend</h4>
                                            <h3>2000$ </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 mb-4">
                    <div class="card border-0 text-light card-shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="homepage_single">

                                        <span class="bg-danger text-center wb-icon-box bg_shedo_light_red"><i class="flaticon-yield" aria-hidden="true"></i></span>
                                        <div class="homepage_fl_right">
                                            <h4 class="mt-0">Avg Yield</h4>
                                            <h3>6%</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 mb-4">
                    <div class="card border-0 text-light card-shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="homepage_single">

                                        <span class="bg-warning text-center wb-icon-box bg_shedo_light_yellow"><i class="flaticon-profit" aria-hidden="true"></i></span>
                                        <div class="homepage_fl_right">
                                            <h4 class="mt-0">Current Value</h4>
                                            <h3>9000$</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 mb-4">
                    <div class="card border-0 text-light card-shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="homepage_single">

                                        <span class="badge-black text-center wb-icon-box bg_shedo_light_black"><i class="flaticon-return" aria-hidden="true"></i></span>
                                        <div class="homepage_fl_right">
                                            <h4 class="mt-0">Net Returns</h4>
                                            <h3><span class="single-count">5000$</span></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 mb-4">
                    <div class="card border-0 text-light card-shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="homepage_single">

                                        <span class="badge-orange text-center wb-icon-box bg_shedo_light_orange"><i class="flaticon-wallet" aria-hidden="true"></i></span>
                                        <div class="homepage_fl_right">
                                            <h4 class="mt-0">Average IRR</h4>
                                            <h3>15%</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Property Tab Starts -->
    <div class="property-tab">
        <div class="pro-tab-wrap">
            <div class="container">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#in_finances" role="tab" data-toggle="tab" data-bs-toggle="tab">Finances</a></li>
                    <li><a href="#in_updates" role="tab" data-toggle="tab" data-bs-toggle="tab">Updates (5)</a></li>
                    <li><a href="#in_voting" role="tab" data-toggle="tab" data-bs-toggle="tab">Voting</a></li>
                    <li><a href="#in_trades" role="tab" data-toggle="tab" data-bs-toggle="tab">Trades</a></li>
                    <li><a href="#in_documents" role="tab" data-toggle="tab" data-bs-toggle="tab">Documents</a></li>
                    <li><a href="#in_reports" role="tab" data-toggle="tab" data-bs-toggle="tab">Reports</a></li>
                    <li><a href="#in_assetmgmt" role="tab" data-toggle="tab" data-bs-toggle="tab">Asset Mgmt</a></li>
                </ul>
            </div>
        </div>
        <!-- Tab panes -->
        <div class="pro-content-tab-wrap">
            <div class="section">
                <div class="tab-content">
                    <!-- Finances Tab Starts -->
                    <div role="tabpanel" class="tab-pane fade active show in" id="in_finances">

                        <section class="container">
                            <!-- Top Details -->

                            <div class="row">

                                <div class="col-md-12 datatable-me">

                                    <h2 align="center">Purchases</h2>

                                    <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Property</th>
                                                <th>Purchase Date</th>
                                                <th>Unit Price</th>
                                                <th>No of Units</th>
                                                <th>Net Investmentt</th>
                                                <th>Net Yield</th>
                                                <th>Current Value</th>
                                                <th>Tenancy</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Incor 1</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 2</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>1500$</td>
                                                <td>Vacant</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 3</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 4</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>1500$</td>
                                                <td>Vacant</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 5</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 6</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 7</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 8</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Prestige Polygon</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 10</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 11</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>1500$</td>
                                                <td>Vacant</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 12</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 13</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>Vacant</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 14</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 15</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>Vacant</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 16</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </section>

                        <!--Table -->
                        <section class="bg-forall table-property">
                            <div class="container">
                                <h2 align="center">Transactions</h2>

                                <div class="col-md-12">
                                    <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Property</th>
                                                <th>Purchase Date</th>
                                                <th>Unit Price</th>
                                                <th>No of Units</th>
                                                <th>Net Investmentt</th>
                                                <th>Net Yield</th>
                                                <th>Current Value</th>
                                                <th>Tenancy</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Incor 1</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 2</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>1500$</td>
                                                <td>Vacant</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 3</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 4</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>1500$</td>
                                                <td>Vacant</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 5</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 6</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 7</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 8</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Prestige Polygon</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 10</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 11</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>1500$</td>
                                                <td>Vacant</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 12</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 13</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>Vacant</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 14</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 15</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>Vacant</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                            <tr>
                                                <td>Incor 16</td>
                                                <td>15-Mar-19</td>
                                                <td>1000$</td>
                                                <td>4</td>
                                                <td>4000$</td>
                                                <td>200$</td>
                                                <td>4500$</td>
                                                <td>In lease</td>
                                                <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </section>
                        <!-- End Table -->

                    </div>
                    <!-- Finances Tab Ends -->
                    <!-- Updates Tab Starts -->
                    <div role="tabpanel" class="tab-pane fade" id="in_updates">

                        <section class="container spaceall">

                            <!-- Due Collapse Starts -->
                            <div class="due-collapse">
                                <div class="panel-group due-panel" id="accordion1" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="due-1">
                                            <a class="collapsed accord-head" role="button" data-toggle="collapse" data-parent="#accordion1" href="#due-collapse1" aria-expanded="true" aria-controls="due-collapse1">
                                                <h5><i class="more-less glyphicon glyphicon-plus"></i>Sponsor Application</h5>
                                                <p>Identifies the Principals of the Sponsoring entity, their Principal experience and track record and provides details on their bankruptcies, lawsuits, judgements, short-sales and foreclosures.</p>
                                            </a>
                                        </div>
                                        <div id="due-collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="due-1">
                                            <div class="panel-body">
                                                <div class="details-wrap">
                                                    <!-- Due Details Box Starts -->
                                                    <div class="due-details-box">
                                                        <div class="row due-details-box-row">
                                                            <div class="col-md-9 left-col due-det-left equal-height">
                                                                <h5 class="due-det-tit"><b>Quick Analysis Flags</b></h5>
                                                                <p class="due-det-txt">Quick Analysis Flags provide a high level indication as to whether or not the applicants themselves, or associates, appear on standard searches such as OFAC or Global Sanctions lists, bankruptcy filings, criminal records and other common disqualifying events.</p>
                                                            </div>
                                                            <div class="col-md-3 right-col due-det-right equal-height">
                                                                <div class="">
                                                                    <img src="{{asset('asset/package/images/tick.svg')}}" class="tick-img">
                                                                    <span>Clear</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Due Details Box Ends -->
                                                    <!-- Due Details Box Starts -->
                                                    <div class="due-details-box">
                                                        <div class="row due-details-box-row">
                                                            <div class="col-md-9 left-col due-det-left equal-height">
                                                                <h5 class="due-det-tit"><b>Criminal Convictions</b></h5>
                                                            </div>
                                                            <div class="col-md-3 right-col due-det-right equal-height">
                                                                <div class="">
                                                                    <img src="{{asset('asset/package/images/tick.svg')}}" class="tick-img">
                                                                    <span>None Reported</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Due Details Box Ends -->
                                                    <!-- Due Details Box Starts -->
                                                    <div class="due-details-box">
                                                        <div class="row due-details-box-row">
                                                            <div class="col-md-9 left-col due-det-left equal-height">
                                                                <h5 class="due-det-tit"><b>Current or Pending Legal Actions</b></h5>
                                                            </div>
                                                            <div class="col-md-3 right-col due-det-right equal-height">
                                                                <div class="">
                                                                    <img src="{{asset('asset/package/images/tick.svg')}}" class="tick-img">
                                                                    <span>None Reported</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Due Details Box Ends -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-btm">
                                            <a class="collapsed due-btn" role="button" data-toggle="collapse" data-parent="#accordion1" href="#due-collapse1" aria-expanded="true" aria-controls="due-collapse1">
                                                <button class="cmn-btn collapse-btn view-collapse">
                                                    <span class="rc-close">View</span>
                                                    <span class="rc-open">Close</span> Details
                                                </button>
                                                <span class="cmn-btn collapse-btn">+10</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="due-2">
                                            <a class="collapsed accord-head" role="button" data-toggle="collapse" data-parent="#accordion1" href="#due-collapse2" aria-expanded="false" aria-controls="due-collapse2">
                                                <h5><i class="more-less glyphicon glyphicon-plus"></i>Thomson Reuters Clear</h5>
                                                <p>Provided by ThomsonReuters CLEAR and used to verify the information found on the Sponsor Application and Track Record. The searches are done on each Principal personally and at the Management Company level. Information available through CLEAR is limited to the States and/or Courts that report publicly. Additionally, commercial and multifamily property owners often hold title in an LLC rather than personally. As a result, foreclosures, lawsuits, short-sales related to LLCs are not reported at the personal level.</p>
                                            </a>
                                        </div>
                                        <div id="due-collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="due-2">
                                            <div class="panel-body">
                                                <div class="details-wrap">
                                                    <!-- Due Details Box Starts -->
                                                    <div class="due-details-box">
                                                        <div class="row due-details-box-row">
                                                            <div class="col-md-9 left-col due-det-left equal-height">
                                                                <h5 class="due-det-tit"><b>Quick Analysis Flags</b></h5>
                                                                <p class="due-det-txt">Quick Analysis Flags provide a high level indication as to whether or not the applicants themselves, or associates, appear on standard searches such as OFAC or Global Sanctions lists, bankruptcy filings, criminal records and other common disqualifying events.</p>
                                                            </div>
                                                            <div class="col-md-3 right-col due-det-right equal-height">
                                                                <div class="">
                                                                    <img src="{{asset('asset/package/images/tick.svg')}}" class="tick-img">
                                                                    <span>Clear</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Due Details Box Ends -->
                                                    <!-- Due Details Box Starts -->
                                                    <div class="due-details-box">
                                                        <div class="row due-details-box-row">
                                                            <div class="col-md-9 left-col due-det-left equal-height">
                                                                <h5 class="due-det-tit"><b>Criminal Convictions</b></h5>
                                                            </div>
                                                            <div class="col-md-3 right-col due-det-right equal-height">
                                                                <div class="">
                                                                    <img src="{{asset('asset/package/images/tick.svg')}}" class="tick-img">
                                                                    <span>None Reported</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Due Details Box Ends -->
                                                    <!-- Due Details Box Starts -->
                                                    <div class="due-details-box">
                                                        <div class="row due-details-box-row">
                                                            <div class="col-md-9 left-col due-det-left equal-height">
                                                                <h5 class="due-det-tit"><b>Current or Pending Legal Actions</b></h5>
                                                            </div>
                                                            <div class="col-md-3 right-col due-det-right equal-height">
                                                                <div class="">
                                                                    <img src="{{asset('asset/package/images/tick.svg')}}" class="tick-img">
                                                                    <span>None Reported</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Due Details Box Ends -->
                                                    <!-- Due Details Box Starts -->
                                                    <div class="due-details-box">
                                                        <div class="row due-details-box-row">
                                                            <div class="col-md-9 left-col due-det-left equal-height">
                                                                <h5 class="due-det-tit"><b>Current or Pending Legal Actions</b></h5>
                                                            </div>
                                                            <div class="col-md-3 right-col due-det-right equal-height">
                                                                <div class="">
                                                                    <img src="{{asset('asset/package/images/tick.svg')}}" class="tick-img">
                                                                    <a href="#" class="report-btn">See Report</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Due Details Box Ends -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-btm">
                                            <a class="collapsed due-btn" role="button" data-toggle="collapse" data-parent="#accordion1" href="#due-collapse2" aria-expanded="true" aria-controls="due-collapse2">
                                                <button class="cmn-btn collapse-btn view-collapse">
                                                    <span class="rc-close">View</span>
                                                    <span class="rc-open">Close</span> Details
                                                </button>
                                                <span class="cmn-btn collapse-btn">+10</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- panel-group -->
                            </div>
                            <!-- Due Collapse Ends -->

                        </section>

                    </div>
                    <!-- Updates Tab Ends -->
                    <!-- Voting Tab Starts -->
                    <div role="tabpanel" class="tab-pane fade" id="in_voting">

                        <section class="container spaceall">

                            <div class="col-xs-12">
                                <!-- tabs -->
                                <div class="tabbable tabs-left">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#voting_1" data-toggle="tab" data-bs-toggle="tab">Start Voting</a></li>
                                        <li><a href="#vote_poll" data-toggle="tab" data-bs-toggle="tab">List of previous voting results</a></li>
                                        <li><a href="#respond_vote" data-toggle="tab" data-bs-toggle="tab">Respond to voting</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active show" id="voting_1">

                                            <form role="form">

                                                <div class="col-md-12">
                                                    <h3> Question 1</h3>
                                                    <div class="form-group">

                                                        <div class="row radio-btn">
                                                            <div class="col-md-6">
                                                                <label>
                                                                    <input type="radio" class="radio-inline" name="radios" value=""><span class="outside"><span class="inside"></span></span>Neopolitan</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>
                                                                    <input type="radio" class="radio-inline" name="radios" value=""><span class="outside"><span class="inside"></span></span>Mint Choco Chip</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>
                                                                    <input type="radio" class="radio-inline" name="radios" value=""><span class="outside"><span class="inside"></span></span>Blue Moon</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>
                                                                    <input type="radio" class="radio-inline" name="radios" value=""><span class="outside"><span class="inside"></span></span>All the Ice Cream</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-12">
                                                    <h3>  Question 2</h3>
                                                    <div class="form-group">
                                                        <input maxlength="200" type="text" required="required" class="form-control" placeholder="Enter Answer" />
                                                    </div>

                                                </div>

                                                <div class="col-md-12">
                                                    <h3> Question 3</h3>

                                                    <div class="row radio-btn">
                                                        <div class="col-md-6">
                                                            <label>
                                                                <input type="radio" class="radio-inline" name="radiosq" value=""><span class="outside"><span class="inside"></span></span>Answer 1</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>
                                                                <input type="radio" class="radio-inline" name="radiosq" value=""><span class="outside"><span class="inside"></span></span>Answer 2</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>
                                                                <input type="radio" class="radio-inline" name="radiosq" value=""><span class="outside"><span class="inside"></span></span>Answer 3</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>
                                                                <input type="radio" class="radio-inline" name="radiosq" value=""><span class="outside"><span class="inside"></span></span>Answer 4</label>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-12">
                                                    <h3> Question 4</h3>

                                                    <div class="row radio-btn">
                                                        <div class="col-md-6">
                                                            <label>
                                                                <input type="radio" class="radio-inline" name="radiosa" value=""><span class="outside"><span class="inside"></span></span>Answer 1</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>
                                                                <input type="radio" class="radio-inline" name="radiosa" value=""><span class="outside"><span class="inside"></span></span>Answer 2</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>
                                                                <input type="radio" class="radio-inline" name="radiosa" value=""><span class="outside"><span class="inside"></span></span>Answer 3</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>
                                                                <input type="radio" class="radio-inline" name="radiosa" value=""><span class="outside"><span class="inside"></span></span>Answer 4</label>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-12">
                                                    <h3>  Question 5</h3>
                                                    <div class="form-group">
                                                        <input maxlength="200" type="text" required="required" class="form-control" placeholder="Enter Answer" />
                                                    </div>

                                                </div>

                                                <button class="btn1 btn2 pull-right" type="submit">Submit</button>

                                            </form>

                                        </div>
                                        <div class="tab-pane" id="vote_poll">
                                            <div class="listofvotes">

                                                <div id="poll_main">
                                                    <a name="poll_bar">Poll 1 </a> <span name="poll_val">60.1% </span>
                                                    <br/>
                                                    <a name="poll_bar">Poll 2</a> <span name="poll_val">23.4% </span>
                                                    <br/>
                                                    <a name="poll_bar">Poll 3     </a> <span name="poll_val">9.8%  </span>
                                                    <br/>
                                                    <a name="poll_bar">Poll 4 </a> <span name="poll_val">3.7%  </span>
                                                    <br/>
                                                    <a name="poll_bar">Poll 5  </a> <span name="poll_val">1.6%  </span>
                                                    <br/>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="tab-pane" id="respond_vote">
                                            <div class="responded_vote">
                                                <h4>1165 candidates were polled</h4>
                                                <h5>769 responded</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /tabs -->
                            </div>

                        </section>

                    </div>
                    <!-- Voting Tab Ends -->
                    <!-- Trades  Tab Starts -->
                    <div role="tabpanel" class="tab-pane fade" id="in_trades">

                        <section class="container">

                            <div class="col-md-12 spaceall trades_tbl">

                                <div class="col-md-8">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>No of units:</td>
                                            <td> 12</td>
                                            <td>Purchase Price:</td>
                                            <td>1000$</td>
                                        </tr>
                                        <tr>
                                            <td>Purchase Dt:</td>
                                            <td>15-Mar-2018</td>
                                            <td colspan="2"> View Valuation Report</td>
                                        </tr>
                                    </table>

                                </div>

                                <div class="col-md-4">

                                    <a href="sell-token.php" class="btn cust-bigbtn">Sell New</a>

                                </div>

                            </div>

                        </section>

                        <section class="datatable-me">

                            <div class="container">

                                <h2 align="center">Pending Offers </h2>

                                <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Offer Dt</th>
                                            <th>Offer Price</th>
                                            <th>No of Units</th>
                                            <th>Transaction type</th>
                                            <th>Offer Status</th>
                                            <th>Current Offer</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>01</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>02</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>03</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>04</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>05</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>06</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>07</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>08</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>09</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell </a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy </a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>13</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell </a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy </a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>14</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </section>

                        <section class="bg-forall spaceall">

                            <div class="container">

                                <h2 align="center">Previous Transactions</h2>

                                <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Offer Dt</th>
                                            <th>Offer Price</th>
                                            <th>No of Units</th>
                                            <th>Transaction type</th>
                                            <th>Offer Status</th>
                                            <th>Current Offer</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>01</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>02</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>03</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>04</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>05</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>06</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>07</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>08</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>09</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>13</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Rejected</td>
                                            <td>4500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                        <tr>
                                            <td>14</td>
                                            <td>15-Mar-19</td>
                                            <td>1000$</td>
                                            <td>4</td>
                                            <td>Buy</td>
                                            <td>Not Match</td>
                                            <td>1500$</td>
                                            <td class="text-center"><a class='btn btn-info btn-xs badge-black' href="#"><i class="far fa-money-bill-alt"></i> Sell</a> <a href="#" class="btn btn-success btn-xs badge-orange"><i class="far fa-building"></i> Buy</a> <a href="#" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i> Stop sale</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </section>

                    </div>
                    <!-- Trades  Tab Ends -->
                    <!-- Documents Tab Start -->
                    <div role="tabpanel" class="tab-pane fade" id="in_documents">
                        <section class="container doc-style spaceall">
                            <div class="row">
                                <div class="col-md-9 left-col">
                                    <div class="tab-box">
                                        <h5 class="tab-tit">Documents</h5>
                                        <!-- Documents Starts -->
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="10%"></th>
                                                    <th width="90%">Title</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="#"><i class="far fa-file-pdf fa-2x"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#">Investor Dossier</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#"><i class="far fa-file-pdf fa-2x"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#">Report</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#"><i class="far fa-file-pdf fa-2x"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#">Valuation Report</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#"><i class="far fa-file-pdf fa-2x"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#">Agreements</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#"><i class="far fa-file-word fa-2x"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#">Others </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- Documents Ends -->
                                    </div>
                                </div>
                                <div class="col-md-3 right-col">
                                </div>
                            </div>
                        </section>

                    </div>
                    <!-- Documents Tab Ends -->

                    <!-- Reports Tab Start -->
                    <div role="tabpanel" class="tab-pane fade" id="in_reports">

                        <section class="container doc-style spaceall">
                            <div class="row">
                                <div class="col-md-9 left-col">
                                    <div class="tab-box">
                                        <h5 class="tab-tit">Reports</h5>
                                        <!-- Documents Starts -->
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="10%"></th>
                                                    <th width="90%">Title</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="#"><i class="far fa-file-pdf fa-2x"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#">Investor Dossier</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#"><i class="far fa-file-pdf fa-2x"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#">Report</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#"><i class="far fa-file-pdf fa-2x"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#">Valuation Report</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#"><i class="far fa-file-pdf fa-2x"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#">Agreements</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#"><i class="far fa-file-word fa-2x"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#">Others </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- Documents Ends -->
                                    </div>
                                </div>
                                <div class="col-md-3 right-col">
                                </div>
                            </div>
                        </section>

                    </div>
                    <!-- Reports Tab Ends -->

                    <!-- Asset Mgmt Tab Start -->
                    <div role="tabpanel" class="tab-pane fade" id="in_assetmgmt">

                        <section class="container spaceall">

                            <div class="col-md-12 right-col">
                                <h5 class="tab-tit">Contact Asset Manager</h5>
                                <div class="have-a-qu">
                                    <h5 class="tab-tit">Have a Question?</h5>
                                    <form id="regForm">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Your Name">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Your Email Address">
                                        </div>
                                        <div class="form-group row m-0">
                                            <select class="form-control" id="country" required="true" name="country">
                                                <option value="" disabled selected>Send to Cooper Street Capital</option>
                                                <option value="1">Option 1</option>
                                                <option value="2">Option 2</option>
                                                <option value="3">Option 3</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" placeholder="I'm interested in learning more about.."></textarea>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="cmn-btn" value="Submit Question">
                                        </div>
                                    </form>
                                </div>
                                <br/>
                                <br/>
                                <h5 class="tab-tit">Frequently Asked Questions</h5>
                                <p class="tab-txt tab-txt1">Below are some of the most frequently asked questions about this offering.</p>
                                <div class="demo">
                                    <div class="panel-group faq-panel" id="accordion1" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="faq-1">
                                                <a class="accord-head" role="button" data-toggle="collapse" data-parent="#accordion1" href="#faq-collapse1" aria-expanded="true" aria-controls="faq-collapse1">
                                                    <h5><i class="more-less glyphicon glyphicon-plus"></i>Does RealCrowd charge a fee to invest?</h5>
                                                </a>
                                            </div>
                                            <div id="faq-collapse1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="faq-1">
                                                <div class="panel-body">
                                                    <p class="acc-txt">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="faq-2">
                                                <a class="collapsed accord-head" role="button" data-toggle="collapse" data-parent="#accordion1" href="#faq-collapse2" aria-expanded="false" aria-controls="faq-collapse2">
                                                    <h5><i class="more-less glyphicon glyphicon-plus"></i>Who can invest in a RealCrowd offering?</h5>
                                                </a>
                                            </div>
                                            <div id="faq-collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="faq-2">
                                                <div class="panel-body">
                                                    <p class="acc-txt">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="faq-3">
                                                <a class="collapsed accord-head" role="button" data-toggle="collapse" data-parent="#accordion1" href="#faq-collapse3" aria-expanded="false" aria-controls="faq-collapse3">
                                                    <h5><i class="more-less glyphicon glyphicon-plus"></i>Is RealCrowd an equity investor in the offerings?</h5>
                                                </a>
                                            </div>
                                            <div id="faq-collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="faq-3">
                                                <div class="panel-body">
                                                    <p class="acc-txt">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- panel-group -->
                                </div>
                                <!-- container -->
                            </div>

                        </section>

                    </div>
                    <!-- Asset Mgmt Tab Ends -->

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Property Tab Ends -->
@endsection


@section('scripts')
<script>
    // Voting Poll

    $(document).ready(function() {
        // add button style 
        $("[name='poll_bar'").addClass("btn btn-default");
        // Add button style with alignment to left with margin.
        $("[name='poll_bar'").css({
            "text-align": "left",
            "margin": "5px"
        });

        //loop 
        $("[name='poll_bar'").each(
            function(i) {
                //get poll value  
                var bar_width = (parseFloat($("[name='poll_val'").eq(i).text()) / 2).toString();
                bar_width = bar_width + "%"; //add percentage sign.                                     
                //set bar button width as per poll value mention in span.
                $("[name='poll_bar'").eq(i).width(bar_width);

                //Define rules.
                var bar_width_rule = parseFloat($("[name='poll_val'").eq(i).text());
                if (bar_width_rule >= 50) {
                    $("[name='poll_bar'").eq(i).addClass("btn btn-sm btn-colour")
                }
                if (bar_width_rule < 50) {
                    $("[name='poll_bar'").eq(i).addClass("btn btn-sm btn-colour")
                }
                if (bar_width_rule <= 10) {
                    $("[name='poll_bar'").eq(i).addClass("btn btn-sm btn-colour")
                }

                //Hide dril down divs
                $("#" + $("[name='poll_bar'").eq(i).text()).hide();
            });

        //On click main menu bar show its particular detail div.
        $("[name='poll_bar'").click(function() {

            //Display only selected bar texted div sub chart.
            $("#" + $(this).text()).show();
            //If not inner drill down sub detail found then move to main menu.
            if ($("#" + $(this).text()).length == 0) {
                $("#poll_main").show();
            }
        });
    });
</script>

<script type="text/javascript">
    //My Investment
    Highcharts.chart('container-chat', {
        chart: {
            type: 'column'
        },
        xAxis: {
            categories: ['Apr 18', 'May 18', 'Jun 18', 'Jul 18', 'Aug 18', 'Sep 18', 'Oct 18', 'Nov 18', 'Dec 18', 'Jan 18', 'Feb 18', 'Mar 18']
        },
        yAxis: {
            labels: {
                formatter: function() {
                    if (this.value >= 1E6) {
                        return (this.value / 1000000).toFixed(2) + 'M';
                    }
                    return this.value / 1000 + 'k';
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: [{
            name: 'Sample Property I',
            data: [815, 2323, 1224, 1427, 2122, 5323, 2216, 4453, 9231, 3242, 6434, 5325]
        }, {
            name: 'Sample Property II',
            data: [1235, 2132, 3543, 2342, 2351, 3464, 5632, 1321, 5648, 3245, 4636, 1233]
        }, {
            name: 'Sample Property III',
            data: [2343, 5324, 3454, 6432, 7535, 3123, 1239, 4654, 3236, 7555, 2317, 9323]
        }]
    });

    //My Investment 2
    Highcharts.chart('container-chat2', {
        chart: {
            type: 'column'
        },
        xAxis: {
            categories: ['Apr 18', 'May 18', 'Jun 18', 'Jul 18', 'Aug 18', 'Sep 18', 'Oct 18', 'Nov 18', 'Dec 18', 'Jan 18', 'Feb 18', 'Mar 18']
        },
        yAxis: {
            labels: {
                formatter: function() {
                    if (this.value >= 1E6) {
                        return (this.value / 1000000).toFixed(2) + 'M';
                    }
                    return this.value / 1000 + 'k';
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: [{
            name: 'Sample Property I',
            data: [815, 2323, 1224, 1427, 2122, 5323, 2216, 4453, 9231, 3242, 6434, 5325]
        }, {
            name: 'Sample Property II',
            data: [1235, 2132, 3543, 2342, 2351, 3464, 5632, 1321, 5648, 3245, 4636, 1233]
        }, {
            name: 'Sample Property III',
            data: [2343, 5324, 3454, 6432, 7535, 3123, 1239, 4654, 3236, 7555, 2317, 9323]
        }]
    });

    //My Investment3
    Highcharts.chart('container-chat3', {
        chart: {
            type: 'column'
        },
        xAxis: {
            categories: ['Apr 18', 'May 18', 'Jun 18', 'Jul 18', 'Aug 18', 'Sep 18', 'Oct 18', 'Nov 18', 'Dec 18', 'Jan 18', 'Feb 18', 'Mar 18']
        },
        yAxis: {
            labels: {
                formatter: function() {
                    if (this.value >= 1E6) {
                        return (this.value / 1000000).toFixed(2) + 'M';
                    }
                    return this.value / 1000 + 'k';
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: [{
            name: 'Sample Property I',
            data: [815, 2323, 1224, 1427, 2122, 5323, 2216, 4453, 9231, 3242, 6434, 5325]
        }, {
            name: 'Sample Property II',
            data: [1235, 2132, 3543, 2342, 2351, 3464, 5632, 1321, 5648, 3245, 4636, 1233]
        }, {
            name: 'Sample Property III',
            data: [2343, 5324, 3454, 6432, 7535, 3123, 1239, 4654, 3236, 7555, 2317, 9323]
        }]
    });

    // Bar Chart 1
    Highcharts.chart('container', {
        data: {
            table: 'datatable'
        },
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        yAxis: {
            categories: ['2000$', '4000$', '6000$', '8000$', '10000$', '12000$'],
            title: {
                text: 'Dollars',
                overflow: 'justify'
            }
        },

        tooltip: {
            formatter: function() {
                return '<b>' + this.series.name + '</b><br/>' +
                    this.point.y + ' ' + this.point.name.toLowerCase();
            }
        }
    });
    // End Bar Chart 1

    //owlCarousel
    $(".owl-carousel").owlCarousel({

        autoPlay: false, //Set AutoPlay to 3 seconds
        dots: false,
        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 2]
    });
    //owlCarousel

    /* Signature Code */
    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function(e) {
                $('.image-upload-wrap').hide();

                $('.file-upload-image').attr('src', e.target.result);
                $('.file-upload-content').show();

                $('.image-title').html(input.files[0].name);
            };

            reader.readAsDataURL(input.files[0]);

        } else {
            removeUpload();
        }
    }

    function removeUpload() {
        $('.file-upload-input').replaceWith($('.file-upload-input').clone());
        $('.file-upload-content').hide();
        $('.image-upload-wrap').show();
    }
    $('.image-upload-wrap').bind('dragover', function() {
        $('.image-upload-wrap').addClass('image-dropping');
    });
    $('.image-upload-wrap').bind('dragleave', function() {
        $('.image-upload-wrap').removeClass('image-dropping');
    });
    /*End Signature Code */

    $(function() {
        $("#map").googleMap({
            zoom: 15, // Initial zoom level (optional)
            coords: [17.438136, 78.395246], // Map center (optional)
            type: "ROADMAP" // Map type (optional)
        });
    })

    //Date Picker 

    $('#datePicker')
        .datepicker({
            format: 'mm/dd/yyyy'
        })
        .on('changeDate', function(e) {
            // Revalidate the date field
            $('#eventForm').formValidation('revalidateField', 'date');
        });

    $('#eventForm').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The name is required'
                    }
                }
            },
            date: {
                validators: {
                    notEmpty: {
                        message: 'The date is required'
                    },
                    date: {
                        format: 'MM/DD/YYYY',
                        message: 'The date is not a valid'
                    }
                }
            }
        }
    });
</script>
@endsection
