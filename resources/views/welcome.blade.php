@extends('layout.appHome')

@section('content')

<!-- Intro top content -->
    <section class="intro" id="home">

        <!-- Fullscreen Video-->
        <div class="video-bg">
            <div class="video">
                <video autoplay muted loop id="myVideo">
                    <source src="{{asset('asset/package/video/real.mp4')}}" type="video/mp4">
                    {{-- <source src="{{asset('asset/package/video/realestate_new.mp4')}}" type="video/mp4"> --}}
                </video>
            </div>
        </div>
        <!-- Fullscreen Video-->

        <!-- <div class="cover"></div> -->
        <div class="intro-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 animated text-slide">
                      <div class="row banner-Logo" style="display:none;">
                        <div class="col-sm-12">
                          <p class="brand-logo_head">{{ Setting::get('site_title') }}</p>
                          <img src="../asset/package/images/Logo_Final_3.png" alt="Assetmonk" width="150" height="auto">
                        </div>
                      </div>
                        <div id="intro-slider" class="flexslider">
                            <ul class="slides">
                                <li>
                                    <!-- <p class="brand-heading-para">Digital Real Estate Exchange Technology</p> -->
                                    <h3 class="brand-heading">{{ Setting::get('site_title') }}</h3>
                                    <div class="line"></div>
                                    <p class="intro-text">The only project marketplace that simplifies the deployment of capital into secured  commercial ventures and provides bankable assets for every investment.</p>
                                </li>
                                <li>
                                    <!-- <p class="brand-heading-para">Digital Real Estate Exchange Technology</p> -->
                                    <h3 class="brand-heading">ACCESSIBLE {{$project_name}} INVESTMENTS</h3>
                                    <div class="line"></div>
                                    <p class="intro-text">Vetted Security Token Offerings.</p>
                                </li>
                                <li>
                                    <!-- <p class="brand-heading-para">Digital Real Estate Exchange Technology</p> -->
                                    <h3 class="brand-heading">CONNECTING BLOCKCHAIN TO ASSETS - CONNECTING BLOCKCHAIN TO REAL WORLD ASSETS</h3>
                                    <div class="line"></div>
                                    <p class="intro-text">Vetted Security Token Offerings.</p>
                                </li>
                                <li>
                                    <!-- <p class="brand-heading-para">Digital Real Estate Exchange Technology</p> -->
                                    <h3 class="brand-heading">COMPLETE VISIBILTIY OF ALL YOUR ASSETS.</h3>
                                    <div class="line"></div>
                                    <p class="intro-text">Our Platform allows you manage all your investments in one place.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Intro top content -->
    <!-- Welcome content -->
    <section class="welcome-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12 welcome-text animated">
                    <h3 class="sec-tit">Welcome to the {{$project_name}} Investment Platform</h3>
                    <div class="line1"></div>
                    <!-- <p>Investment protection and integrity is our number one priority. This being a tokenized and blockchain driven platform ensures these standards are met. This technology also allows for a lowered barrier of entry with increased liquidity in the secondary market. Our regulatory compliant ecosystem enables fractional property investments and ownership.</p>
                    <p>Properties are officially and legally represented as security tokens on the Ethereum blockchain.</p> -->
                    <p class="wel-para">{{ Setting::get('site_title') }} is your full-service, fully integrated, digital investment ecosystem for every type of individual and institutional investor..</p>
                    <!-- <a class="btn1" href="#">Get Started</a> -->

                </div>
            </div>
        </div>
    </section>
    <!-- Welcome content -->

    <!-- Team content -->
    <!-- <section class="team-content" id="team" >
        <div class="container">
            <div class="row">
                <div class="fade-text animated">
                    <h3 class="sec-tit">Our team</h3>
                    <div class="line1"></div>
                    <p>Our management team comprises of experienced professionals from diverse backgrounds including real estate, finance, fund management and technology.</p>
                </div>
                <div class="space90"></div>
                <div class="col-md-12 no-padding">
                    <div class="col-md-3 staff-content">
                        <div class="staff-img">
                            <span class="overlay">
                            <ul class="team-social">
                                <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-skype"></i></a></li>
                            </ul>
                        </span>
                            <img src="{{asset('asset/package/images/1.jpg')}}" class="img-responsive" alt="" />
                        </div>
                        <h4>Surya Pulagam <span>CEO & Co-founder</span></h4>
                    </div>
                    <div class="col-md-3 staff-content">
                        <div class="staff-img">
                            <span class="overlay">
                            <ul class="team-social">
                                <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-skype"></i></a></li>
                            </ul>
                        </span>
                            <img src="{{asset('asset/package/images/2.jpg')}}" class="img-responsive" alt="" />
                        </div>
                        <h4>Adi Reddy <span>CTO & Co-founder</span></h4>
                    </div>
                    <div class="col-md-3 staff-content">
                        <div class="staff-img">
                            <span class="overlay">
                            <ul class="team-social">
                                <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-skype"></i></a></li>
                            </ul>
                        </span>
                            <img src="{{asset('asset/package/images/3.jpg')}}" class="img-responsive" alt="" />
                        </div>
                        <h4>Prudhvi Reddy <span>COO</span></h4>
                    </div>
                    <div class="col-md-3 staff-content">
                        <div class="staff-img">
                            <span class="overlay">
                            <ul class="team-social">
                                <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-skype"></i></a></li>
                            </ul>
                        </span>
                            <img src="{{asset('asset/package/images/4.jpg')}}" class="img-responsive" alt="" />
                        </div>
                        <h4>Ankit Shah <span>CFO</span></h4>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- Team content -->
    <!-- Services content -->
    {{-- <section class="services-content" id="services" data-stellar-background-ratio="0.3">
        <div class="cover-red"></div>
        <div class="container">
            <div class="row">
                <div class="fade-text">
                    <!-- <h3 class="sec-tit">Key Features</h3> -->
                    <h3 class="sec-tit">How It Works</h3>
                    <div class="line3"></div>
                    <!-- <p>Owning a fraction of real estate is a great way to diversify risk instead of locking funds in a single asset. Our platform offers the following benefits to make help you invest efficiently.</p> -->
                    <p>As a potential investor, you start by identifying an investment of interest through our "Offerings". From there you register as a member of {{ Setting::get('site_title') }} with a simple KYC process . With that you receive more detailed information related to the "Offerings" and also get added support through our onboarding customer service. Once you are comfortable making an informed investment decision you click the "Ready To Invest" button where you will then fill out a client profile to insure our mutual protection. Once approved, you will be guided to your personal investment portal which will allow you to choose and explain the level of your funding commitment. You are then given the opportunity to invest and become an owner of one of the entities investing in the project and given a dashboard to oversee and navigate your investment portfolio. </p>
                    @unless (Auth::check()) <a class="btn1" href="{{url('/propertyList')}}" target="_blank">Lets Get Started</a>@endunless
                </div>
                <!-- <div class="space60"></div> -->
                <div class="col-md-12 no-padding" style="display:none;">
                    <div class="col-md-4 service-content animated">
                        <div class="service-content-inner">
                            <div class="serv-icon-center">
                               <!-- <i class="flaticon-shield"></i> -->
                               <img src="asset/images/life-insurance.svg" style="height:60px;">
                           </div>
                            <h4>Investor Protection</h4>
                            <div class="text-center">
                              <a type="button" class="btn read-btn" data-toggle="modal" data-bs-toggle="modal" data-target="#myModal" data-bs-target="#myModal">Read More</a>
                            </div>
                        </div>
                    </div>
                    <!-- Model 1 Start -->
                    <div class="modal fade" id="myModal" role="dialog">
                      <div class="modal-dialog first-model">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                            <h4 class="modal-title">INVESTOR PROTECTION (Blockchain Security)</h4>
                          </div>
                          <div class="modal-body">
                            <p class="model-para-class">A blockchain is a public ledger, recording every transaction involving the currencies and virtual tokens running on it. This structure makes it impossible for a token holder to “double-sell” a token—accepting a transfer for the same token to two different sources. Blockchains are distributed ledgers, which means that no one
person, group, or organization controls them. In addition, blockchains rely on advanced cryptography to provide security to users. Each user has his or her own private key that allows access to his or her blockchain assets. That key is a long string of random characters that is very difficult for a computer—let alone another user—to guess. After a transaction has been recorded and confirmed on the blockchain, it cannot be changed.
This helps assure investors that no one can falsify transactions after the fact. These protections are also embedded in technologies like smart contracts and multi signature wallets, utilized on this platform to ensure every step in the real estate transactional process is verified through digital consensus, thus making every step more efficient, more transparent and more secure. There is no higher level of investment security.</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default para-btn" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Model 1 End -->
                    <div class="col-md-4 service-content animated">
                        <div class="service-content-inner">
                            <div class="serv-icon-center">
                            <!-- <i class="flaticon-law-1"></i> -->
                            <img src="asset/images/auction.svg" style="height:60px;">
                            </div>
                            <h4>Legal Compliance</h4>
                            <div class="text-center">
                              <a type="button" class="btn read-btn" data-toggle="modal" data-bs-toggle="modal" data-target="#myModal2" data-bs-target="#myModal2">Read More</a>
                            </div>
                        </div>
                    </div>
                    <!-- Model 2 Start -->
                    <div class="modal fade" id="myModal2" role="dialog">
                      <div class="modal-dialog first-model">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                            <h4 class="modal-title">LEGAL COMPLIANCE</h4>
                          </div>
                          <div class="modal-body">
                            <p class="model-para-class">There is growing cohesion as it relates to a regulatory framework for tokenized assets internationally. Entities like the European Securities Market Authority, the Securities and Exchange Commission (USA) and the Canadian Securities Administrators all agree on the need for a set of standards to ensure protection and recourse for issuance and custody of digitized assets. As such, we have incorporated KYC/ AML (Know Your Customer and Anti-Money Laundering) adherence as well as compliance standards for accredited and non accredited investors.</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default para-btn" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Model 2 End -->
                    <div class="col-md-4 service-content animated">
                        <div class="service-content-inner">
                            <div class="serv-icon-center">
                            <!-- <i class="flaticon-lock"></i> -->
                            <img src="asset/images/ownership.svg" style="height:60px;">
                            </div>
                            <h4>FRACTIONAL OWNERSHIP</h4>
                            <div class="text-center">
                              <a type="button" class="btn read-btn" data-toggle="modal" data-bs-toggle="modal" data-target="#myModal3" data-bs-target="#myModal3">Read More</a>
                            </div>
                        </div>
                    </div>
                    <!-- Model 3 Start -->
                    <div class="modal fade" id="myModal3" role="dialog">
                      <div class="modal-dialog first-model">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                            <h4 class="modal-title">FRACTIONAL OWNERSHIP</h4>
                          </div>
                          <div class="modal-body">
                            <p class="model-para-class">Look at a security token like a poker chip, where it represents a monetary value . In the case of this platform, a token represents your share, or ownership stake in a real estate offering. Tokenization on Distributed Ledger Technology (DLT- Blockchain) removes geographical and intermediary barriers which existed previously. What counts as “sufficient capital” will also change with tokenization. A virtual token does not necessarily have to be sold as a whole unit. Instead, the code underlying the token may permit it to be subdivided, allowing the issuer or subsequent holders to sell fractional tokens at lower prices. This opens the market to smaller investors who could not otherwise participate and enables greater opportunities for diversification for wealthier investors. Owning “a piece of the pie” has never been easier or safer.</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default para-btn" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Model 3 End -->
                </div>


                <div class="col-md-12 no-padding" style="display:none;">
                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-md-4 service-content animated">
                        <div class="service-content-inner">
                            <div class="serv-icon-center">
                            <!-- <i class="flaticon-meeting"></i> -->
                            <img src="asset/images/oil.svg" style="height:60px;">
                            </div>
                            <h4>LIQUIDITY</h4>
                            <div class="text-center">
                              <a type="button" class="btn read-btn" data-toggle="modal" data-bs-toggle="modal" data-target="#myModal4" data-bs-target="#myModal4">Read More</a>
                            </div>
                        </div>
                    </div>
                    <!-- Model 4 Start -->
                    <div class="modal fade" id="myModal4" role="dialog">
                      <div class="modal-dialog first-model">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                            <h4 class="modal-title">LIQUIDITY</h4>
                          </div>
                          <div class="modal-body">
                            <p class="model-para-class">Chief among the advantages of tokenization, is liquidity. Liquidity refers to the ease with which an asset can be bought or sold. Currently, real estate investments are considered relatively illiquid. It affects the price such investments command, imposing an illiquidity discount on the underlying assets’ true value. Tokenization has the potential to recapture at least some of the value lost to illiquidity, by making real estate investments easier to buy and sell.</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default para-btn" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Model 4 End -->
                    <div class="col-md-4 service-content animated">
                        <div class="service-content-inner">
                            <div class="serv-icon-center">
                            <!-- <i class="flaticon-track"></i> -->
                            <img src="asset/images/track.svg" style="height:60px;">
                            </div>
                            <h4>PERFORMANCE TRACKING</h4>
                            <div class="text-center">
                              <a type="button" class="btn read-btn" data-toggle="modal" data-bs-toggle="modal" data-target="#myModal5" data-bs-target="#myModal5">Read More</a>
                            </div>
                        </div>
                    </div>
                    <!-- Model 5 Start -->
                    <div class="modal fade" id="myModal5" role="dialog">
                      <div class="modal-dialog first-model">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                            <h4 class="modal-title">PERFORMANCE TRACKING</h4>
                          </div>
                          <div class="modal-body">
                            <p class="model-para-class">Whether you are an issuer or investor in our platform, we have a comprehensive set of tools for you to track
your performance and execute as a buyer or seller 24/7.</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default para-btn" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Model 5 End -->
                </div>
            </div>
        </div>
    </section> --}}
    <!-- Services content -->
    <!-- Portfolio content -->
    <section class="property-list-sec white-bg p-60 pos-rel" id="featured_properties" data-stellar-background-ratio="0.3">
        <div class="container">
            <div class="fade-text animated">
                <h3 class="sec-tit">Latest Offerings</h3>
                <div class="line1"></div>
            </div>
            <div class="pro-list-wrap row mt60">

                <div class="owl-carousel owl-theme owl-loaded">
                    <div class="owl-stage-outer">
                        <div class="owl-stage">
                            @foreach ($property as $value)
                                <div class="owl-item">
                                    <!-- Property Box Starts -->
                                    <div class="col-md-12">
                                        <div class="pro-box equal-height">
                                            <div class="pro-badge-out"><span class="pro-badge">
                                                @if($value->property_state ==  'comingsoon')
                                                Coming Soon
                                                @else
                                                Funding Live
                                                @endif
                                            </span></div>
                                            <div class="pro-name">
                                                <h4>{{ @$value->propertyName }}</h4>
                                                <p>{{ @$value->propertyLocation }}</p>
                                            </div>
                                            <div class="pro-img pos-rel">
                                                @php $image = (!is_null($value->propertyLogo)) ? asset('storage/'.$value->propertyLogo) : asset('asset/package/images/building.jpg'); @endphp
                                                <img src="{{ @$image }}" alt="">
                                            </div>
                                            <div class="pro-details">
                                                <div class="property-progress">
                                                    <div class="pro-progress-block">
                                                        <div class="progress-value" @if ($value->accuired_percentage < 100)
                                                            style="width: {{ @$value->accuired_percentage }}%;" @else style="width: 100%;"
                                                        @endif>

                                                        </div>
                                                    </div>
                                                    <span class="progress-txt"><b>{{ @$value->accuired_percentage }}% FUNDED</b> ${{ @$value->accuired_usd }} OF ${{ @$value->totalDealSize }}</span>
                                                </div>
                                                <div class="detail-badge-wrap">
                                                    <span class="detail-badge">Trusted</span>
                                                    <span class="detail-badge">High Growth</span>
                                                    <span class="detail-badge">Office</span>
                                                    <span class="share-btn-wrap pos-rel">
                                                        <ul class="share-buttons">
                                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                                            <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                                                            <li><a href="#"><i class="fab fa-pinterest-p"></i></a></li>
                                                            <li><a href="#" class="social-share"> <i class="fas fa-share-alt"></i></a></li>
                                                        </ul>
                                                    </span>
                                                </div>
                                                <div class="pro-details-2">
                                                    @if($value->token_type == 1)
                                                    <!-- Property Details List Starts -->
                                                    <div class="row pro-det-list m-0">
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt1 pro-det-txt">
                                                                <b>Asset Type:</b>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt2 pro-det-txt">{{ @$value->propertyType }}</p>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <div class="row pro-det-list m-0">
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt1 pro-det-txt">
                                                                <b>Asset Type:</b>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt2 pro-det-txt">Asset Fund</p>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <!-- Property Details List Ends -->
                                                    <!-- Property Details List Starts -->
                                                    <div class="row pro-det-list m-0">
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt1 pro-det-txt">
                                                                <b>Total Deal size:</b>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt2 pro-det-txt">${{ @$value->totalDealSize }}</p>
                                                        </div>
                                                    </div>
                                                    <!-- Property Details List Ends -->
                                                    <!-- Property Details List Starts -->
                                                    <div class="row pro-det-list m-0">
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt1 pro-det-txt"><b>Expected <a class="tooltip_sto" title="Internal Rate of Return">Annual Return</a>:</b></p>
                                                        </div>
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt2 pro-det-txt">{{ @$value->expectedIrr }}%</p>
                                                        </div>
                                                    </div>
                                                    <!-- Property Details List Ends -->
                                                    <!-- Property Details List Starts -->
                                                    <div class="row pro-det-list m-0">
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt1 pro-det-txt"><b>Min Investment:</b></p>
                                                        </div>
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt2 pro-det-txt">${{ @$value->initialInvestment }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <!-- Property Details List Ends -->
                                                    <!-- Property Details List Starts -->
                                                    <div class="row pro-det-list m-0">
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt1 pro-det-txt"><b>Asset Status:</b></p>
                                                        </div>
                                                        <div class="col-md-6 col-xs-12 p-0">
                                                            <p class="pro-det-txt2 pro-det-txt">
                                                                @if($value->property_state ==  'comingsoon')
                                                                Coming Soon
                                                                @else
                                                                Funding Live
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <!-- Property Details List Ends -->
                                                </div>
                                                <div class="text-center">
                                                    <a href="{{url('propertyDetail/'.$value->id)}}" class="view-btn">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Property Box Ends -->
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>
            <div class="text-center">
                <a href="{{url('/propertyList')}}" class="view-all-btn">Investment Opportunities</a>
            </div>
        </div>
    </section>
    <!-- Portfolio content -->
    <!-- Facts content -->
    @unless (Auth::check())
    <section class="facts-content" id="facts" data-stellar-background-ratio="0.3">
        <div class="cover-normal"></div>
        <div class="container">
            <div class="row">
                <!-- <h3>Have a property to sell or project to fund?</h3> -->
                <h3>Want to get smarter about blockchain / crypto and discover <br>how you may fit into its assets ecosystem ?</h3>
                <div class="col-md-12 fact-info animated">
                    <div class="line2"></div>
                    <!-- <h4><span>We're eager to hear from you. Write to us @ <a href="mailto:assets@realestatesto.com">assets@realestatesto.com</a></span></h4> -->
                    <!-- <h4><span>We're eager to hear from you.</span></h4> -->
                    <!-- <a class="btn1" href="{{url('/login')}}" target="_blank">Start Here</a> -->
                    <a class="btn1" href="{{url('/under_construction')}}" target="_blank">Start Here</a>
                </div>
            </div>
        </div>
    </section>
    @endunless

    <!-- Facts content -->
    <!-- Testimonial content -->
    {{-- <section class="testimonial-wrap" id="testimonials" data-stellar-background-ratio="0.3" style="display:none">
        <div class="cover-green"></div>
        <div class="container">
            <div class="row">
                <div class="fade-text animated">
                    <h3 class="sec-tit">Uworld Metaverse </h3>
                </div>
                <div class="col-md-12 quote-slide animated">
                    <div id="quote-slider" class="flexslider">
                        <ul class="slides">
                            <li>
                                <img src="{{asset('asset/package/images/cli-1.png')}}" class="client-img" alt="" />
                                <!-- <div class="sign">Blockcain App Factory</div> -->
                            </li>
                             <li>
                                <img src="{{asset('asset/package/images/cli-2.png')}}" class="client-img" alt="" />
                                <!-- <div class="sign">Trilegal</div> -->
                            </li>
                            <!-- <li>
                                <img src="{{asset('asset/package/images/client3.png')}}" class="client-img" alt="" />
                                <div class="sign">JLL</div>
                            </li>
                            <li>
                                <img src="{{asset('asset/package/images/client4.png')}}" class="client-img" alt="" />
                                <div class="sign">Motilal Oswal</div>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- Testimonial content -->
    <!-- Contact content -->
    <section class="contact-wrap" id="contact">
        <div class="container">
            <div class="row">
                <div class="fade-text animated">
                    <h3 class="sec-tit">Contact us</h3>
                    <div class="line1"></div>
                    <p>We look forward to hearing from you!</p>
                </div>
                <div class="space80"></div>
                <div class="col-md-12 no-padding">
                    <!-- <div class="col-md-4 contact-info animated">
                        <h5><i class="fas fa-phone-volume"></i> Telephone</h5>
                        <p>+044-23456 400</p>
                    </div>
                    <div class="col-md-4 contact-info animated">
                        <h5><i class="far fa-building"></i> Address</h5>
                        <p>123/345, AAA Hills,
                            <br> XXX, YYYYYY,
                            <br> ZZZZZZ 500033, XYZ</p>
                    </div> -->
                    <div class="col-md-12 contact-info animated">
                        <h5><i class="far fa-envelope-open"></i> Email</h5>
                        <p><a href="mailto:{{ Setting::get('enquiry_mail') }}">{{ Setting::get('enquiry_mail') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact content -->
@endsection


@section('scripts')
 	        <!-- End Scripts -->
        <script type="text/javascript">
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                autoplay: true,
                autoplayTimeout: 6000,
                nav: true,
                dots: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 3
                    }
                }
            })
        </script>


        <script>
        $(document).ready(function(){
         $("a").on('click', function(event) {
           if (this.hash !== "") {
             event.preventDefault();
             var hash = this.hash;
             $('html, body').animate({
               scrollTop: $(hash).offset().top
             }, 800, function(){
               window.location.hash = hash;
             });
           }
         });
        });
        </script>
        <script>
        $(document).ready(function() {
            $('#intro-slider').flexslider({
                animation: "fade",
                controlNav: false,
                DirectionNav: false,
                slideshowSpeed: 4000,
                animationSpeed: 600
            });
        });

        $(document).ready(function() {
            $('#quote-slider').flexslider({
                animation: "slide",
                controlNav: "thumbnails",
                DirectionNav: "true"
            });
        });
        </script>
@endsection
