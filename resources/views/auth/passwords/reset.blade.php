@extends('layout.auth')

@section('content')
{{--
    Two reset flows render this view:

     - Laravel's standard reset (Auth\ResetPasswordController@showResetForm), which
       the forgot-password email links to. It passes $token and $email and posts to
       /password/reset with password + password_confirmation.
     - The older custom flow (HomeController@ShowResetForm, /reset/password/{token}),
       which passes $user and posts to /check_password with confirm_password.

    The view used to assume the custom flow only, so the standard reset page read
    an undefined $user and every forgot-password link opened a server error.
--}}
@php $customFlow = isset($user); @endphp
<div class="form-holder">
    <div class="form-content">
        <div class="form-items">
        @include('common.notify')
            <form method="POST" action="{{ $customFlow ? url('/check_password') : url('/password/reset') }}">
                @csrf
                @unless ($customFlow)
                    <input type="hidden" name="token" value="{{ $token }}">
                @endunless
                <div class="form-group row">
                    <b><label for="reset_email" class="col-md-2 col-form-label"> Email</label></b>
                    <div class="col-md-10">
                        <input id="reset_email" class="form-control" type="email" name="email"
                            value="{{ $customFlow ? $user->email : ($email ?? old('email')) }}"
                            {{ $customFlow ? 'readonly' : 'required' }}>
                    </div>
                </div>
                <div class="form-group row">
                    <b><label for="reset_password" class="col-md-2 col-form-label"> Password</label></b>
                    <div class="col-md-10">
                        <input id="reset_password" class="form-control" type="password" value="" name="password" required>
                    </div>
                </div>
                <div class="form-group row">
                    <b><label for="reset_password_confirmation" class="col-md-2 col-form-label">Confirm Password</label></b>
                    <div class="col-md-10">
                        <input id="reset_password_confirmation" class="form-control" type="password" value=""
                            name="{{ $customFlow ? 'confirm_password' : 'password_confirmation' }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-number-input" class="col-md-2 col-form-label"></label>
                    <div class="col-md-10">
                        <button type="submit" class="btn btn-primary mr-1 w-md">Reset Password</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
