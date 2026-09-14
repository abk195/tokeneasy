@extends('layout.app')

@section('content')
<style type="text/css">
    .d-none {
        display: none!important;
    }
</style>
<!-- Breadcrumb -->
<div class="page-content">
    <div class="pro-breadcrumbs">
        <div class="container">
            <a href="{{url('/dashboard')}}" class="pro-breadcrumbs-item">Home</a>
            <span>/</span>
            <a href="#" class="pro-breadcrumbs-item">Settings</a>
        </div>
    </div>
    <!-- End Breadcrumb -->
    <!-- Property Head Starts --> 
    <div class="property-head grey-bg pt30">
        <div class="container">
            <div class="property-head-btm row">
                <div class="col-md-8">
                    <h2 class="pro-head-tit">Settings</h2>
                    <!-- <p class="pro-head-txt">Hello, Alex</p> -->
                </div>
                <div class="col-md-4">
                    <div class="pro-type text-right">
                        <img src="{{asset('asset/package/images/office.svg')}}">
                        <p class="pro-type-txt">MULTI-FAMILY PROPERTY <span class="pro-info"><i class="fas fa-info-circle"></i></span></p>
                    </div>
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
                    {{-- <li class="active"><a href="#communication" role="tab" data-toggle="tab">Communication</a></li> --}}
                    <li class="active"><a href="#profile" role="tab" data-toggle="tab" data-bs-toggle="tab">Profile</a></li>
                    <li><a href="#password" role="tab" data-toggle="tab" data-bs-toggle="tab">Password</a></li>
                </ul>
            </div>
        </div>
        <!-- Tab panes -->
        <div class="pro-content-tab-wrap p40">
            <div class="container">
                <div class="tab-content">
                    {{-- @include('common.notify')  --}}
                    <!-- Identity Tab Starts -->
                    {{-- <div role="tabpanel" class="tab-pane fade active in" id="communication">
                        <div class="row">
                            <div class="col-md-12">

                                <form role="form">
                                    <div class="col-md-6">
                                        <div class="form-group communication_check">
                                            <label class="control-label">Communication</label>
                                            <div class="checkbox">
                                                <input type="checkbox" id="it1" value="">
                                                <label class="checkbox-label" for="it1">Send new offerings </label>
                                            </div>
                                            <div class="checkbox disabled">
                                                <input type="checkbox" id="it2" value="">
                                                <label class="checkbox-label" for="it2"> Send news letter </label>
                                            </div>
                                            <div class="checkbox disabled">
                                                <input type="checkbox" id="it3" value="">
                                                <label class="checkbox-label" for="it3"> Send promotional offers </label>
                                            </div>
                                            <div class="checkbox disabled">
                                                <input type="checkbox" id="it4" value="">
                                                <label class="checkbox-label" for="it4"> Send insights </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="file-upload" style="padding: 0px;">
                                                <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Upload Image</button>
                                                <div class="image-upload-wrap">
                                                    <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*" />
                                                    <div class="drag-text">
                                                        <h3>Drag and drop a Image or select add Image</h3>
                                                    </div>
                                                </div>
                                                <div class="file-upload-content">
                                                    <img class="file-upload-image" src="#" alt="your image" />
                                                    <div class="image-title-wrap">
                                                        <button type="button" onclick="removeUpload()" class="remove-image">Remove
                                                            <span class="image-title">Upload Image</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <ul class="list-inline pull-right">
                                        <li>
                                            <button type="button" class="btn1 btn2">Send via email</button>
                                        </li>
                                    </ul>

                                    <div class="clearfix"></div>

                                </form>

                            </div>
                        </div>
                    </div> --}}
                    <!-- Identity Tab Ends -->
                    <!-- Finance Tab Starts -->
                    <div role="tabpanel" class="tab-pane fade active show in" id="profile">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="emp-profile">
                                    <form method="post">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="profile-img">
                                                    <img src="{{ asset('asset/package/images/user-profile.jpg') }}" alt="" />
                                                    <!-- <div class="file btn btn-lg btn-primary">
                                                        Change Photo
                                                        <input type="file" name="file" />
                                                    </div> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="profile-head">
                                                    <h2>
                                                    Details
                                                </h2> 

                                                    <table class="table table-hover cus-tabel">
                                                        <tbody> 
                                                            <tr>
                                                                <td>Name</td>
                                                                <td>{{@$user->name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td>{{@$user->email}}</td>
                                                            </tr>                                                            
                                                           <!--  <tr>
                                                                <td>Profession</td>
                                                                <td>Design and Build</td>
                                                            </tr> -->
                                                        </tbody>
                                                    </table>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label></label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label></label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p></p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- <div class="col-md-2">
                                                <input type="submit" class="profile-edit-btn" name="btnAddMore" value="Edit Profile" />
                                            </div> -->
                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Finance Tab Ends -->
                    <!-- Background Tab Starts -->
                    <div role="tabpanel" class="tab-pane fade" id="password">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="password_reset">

                                    <form action="{{route('change.password')}}" method="POST">
                                        @csrf()
                                        <label>Current Password</label>
                                        <div class="form-group pass_show">
                                            <input type="password" class="form-control" placeholder="Current Password" name="current_password">
                                        </div>
                                        <label>New Password</label>
                                        <div class="form-group pass_show">
                                            <input type="password" class="form-control" placeholder="New Password" name="password">
                                        </div>
                                        <label>Confirm Password</label>
                                        <div class="form-group pass_show">
                                            <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation">
                                        </div>

                                        <button type="submit" class=" btn1 btn2 btn-success">Change Password</button>

                                    </form>
                                </div>

                                <hr/>

                                <div class="two_factor_pass">
                                    <!--   <h3>ACCOUNT SECURITY</h3>
                            <p>Two-Factor sign in can be used to help protect your account from unauthorized access by requiring you to enter an additional code when you sign in.</p> -->
                                    <h3> TWO-FACTOR SIGN IN</h3>
                                    <p>Protect your account from unauthorized access by enabling Two-factor Sign in.</p>

                                    <!-- <button type="button" class=" btn1 btn2 btn-success">ENABLE TWO-FACTOR SIGN IN</button> -->
                                    @if(Auth::user()->google2fa_secret == null)
                                        <div class="row">
                                          <div class="col-md-6"> 
                                            <div style="text-align: center;" class="2faenablebutton">
                                              <!-- <button id="google2faenable" class="btn btn-theme">Enable 2FA</button> -->
                                              <button type="button" class=" btn1 btn2 btn-success" id="google2faenable" >ENABLE TWO-FACTOR SIGN IN</button>
                                            </div>
                                            <div class="2facontent d-none">
                                            <h5 class="mb-30">Two-Factor Authentication</h5>
                                              <p><strong>Step 1 : Configure your 2FA app</strong></p>
                                              <p>To enable 2FA, you'll need to have a 2FA auth app installed on your phone or tablet(examples include Google Authenticator, Duo Mobile, and Authy).</p>
                                              <p>Most apps will let you get set up by scannig our OR code from within the app. If you prefer, you can tyoe in the key manually.</p>
                                            </div>
                                          </div>
                                          <div class="col-md-6" id="barcode" style="display: none;">
                                              
                                              <div id="barcodehash" style="text-align: center;">
                                              </div>
                                              <p  class="mb-30" style="text-align: center;"><strong>KEY : <span id="barcodekey">LGE4TRE34SF68SDFVAEW2DSD</span></strong></p>
                                              <p  class="mb-40"><strong>Step 2 : Enter a 2FA Code</strong></p>
                                              <p class="mb-20">Generate a code from your newly-activated 2FA app to confirm that you're all set up.
                                              </p>
                                              <div class="form-group">
                                              <label for="sendamt">Enter Google 2FA Code</label>
                                              <input type="text" class="form-control" id="sendamt" placeholder="Enter a generated 2FA  passcode" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" maxlength="6">
                                              </div>
                                               <div class="alert alert-danger" style="display: none;"> </div>
                                                <div class="alert alert-success" style="display: none;"></div>
                                              <div class="enabl2fa" style="text-align: center;">
                                              <button type="submit" class="btn btn-theme" onclick="myfuncgoogleotp();">Enable</button>
                                              <p style="display: none;" id="otp_msg"></p>
                                              </div>
                                          </div>
                                        </div>
                                @else

                                    <form method=get action="{{ route('disableTwoFactor') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="row">
                                      <div class="col-lg-6 col-md-6 col-sm-12 ">
                                      <div class="form-group">
                                        <label for="username">2FA OTP</label>
                                        <input type="text" class="form-control" id="otp" aria-describedby="" placeholder="Enter 6 digits 2FA Code" name="otp" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" maxlength="6">
                                       </div>
                                     </div>
                                      <div class="col-md-12 col-sm-12 ">
                                        <div>
                                          <button type="submit" class="btn btn-theme">Disable 2FA</button>
                                        </div>
                                      </div>
                                    </div>
                                    </form>
                                @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Background Tab Ends -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Property Tab Ends -->
@endsection


@section('scripts')
<script type="text/javascript">
    /* Show and Hide Password */
    $(document).ready(function() {
        $('.pass_show').append('<span class="ptxt">Show</span>');
    });

    $(document).on('click', '.pass_show .ptxt', function() {

        $(this).text($(this).text() == "Show" ? "Hide" : "Show");

        $(this).prev().attr('type', function(index, attr) {
            return attr == 'password' ? 'text' : 'password';
        });

    });

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

    $(document).on('click','#google2faenable',function(){
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        // alert(key); 
        $.ajax({
            type:"GET",
            url: "{{url('2fa/enable')}}",
            success:function(response){
              $('#barcode').css('display','block');
              $('.2facontent').removeClass('d-none');
              $('.2faenablebutton').addClass('d-none');
              $('#barcodekey').html(response.secret);
              $('#barcodehash').html('<img src="'+response.image+'" class="img-fluid">');
                // location.reload();
                // $('.lang').val(response);
            },
                   
        });
    });
    function myfuncgoogleotp() {
        $(".alert-danger").hide();
        $(".alert-success").hide();

        var otp=$('#sendamt').val();
        if(otp == ""){
            $(".alert-danger").html("Please enter valid 6 digits OTP.");
            $(".alert-danger").show().delay(5000).fadeOut();
        }

        $.ajax({
              url: "{{url('/g2fotpcheckenable')}}",
              type: "POST",
              data:{"_token": '{{ csrf_token() }}','totp':otp}
        }).done(function(response){

            if(response.status==1){
                $(".alert-success").html(response.message);
                $(".alert-success").show().delay(5000).fadeOut();                
                // location.reload();
                $("#google2faenable").html('Disable 2FA');
                $('.enabl2fa').hide();
                // $(".alert-success").html(response.message);
                setTimeout(function() {
                    location.reload();
                }, 3000);

            }else{
                $(".alert-danger").html(response.message);
                $(".alert-danger").show().delay(5000).fadeOut();
            }
            

        }).fail(function(key,status){
              // $('.alert-danger').show();
              // $('.alert-danger').append('<p>'+status+'</p>');
        });

    }

</script>
@endsection
