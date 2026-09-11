@extends('issuer.layout.base')
@section('content')
<div class="content-page-inner">
  <!-- Header Banner Start -->
  <div class="header-breadcrumbs">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>Upload KYC Documents</h1>
        </div>
        <div class="col-sm-6">
          <div class="breadcrumb-four" style="text-align: right;">
            <ul class="breadcrumb">
              <li><a href="{{url('issuer/dashboard')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-box">
                    <path
                      d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                    </path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                  </svg> <span>Dashboard</span></a></li>
              <li class="active"><a href=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-cpu">
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
                  </svg> <span>KYC</span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Header Banner Start -->
  <div class="content">
    <!-- Start container-fluid -->
    <div class="container-fluid wizard-border">
      <div class="row">
        <div class="col-lg-12">
          <div class="card-box">
              <form action="{{ route('updateKyc') }}" method="POST" class="dropzone dz-clickable" enctype="multipart/form-data">
              @csrf
                @foreach($documents as $key => $document)
                <div class="row">
                  <div class="col-sm-6">
                    <h4 class="card-title">{{ @$document->name }} :</h4>
                      <input type="hidden" name="documentid" value="{{ @$document->id }}">
                      <div class="mb-5">
                          <input type="file" name="image" id="" class="form-control">
                      </div>
                      @if(!is_null($document->kycdocument))
                        <img class="kyc-preview" src="{{ @img($document->kycdocument->url) }}" height="50%">
                      @endif
                      <!-- <div class="row">
                        <div class="col-sm-3 preview-btn">
                          <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-bs-toggle="modal" data-target=".bs-example-modal-center" data-bs-target=".bs-example-modal-center">Preview</button>
                        </div>
                        <div class="col-sm-3 preview-btn">
                          <input type="submit" class="btn btn-primary waves-effect waves-light" value="Procced">
                        </div>
                      </div> -->
                  </div>
                  <div class="col-sm-6 text-center">
                    <h4 class="card-title">Example :</h4>
                    <img src="{{ @$document->image }}" class="kyc-preview">
                  </div>
                </div>
                @endforeach
                <div class="row">
                  <div class="col-sm-3 preview-btn">
                    <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-bs-toggle="modal" data-target=".bs-example-modal-center" data-bs-target=".bs-example-modal-center">Preview</button>
                  </div>
                  <div class="col-sm-3 preview-btn">
                    <input type="submit" class="btn btn-primary waves-effect waves-light" value="Procced">
                  </div>
                </div>
              </form>
              <!-- <div class="col-sm-4">
                <h4 class="card-title">Proof of Address :</h4>
                <div class="mb-5">
                  <form action="#" class="dropzone dz-clickable">
                    <div class="dz-default dz-message"><button class="dz-button" type="button">Add Your Doc</button>
                    </div>
                  </form>
                </div>
                <div class="row">
                  <div class="col-sm-3 preview-btn"><button type="button"
                      class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-bs-toggle="modal"
                      data-target=".bs-example-modal-center" data-bs-target=".bs-example-modal-center">Preview</button></div>
                  <div class="col-sm-3 preview-btn"><button type="button"
                      class="btn btn-primary waves-effect waves-light">Proceed</button></div>
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Model Start -->
    <div class="modal fade bs-example-modal-center show" tabindex="-1" role="dialog"
      aria-labelledby="myCenterModalLabel" aria-modal="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myCenterModalLabel">Preview</h5>
            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body text-center">
            <img src="assets/images/id.png" style="width:75%;">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Model End -->
    <!-- end container-fluid -->
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
  <!-- end content -->
</div>
<!-- END content-page -->
@endsection
