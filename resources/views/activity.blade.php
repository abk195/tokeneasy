@extends('layout.app')

@section('content')
<!-- Breadcrumb -->
<div class="page-content">
    <div class="pro-breadcrumbs">
        <div class="container">
            <a href="{{url('/dashboard')}}" class="pro-breadcrumbs-item">Home</a>
            <span>/</span>
            <a href="#" class="pro-breadcrumbs-item">Activity </a>
        </div>
    </div>
    <!-- End Breadcrumb -->
    <!-- Property Head Starts -->
    <div class="property-head grey-bg pt30">
        <div class="container">
            <div class="property-head-btm row">
                <div class="col-md-12">
                    <h2 class="pro-head-tit">Activity </h2>
                    <p class="pro-head-txt">Hello, User</p>
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
                    <li class="active"><a href="#act_updates" role="tab" data-toggle="tab" data-bs-toggle="tab">Updates</a></li>
                    <li><a href="#act_referrals" role="tab" data-toggle="tab" data-bs-toggle="tab">Referrals</a></li>
                    <li><a href="#act_offers" role="tab" data-toggle="tab" data-bs-toggle="tab">Offers</a></li>
                    <li><a href="#act_tasks" role="tab" data-toggle="tab" data-bs-toggle="tab">Tasks</a></li>
                    <li><a href="#act_faq" role="tab" data-toggle="tab" data-bs-toggle="tab">Help Desk - FAQ</a></li>
                    <li><a href="#act_contact" role="tab" data-toggle="tab" data-bs-toggle="tab">Help desk - Contact</a></li>
                </ul>
            </div>
        </div>
        <!-- Tab panes -->
        <div class="pro-content-tab-wrap">
            <div class="section">
                <div class="tab-content">
                    <!-- Updates Tab Starts -->
                    <div role="tabpanel" class="tab-pane fade active show in" id="act_updates">
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
                    <!-- Referrals Tab Starts -->
                    <div role="tabpanel" class="tab-pane fade" id="act_referrals">
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
                    <!-- Referrals Tab Ends -->
                    <!-- Offers Tab Start -->
                    <div role="tabpanel" class="tab-pane fade" id="act_offers">
                        <section class="container doc-style spaceall">
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
                    <!-- Offers Tab Ends -->
                    <!-- Tasks Tab Start -->
                    <div role="tabpanel" class="tab-pane fade" id="act_tasks">
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
                    <!-- Tasks Tab Ends -->
                    <!-- Help Desk - FAQ Tab Start -->
                    <div role="tabpanel" class="tab-pane fade" id="act_faq">
                        <section class="container spaceall">

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
                                        <div id="faq-collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="faq-1">
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

                        </section>
                    </div>
                    <!-- Help Desk - FAQ Tab Ends -->
                    <!-- Help desk - Contact Tab Start -->
                    <div role="tabpanel" class="tab-pane fade" id="act_contact">
                        <section class="container spaceall">

                            <div class="col-md-12 right-col">
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
                            </div>

                            <div class="col-md-12 spaceall">

                                <h4 class="email_detls">Email: info@sto.com</h4>

                            </div>

                        </section>
                    </div>
                    <!-- Help desk - Contact Tab Ends -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Property Tab Ends -->
@endsection


@section('scripts')
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
