@extends('layout.app')

@section('content')
<style>
#services
{
  background: #fff !important;
}
</style>
<!-- Breadcrumb -->
    <div class="page-content">
        <div class="pro-breadcrumbs">
            <div class="container">
                <a href="{{url('/dashboard')}}" class="pro-breadcrumbs-item">Home</a>
                <span>/</span>
                <a href="#" class="pro-breadcrumbs-item">Intel</a>
            </div>
        </div>
        <!-- End Breadcrumb -->
        <!-- Property Head Starts -->
        <div class="property-head grey-bg pt30">
            <div class="container">
                <div class="property-head-btm row">
                    <div class="col-md-12">
                        <h2 class="pro-head-tit">Intel</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Property Head Ends -->

        <section class="services-content" id="services" data-stellar-background-ratio="0.3">
            <div class="cover-red"></div>
            <div class="container">
                <div class="row">
                    <div class="fade-text">
                        <h3 class="sec-tit">Key Features</h3>
                        <div class="line3"></div>
                        <p>Owning a fraction of an project opportunity is a great way to diversify risk instead of locking funds in a single asset. Our platform offers the following benefits to make help you invest efficiently.</p>
                    </div>
                    <div class="space60"></div>
                    <div class="col-md-12 no-padding">
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


                    <div class="col-md-12 no-padding">
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
        </section>

    </div>
@endsection


@section('scripts')

@endsection
