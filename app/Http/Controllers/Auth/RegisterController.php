<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\User;
use App\Country;
use Mail;
use App\Mail\WelcomeMail;
use App\Mail\AdminWelcomeMail;
use Setting;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $countries = Country::get();
        return view('auth.register', compact('countries'));
    }

    public function showIssuerRegistrationForm()
    {
        $countries = Country::get();

        //echo public_path().'/issuer_doc/';
        //echo "Testing";
        return view('auth.issuerregister', compact('countries'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data){
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed', 'regex:/^(?=(.*[A-Z]){1,})(?=(.*[0-9]){1,})(?=(.*[!@#$%^&*()\-__+.]){1,}).{6,}$/'],
            'user_type' => ['required'],
            'issuer_pros_doc' => 'required|max:5012|mimes:doc,docx,pdf,jpg',
            'issuer_kyc_doc' => 'required|max:5012|mimes:doc,docx,pdf,jpg',
            'list1'=>'required',
            'list2'=>'required',
            'list3'=>'required',
            'list4'=>'required',
        ];

        $validator = Validator::make($data, $rules, [
            'password.regex' => 'Password must be 6 digits, must contain 1 capital letter, 1 special character, 1 number',
            'list1.required'=>'Please Select Terms of Service checkbox (1)',
            'list2.required'=>'Please Select No damage checkbox (2)',
            'list3.required'=>'Please Select Risk Investment checkbox (3)',
            'list4.required'=>'Please Select Terms and conditions checkbox (4)',
        ]);

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data){
        $email = strtolower($data['email']);
        $email_token = base64_encode($email);
        // Upload issuer prospectus document
        $iss_doc_url = $this->uploadDocument($data['issuer_pros_doc'] ?? null, 'issuer_doc');

        // Upload issuer KYC document
        $iss_kyc_doc_url = $this->uploadDocument($data['issuer_kyc_doc'] ?? null, 'issuer_kyc_doc');


        $userdata = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'user_type' => $data['user_type'],
            'email_token' => $email_token,
            'country_id'    =>  $data['country_id'],
            'account_type'  =>  $data['account_type'],
            'issuer_pros_doc' => $iss_doc_url,
            'issuer_kyc_doc' => $iss_kyc_doc_url,
            'verified' => 1
        ];

        $email_userdata = [
            'name' => $data['name'],
            'email' => $email,
            'email_token' => base64_encode($email)
        ];

        if (config('mail.MAIL_STATUS')) {

            Mail::to($email)->send(new WelcomeMail($email_userdata));
        }

        return User::create($userdata);
    }


    protected function create_issuer(array $data){
        $email = strtolower($data['email']);
        $email_token = base64_encode($email);

        // These used to be assigned straight from the request. An UploadedFile
        // stringifies to its PHP temp path, which is deleted when the request
        // ends, so the stored document was never retrievable.
        $iss_doc_url = $this->uploadDocument($data['issuer_pros_doc'] ?? null, 'issuer_doc');
        $iss_kyc_doc_url = $this->uploadDocument($data['issuer_kyc_doc'] ?? null, 'issuer_kyc_doc');

        $userdata = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'user_type' => $data['user_type'],
            'email_token' => $email_token,
            'issuer_pros_doc' => $iss_doc_url,
            'issuer_kyc_doc' => $iss_kyc_doc_url,
            'country_id'    => $data['country_id'],
            'verified' => 1,
        ];
        // if(!config('app.is_demo')){
            $userdata['approved'] = 1;
        // }

        $email_userdata = [
            'name' => $data['name'],
            'email' => $email,
            'user_type' => "Issuer",
            'email_token' => base64_encode($email)
        ];

        // USER EMAIL
        if (config('mail.MAIL_STATUS')) {

            Mail::to($email)->send(new WelcomeMail($email_userdata));
        }
        $supportemail = Setting::get('support_mail');
        // ADMIN EMAIL
        // if (env('MAIL_STATUS', true)) {

        //     Mail::to($supportemail)->send(new AdminWelcomeMail($email_userdata));
        // }



        return User::create($userdata);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request){

        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        \Session::flash('flash_success', 'Account created successfully, verify your account by your welcome mail from your mail account...');
        return redirect('/login');
    }

    public function register_issuer(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create_issuer($request->all())));

        // \Session::flash('flash_success', 'Account created successfully, Admin will approve your account');
        \Session::flash('flash_success', 'Account created successfully, Please login to your account');
        return redirect('/issuer/register');
    }


    public function verify($token)
    {
        $user = User::where('email_token', $token)->first();
        $user->verified = 1;
        $user->save();

        \Session::flash('flash_success', 'Your account has verified successfully...');
        return redirect('/login');
    }

    protected function uploadDocument($file, $folder){
        if (empty($file)) {
            return '';
        }

        $docFilename = time() . '-doc.' . $file->getClientOriginalExtension();

        // Store the file in storage/app/public/{folder}
        $file->storeAs($folder, $docFilename, 'public');

        // Return public URL
        return asset('storage/' . $folder . '/' . $docFilename);
    }

}
