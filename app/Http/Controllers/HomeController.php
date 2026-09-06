<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Mail;
use App\Admin;
use Stripe;
use Session;
use Setting;
use App\User;
use App\Vote;
use App\Coins;
use Exception;
use App\Country;
use App\Support;
use App\Document;
use App\Bank;
use App\DepositFiat;
use App\Property;
use App\UserToken;
use App\UserFinance;
use App\WithdrawEth;
use App\WithdrawShare;
use App\UserContract;
use App\UserIdentity;
use App\Votequestion;
use App\InvestorWhitelist;
use App\AdminEarning;
use App\Trade;
use GuzzleHttp\Client;
use App\DepositHistory;
use App\UserBackground;
use App\Mail\WelcomeMail;
use App\Mail\Withdrawalotp;
use App\Mail\ResetPassword;
use App\UserCompanyDetails;
use Illuminate\Http\Request;
use App\UserTokenTransaction;
use App\AccreditedKycDocument;
use App\Http\Requests\InvestStore;
use App\Http\Requests\UpdatePassword;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\ProfileIdentity;
use App\Http\Controllers\CommonController;
use App\Console\Commands\ETHTransactionCheck;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\PropertyImage;
use App\InvestorShares;
use App\WhiteListedWalletAddress;
use App\Enums\PaymentMethod;
use App\KeystoreModel;
use App\Notification;
use App\Services\TokenPaymentService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\BlockchainModel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->chains = ['ETH', 'BNB', 'MATIC', 'USD'];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function WalletConnet(){
        return view('connect_wallet');
    }

    public function TriggerMail(Request $request){
        try{
            $checkUser = User::where('email', $request->email)->first();
            if($checkUser){
                Mail::to($checkUser->email)->send(new ResetPassword($checkUser->email_token));
                return back()->with('flash_success', 'Reset password link send to your email address.');
            }else{
                return back()->with('flash_error', 'Invalid email address');
            }
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function ShowResetForm($token){
        try{
            $user = User::where('email_token', $token)->first();
            return view('auth.passwords.reset', compact('user'));
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function CheckPassword(Request $request){
        try{
            $user = User::where('email', $request->email)->first();
            if($user){
                if($request->password == $request->confirm_password){
                    $user->password = bcrypt($request->password);
                    $user->save();

                    return redirect('/login')->with('flash_success', 'Password reset successfully');
                }else{
                    return back()->with('flash_error', 'Password and cofirm password are not same');
                }
            }else{
                return back()->with('flash_error', 'Invalid email address');
            }
        }catch(Exception $e){

        }
    }

    public function index()
    {
        if(config('app.is_demo')){
            return redirect()->route('propertyList');
        }
        try {
            $user = Auth::user();

            // Fetch all required data in fewer queries
            $tokens = UserTokenTransaction::with('usercontract.property')
                ->where('user_id', $user->id)
                ->where('status', 1)
                ->get();

            $tokenCount = $tokens->sum('number_of_token');

            // Calculate crypto balance in a single loop
            $coinPrices = $this->getCryptoPrices();
            $balance = collect($this->chains)->reduce(function ($carry, $chain) use ($user, $coinPrices) {
                return $carry + ($coinPrices[$chain] ?? 0) * ($user->$chain ?? 0);
            }, 0);

            // Fetch user's tokens grouped by status
            $groupedTokens = UserToken::with(['usercontract.property', 'withdraw_share_amount', 'tokenTransaction'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['inReview', 'inProgress', 'success'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('status');

            $user_tokens = $groupedTokens->get('success', collect());
            $pendingApprovalRequests = $groupedTokens->get('inReview', collect());
            $pendingBuyRequests = $groupedTokens->get('inProgress', collect());
            // Assign commission (interest) directly from loaded property relation.
            // ?? does not protect a property read on null, and Laravel promotes
            // that notice to a thrown exception, so one holding whose contract or
            // property is missing would otherwise take the whole dashboard down.
            $user_tokens->each(function ($token) {
                $token->commission = optional(optional($token->usercontract)->property)->interest ?? 0;
            });

            // Calculate total net investment
            $totalNetInvestment = $user_tokens->sum(function ($token) {
                $actualAmount = optional($token->usercontract)->tokenvalue ?? 0;
                $quantity = optional($token->tokenTransaction)->number_of_token ?? 0;
                return $actualAmount * $quantity;
            });

            // Fetch unread notifications and mark just those as delivered. This
            // used to delete every notification the user had, so anything raised
            // while they were on another screen was destroyed unseen.
            $notifications = Notification::where('user_id', $user->id)
                ->where('is_viewed', 0)
                ->get();
            Notification::whereIn('id', $notifications->pluck('id'))->update(['is_viewed' => 1]);
            return view('dashboard', compact(
                'user',
                'notifications',
                'user_tokens',
                'totalNetInvestment',
                'balance',
                'pendingBuyRequests',
                'pendingApprovalRequests',
                'tokenCount'
            ));
        } catch (\Throwable $th) {
            \Log::error('Dashboard error', [
                'line' => $th->getLine(),
                'file' => $th->getFile(),
                'message' => $th->getMessage(),
            ]);

            // Deliberately not back(). After login the referer is /login, which
            // bounces an authenticated investor straight back to /home — so a
            // dashboard that always throws produced an infinite redirect loop
            // rather than an error. Send them somewhere that cannot redirect here.
            return redirect()->route('propertyList')
                ->with('flash_error', 'Unable to get dashboard details. Please try again later');
        }
    }


    /**
     * Used to show Property List
     */
    public function propertyList($type = 'single'){
        try {
            $token_type = ($type == 'asset') ? 2 : 1;
            // $property   = (new Property)->getProperty(0, 0, 0, $token_type);
            // Only assets with a deployed contract belong on the marketplace: they
            // are the only ones an investor can buy, and both this method and the
            // view read the contract without a null check. A half-deployed asset
            // used to throw here and take every other asset down with it.
            $properties = Property::with(['userContract', 'propertyImages','blockchain'])
                ->whereNotIn('status',['block','pending'])
                ->whereHas('userContract', function ($query) {
                    $query->whereNotNull('contract_address')->where('contract_address', '<>', '');
                })
                ->orderBy('created_at', 'desc')
                ->get();
            $properties   = (new CommonController)->calculatePercentage($properties);
            foreach ($properties as $property) {
                if (empty($property->userContract)) {
                    continue;
                }

                $remainingTokens = round(($property->userContract->tokensupply - $property->userContract->tokenbalance));
                $property->sold_percentage = $property->userContract->tokensupply > 0 ? round(($remainingTokens / $property->userContract->tokensupply) * 100, 2) : 0;
                $property->accuired_usd = $property->userContract->tokenvalue *  $remainingTokens;
                if (!empty( $property->blockchain)) {

                    $property->contract_address = $property->userContract->contract_address;

                    $url = $property->blockchain->link;
                    $property->contract_link = $url .'token/'. $property->contract_address;
                    $property->coin = $property->blockchain->blockchain_name;
                }
            }
            $property = $properties;
            return view('propertyList', compact('property'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Unable to fetch property details. Please try again later');
        }
    }

    public function propertyAssetList($type)
    {
        try {
            //$user=Auth::user();
            $token_type = ($type == 'asset-fund') ? 2 : 1;
            $property   = (new Property)->getProperty(0, 0, 0, $token_type);
            $property   = (new CommonController)->calculatePercentage($property);
            return view('propertyAssetList', ["property" => $property]);
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Unable to fetch property details. Please try again later');
        }
    }

    public function intel()
    {
        try {
            return view('intel');
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Unable to fetch property details. Please try again later');
        }
    }

    /**
     * Used to show Property Details
     */
    public function propertyDetail($id)
    {
        try {
            $user     = Auth::user();
            $property = Property::where('id', $id)->first();
            if(empty($user)){
                $user = User::find($property->user_id);
            }

            // $property = Property::with('userContract')->where('id', $id)->where('status', '<>', 'pending')->whereNotIn('status',['block'])->orderBy('created_at', 'desc')->get();
            if (!is_null($property->userContract)) {
                $tokens                          = (new UserToken)->getUserToken($property->userContract->id)->sum('token_acquire');
                $usd_value                       = $property->userContract->tokenvalue * $tokens;
                $property['accuired_usd']        = $usd_value;
                $percentage                      = ($usd_value / $property->totalDealSize) * 100;
                $property['accuired_percentage'] = $percentage;
            } else {
                $property['accuired_usd']        = 0;
                $property['accuired_percentage'] = 0;
            }

            $this->setWhitelist($id);
            $checkWhitelist = InvestorWhitelist::where('user_id', $user->id)->where('type', 'purchase')->where('property_id', $id)->first();
            if(!empty($property['userContract']) && !empty($property['blockchain'])){
                $property->contract_address = $property['userContract']->contract_address;
                $property->coin = $property['userContract']->coin;
                $property->contract_link= $property['blockchain']->link.'token/'. $property->contract_address ;

            }
            $showRejectAlert = false;
            if(!empty($checkWhitelist) && $checkWhitelist->status == 'Cancelled' && $checkWhitelist->alert_viewed == false){
                $checkWhitelist->alert_viewed = true;
                $checkWhitelist->save();
                $showRejectAlert = true;
            }
            $propertyImages =  PropertyImage::where('property_id',$property->id)->get();

            $layout = auth()->user() ? auth()->user()->user_type === 2 ? 'issuer.layout.base' : 'layout.app' :'layout.app';

            return view('propertyDetail', compact('user', 'property', 'checkWhitelist','propertyImages','showRejectAlert','layout'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()->with('flash_error', 'Unable to Get Property Details');
        }
    }

    public function setWhitelist($id){
        if(!config('app.is_demo')){
            return;
        }
        $user = Auth::user();
        $checkWhitelist = InvestorWhitelist::where('user_id', $user->id)->where('type', 'purchase')->where('property_id', $id)->first();
        if($checkWhitelist){
            $checkWhitelist->status = 'Approved';
            $checkWhitelist->save();
        }else{
            $property = Property::where('id', $id)->first();
            $whitelist_request = new InvestorWhitelist();
            $whitelist_request->wallet_address = 'Purchase purpose';
            $whitelist_request->amount = 0;
            $whitelist_request->user_id = $user->id;
            $whitelist_request->issuer = $property->user_id;
            $whitelist_request->type = 'purchase';
            $whitelist_request->property_id = $id;
            $whitelist_request->status = 'Approved';
            $whitelist_request->save();
        }
    }
    /**
     * Used to Show Invest Details
     */
    public function applyInvest($id,TokenPaymentService $tokenService)
    {
        // Check if user can make more investments
        $user = Auth::user();
        if (!$user->canMakeInvestment()) {
            return redirect()->route('platform.purchase');
        }

        // dd(request()->all());
        // try {
            $paymentMethods = PaymentMethod::labels();

            $this->setWhitelist($id);
            $checkWhitelist = InvestorWhitelist::where('user_id', $user->id)->where('type', 'purchase')->where('property_id', $id)->first();
            if($checkWhitelist){
                if($checkWhitelist->status == 'Pending'){
                    return back()->with('flash_error', 'Your request still in pending.');
                }elseif ($checkWhitelist->status == 'Cancelled') {
                    return back()->with('flash_error', 'Your request has been cancelled by issuer');
                }

                $property = (new Property)->getProperty(0, $id);
                $issuerDetails = $property->getIssuerDetails();
                $contract = UserContract::where('property_id', $property->id)->where('user_id', $property->user_id)->first();
                if ($contract->tokenbalance === null) {
                    $total_supply = $this->getTotalSupply($contract->user->eth_address, $contract->contract_address, $contract->decimal, $contract->coin);
                    $contract->tokenbalance = price_format($total_supply, 6);
                    $contract->save();
                }
                if ($contract->tokenbalance == 0)
                    return back()->with('flash_error', 'Tokens not available');


                $coins = Coins::where('status', 1)->pluck('symbol')->toArray();
                $userToken = UserToken::where('user_id',$user->id)->where('user_contract_id',$contract->id)->whereIn('status', ['inProgress', 'inReview'])->first();

                $this->addDefaultWhitelistAddress($contract);
                $whitelistedWallets = WhiteListedWalletAddress::where('user_id', $user->id)
                ->where('contract_id', $contract->id)
                ->get(['id', 'wallet_address']);
                if (!empty($userToken) && $userToken->current_stage <= 2 && !$tokenService->isTokenAvailable($userToken, $contract)) {
                    // Flash a warning or info message to the session
                    $userToken->current_stage = 1;
                    $userToken->save();
                    $userToken->refresh();
                    \Session::flash('flash_error', "Token supply is exhausted! Please update the requested token count.");

                }

                $paymentConfigs = getPaymentMethodsJson($paymentMethods,$property->user_id,$contract->id);
                return view('invest', compact('property', 'contract', 'coins', 'checkWhitelist','issuerDetails','user','userToken','whitelistedWallets','paymentConfigs'));
            }else{
                $property = (new Property)->getProperty(0, $id);
                $whitelist_request = new InvestorWhitelist();
                $whitelist_request->user_id = $user->id;
                $whitelist_request->issuer = $property->user_id;
                $whitelist_request->type = 'purchase';
                $whitelist_request->property_id = $id;
                $whitelist_request->wallet_address = 'Purchase purpose';
                $whitelist_request->amount = 0;
                $whitelist_request->save();

                return back()->with('flash_success', 'Admin is notified for your purchase request. Please wait for admin to approve your purchase request');
            }

        // } catch (\Throwable $th) {
        //     return back()->with('flash_error', 'Unable to get invest Details');
        // }
    }

    public function addDefaultWhitelistAddress($userContract){
        if(!config('app.is_demo')){
            return;
        }
        $user = Auth::user();
        $whitelistedWallets = WhiteListedWalletAddress::where('user_id', $user->id)
        ->where('contract_id', $userContract->id)
        ->first();
        if(!$whitelistedWallets){
            $defaultAddress = '0x461AfA77428Bf68553DeD8d098C43e0e0f793468';
            $keystore = KeystoreModel::first();

            $payload = [
                'contract_address' => $userContract->contract_address,
                'privateKey' => $keystore->getPrivateKey(),
                'chain' => $userContract->blockchain->abbreviation,
                'address' => $defaultAddress,
                'amount' => 100,
                'receiveDayLimit' => 100000000,
                'sendDayLimit' => 100000000
            ];

            $result = callNodeOperations('whitelist', $payload);

            if (isset($result['status']) && $result['status'] == 'success') {
                $whiteListedWallet = WhiteListedWalletAddress::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'contract_id' => $userContract->id,
                        'wallet_address' => trim($defaultAddress),
                    ],
                    [
                        'whitelisted_by' => $userContract->user_id,
                        'status' => 1,
                        'tx_hash' => $result['txHash'],
                    ]
                );
            }
        }
    }
    public function check_min_invest(Request $request){
        $check=$request->min_invest;
        if($check > $request->no_of_token ){
            return response(['status'=>0,'min'=>$check]);
        }
         elseif($check<=$request->no_of_token || $check<0){
            return response(['status'=>1,'min'=>$check]);
        }
    }

    public function check_min_invest_paystack(Request $request){
        $user=Auth::user();
        $client=new Client();
        $user_token = UserToken::where('user_contract_id', $request->token_id)->first();
        // dd($user_token);
        $user_contract = UserContract::find($request->token_id);
        // $token_bal=(new NodeController)->getTokenBalance($user_contract->user->eth_address,$user_contract->contract_address);
        $token_rem_usd=$user_contract->tokenbalance*$user_contract->tokenvalue ;
        $user_purc_tok=$request->amount/$user_contract->tokenvalue;

        if($user_purc_tok > $user_contract->tokenbalance){
            return response(['status'=>2,'msg'=>'Only '. $user_contract->tokenbalance.' Tokens is Left.You can buy tokens for '.$token_rem_usd.' USD Only']);

        }
             if($request->min_inves>$request->amount){
             return response(['status'=>0,'min'=>$request->min_inves,'no_of_tokens'=>0]);

             }
             elseif($request->min_inves<=$request->amount){
             $no_of_tokens=$request->amount/$request->token_value;
             return response(['status'=>1,'min'=>$request->min_inves,'no_of_tokens'=>$no_of_tokens,'usd_equivalent'=>$request->amount]);

             }

    }

    private function getTotalSupply($address, $contract, $decimal, $chain){
        try {
            $result = callNodeOperations('getTokenDetails', [
                'chain' => $chain,
                'contractAddress' => $contract,
            ]);

            if ($result['status'] === 'success') {
                $rawSupply = $result['data']['totalSupply'] ?? 0;
                return $rawSupply;
            }

            return 0;
        } catch (\Throwable $th) {
            logger()->error('Error fetching token total supply', [
                'exception' => $th,
                'contract' => $contract,
                'chain' => $chain
            ]);
            return 0;
        }
    }

    public function updateKYC($id, Request $request){
        $user = auth()->user();
        $this->validate($request,[
            'image'=>'required|mimes:png,jpg,jpeg',
            'back_image'=>'required|mimes:png,jpg,jpeg'

        ],[
            'image.image'=>'Front Side  Should be Image type',
            'image.mimes'=>'Png,Jpg,Jpeg Formats only will be Accepted For Front Side Image..',
            'back_image.image'=>'Back Side Should be Image type',
            'back_image.mimes'=>'Png,Jpg,Jpeg Formats only will be Accepted For Back Side Image..'
        ]);
        $image = $request->image;
        AccreditedKycDocument::where(['id' => $id, 'user_id' => $user->id])->update([
            'user_id' => $user->id,
            'url' => $image->store('accredited_kyc/documents'),
            'back_url' => $back_image->store('accredited_kyc/documents'),
            'status' => 'PENDING',
        ]);
        return back()->with('flash_success', "Your KYC details updated successfully...");
    }

    /**
     * Used to Invest Token
     */
    // public function investstore(InvestStore $request){
    //     try {
    //         $user            = Auth::user();
    //         $token_equ_value = $bonus_token = $total_token = 0;
    //         $coin_price = 0;

    //         $from_address = $user->eth_address;
    //         $user_contract = (new UserContract)->getUserContract($request->token_id);

    //         if ($request->nooftoken > $user_contract->tokenbalance) {
    //             return back()->with('flash_error', 'No. of token must be less than or equal to ' . $user_contract->tokenbalance);
    //         }
    //         $chain = $request->payby;
    //         $issuer_id = $user_contract->user_id;
    //         $issuer_user = User::where('id', $issuer_id)->first();

    //         $total_token = $request->nooftoken;

    //         $client    = new Client;
    //         $coinprice = $this->getCryptoPrices();
    //         $eth_balance = $user->$chain;

    //         if ($eth_balance <= 0) {
    //             return redirect('/wallet')->with('flash_error', "You have zero balance");
    //         }


    //         // -------------- Current USD Value s-------------------
    //         $eth_price       = $coinprice[$chain];
    //         $coin_price = $eth_price;
    //         $token_equ_value = $request->nooftoken * $user_contract->tokenvalue / $eth_price;
    //         $token_equ_value =  number_format($token_equ_value, 10, '.', '');
    //         $interest = $user_contract->property->interest != null ? $user_contract->property->interest : Setting::get('admin_commission');
    //         $admin_commission = $interest/100 * $token_equ_value;

    //         if ($eth_balance < ($admin_commission + $token_equ_value)) {
    //             return redirect('/wallet')->with('flash_error', "Insufficient Balance for gas Fee.");
    //         }

    //         $issuer_amount=$token_equ_value;

    //         $user->$chain -= $token_equ_value+$admin_commission;
    //         $user->save();

    //         $admin=Admin::first();
    //         $admin->earnings+=number_format($admin_commission,10,'.','');
    //         $admin->save();

    //         $issuer_user->$chain += $issuer_amount;
    //         $issuer_user->save();

    //         $user_token = UserToken::where('user_id', $user->id)->where('user_contract_id', $request->token_id)->first();
    //         \Log::info($user_token);

    //         if (!is_null($user_token)) {
    //             $user_token->token_acquire = $user_token->token_acquire + $request->nooftoken;
    //             $user_token->save();
    //         } else {
    //             $user_token                   = new UserToken;
    //             $user_token->token_acquire    = $request->nooftoken;
    //             $user_token->user_id          = $user->id;
    //             $user_token->user_contract_id = $request->token_id;
    //             $user_token->save();
    //         }

    //         $user_token_txn                   = new UserTokenTransaction;
    //         $user_token_txn->user_id          = $user->id;
    //         $user_token_txn->user_token_id    = $user_token->id;
    //         $user_token_txn->user_contract_id = $request->token_id;
    //         $user_token_txn->payment_type     = $request->payby;
    //         $user_token_txn->payment_amount   = $issuer_amount;
    //         $user_token_txn->token_price      = $user_contract->tokenvalue;
    //         $user_token_txn->number_of_token  = $request->nooftoken;
    //         $user_token_txn->txn_hash         = 'Transfered';
    //         $user_token_txn->bonus_value      = $user_contract->property->dividend;
    //         $user_token_txn->bonus_token      = $bonus_token;
    //         $user_token_txn->total_token      = $total_token;
    //         $user_token_txn->status           = 1;
    //         $user_token_txn->coin_price       = $coin_price;
    //         $user_token_txn->admin_commission = number_format($admin_commission,6,'.','');
    //         $user_token_txn->save();

    //         $admin_earning = new AdminEarning();
    //         $admin_earning->user_id = $user->id;
    //         $admin_earning->trx_id = $user_token_txn->id;
    //         $admin_earning->contract_id = $request->token_id;
    //         $admin_earning->payment = $request->payby;
    //         $admin_earning->amount = $token_equ_value;
    //         $admin_earning->trx_amount = $issuer_amount;
    //         $admin_earning->earning = number_format($admin_commission,6,'.','');
    //         $admin_earning->save();

    //         $user_contract->tokenbalance = price_format($user_contract->tokenbalance -  $request->nooftoken, 6);
    //         $user_contract->save();
    //         return back()->with('flash_success', "Token Purchased Successfully !");

    //     } catch (Exception $e) {
    //         dd($e);
    //         $this->storeFailedTransaction($user, $request, $user_contract, $bonus_token, $total_token, $coin_price, $token_equ_value);
    //         return back()->with('flash_error', "Transaction Failed...");
    //     }
    // }
    public function investstore(InvestStore $request){

        try {

            $user = Auth::user();

            // Initialize values
            $token_equ_value = 0;
            $bonus_token = 0;
            $total_token = 0;
            $coin_price = 0;

            $from_address = $user->eth_address;

            // Get the user contract related to this token
            $user_contract = (new UserContract)->getUserContract($request->token_id);

            // Validation: ensure user is not requesting more tokens than available
            if ($request->nooftoken > $user_contract->tokenbalance) {
                return back()->with('flash_error', 'No. of token must be less than or equal to ' . $user_contract->tokenbalance);
            }

            $chain = $request->payby; // e.g., 'eth', 'bnb'
            $issuer_id = $user_contract->user_id;

            // Get the issuer (property owner)
            $issuer_user = User::where('id', $issuer_id)->first();

            $total_token = $request->nooftoken;

            // Initialize client and fetch coin prices
            $client = new Client;
            $coinprice = $this->getCryptoPrices();

            // Check user's chain balance (eth, bnb, etc.)
            $eth_balance = $user->$chain;

            if ($eth_balance <= 0) {
                return redirect('/wallet')->with('flash_error', "You have zero balance");
            }

            // ----------------- Calculate USD Equivalent ---------------------
            $eth_price = $coinprice[$chain];
            $coin_price = $eth_price;

            // Calculate required amount in crypto based on token value
            $token_equ_value = $request->nooftoken * $user_contract->tokenvalue / $eth_price;
            $token_equ_value = number_format($token_equ_value, 10, '.', '');

            // Get interest / admin commission rate
            $interest = $user_contract->property->interest != null
                ? $user_contract->property->interest
                : Setting::get('admin_commission');

            $admin_commission = $interest / 100 * $token_equ_value;

            // Ensure user has enough balance for token value + admin commission
            if ($eth_balance < ($admin_commission + $token_equ_value)) {
                return redirect('/wallet')->with('flash_error', "Insufficient Balance for gas Fee.");
            }

            $issuer_amount = $token_equ_value;

            // Deduct from user balance
            $user->$chain -= ($token_equ_value + $admin_commission);
            $user->save();

            // Add admin earnings
            $admin = Admin::first();
            $admin->earnings += number_format($admin_commission, 10, '.', '');
            $admin->save();

            // Add to issuer's balance
            $issuer_user->$chain += $issuer_amount;
            $issuer_user->save();

            // Fetch existing user-token record
            $user_token = UserToken::where('user_id', $user->id)
                ->where('user_contract_id', $request->token_id)
                ->first();

            \Log::info($user_token);

            // Update or create user-token record
            if (!is_null($user_token)) {
                $user_token->token_acquire += $request->nooftoken;
                $user_token->save();
            } else {
                $user_token = new UserToken;
                $user_token->token_acquire = $request->nooftoken;
                $user_token->user_id = $user->id;
                $user_token->user_contract_id = $request->token_id;
                $user_token->save();
            }

            // Create user token transaction log
            $user_token_txn = new UserTokenTransaction;
            $user_token_txn->user_id = $user->id;
            $user_token_txn->user_token_id = $user_token->id;
            $user_token_txn->user_contract_id = $request->token_id;
            $user_token_txn->payment_type = $request->payby;
            $user_token_txn->payment_amount = $issuer_amount;
            $user_token_txn->token_price = $user_contract->tokenvalue;
            $user_token_txn->number_of_token = $request->nooftoken;
            $user_token_txn->txn_hash = 'Transfered';
            $user_token_txn->bonus_value = $user_contract->property->dividend;
            $user_token_txn->bonus_token = $bonus_token;
            $user_token_txn->total_token = $total_token;
            $user_token_txn->status = 1;
            $user_token_txn->coin_price = $coin_price;
            $user_token_txn->admin_commission = number_format($admin_commission, 6, '.', '');
            $user_token_txn->save();

            // Save admin earning entry
            $admin_earning = new AdminEarning();
            $admin_earning->user_id = $user->id;
            $admin_earning->trx_id = $user_token_txn->id;
            $admin_earning->contract_id = $request->token_id;
            $admin_earning->payment = $request->payby;
            $admin_earning->amount = $token_equ_value;
            $admin_earning->trx_amount = $issuer_amount;
            $admin_earning->earning = number_format($admin_commission, 6, '.', '');
            $admin_earning->save();

            // Update remaining token balance for the contract
            $user_contract->tokenbalance = price_format(
                $user_contract->tokenbalance - $request->nooftoken,
                6
            );
            $user_contract->save();

            return back()->with('flash_success', "Token Purchased Successfully !");
        } catch (Exception $e) {
            // In case of error, log failed transaction and show error
            dd($e); // Debug output for development; remove in production
            $this->storeFailedTransaction(
                $user,
                $request,
                $user_contract,
                $bonus_token,
                $total_token,
                $coin_price,
                $token_equ_value
            );

            return back()->with('flash_error', "Transaction Failed...");
        }
    }



    private function storeFailedTransaction($user, $request, $user_contract, $bonus_token, $total_token, $coin_price, $token_equ_value){
        $user_token_txn                   = new UserTokenTransaction;
        $user_token_txn->user_id          = $user->id;
        $user_token_txn->user_token_id    = null;
        $user_token_txn->user_contract_id = $request->token_id;
        $user_token_txn->payment_type     = $request->payby;
        $user_token_txn->payment_amount   = $token_equ_value;
        $user_token_txn->token_price      = $user_contract->tokenvalue;
        $user_token_txn->number_of_token  = $request->nooftoken;
        $user_token_txn->txn_hash         = $details ?? null;
        $user_token_txn->bonus_value      = $user_contract->property->dividend;
        $user_token_txn->bonus_token      = $bonus_token;
        $user_token_txn->total_token      = $total_token;
        $user_token_txn->status           = 0;
        $user_token_txn->coin_price       = $coin_price;
        $user_token_txn->save();
    }

    public function purchaseTokenByETH($fromAddress, $fromEmail, $toAddress, $tokenValue)
    {
        try {
            $client  = new Client();
            $headers = ['Content-Type' => 'application/json',];
            $url     = env('BASE_NODE_URL') . '/getKey';
            $res = $client->post($url, [
                'headers'     => $headers,
                'body' => json_encode([
                    'address'    => $fromAddress,
                    'string' => $fromEmail,
                ]),
            ]);

            $res = json_decode($res->getBody(), true);
            if (isset($res['status']) && $res['status'] == true) {
                //$price = '0x' . dechex(($tokenValue) * 1000000000000000000);
                $eth_pvt_key = $res['key'];
                $url         = 'http://localhost:3008/eth_transfer';

                $client      = new Client();
                $headers     = ['Content-Type' => 'application/json',];
                $body = [
                    'from' => $fromAddress,
                    'password' => $eth_pvt_key,
                    'to' => $toAddress,
                    'value' => $tokenValue,
                ];

                $res = $client->post($url, [
                    'headers' => $headers,
                    'body' => json_encode($body),
                ]);

                $details = json_decode($res->getBody(), true);
                logger()->info($details);

                return $details;
            } else {
                return false;
            }
        } catch (Exception $e) {
            logger()->info($e->getMessage());
            return false;
        }
    }

    public function transferEther($fromAddress, $fromEmail, $toAddress, $tokenValue, $chain)
    {
        try {
            $client = new Client;
            $headers = [
                'Content-Type' => 'application/json',
            ];
            $url = "http://localhost:3000/getKey";
            $body=['address' => $fromAddress, 'string' => $fromEmail];
            $res = $client->post($url, [
                'headers' => $headers,
                'body' => json_encode($body),
            ]);
            $res = json_decode($res->getBody(), true);
            \Log::info($res);
            if (isset($res['status']) && $res['status'] == true) {
                $client     = new Client();
                $headers    = [
                    'Content-Type' => 'application/json',
                ];
                $body       = ["chain" => $chain, "from" => $fromAddress, "to" => $toAddress, "value" => strval($tokenValue), "key" => $res['key']];
                $url        = "http://localhost:3000/eth_transfer";
                $res        = $client->post($url, [
                    'headers' => $headers,
                    'body'    => json_encode($body),
                ]);
                $details = json_decode($res->getBody(), true);
                if (isset($details['status']) && $details['status'] == 'success') {
                    return $details;
                } else {
                    return false;
                }
            } else {
                return false;
            }

            // $client  = new Client();
            // $headers = ['Content-Type' => 'application/json'];
            // $body = ["jsonrpc" => "2.0","method" => "personal_unlockAccount", "params" => [$fromAddress, $fromEmail, 3600], "id" => 1];
            // $url    = env('ETHURL', 'http://localhost');
            // $res    = $client->post($url, [
            //     'headers' => $headers,
            //     'body'    => json_encode($body),
            // ]);
            // $unlock = json_decode($res->getBody(), true);
            // \Log::info($unlock);
            // $price = '0x'.dechex(($tokenValue) * 1000000000000000000);
            // if (isset($unlock['result'])) {
            //     $body = ["jsonrpc" => "2.0", "method" => "eth_sendTransaction", "params" => array(["from" => $fromAddress, "to" => $toAddress, "value" => $price]), "id" => 1];
            //     $res     = $client->post($url, [
            //         'headers' => $headers,
            //         'body'    => json_encode($body),
            //     ]);
            //     $details = json_decode($res->getBody(), true);
            //     \Log::info($details);
            //     if (isset($details['result'])) {
            //         $details['status'] = 'success';
            //         return $details;
            //     }else{
            //         return false;
            //     }

            // }
            // return false;
        } catch (Exception $e) {
            logger()->info($e->getMessage());
            return false;
        }
    }

    public function transferToken($fromAddress, $fromEmail, $toAddress)
    {
        try {
            $client  = new Client();
            $headers = ['Content-Type' => 'application/json',];
            $url     = env('BASE_NODE_URL') . '/getKey';

            $res = $client->post($url, [
                'headers'     => $headers,
                'body' => json_encode([
                    'address'    => $fromAddress,
                    'string' => $fromEmail,
                ]),
            ]);
        } catch (Exception $e) {
            return back()->with('flash_error', "Transaction Failed...");
        }
    }

    public function generateWithdrawOTP(Request $request)
    {
        try {
            $user = Auth::user();
            $data = rand(100000, 999999);
            $user->eth_otp = Hash::make($data);
            $user->save();

            $to_email = $user->email;
            //$to_email = 'test12345671@mailinator.com';

            $maildata = ['otp' => $data];
            if (env('MAIL_STATUS', true)) {

                \Illuminate\Support\Facades\Mail::to($to_email)->send(new Withdrawalotp($maildata));
            }

            return response()->json(['success' => ['msg' => 'We have sent 6 digits OTP to your email address.']], 200);
        } catch (Exception $e) {
            \Log::critical($e->getMessage());
            return response()->json(['error' => ['msg' => 'Something Went Wrong']], 500);
        }
    }

    //Change Password
    public function update_password(UpdatePassword $request)
    {
        $User = Auth::user();
        if (Hash::check($request->current_password, $User->password)) {
            if ($request->current_password != $request->password) {
                $User->password = bcrypt($request->password);
                $User->save();
                //Auth::logout();
                return response(['status' => true, 'message' => 'Password Updated Successfully.']);
            } else {
                return response(['status' => false, 'message' => trans('user.profiles.same')]);
            }
        } else {
            return response(['status' => false, 'message' => trans('user.profiles.current_wrong_pwd')]);
        }
        return response(['status' => false, 'message' => trans('user.profiles.current_wrong_pwd')]);
    }


    public function supportstore(Request $request)
    {
        $this->validate($request, [
            'title'       => 'required',
            'description' => 'required',
        ]);

        try {
            $support              = new Support;
            $support->title       = $request->title;
            $support->description = $request->description;
            $support->status      = 0;
            $support->save();

            return back()->with('flash_success', "Support Created Successfully...");
        } catch (Exception $e) {
            return back()->with('flash_error', "Something went wrong...");
        }
    }

    public function blog()
    {
        $user = Auth::user();
        return view('blog', compact('user'));
    }

    public function blogDetail()
    {
        $user = Auth::user();
        return view('blogDetail', compact('user'));
    }

    public function userKYCUpload(Request $request)
    {
        $user = Auth::user();
        if ($request->hasFile('image') || $request->hasFile('back_image')) {

            $this->validate($request,[
                'image'=>'required|file|mimes:png,jpg,jpeg',
                'back_image'=>'required|file|mimes:png,jpg,jpeg'

            ],[
                'image.file'=>'File Should be Image type',
                'image.mimes'=>'png,jpg,jpeg Formats only will be Accepted..',
                'back_image.file'=>'File Should be Image type',
                'back_image.mimes'=>'png,jpg,jpeg Formats only will be Accepted..'
            ]);

            $key = $request->accredited_kyc_select;
            $image = $request->image;
            $back_image = $request->back_image;

            $document = AccreditedKycDocument::where('user_id', $user->id)->where('accredited_document_id', $key)->delete();

            AccreditedKycDocument::create([
                'user_id' => $user->id,
                'accredited_document_id' => $key,
                'url' => $image->store('accredited_kyc/documents'),
                'back_url' => $back_image->store('accredited_kyc/documents'),
                'status' => 'PENDING',
            ]);
        }

        return back()->with('flash_success', "Your details updated successfully...");
    }

    public function profile()
    {
        $data['user'] = Auth::user();
        // dd($data['user']);
        // $user_id = $user->id;
        $data['country'] = Country::all();
        $data['accredited_documents'] = Document::get();
        $data['kyc_doc'] = AccreditedKycDocument::where('user_id', $data['user']->id)->get();
        return view('profile', $data);
    }

    public function profile_update(Request $request)
    {
        try {
            $user = Auth::user();
            $mobile = $request->country_code . '-' . $request->mobileno;

            $user_identity = new UserIdentity();
            $user_identity->user_id = $user->id;
            $user_identity->dob = $request->dob;
            $user_identity->citizenship = $request->citizenship;
            $user_identity->residence = $request->residence;
            $user_identity->ssn_tax_id = $request->ssn_tax_id;
            if ($request->hasFile('document')) {
                $document = $request->document->store('document');
                $user_identity->document = $document;
            }
            if ($request->hasFile('photo')) {
                $photo = $request->photo->store('photo');
                $user_identity->photo = $photo;
            }
            $user_identity->save();

            $user->name    = $request->fname;
            $user->country_id = $request->country_id;
            $user->save();

            return back()->with('flash_success', 'Profile Has been updated successfully');
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Oops, Unable to update Profile');
        }
    }

    public function profile_identity(ProfileIdentity $request)
    {
        Session::put('check_url', $_SERVER['REQUEST_URI']);
        try {
            $user = Auth::user();
            $name = $request->first_name.' '.$request->last_name;
            $user->name = $name;
            $user->save();

            $data = $request->all();

            unset($data['document'], $data['photo'], $data['_token'], $data['email']);
            if ($request->hasFile('document'))
                $data['document'] = $request->document->store('userdata');
            if ($request->hasFile('photo'))
                $data['photo'] = $request->photo->store('userdata');
            $data['user_id'] = $user->id;

            UserIdentity::updateOrCreate(['user_id' => $user->id], $data);

            if ($request->hasFile('image')) {
                $key = $request->accredited_kyc_select;
                $image = $request->image;

                $document = AccreditedKycDocument::where('user_id', $user->id)->where('accredited_document_id', $key)->delete();

                AccreditedKycDocument::create([
                    'user_id' => $user->id,
                    'accredited_document_id' => $key,
                    'url' => $image->store('accredited_kyc/documents'),
                    'status' => 'PENDING',
                ]);
            }

            return back()->with('flash_success', "Identity updated Successfully...");
        } catch (Exception $e) {
            return back()->with('flash_error', "Something went wrong...");
        }
    }

    public function usercompanydetail(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();
        $user_id = $user->id;
        $user_details_temp = UserCompanyDetails::where('user_id', $user_id)->first();
        if ($user_details_temp) {
            $this->validate($request, [
                'company_name' => 'required',
                'headquarters' => 'required',
                'company_date' => 'required',
                'team_size' => 'required|numeric',
                'company_url' => 'required',
                'social_channels' => 'required',
                //'fund_type' => 'required',
                //'other_fund_type' => 'required',
            ]);
        } else {
            $this->validate($request, [
                'company_name' => 'required',
                'headquarters' => 'required',
                'company_date' => 'required',
                'signing_authority' => 'required|mimes:jpg,jpeg,png,pdf',
                'team_size' => 'required|numeric',
                'company_url' => 'required',
                'social_channels' => 'required',
                'incorporation_certificate' => 'required|mimes:jpg,jpeg,png,pdf',
                'partnership_deed' => 'required|mimes:jpg,jpeg,png,pdf',
                'trust_deed' => 'required|mimes:jpg,jpeg,png,pdf',
                'register_socities' => 'required|mimes:jpg,jpeg,png,pdf',
                //'fund_type' => 'required',
                //'other_fund_type' => 'required',
            ]);
        }
        try {



            if ($user_details_temp) {
                $user_details = $user_details_temp;
            } else {
                $user_details = new UserCompanyDetails;
                $user_details->user_id = $user_id;
            }

            $user_details->company_name = $request->company_name;
            $user_details->headquarters = $request->headquarters;
            $user_details->date_founded = $request->company_date;
            $user_details->team_size = $request->team_size;
            $user_details->company_url = $request->company_url;
            $user_details->social_channels = $request->social_channels;
            $user_details->fund_type = $request->fund_type;
            $user_details->other_fund_type = $request->other_fund_type;

            if ($request->hasFile('incorporation_certificate')) {
                $user_details->incorporation_certificate = $request->incorporation_certificate->store('incorporation_certificates');
            }

            if ($request->hasFile('partnership_deed')) {
                $user_details->partnership_deed = $request->partnership_deed->store('partnership_deeds');
            }

            if ($request->hasFile('trust_deed')) {
                $user_details->trust_deed = $request->trust_deed->store('trust_deeds');
            }

            if ($request->hasFile('register_socities')) {
                $user_details->register_socities = $request->register_socities->store('register_socities');
            }

            if ($request->hasFile('signing_authority')) {
                $user_details->signing_authority = $request->signing_authority->store('signing_authorities');
            }

            $user_details->save();

            if ($request->hasFile('image') || $request->hasFile('back_image')) {
                $key = $request->accredited_kyc_select;
                $image = $request->image;
                $back_image = $request->back_image;

                AccreditedKycDocument::where('user_id', $user->id)->where('accredited_document_id', $key)->delete();

                AccreditedKycDocument::create([
                    'user_id' => $user->id,
                    'accredited_document_id' => $key,
                    'url' => $image->store('accredited_kyc/documents'),
                    'back_url' => $back_image->store('accredited_kyc/documents'),
                    'status' => 'PENDING',
                ]);
            }

            return back()->with('flash_success', "Your details updated successfully...");
        } catch (Exception $e) {
            // dd($e);
            return back()->with('flash_error', "Whoops! Something went wrong");
        }
    }

    public function profile_finance(ProfileFinance $request)
    {
        Session::put('check_url', $_SERVER['REQUEST_URI']);

        try {
            $user = Auth::user();
            $data = $request->all();

            unset($data['e_signature'], $data['accreditation'], $data['_token']);
            if ($request->hasFile('e_signature'))
                $data['e_signature'] = $request->e_signature->store('userdata');
            $data['user_id'] = $user->id;
            if ($request->accreditation)
                $data['accreditation'] = json_encode($request->accreditation);

            UserFinance::updateOrCreate(['user_id' => $user->id], $data);

            return back()->with('flash_success', "Finance updated Successfully...");
        } catch (Exception $e) {
            return back()->with('flash_error', "Something went wrong...");
        }
    }

    public function profile_background(Request $request)
    {
        Session::put('check_url', $_SERVER['REQUEST_URI']);
        $this->validate($request, [
            'investment_experience'           => 'required|max:10',
            'investment_size'                 => 'required|numeric|min:1',
            // 'investment_type' => 'required|max:10',
            'investment_objective'            => 'required|max:30',
            // 'previously_invested' => 'required|max:10',
            'property_type'                   => 'required|max:10',
            // 'geography' => 'required|max:30',
            'holding_period'                  => 'required|max:10',
            'expected_investment_nxt_1year'   => 'required|max:10',
            'expected_investment_per_project' => 'required|max:10',
            'risk_type'                       => 'required|max:30'
        ]);

        try {
            $user = Auth::user();
            $data = $request->all();

            unset($data['geography'], $data['previously_invested'], $data['investment_type'], $data['_token']);

            $data['user_id'] = $user->id;
            if ($request->previously_invested)
                $data['previously_invested'] = json_encode($request->previously_invested);
            if ($request->geography)
                $data['geography'] = json_encode($request->geography);
            if ($request->investment_type)
                $data['investment_type'] = json_encode($request->investment_type);

            UserBackground::updateOrCreate(['user_id' => $user->id], $data);

            return back()->with('flash_success', "Background updated Successfully...");
        } catch (Exception $e) {

            return back()->with('flash_error', "Something went wrong...");
        }
    }

    public function wallet()
    {
        $user    = Auth::user();
        $coins = Coins::where('status', 1)->pluck('symbol')->toArray();
        $admin = Admin::where('id', 1)->first();
        $history_eth = DepositHistory::where('user_id', $user->id)->where('amount', '>', 0)->orderBy('created_at', 'DESC')->take(3)->get();
        $fields = Bank::where('status', 'Active')->get();
        $deposit_history = DepositFiat::where('user_id', $user->id)->take(4)->orderBy('created_at', 'DESC')->get();
        $user_tokens = UserToken::with('usercontract','withdraw_share_amount')->where('user_id',$user->id)->get();
        return view('wallet', compact('user', 'history_eth','fields','deposit_history', 'coins','admin', 'user_tokens'));
    }

    public function GetShareDetail($id){
        try{
            $property = Property::where('id', $id)->first();
            $issuer = User::where('id', $property->user_id)->first();
            return response()->json(['status'=>'success', 'address'=>$issuer->eth_address]) ;
        }catch(Exception $e){
            return response()->json(['status'=>'error']);
            return back()->with('flash_error', "Something went wrong");
        }
    }

    public function withdrawETHBalance()
    {
        $user = Auth::user();
        $history = WithdrawEth::whereuser_id($user->id)->where('coin', '!=', 'USD')->orderBy('created_at', 'DESC')->get();
        $fiat_history = WithdrawEth::whereuser_id($user->id)->where('coin', 'USD')->orderBy('created_at', 'DESC')->get();

        $eth_address = $user->eth_address;

        $coins = Coins::where('status', 1)->pluck('symbol')->toArray();
        // foreach ($coins as $key => $value) {
        //     if($value != 'USD'){
        //         $wallet = json_decode($this->getETHbalance($eth_address,$value), TRUE);
        //         $ethbalance[$value] = $wallet['balance'];
        //     }
        // }

        $user_tokens = UserToken::with('usercontract.property')->where('user_id', $user->id)->get();
        // $whitelistedAccount = $checkInvest = InvestorWhitelist::where('type','withdraw')->where('user_id', $user->id)->get();

        return view('ethwithdraw', compact('user', 'history', 'fiat_history', 'coins','user_tokens'));
    }

    public function investment()
    {
        try {
            $user    = Auth::user();
            $tokens = $user_tokens  = UserToken::with(['usercontract','withdraw_share_amount','tokenTransaction'])->where('user_id', $user->id)->where('status','success')->whereHas('tokenTransaction')->get();
            $coinprice = $this->getCryptoPrices();
            $balance = 0;
            foreach ($this->chains as $key => $value) {
                $eth     = $user->$value;
                $balance +=  $coinprice[$value] * $eth;
                \Log::info($balance);
            }

            $totalNetInvestment = 0;

            foreach ($user_tokens as $key => $value) {
                $property = Property::where('id', @$value->usercontract->property_id)->first();
                $interest = @$property->interest ? @$property->interest : 0;
                $value->commission = @$interest;
            }


            $coins = Coins::where('status', 1)->pluck('symbol')->toArray();


            $shares = InvestorShares::with(['userContract'])
            ->where('user_id', $user->id)
            ->whereNotNull('user_contract_id')
            ->get();

            // Preload whitelisted wallet addresses and index by contract_id
            $whitelistedWalletAddresses = WhiteListedWalletAddress::where('user_id', $user->id)->get()->groupBy('contract_id');

            foreach ($shares as $share) {
                $share->userTokens = UserToken::with('tokenTransaction')
                    ->where('user_contract_id', $share->user_contract_id)
                    ->where('user_id', $share->user_id)
                    ->get();

                // Group external tokens by wallet_id
                $share->ew = $share->userTokens
                    ->where('wallet_type', 'external')
                    ->groupBy('wallet_id')
                    ->map(function ($tokens, $walletId) {
                        return [
                            'wallet_id' => $walletId,
                            'number_token' => $tokens->sum(function ($token) {
                                return $token->tokenTransaction ? $token->tokenTransaction->number_of_token : 0;
                            }),
                            'address' => isset($tokens[0]) ? $tokens[0]->receiver_wallet_address : null,
                        ];
                    })
                    ->values();

                // Safely fetch whitelisted addresses without optional chaining
                $whitelistGroup = $whitelistedWalletAddresses->get($share->user_contract_id);
                $share->whitelisted_address = $whitelistGroup ? $whitelistGroup->toArray() : [];
            }


            $totalNetInvestment = $shares->reduce(function ($carry, $share) {
                $walletTotal = $share->internal_wallet + $share->external_wallet;
                $tokenValue = optional($share->userContract)->tokenvalue ?? 0;
                return $carry + ($walletTotal * $tokenValue);
            }, 0);

            return view('investment', compact('user', 'balance',  'totalNetInvestment','coins','shares'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Unable to get investment details. Please try again later');
        }
    }

    public function ViewProperty($id){
        // try{
            $user = Auth::user();
            $user_contract = UserContract::where('id', $id)->first();
            $property = (new Property)->getProperty(0, $user_contract->property_id);
            if (!is_null($property->userContract)) {
                $tokens                          = (new UserToken)->getUserToken($property->userContract->id)->sum('token_acquire');
                $usd_value                       = $property->userContract->tokenvalue * $tokens;
                $property['accuired_usd']        = $usd_value;
                $percentage                      = ($usd_value / $property->totalDealSize) * 100;
                $property['accuired_percentage'] = $percentage;
            } else {
                $property['accuired_usd']        = 0;
                $property['accuired_percentage'] = 0;
            }

            $transactions = UserTokenTransaction::where('user_contract_id', $id)->where('user_id', $user->id)->get();
            return view('view_prop', compact('user', 'property', 'transactions'));
        // }catch(Exception $e){
        //     return back()->with('flash_error', 'Unable to get details. Please try again later');
        // }
    }

    public function ViewInvestment($id){
        try{
            $user = Auth::user();
            $user_tokens = UserTokenTransaction::where('user_id', $user->id)->where('user_contract_id', $id)->orderBy('id','desc')->get();
            return view('view_invest', compact('user_tokens'));
        }catch(Exception $e){
            dd($e);
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function getCoinValues($Coin)
    {
        $value = 0;
        try {
            $client      = new Client();
            $requestdata = $client->get('https://min-api.cryptocompare.com/data/price?fsym='.$Coin.'&tsyms=USD');
            $response    = json_decode($requestdata->getBody(), 1);
            $value       = $response['USD'];
            \Log::info($value);
            return $value;
        } catch (\Throwable $throwable) {
            $value = 0;
            return $value;
        }
    }

    public function investDetail()
    {
        $user = Auth::user();
        return view('investDetail', compact('user'));
    }

    public function setting(Request $request)
    {
        try {
            $user = Auth::user();
            return view('setting', compact('user'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Unable to get Setting Data. Please try again later');
        }
    }

    public function activity()
    {
        $user = Auth::user();
        return view('activity', compact('user'));
    }

    public function sellToken()
    {
        $user = Auth::user();
        return view('sellToken', compact('user'));
    }

    /**
     * Generate a secret key in Base32 format
     *
     * @return string
     */
    private function generateSecret()
    {
        $randomBytes = random_bytes(10);
        return Base32::encodeUpper($randomBytes);
    }

    /**
     * Used to calculate Token value
     */
    public function gettokeninvestvalue(Request $request){
        try {
            $token_equ_value = $bonus_token = $total_token = $price = 0;
            $user_contract = UserContract::with('property')->where('id', $request->token_id)->first();
            $token_value   = $user_contract->tokenvalue;
            $bonus         = $user_contract->property->dividend;

            $coin_value = $this->getCryptoPrices();
            $price = $coin_value[$request->payment_id];
            $token_equ_value = $price > 0 ? number_format($request->no_of_token * $token_value / $price, 6, '.', '') : number_format($token_value * $request->no_of_token, 2, '.', '');
            // $admin_commission = $user_contract->property->interest ? $user_contract->property->interest : setting('admin_commission');
            // \Log::info($admin_commission);
            // $admin_commission = number_format(($token_equ_value / 100 * $admin_commission), 6);
            // $admin_commission = floatval(str_replace(',', '', $admin_commission,));
            $token_equ_value = floatval(str_replace(',', '', $token_equ_value));
            // $token_equ_value = number_format($token_equ_value + $admin_commission, 6);

            \Log::info(['TokenEqu' => $token_equ_value]);
            if ($request->payment_id == 'stripe') {
                $token_equ_value = number_format($token_value * $request->no_of_token, 2, '.', '');
                // $admin_commission = number_format($token_equ_value / 100 * $admin_commission);
                // $token_equ_value = number_format($token_equ_value + $admin_commission, 2, '.', '');
            }


            if ($bonus == 0) {
                $bonus_token = 0;
            } else {
                $bonus_token = ($request->no_of_token * $bonus) / 100;
            }

            $total_token = $request->no_of_token + $bonus_token;

            $result = ['status' => 1, 'token_equ_value' => $token_equ_value, 'bonus_token' => $bonus_token, 'total_token' => $total_token];

            return $result;
        } catch (Exception $e) {
            dd($e->getMessage(),$e->getLine());
\Log::info($e);
            $result = ['status' => 0];

            return $result;
        }
    }

    /**
     * Used to get Coin Price
     */
    public function getCryptoPrices()
    {
        // Display path: a price feed outage must not take a page down, so an
        // unavailable feed reports zero rather than throwing. This used to index
        // the raw response and died with "Illegal string offset 'USD'" whenever
        // the API answered with an error body, which put the investor dashboard
        // into a redirect loop via the catch block's back().
        $coin_type = fetchCryptoPrices() ?: [
            'ETH'   => 0,
            'BNB'   => 0,
            'MATIC' => 0,
            'USD'   => 0,
        ];
        // $coin_type        = [];
        // $coin_type['ETH'] = 0;
        // $coin_type['MATIC'] = 0;
        // $coin_type['BNB'] = 0;
        // if($cryptoprices['USD']){
        //     $coin_type        = [];
        //     $coin_type['MATIC'] = $cryptoprices['USD'] ;
        // }
        // dd($cryptoprices);// print json decoded response
         // Close request

        // $coin_type['XRP'] = 0;

        return $coin_type;
    }

    /**
     * Get ETH Balance
     */
    public function getETHbalance($address,$chain)
    {
        try {

            if (empty($address)) {
                return json_encode(['msg' => 'failed', 'balance' => 0]);
            }

            $client     = new Client();
            $headers    = [
                'Content-Type' => 'application/json',
            ];
            $body       = ["chain" => $chain, "address" => $address];
            $url        = env('BASE_NODE_URL') . "/native_balance";
            $res        = $client->post($url, [
                'headers' => $headers,
                'body'    => json_encode($body),
            ]);
            $getbalance = json_decode($res->getBody(), true);
            $balance = ($getbalance['status'] == 'success') ? round($getbalance['balance'], 4) : 0;
            return json_encode(['msg' => 'success', 'balance' => $balance]);
        } catch (\Throwable $th) {
            return json_encode(['msg' => 'failed', 'balance' => 0]);
        }
    }

    public function coinPrice()
    {
        try {
            $client = new Client();
            $requestdata = $client->get('https://www.bitstamp.net/api/v2/ticker/' . strtolower(env('BASE_COIN')) . 'usd');
            $response = json_decode($requestdata->getBody(), 1);
            return $response;
        } catch (Exception $e) {
            return response()->json(['message' => 'error', 'error' => 'id_not_valid'], 200);
        }
    }

    public function buyTokens()
    {
        $user = Auth::user();
        $wallet = json_decode($this->getETHbalance($user->eth_address), TRUE);
        $user->ETH = $wallet['balance'];
        $user->save();
        return view('buytokens', compact('user'));
    }

    public function updateDepositHistory($user_id, $eth_address, $chain)
    {
        try {
            if (empty($eth_address)) {
                return false;
            }
            $client      = new Client();
            $url = env($chain.'_COIN_URL') . '/api?apikey='.env("ETHERSCANKEY").'&module=account&action=txlist&address=' . $eth_address . '&page=1&offset=10&sort=asc';
            \Log::info($url);
            $res         = $client->get($url);
            $transaction = json_decode($res->getBody(), true);
            \Log::info($transaction);
            foreach ($transaction['result'] as $item) {
                $hash    = $item['hash'];
                $history = DepositHistory::wheretxn_hash($hash)->where('user_id', $user_id)->first();
                if (!$history) {
                    $eth_amount = $item['value'] / 10 ** 18;

                    $Transaction           = new DepositHistory;
                    $Transaction->user_id  = $user_id;
                    $Transaction->amount   = $eth_amount;
                    $Transaction->type     = $chain;
                    $Transaction->address  = $item['to'];
                    $Transaction->txn_hash = $hash;
                    $Transaction->status   = 'success';
                    $Transaction->save();
                }
            }
        } catch (\Throwable $th) {
            logger()->info($th->getMessage());
        }
    }

    public function checkLastTransaction($eth_address)
    {
        try {
            if (empty($eth_address)) {
                return false;
            }

            $client      = new Client();
            $res         = $client->get('https://api.etherscan.io/api?apikey=' . env("ETHERSCANKEY") . '&module=account&action=txlist&address=' . $eth_address . '&page=1&offset=1&sort=desc');
            $transaction = json_decode($res->getBody(), true);

            $status = false;

            if (!isset($transaction['result'])) {
                $status = true;
            }

            if (count($transaction['result']) == 0) {
                $status = true;
            }

            foreach ($transaction['result'] as $item) {
                if (isset($item['blockNumber']) && isset($item['blockHash'])) {
                    if (!is_null($item['blockNumber']) && !is_null($item['blockHash'])) {
                        $status = true;
                    }
                }
            }

            return $status;
        } catch (\Throwable $th) {
            logger()->info($th->getMessage());
        }
    }

    public function sendETH(Request $request)
    {
        $this->validate($request, [
            'chain'      =>  'required',
            'amount'    =>  'required|numeric|min:0',
            'address'   =>  'required_unless:chain,USD',
            'account'   =>  'required_if:chain,USD',
            'bank_name'   =>  'required_if:chain,USD',
            'account_name'   =>  'required_if:chain,USD',
        ]);

        try {

            $user = Auth::user();

            if($request->chain == 'USD'){

                if($request->amount > $user->USD){
                    return back()->with('flash_error', 'Insufficient funds');
                }

                $receiver_data = [$request->account, @$request->ifsc_code, $request->bank_name, $request->account_name];

                $withdraw_model = new WithdrawEth;
                $withdraw_model->user_id = $user->id;
                $withdraw_model->sender = $user->id;
                $withdraw_model->receiver = json_encode($receiver_data);
                $withdraw_model->amount = $request->amount;
                $withdraw_model->coin = $request->chain;
                $withdraw_model->tx_hash = "***";
                $withdraw_model->reason = "Transaction initiated";
                $withdraw_model->status = "pending";
                $withdraw_model->save();

                return back()->with('flash_success', 'Withdraw request sent successfully.');
            }

            $withdraw_model = new WithdrawEth;
            $withdraw_model->user_id = $user->id;
            $withdraw_model->sender = $user->eth_address;
            $withdraw_model->receiver = $request->address;
            $withdraw_model->amount = $request->amount;
            $withdraw_model->coin = $request->chain;
            $withdraw_model->tx_hash = "***";
            $withdraw_model->reason = "Transaction initiated";
            $withdraw_model->save();

            $reason = null;
            $status = "pending";


            // if(!Hash::check($request->withdrawotp, $user->eth_otp)){
            //     return back()->with('flash_error', 'Withdrawal otp is mismatched');
            // }

            $wallet = json_decode((new HomeController)->getETHbalance($user->eth_address, $request->chain), TRUE);
            $eth_balance = $wallet['balance'];
            if ($eth_balance <= $request->amount || $eth_balance <= 0) {
                $withdraw_model->reason = "Not enough '.$request->chain.' available in your wallet";
                $withdraw_model->status = "failed";
                $withdraw_model->save();
                return back()->with('flash_error', 'Not enough '.$request->chain.' available in your wallet to withdraw');
            }

            $client  = new Client();
            $headers = ['Content-Type' => 'application/json',];
            $url     = env('BASE_NODE_URL') . '/getKey';

            $res = $client->post($url, [
                'headers'     => $headers,
                'body' => json_encode([
                    'address'    => $user->eth_address,
                    'string' => $user->email,
                ]),
            ]);

            $res = json_decode($res->getBody(), true);
            if (isset($res['status']) && $res['status'] == true) {
                    $client     = new Client();
                $headers    = [
                    'Content-Type' => 'application/json',
                ];
                $body       = ["chain" => $request->chain, "from" => $user->eth_address, "to" => $request->address, "value" => $request->amount, "key" => $res['key']];

                $url        = env('BASE_NODE_URL') . "/eth_transfer";
                $res        = $client->post($url, [
                    'headers' => $headers,
                    'body'    => json_encode($body),
                ]);
                $details = json_decode($res->getBody(), true);
                \Log::info($details);
                if (isset($details['status']) && $details['status'] == 'success') {

                    $withdraw_model->reason = "Waiting for confirmation";
                    $withdraw_model->status = "pending";
                    $withdraw_model->tx_hash = $details['txHash'];
                    $withdraw_model->save();
                    return back()->with('flash_success', $request->chain.' has been withdrawn successfully');
                } else {

                    $withdraw_model->reason = "Failed to transfer amount";
                    $withdraw_model->status = "failed";
                    $withdraw_model->save();
                    return back()->with('flash_error', 'Unable to withdraw '.$request->chain.'. Please try again later');
                }
            } else {
                $withdraw_model->reason = "Unable to find the Key";
                $withdraw_model->status = "failed";
                $withdraw_model->save();
                return back()->with('flash_error', 'Unable to withdraw '.$request->chain.'. Please try again later');
            }

            //dd($request->all());

        } catch (\Throwable $th) {
            \Log::critical('withdrawETH', ['message' => $th->getMessage()]);

            $withdraw_model->reason = "Unable to withdraw '.$request->chain.'";
            $withdraw_model->status = "failed";
            $withdraw_model->save();
            return back()->with('flash_error', 'Unable to withdraw '.$request->chain.'. Please try again later');
        }


        //        try {
        //            dd($request->all());
        //            $user = Auth::user();
        //            if(Hash::check($request->withdrawotp, $user->otp)){
        //                $port = $this->checkport('7329');
        //                if($port != false){
        //                    $unlock = $this->unlock($user->eth_address, $user->email);
        //                    if(isset($unlock['result'])) {
        //                        $gasPrice = $this->gasPrice();
        //                        $gasPrice = json_decode($gasPrice, true);
        //                        if(isset($gasPrice['result']) && $gasPrice['result']){
        //                            $gas = $gasPrice['result'];
        //                        }
        //                        else{
        //                            $gas = '0x5208';
        //                        }
        //                        $gwei = hexdec($gas) / 1000000000;
        //                        $ether = $gwei / 1000000000;
        //                        $gasinether = ($ether * 30400);
        //                        $gasinether = ((30 / 100) * $gasinether) + $gasinether;
        //
        //                        $eth_balance = $this->eth_curl($user->eth_address);
        //                        $eth_balance = json_decode($eth_balance, true);
        //                        if(isset($eth_balance['result'])){
        //                            $balance = hexdec($eth_balance['result']);
        //                            $ethbalance = $balance/1000000000000000000;
        //                        }
        //                        else{
        //                            $ethbalance = 0;
        //                        }
        //
        //                        if($eth_balance < $gasinether){
        //                            if($request->ajax())
        //                                return response()->json(['error' => ['msg' => 'Not enough gas fee available in your wallet to withdraw']], 400);
        //                            else
        //                                return back()->with('flash_error', 'Not enough gas fee available in your wallet to withdraw');
        //                        }
        //
        //                        $price = '0x'.dechex(($request->amount) * 1000000000000000000);
        //                        $client = new Client();
        //                        $headers = [
        //                            'Content-Type' => 'application/json',
        //                        ];
        //                        $body = ["jsonrpc" => "2.0","method" => "eth_sendTransaction", "params" => array(["from" => $user->eth_address, "to" => $request->address, "value" => $price]), "id" => 1];
        //                        $url ="http://54.209.140.176:7329";
        //                        $res = $client->post($url, [
        //                            'headers' => $headers,
        //                            'body' => json_encode($body),
        //                        ]);
        //                        $txs = json_decode($res->getBody(),true);
        //                        \Log::info($txs);
        //                        if(isset($txs['result'])) {
        //                            $model = new WithdrawEth;
        //                            $model->user_id = $user->id;
        //                            $model->sender = $user->eth_address;
        //                            $model->receiver = $request->address;
        //                            $model->amount = $request->amount;
        //                            $model->tx_hash = $txs['result'];
        //                            $model->save();
        //                            if($request->ajax())
        //                                return response()->json(['success' => ['msg' => 'ETH has been withdrawn successfully']], 200);
        //                            else
        //                                return back()->with('flash_success', 'ETH has been withdrawn successfully');
        //                        }
        //                        else{
        //                            if($request->ajax())
        //                                return response()->json(['error' => ['msg' => 'Gas fee too high. Please try again later']], 400);
        //                            else
        //                                return back()->with('flash_error', 'Gas fee too high. Please try again later');
        //                        }
        //                    }
        //                    else{
        //                        if($request->ajax())
        //                            return response()->json(['error' => ['msg' => 'Unable to withdraw ETH. Please try again later']], 400);
        //                        else
        //                            return back()->with('flash_error', 'Unable to withdraw ETH. Please try again later');
        //                    }
        //                }
        //                else{
        //                    if($request->ajax())
        //                        return response()->json(['error' => ['msg' => 'Unable to made transaction right now. Please try again later']], 400);
        //                    else
        //                        return back()->with('flash_error', 'Unable to made transaction right now. Please try again later');
        //                }
        //            }
        //            else{
        //                return back()->with('flash_error', 'Withdrawal otp is mismatched');
        //            }
        //        } catch (\Throwable $th) {
        //            \Log::critical('withdrawETH', ['message' => $th->getMessage()]);
        //            if($request->ajax())
        //                return response()->json(['error' => ['msg' => 'Unable to withdraw ETH. Please try again later']], 400);
        //            else
        //                return back()->with('flash_error', 'Unable to withdraw ETH. Please try again later');
        //        }
    }

    public function unlock($address, $email)
    {
        try {
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
            ];
            $body = ["jsonrpc" => "2.0", "method" => "personal_unlockAccount", "params" => [$address, $email, 3600], "id" => 1];
            $url = "http://54.209.140.176:7329";
            $res = $client->post($url, [
                'headers' => $headers,
                'body' => json_encode($body),
            ]);
            $unlock = json_decode($res->getBody(), true);
            return $unlock;
        } catch (\Throwable $th) {
            $unlock = ['status' => false];
            return $unlock;
        }
    }

    /**
     * Used to get ETH Balance
     */
    public function eth_curl($address)
    {
        try {
            $curl = curl_init();
            $request = json_encode([
                'jsonrpc'   =>  '2.0',
                'method'    =>  'eth_getBalance',
                'params'    =>  [
                    $address,
                    'latest'
                ],
                'id'    =>  1
            ]);
            curl_setopt_array($curl, array(
                CURLOPT_URL => env('INFURA_URL'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Throwable $th) {
            \Log::critical('eth_curl', ['message' => $th->getMessage()]);
            return back()->with('flash_error', 'Unable to fetch eth balance data. Please try again later');
        }
    }

    public function checkport($port)
    {
        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
            $host = 'localhost';
        else
            $host = '54.209.140.176';
        $connection = @fsockopen($host, $port);
        return $connection;
    }

    public function gasPrice()
    {
        try {
            $client      = new Client();

            $url = 'https://api.etherscan.io/api?apikey=' . env("ETHERSCANKEY") . '&module=gastracker&action=gasoracle&gasprice=2000000000';
            $res         = $client->get($url);
            $eth_balance = json_decode($res->getBody());
            // dd($eth_balance);
            return $eth_balance->status == 1 ? $eth_balance->result->ProposeGasPrice : 48;
        } catch (\Throwable $th) {
            return 3165;
            return $th->getMessage();
        }
    }

    public function showvoting()
    {
        $questions = Votequestion::with('votechild')->with('votes')->where('status', '!=', 0)->get();
        return view('voting', compact('questions'));
    }

    public function votingstore(Request $request)
    {
        try {
            $user = Auth::user();
            $index = 0;
            foreach ($request->option as $key => $value) {

                $vote = new Vote;
                $vote->user_id = $user->id;
                $question_type = $request->type[$index];

                $vote->question_id = $key;
                $multi = "";
                if ($question_type == 2) {
                    foreach ($value as $key => $value1) {
                        $multi .= $value1 . ',';
                    }
                    $vote->answer = rtrim($multi, ",");
                } else {
                    $vote->answer = $value;
                }

                $index++;
                $vote->save();
            }
            return back()->with('flash_success', "Thanks for voting...");
        } catch (Exception $e) {
            return back()->with('flash_error', "Whoops! Something went wrong...");
        }
    }

    public function GetBankDetails($id){
        try{
            $bank = Bank::where('id', $id)->first();
            return $bank;
        }catch(Exception $e){
            return null;
        }
    }

    public function DepositFiat(Request $request){
        try{
            $user = Auth::user();
            $deposit = new DepositFiat();
            $deposit->user_id = $user->id;
            $deposit->amount = $request->amount;
            if($request->hasFile('proof')){
                $proof = $request->proof->store('proof');
                $deposit->proof = $proof;
            }
            $deposit->bank_id = $request->bank;
            $deposit->save();

            return back()->with('flash_success', 'Deposit successfully. Waiting for admin approval');
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function PlaceTrade(Request $request){
        try{
            $user = Auth::user();

            $checkExistingTrade = Trade::where('user_id', $user->id)->where('status', 'Pending')->first();
            if($checkExistingTrade){
                return back()->with('flash_error', 'Currently you have a open trade. You can initiate a new trade after the existing trade completed or cancelled.');
            }

            $property = Property::with('userContract')->where('id', $request->propert_id)->first();
            $user_token = UserToken::where('user_contract_id', $property->userContract->id)->first();
            $checkTrade = Trade::where('user_id', $user->id)->where('user_contract_id', $property->userContract->id)->where('status', 'Pending')->sum('no_of_tokens');
            $actual_tokens = $checkTrade + $request->no_of_tokens;
            if($actual_tokens > $user_token->token_acquire){
                return back()->with('flash_error', 'Insufficient token balance for trade');
            }
            $trade = new Trade();
            $trade->user_id = $user->id;
            $trade->property_id = $request->propert_id;
            $trade->user_contract_id = $property->userContract->id;
            $trade->no_of_tokens = $request->no_of_tokens;
            $trade->value_of_tokens = $request->total_token_value;
            $trade->buy = $request->buy;
            $trade->buy_value = $request->get_value;
            $trade->save();

            return back()->with('flash_success', 'Trade initiated successfully');
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function OpenTrades(){
        $user = Auth::user();
        $trades = Trade::with('property')->where('user_id', $user->id)->where('status', 'Pending')->get();
        return view('open_trades', compact('trades'));
    }

    public function CancelTrade($id){
        try{
            $trade = Trade::where('id', $id)->first();
            $trade->status = 'Cancelled';
            $trade->save();
            return back()->with('flash_success', 'Trade Cancelled Successfully');
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function GetTradeList(){
        $user = Auth::user();
        $trade_lists = Trade::with('property')->where('user_id', '!=', $user->id)->where('status', 'Pending')->get();
        return view('trades', compact('trade_lists'));
    }

    public function UpdateTrade($id){
        try{
            $user = Auth::user();
            $trade = Trade::where('id', $id)->first();
            $initiate_user = User::where('id', $trade->user_id)->first();

            $coin = $trade->buy;
            $eth_balance = $user->$coin;
            if($eth_balance < $trade->buy_value){
                return back()->with('flash_error', 'Insufficient funds for trade');
            }
            $user->$coin -= $trade->buy_value;
            $user->save();

            $initiate_user->$coin += $trade->buy_value;
            $initiate_user->save();

            $user_tokens = UserToken::where('user_id', $initiate_user->id)->where('user_contract_id',$trade->user_contract_id)->first();
            $user_tokens->token_acquire = $user_tokens->token_acquire - $trade->no_of_tokens;
            $user_tokens->save();

            $updateContract = $this->userTokenSave($user, $trade, 'Completed', null);
            if($updateContract == 'success'){
                $trade->finish_hash = 'Completed';
                $trade->finished_by = $user->id;
                $trade->status = 'Finished';
                $trade->save();
                return back()->with('flash_success', 'Trade Completed Successfully');
            }else{
                return back()->with('flash_error', 'Unable to store data');
            }
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function userTokenSave($user, $trade, $token_hash, $coin_hash){
        try{
            $user_contract = UserContract::with('property')->where('id', $trade->user_contract_id)->first();
            if($user_contract->property->user_id != $user->id){
                $user_token = UserToken::where('user_id', $user->id)->where('user_contract_id', $trade->user_contract_id)->first();
                if($user_token){
                    $user_token->token_acquire = $user_token->token_acquire + $trade->no_of_tokens;
                    $user_token->save();
                }else{
                    $user_token                   = new UserToken;
                    $user_token->token_acquire    = $trade->no_of_tokens;
                    $user_token->user_id          = $user->id;
                    $user_token->user_contract_id = $trade->user_contract_id;
                    $user_token->save();
                }

                $user_token_txn                   = new UserTokenTransaction;
                $user_token_txn->user_id          = $user->id;
                $user_token_txn->user_token_id    = $user_token->id;
                $user_token_txn->user_contract_id = $trade->user_contract_id;
                $user_token_txn->payment_type     = $trade->buy;
                $user_token_txn->payment_amount   = $trade->buy_value;
                $user_token_txn->token_price      = $user_contract->tokenvalue;
                $user_token_txn->number_of_token  = $trade->no_of_tokens;
                $user_token_txn->txn_hash         = $coin_hash;
                $user_token_txn->token_txn_hash   = $token_hash;
                $user_token_txn->bonus_value      = 0;
                $user_token_txn->bonus_token      = 0;
                $user_token_txn->total_token      = $trade->no_of_tokens;
                $user_token_txn->status           = 1;
                $user_token_txn->coin_price       = 510;
                $user_token_txn->save();
            }else{
                $user_contract->tokenbalance += $trade->no_of_tokens;
                $user_contract->save();
            }
            return 'success';
        }catch(Exception $e){
            return 'failed';
        }
    }

    public function getKey($address, $email){
        $client = new Client;
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $url = env('BASE_NODE_URL') . "/getKey";
        $res = $client->post($url, [
            'headers' => $headers,
            'body' => json_encode(['address' => $address, 'string' => $email]),
        ]);
        $res = json_decode($res->getBody(), true);
        if($res['status'] == 'success'){
            $eth_pvt_key = $res['key'];
            return $eth_pvt_key;
        }else{
            return back()->with('flash_error', 'Unable to get key');
        }
    }

    public function TradeHistory(){
        $user = Auth::user();
        $trades = Trade::with('property')->where('user_id', $user->id)->where('status', 'Finished')->get();
        return view('trade_history', compact('trades'));
    }

    public function ExternalWithdraw(){
        $user = Auth::user();
        $withdraw_shares = WithdrawShare::with('user_token')->where('user_id', $user->id)->orderBy('id','desc')->get();
        foreach ($withdraw_shares as $key => $value) {
            $token = UserContract::where('id', $value->user_token->user_contract_id)->first();
            $value->token_name = $token->tokenname;
            $value->symbol = $token->tokensymbol;
            $value->chain = $token->coin;
        }
        return view('withdraw_share_history', compact('withdraw_shares'));
    }

    public function CancelDepositRequest($id){
        try{
            $deposit = DepositFiat::where('id', $id)->first();
            $deposit->status = 'Cancel';
            $deposit->save();

            return back()->with('flash_success', 'Request cancelled successfully');
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function CreateWithdrawShares(Request $request){
        try{
            $this->validate($request,[
                'id' => 'required',
                'amount' => 'required',
                'address' => 'required',
            ]);

            if($request->address == 'another_account'){
                if(!$request->wallet_address){
                    return back()->with('flash_error', 'Wallet address is required, when the address field is add another account');
                }
                $address = $request->wallet_address;
            }else{
                $address = $request->address;
            }

            $user = Auth::user();
            $checkInvest = InvestorWhitelist::with('property')->where('property_id', $request->id)->where('wallet_address',$address)->where('user_id', $user->id)->first();

            if($checkInvest){
                $issuer = User::where('id', $checkInvest->property->user_id)->first();
                $client = new Client;
                $headers = [
                    'Content-Type' => 'application/json',
                ];
                $url = "http://localhost:3000/getKey";
                $res = $client->post($url, [
                    'headers' => $headers,
                    'body' => json_encode(['address' => $issuer->eth_address, 'string' => $issuer->email]),
                ]);
                $res = json_decode($res->getBody(), true);
                if($res['status'] == 'success'){
                    $user_contract = UserContract::where('property_id', $checkInvest->property_id)->first();
                    $transfer_url = env('BASE_NODE_URL', 'http://localhost:3000') . '/transfer';
                    $headers = [
                        'Content-Type' => 'application/json',
                    ];
                    $transfer_res = $client->post($transfer_url, [
                        'headers' => $headers,
                        'body' => json_encode([
                            'contract_address' => $user_contract->contract_address,
                            'to'               => $checkInvest->wallet_address,
                            'amount'           => $request->amount,
                            'senderAddress'    => $issuer->eth_address,
                            'key' => $res['key'],
                            'decimal'          => $user_contract->decimal,
                            'chain' => $user_contract->coin,
                        ]),
                    ]);
                    $transfer_res = json_decode($transfer_res->getBody(), true);
                    if($transfer_res['status'] == 'success'){
                        $user_token = UserToken::where('user_contract_id', $user_contract->id)->where('user_id', $user->id)->first();
                        $user_token->token_acquire -= $request->amount;
                        $user_token->save();

                        $withdraw_share = new WithdrawShare();
                        $withdraw_share->user_id = $user->id;
                        $withdraw_share->user_token_id = $user_token->id;
                        $withdraw_share->trx_hash = $transfer_res['txHash'];
                        $withdraw_share->amount = $request->amount;
                        $withdraw_share->address = $address;
                        $withdraw_share->save();

                        return back()->with('flash_success', 'Share withdraw successfully');
                    }else{
                        return back()->with('flash_error', 'Unable to withdraw share');
                    }
                }else{
                    return back()->with('flash_error', 'Unable to get Key');
                }

            }else{
                $property = (new Property)->getProperty(0, $request->id);

                $whitelist_request = new InvestorWhitelist();
                $whitelist_request->user_id = $user->id;
                $whitelist_request->issuer = $property->user_id;
                $whitelist_request->type = 'withdraw';
                $whitelist_request->property_id = $request->id;
                $whitelist_request->wallet_address = $address;
                $whitelist_request->amount = $request->amount;
                $whitelist_request->save();

                return back()->with('flash_success', 'Request sent to the issuer');
            }
        }catch(Exception $e){
            dd($e);
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function CheckWhiteList($id, $address){
        try{
            $user = Auth::user();
            $checkInvest = InvestorWhitelist::where('property_id', $id)->where('wallet_address',$address)->where('user_id', $user->id)->first();
            if($checkInvest){
                return 'Verified';
            }else{
                return 'Unverified';
            }
        }catch(Exception $e){
            return 'error';
        }
    }

    public function GetPropertyDetails($id){
        try{
            $user = Auth::user();
            $user_contract = UserContract::where('property_id',$id)->first();
            $user_token = UserToken::with('usercontract')->where('user_id', $user->id)->where('user_contract_id', $user_contract->id)->first();
            $checkInvest = InvestorWhitelist::where('property_id', $id)->where('type', 'withdraw')->where('user_id', $user->id)->get();
            return response()->json(['status'=>'success', 'user_token'=>$user_token, 'check_invest'=>$checkInvest]);
        }catch(Exception $e){
            return response()->json(['status'=>'error']);
        }
    }

    public function DepositCrypto(Request $request){
        try{
            // dd($request->all());
            \Log::info($request->all());
            if(!$request->coin){
                return back()->with('flash_error', 'Coin field is required');
            }elseif (!$request->amount) {
                return back()->with('flash_error', 'Amount field is required');
            }elseif (!$request->proof) {
                return back()->with('flash_error', 'Proof field is required');
            }elseif (!$request->hash) {
                return back()->with('flash_error', 'Hash field is required');
            }

            $user = Auth::user();

            if($request->type){
                if($request->type == 'metamask'){
                    $user->USD += $request->amount;
                    $user->save();
                }
            }
            $history = new DepositHistory();
            $history->user_id = $user->id;
            $history->type = $request->coin;
            $history->address = $request->admin_address;
            $history->txn_hash = $request->hash;
            $history->amount = $request->amount;
            if($request->type){
                if($request->type == 'metamask'){
                    $history->status = 'success';
                }
            }else{
                $history->status = 'pending';
            }
            if($request->hasFile('proof')){
                $proof = $request->proof->store('proof');
                $history->proof = $proof;
            }else{
                $history->proof = 'Not needed';
            }
            $history->save();

            if($request->ajax()){
                return response()->json(['status'=>'success']);
            }
            return back()->with('flash_success', 'Deposit request sent to the admin');
        }catch(Exception $e){
            \Log::info($e);
            if($request->ajax()){
                return response()->json(['status'=>'error']);
            }
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function upsertPurchaseRequest($user_id, $id, Request $request,TokenPaymentService $tokenService){
        $user = Auth::user();
        if(!$user->canMakeInvestment()){
            return redirect()->route('platform.purchase');
        }
        try {

            $userContract = UserContract::where('id', $id)
                ->where('status', 1)
                ->firstOrFail();

            if(!$userContract){
                return back()->with('flash_error', 'Invalid Token Contract');
            }
            $userToken = $this->checkIfUserTokenExists($user->id, $id);
            // Step 1: Validate wallet custody type
            if ($request->currentStep == 2) {
                $request->validate([
                    'custody' => 'required|in:internal,external',
                ]);
            }


            // Step 2: Validate tokens when creating a new request
            if ($request->currentStep == 1 && !empty($userToken)) {
                if (!$userContract || $userContract->status != 1) {
                    return back()->with('flash_error', 'Invalid Token Details');
                }

                $request->validate([
                    'tokens' => 'required|numeric|min:1|max:' . $userContract->tokenbalance,
                ]);

            }

            // Step 3: Validate payment fields for step 3
            if ($request->currentStep == 3) {
                $request->validate([
                    'payment_method' => 'required|string',
                    'selected_payment_id' => 'required|numeric',
                    'payment_reference' => 'required|string|max:255',
                    'payment_proof' => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
                ]);
            }

            $data = $request->only([
                'currentStep', 'custody', 'tokens',
                'contract_address', 'whitelisted_wallet_id',
                'payment_method', 'selected_payment_id',
                'payment_reference', 'payment_proof','payby'
            ]);

            $tokenService->handleUpsertRequest($user,$userContract,$data);
            if($data['currentStep'] == 3 && config('app.is_demo')){
                return redirect()->route('investment');
            }
            return redirect()->route('applyInvest', ['id' => $userContract->property_id])
                ->with('success', 'Request Updated Successfully');

        } catch (\Exception $e) {
            logException($e, [
                'user_id' => $user_id,
                'request_data' => $request->all(),
                'contract_id' => $id,
            ]);
            return back()->with('flash_error', $e->getMessage() ?: 'Unable to process your request. Please try again later.');
        }
    }

    private function checkIfUserTokenExists($userId, $contractId){
        return UserToken::where('user_id', $userId)
            ->where('user_contract_id', $contractId)
            ->whereIn('status', ['inProgress', 'inReview'])
            ->first();
    }



    public function discardPurchaseRequest($user_id, $id, Request $request){
        $user = Auth()->user();
        $userToken = UserToken::where('user_id' ,$user->id)->where('status', 'inProgress')->first();
        $userToken->delete();
        return back()->with('flash_success', 'Request discarded successfully');
    }

    public function whitelistAddress($user_id, $id, Request $request)
    {
        try {
            $user = Auth()->user();

            $userToken = UserToken::find($request->token_id);

            $userContract = UserContract::where('id', $id)->first();
            if (!$userContract) {
                return [
                    'status' => false,
                    'message' => 'User contract not found.',
                    'code' => 404
                ];
            }

            $property = $userContract->property()->first();
            if (!$property) {
                return [
                    'status' => false,
                    'message' => 'Property not found.',
                    'code' => 404
                ];
            }

            $privateKey = $property->getPrivateKey($user->id);

            $whitelistedAddress = WhiteListedWalletAddress::where('user_id', $user->id)
                ->where('contract_id', $userContract->id)
                ->where('wallet_address', 'like', '%' . $request->address . '%')
                ->first();

            if (!empty($whitelistedAddress)) {
                return [
                    'status' => true,
                    'message' => 'Address already whitelisted.',
                    'code' => 200
                ];
            }

            $payload = [
                'contract_address' => $userContract->contract_address,
                'privateKey' => $privateKey,
                'chain' => $userContract->blockchain->abbreviation,
                'address' => $request->address,
                'amount' => 100,
                'receiveDayLimit' => 100000000,
                'sendDayLimit' => 100000000
            ];

            $result = callNodeOperations('whitelist', $payload);

            if (isset($result['status']) && $result['status'] == 'success') {
                $whiteListedWallet = WhiteListedWalletAddress::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'contract_id' => $userContract->id,
                        'wallet_address' => trim($request->address),
                    ],
                    [
                        'whitelisted_by' => $property->user_id,
                        'status' => 1,
                        'tx_hash' => $result['txHash'],
                    ]
                );


                $whitelistedWallets = WhiteListedWalletAddress::where('user_id', $user->id)
                    ->where('contract_id', $userContract->id)
                    ->get(['id', 'wallet_address']);

                return [
                    'status' => true,
                    'message' => 'Address successfully whitelisted.',
                    'data' => $whitelistedWallets,
                    'code' => 201
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Failed to whitelist address. Node server response: ' . ($result['message'] ?? json_encode($result)),
                    'code' => 500
                ];
            }
        } catch (\Throwable $e) {
            logException($e, [
                'user_id' => $user_id,
                'contract_id' => $id,
                'request_address' => $request->address
            ]);
            return [
                'status' => false,
                'message' => 'Something went wrong. Please try again later.',
                'code' => 500
            ];
        }
    }


    protected function updateUserTokenForBuyProcess(Request $request ,$user,  $userContract,$userToken){
        $chain = $request->payby;
        $coin_price_data = $this->getCryptoPrices();
        $coin_price = $coin_price_data[$chain];
        $eth_balance = $user->$chain;

        // Re-Calculate token equivalent in crypto
        $token_equ_value = $this->calculateTokenEquivalent(
            $request->tokens,
            $userContract->tokenvalue,
            $coin_price
        );

        // Recalculate admin commission
        $adminCommission = $this->calculateAdminCommission(
            $userContract->property->interest ?? Setting::get('admin_commission'),
            $token_equ_value
        );

        // Check if the user has enough balance
        if ($eth_balance < ($token_equ_value + $adminCommission)) {
            return [
                'status' => 'fail',
                'errorCode' => 0,
                'message' => 'Insufficient balance'
            ];
        }

        if (!$userToken) {
            return [
                'status' => 'fail',
                'errorCode' => 1,
                'message' => 'User token not found'
            ];
        }

        $userToken->update([
            'token_acquire' => $request->tokens,
            'commission' => $adminCommission,
            'deal_amount' => $token_equ_value,
            'payment_by' => $chain
        ]);

        return [
            'status' => 'success',
            'userToken' => $userToken
        ];
    }

    protected function createUserTokenForBuyProcess(Request $request,$user, $tokenId ,$userContract){
        $coinprice = $this->getCryptoPrices();
        $chain = $request->payby;
        $eth_price = $coinprice[$chain];
        $coin_price = $eth_price;

        $tokens = floatval($request->tokens);
        $tokenValue = floatval($userContract->tokenvalue);
        $ethPrice = floatval($eth_price);

        // perform the calculation
        $token_equ_value = ($tokens * $tokenValue) / $ethPrice;

        // $adminCommission = $this->calculateAdminCommission(
        //     $userContract->property->interest ?? 0,
        //     $token_equ_value
        // );
        $totalTokenAmount = $this->calculateTokenEquivalent(
            $request->tokens,
            $userContract->tokenvalue,
            $coin_price
        );

        $eth_balance = $user->$chain;
        $coin_price_data = currentCryptoPrices();
        $coin_price = $coin_price_data[$chain];

        $token_equ_value = $this->calculateTokenEquivalent(
            $request->tokens,
            $userContract->tokenvalue,
            $coin_price
        );


        // Check if user has enough balance for token + commission
        // if ($eth_balance < ( $token_equ_value + $adminCommission)) {
        //     return [
        //         'status' => 'fail',
        //         'errorCode' => 0
        //     ];
        // }

        $userToken = UserToken::create([
            'user_id' => $user->id,
            'user_contract_id' => $tokenId ,
            'token_acquire' => $request->tokens,
            'payment_by' =>  $chain,
            // 'commission' => $adminCommission,
            'commission' => 0,
            'deal_amount'   =>  $token_equ_value,
            'issuer_id' => $userContract->issued_by,
            'property_id' => $userContract->property_id,
            'current_stage' => 1
        ]);

        return [
            'status' => 'success',
            'userToken' => $userToken
        ];

    }

    protected function calculateAdminCommission($interest_rate, $token_equ_value)
    {
        // Ensure values are treated as numbers
        $interest_rate = floatval($interest_rate);
        $token_equ_value = floatval($token_equ_value);

        $commission = ($interest_rate / 100) * $token_equ_value;

        // Return raw value OR format only if needed for display
        return $commission; // or number_format($commission, 2)
    }

    protected function calculateTokenEquivalent($tokens, $token_value, $coin_price){
        // Ensure values are treated as numbers
        $tokens = floatval($tokens);
        $token_value = floatval($token_value);
        $coin_price = floatval($coin_price);

        if ($coin_price == 0) {
            return 0; // prevent division by zero
        }

        $equivalent = ($tokens * $token_value) / $coin_price;

        // Format with high precision (10 decimal places)
        return number_format($equivalent, 10, '.', '');
    }



    public function getInvestorBuyRequest(){
        $user = Auth()->user();
        if($user->user_type == 2){


            $requests = UserToken::with(['property', 'usercontract', 'user','tokenTransaction'])
            ->whereHas('usercontract', function ($query) use ($user) {
                $query->where('issued_by', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            return view('issuer.buy_request_history', compact('requests', 'user'));
        }else{
            $requests = UserToken::with(['property','usercontract'])->where('user_id',$user->id)->paginate(50);

            return view('buy_requests', compact('requests', 'user'));

        }

    }

    /**
     * Update investment step for navigation
     */
    public function updateInvestmentStep($user_id, $id, Request $request){
        $user = Auth::user();
        try {
            $userContract = UserContract::where('id', $id)
                ->where('status', 1)
                ->firstOrFail();

            if(!$userContract){
                return back()->with('flash_error', 'Invalid Token Contract');
            }

            $userToken = UserToken::where('user_id', $user->id)
                ->where('user_contract_id', $id)
                ->whereIn('status', ['inProgress', 'inReview'])
                ->first();

            if(!$userToken){
                return back()->with('flash_error', 'No active investment request found');
            }

            // Validate the step number
            $request->validate([
                'currentStep' => 'required|integer|min:1|max:3',
            ]);

            $targetStep = $request->currentStep;

            // Update the current_stage (0-based) to target step - 1
            $userToken->current_stage = $targetStep - 1;
            $userToken->save();

            return redirect()->route('applyInvest', ['id' => $userContract->property_id])
                ->with('success', 'Step updated successfully');

        } catch (\Exception $e) {
            logException($e, [
                'user_id' => $user_id,
                'request_data' => $request->all(),
                'contract_id' => $id,
            ]);
            return back()->with('flash_error', $e->getMessage() ?: 'Unable to update step. Please try again later.');
        }
    }

    /**
     * Get network configurations for frontend network switching
     */
    public function getNetworkConfigs()
    {
        try {
            $blockchains = BlockchainModel::all();
            $networkConfigs = [];

            \Log::info('Blockchains found:', $blockchains->toArray());

            foreach ($blockchains as $blockchain) {
                $chainId = $blockchain->chain_id ?? $blockchain->test_chain_id;

                \Log::info("Processing blockchain: {$blockchain->blockchain_name}, chain_id: {$chainId}");

                if ($chainId) {
                    $networkConfigs[$chainId] = [
                        'chainId' => '0x' . dechex($chainId),
                        'chainName' => $blockchain->blockchain_name,
                        'nativeCurrency' => [
                            'name' => $this->getNativeCurrencyName($blockchain->abbreviation),
                            'symbol' => $blockchain->abbreviation,
                            'decimals' => 18
                        ],
                        'rpcUrls' => [$this->getRpcUrl($blockchain)],
                        'blockExplorerUrls' => [$blockchain->link ?? '']
                    ];
                }
            }

            \Log::info('Final network configs:', $networkConfigs);

            return response()->json([
                'status' => 'success',
                'data' => $networkConfigs
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getNetworkConfigs:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get network configurations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get native currency name for blockchain
     */
    private function getNativeCurrencyName($abbreviation)
    {
        $currencies = [
            'ETH' => 'Ether',
            'MATIC' => 'MATIC',
            'BNB' => 'BNB',
            'USDC' => 'USD Coin',
            'USDT' => 'Tether'
        ];

        return $currencies[$abbreviation] ?? $abbreviation;
    }

    /**
     * Get RPC URL for blockchain
     */
    private function getRpcUrl($blockchain)
    {
        // You can customize these URLs based on your needs
        $rpcUrls = [
            'ETH' => 'https://mainnet.infura.io/v3/',
            'MATIC' => 'https://polygon-rpc.com/',
            'BNB' => 'https://bsc-dataseed.binance.org/'
        ];

        return $rpcUrls[$blockchain->abbreviation] ?? 'https://mainnet.infura.io/v3/';
    }
}