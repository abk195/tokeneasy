<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Each role to its own dashboard once the reset has signed them in.
     *
     * Issuers and investors share this reset flow, but /home is investor-only:
     * its middleware sends anyone else to issuer/token-demo, so an issuer who
     * reset their password used to land on the demo token page.
     */
    public function redirectTo()
    {
        $user = $this->guard()->user();

        return $user && (int) $user->user_type === \App\User::USER_TYPE_ISSUER
            ? '/issuer/dashboard'
            : $this->redirectTo;
    }
}
