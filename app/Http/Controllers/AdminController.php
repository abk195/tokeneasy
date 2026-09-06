<?php

namespace App\Http\Controllers;

use Auth;

use Mail;
use Cache;
use Crypt;
use Setting;
use Storage;
use App\User;
use App\Admin;
use App\Coins;
use App\UserToken;
use App\WithdrawEth;
use App\Support;
use App\UserContract;
use Carbon\Carbon;
use App\Document;
use App\KycDocument;
use App\AdminAddress;
use App\KrakenResult;
use App\Mail\Kycmail;
use GuzzleHttp\Client;
use App\UserTrasaction;
use App\WalletPassbook;
use App\DepositHistory;
use App\AdminEarning;
use App\Mail\KycApproval;
use App\Mail\UsdWithdraw;
use App\AccreditedDocument;
use App\Mail\Userwirealert;
use App\Mail\CryptoWithdraw;
use App\Mail\KycDocRejected;
use Illuminate\Http\Request;
use App\Mail\Adminbtcotpmail;
use App\UserTokenTransaction;
use App\AccreditedKycDocument;
use App\Mail\AdminApprovalMail;
use App\Mail\Userwirealertfailed;
use App\Http\Controllers\simple_crypt;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\TransactionController;
use App\Http\Requests\ValidateAdminSecretRequest;
use App\Property;
use App\KeystoreModel;
use App\PropertyComparable;
use App\IssuerTokenRequest;
use App\ManagementMembers;
use App\AssetType;



class AdminController extends Controller
{
    /**
     * Used to get Dashboard Details
     */
    public function dashboard()
    {
        try {
            $admin = Auth::guard('admin')->user();
            // if(!$admin->wallet_address){
            //     $eth_address       = ((new NodeController)->eth_address($admin->email))->getData();
            //     $eth_address = ($eth_address->success == true) ? $eth_address->response : null;
            //     $admin->wallet_address = $eth_address;
            //     $admin->save();
            // }

            $totalNetInvestment = 0;
            $fiat_balance=0;
            $tokensss= UserToken::with('usercontract.property')->get();
            foreach ($tokensss as $item) {
                $user_tokens = UserTokenTransaction::get();
                $coinInvestment = 0;
                $coinValue = 0;
                $coinType = '';
                $fiatvalues=0;
                foreach ($user_tokens as $utoken) {
                    $coinInvestment += $utoken->payment_amount;
                    if($utoken->payment_type=='TAZAPAY'){
                        $coinValue += $utoken->payment_type == 'TAZAPAY' ? $utoken->payment_amount: $utoken->payment_amount;
                        $fiatvalues += $utoken->payment_type == 'TAZAPAY' ? $utoken->payment_amount: $utoken->payment_amount;


                    }else{
                        $coinValue += $utoken->payment_type == 'MATIC' ? $utoken->payment_amount * $utoken->coin_price : $utoken->payment_amount;
                    }
                    $coinType = $utoken->payment_type;
                }

                $totalNetInvestment += $coinValue;
                $fiat_balance+=$fiatvalues;

            $users =UserTokenTransaction::getGroupingByMonthsAdmin();
            $usermcount = [];
            $tokens = [];
            foreach ($users as $key => $value) {
                $usermcount[(int)$key] = count($value);
            }
            for($i = 1; $i <= 12; $i++){
                if(!empty($usermcount[$i])){
                    $tokens[$i] = $usermcount[$i];    
                }else{
                    $tokens[$i] = 0;    
                }
            }
            $users_i =UserContract::getGroupingByMonthsAdmin();
            $usermcount_i = [];
            $tokens_i = [];
            foreach ($users_i as $key => $value) {
                $usermcount_i[(int)$key] = count($value);
            }
            for($i = 1; $i <= 12; $i++){
                if(!empty($usermcount_i[$i])){
                    $tokens_i[$i] = $usermcount_i[$i];    
                }else{
                    $tokens_i[$i] = 0;    
                }
            }
        }
        if(count($tokensss) == 0){
            $tokens = [];
            $tokens_i = [];
        }
        $ETH_balance = 1;
            return view('admin.home', compact('tokens','tokens_i','totalNetInvestment','fiat_balance','ETH_balance'));
        } catch (Exception $e) {
            return back()->with('flash_error', 'Unable to get Dashboard Details');
        }
    }

    public function Earnings(){
        try{
            $earnings = AdminEarning::with('user_toke_transaction')->orderBy('id', 'desc')->get();
            $total_earnings = AdminEarning::sum('earning');
            return view('admin.earnings', compact('earnings', 'total_earnings'));
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function UserWallet($type){
        try{
            if($type == 'investor'){
                $users = User::where('approved', 1)->where('user_type', 1)->orderBy('id', 'desc')->get();
            }else{
                $users = User::where('approved', 1)->where('user_type', 2)->orderBy('id', 'desc')->get();
            }
            
            return view('admin.user_wallets', compact('users'));
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    /**
     * Used to Check User KYC Documents
     */
    public function kycdoc($id)
    {
        try {
            $Doc = KycDocument::getDocuments($id);
            $user = User::getUser($id);
            return view('admin.user.document', compact('Doc', 'user'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.user.user_not_found'));
        }
    }

    /**
     * Approve or reject one of a user's KYC documents.
     *
     * Both buttons on admin/user/document.blade.php post here and carry the
     * target state in `status`, which is why reject() below simply defers to
     * this method.
     */
    public function userdocument_approve(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'doc_id'  => 'required|integer',
            'status'  => 'required|in:APPROVED,REJECTED,PENDING',
        ]);

        try {
            $kyc = KycDocument::where('user_id', $request->user_id)
                ->where('document_id', $request->doc_id)
                ->first();

            if (!$kyc) {
                return back()->with('flash_error', trans('api.something_went_wrong'));
            }

            $kyc->status = $request->status;
            $kyc->save();

            $user = User::find($kyc->user_id);
            if ($user) {
                $user->kyc = $this->hasApprovedEveryMandatoryDocument($user->id) ? 1 : 0;
                $user->save();
            }

            return back()->with('flash_success', trans('api.success_status'));
        } catch (\Throwable $e) {
            logException($e, [
                'user_id' => $request->user_id,
                'doc_id'  => $request->doc_id,
                'status'  => $request->status,
            ]);

            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    /**
     * Reject one of a user's KYC documents.
     */
    public function userdocument_reject(Request $request)
    {
        $request->merge(['status' => 'REJECTED']);

        return $this->userdocument_approve($request);
    }

    /**
     * A user is KYC-verified once every mandatory document has been approved.
     */
    private function hasApprovedEveryMandatoryDocument($userId)
    {
        $mandatory = Document::where('mandatory', '1')->pluck('id');

        if ($mandatory->isEmpty()) {
            return false;
        }

        $approved = KycDocument::where('user_id', $userId)
            ->whereIn('document_id', $mandatory)
            ->where('status', 'APPROVED')
            ->distinct()
            ->count('document_id');

        return $approved >= $mandatory->count();
    }

    /**
     * Used to Change User Status
     */
    public function userStatus($id, $status)
    {
        try {
            if ($status == 'approve') {
                $Kyc = KycDocument::getDocuments($id, 'APPROVED');
                if (count($Kyc) >= 1)
                    return back()->with('flash_error', trans('api.kyc_not_verified'));

                User::getUser($id)->update(['kyc' => 1]);
                return back()->with('flash_success', trans('api.approved'));
            } else {
                User::getUser($id)->update(['kyc' => 0]);
                return back()->with('flash_error', trans('api.disapproved'));
            }
        } catch (Exception $e) {
            return back()->with('flash_error',  trans('api.something_went_wrong'));
        }
    }

    public function userApprovalStatus($id, $status)
    {
        try {
            if ($status == 'approve') {
                User::getUser($id)->update(['approved' => 1]);
                $user = User::getUser($id);
                return back()->with('flash_success', trans('api.approved'));
            }elseif ($status == 'Block') {
                User::getUser($id)->update(['approved' => 2]);
                return back()->with('flash_error', 'User Blocked');
            }elseif ($status == 'Un-Block') {
                User::getUser($id)->update(['approved' => 1]);
                return back()->with('flash_success', 'User Un-Blocked');
            }
        } catch (Exception $e) {
            return back()->with('flash_error',  trans('api.something_went_wrong'));
        }
    }



    /**
     * Used to Check User Status
     */
    public function history()
    {
        try {

            $History = UserTrasaction::orderBy('id', 'desc')->get();

            return view('admin.history.index', compact('History'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }



    public function settings()
    {

        try {

            if (Auth::guard('admin')->user()->role == 1) {
                return view('errors.404');
            }
            return view('admin.settings.settings');
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function settings_store(Request $request)
    {

        try {

            $this->validate($request, [
                'site_title' => 'required',
                'site_icon' => 'mimes:jpeg,jpg,bmp,png|max:70000',
                'site_logo' => 'mimes:jpeg,jpg,bmp,png|max:70000',
            ]);

            if ($request->hasFile('site_icon')) {
                $site_icon = $request->site_icon->store('settings');
                Setting::set('site_icon', $site_icon);
            }

            if ($request->hasFile('site_logo')) {
                $site_logo = $request->site_logo->store('settings');
                Setting::set('site_logo', $site_logo);
            }

            if ($request->hasFile('site_email_logo')) {
                $site_email_logo = $request->site_email_logo->store('settings');
                Setting::set('site_email_logo', $site_email_logo);
            }

            Setting::set('site_title', $request->site_title);
            Setting::set('site_copyright', $request->site_copyright);
            Setting::set('kyc_approval', $request->kyc_approval == 'on' ? 1 : 0);


            // $admin = Auth::guard('admin')->user();
            // $admin->wallet_address = $request->admin_address;
            // $admin->save();

            // Setting::set('admin_address_bnb', $request->admin_address_bnb);
            // Setting::set('admin_address_matic', $request->admin_address_matic);

            // Setting::set('admin_address', $request->admin_address);
            Setting::set('support_mail', $request->support_mail);
            Setting::set('enquiry_mail', $request->enquiry_mail);
            Setting::set('number', $request->number);
            Setting::set('instagram', $request->instagram);
            Setting::set('twitter', $request->twitter);
            Setting::set('facebook', $request->facebook);
            Setting::set('default_currency', $request->default_currency);
            Setting::set('admin_commission', $request->admin_commission);
            Setting::save();

            return back()->with('flash_success', trans('api.setting_status'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function AdminWallets(){
        return view('admin.wallet');
    }

    public function StoreWalletAddress(Request $request){
        try{
            $this->validate($request, [
                'admin_address_eth' => 'required',
                'admin_address_bnb' => 'required',
                'admin_address_matic' => 'required',
                'admin_usdc_address' => 'required',
                'admin_usdt_address' => 'required',
                'admin_die_address' => 'required',
                'usdt_address' => 'required',
                'usdc_address' => 'required',
                'die_address' => 'required',
            ]);

            Setting::set('admin_address_eth', $request->admin_address_eth);
            Setting::set('admin_address_bnb', $request->admin_address_bnb);
            Setting::set('admin_address_matic', $request->admin_address_matic);
            Setting::set('admin_usdc_address', $request->admin_usdc_address);
            Setting::set('admin_usdt_address', $request->admin_usdt_address);
            Setting::set('admin_die_address', $request->admin_die_address);
            Setting::set('usdt_address', $request->usdt_address);
            Setting::set('usdc_address', $request->usdc_address);
            Setting::set('die_address', $request->die_address);
            setting::save();

            return back()->with('flash_success','Wallet address updated successfully');
        }catch(Exception $e){
            dd($e);
            return back()->with('flash_error','Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_payment()
    {
        try {

            if (Auth::guard('admin')->user()->role == 1) {
                return view('errors.404');
            }

            return view('admin.payment.settings');
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    /**
     * Save payment related settings.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_payment_store(Request $request)
    {
        $this->validate($request, [
            'CARD' => 'in:on',
            'CASH' => 'in:on',
            'PAYPAL' => 'in:on',

            'stripe_secret_key' => 'required_if:CARD,on|max:255',
            'stripe_publishable_key' => 'required_if:CARD,on|max:255',
            'currency' => 'required',
            'bit_coin_price' => 'required',
            'ethereum_price' => 'required',
            'ripple_price' => 'required',
        ]);

        try {

            if (Auth::guard('admin')->user()->role == 1) {
                return view('errors.404');
            }

            Setting::set('CARD', $request->has('CARD') ? 1 : 0);
            Setting::set('CASH', $request->has('CASH') ? 1 : 0);
            Setting::set('PAYPAL', $request->has('PAYPAL') ? 1 : 0);

            Setting::set('stripe_secret_key', $request->stripe_secret_key ?: '');
            Setting::set('stripe_publishable_key', $request->stripe_publishable_key ?: '');
            Setting::set('increase_percentage', $request->increase_percentage ?: '');
            Setting::set('admin_gaslimit', $request->admin_gaslimit);
            Setting::set('user_gaslimit', $request->user_gaslimit);
            Setting::set('gas', $request->gas);
            Setting::set('eth_alert', $request->eth_alert);

            Setting::set('currency', $request->currency);
            Setting::set('referral', $request->referral);
            Setting::set('withdraw_time', $request->withdraw_time);
            Setting::set('withdraw_comission', $request->withdraw_comission);

            Setting::set('account_name', $request->account_name);
            Setting::set('bank_address', $request->bank_address);
            Setting::set('bank_number', $request->bank_number);
            Setting::set('account_number', $request->account_number);
            Setting::set('sort_code', $request->sort_code);
            Setting::set('iban', $request->iban);
            Setting::set('swift_code', $request->swift_code);
            Setting::set('routing_code', $request->routing_code);
            Setting::set('bank_name', $request->bank_name);
            Setting::set('reply_message', $request->reply_message);

            Setting::set('lgc_commission', $request->lgc_commission);
            Setting::set('btc_commission', $request->btc_commission);
            Setting::set('eth_commission', $request->eth_commission);
            Setting::set('xrp_commission', $request->xrp_commission);
            Setting::set('bch_commission', $request->bch_commission);
            Setting::set('ltc_commission', $request->ltc_commission);

            Setting::set('lgc_withdrawl', $request->lgc_withdrawl);
            Setting::set('btc_withdrawl', $request->btc_withdrawl);
            Setting::set('eth_withdrawl', $request->eth_withdrawl);
            Setting::set('xrp_withdrawl', $request->xrp_withdrawl);
            Setting::set('bch_withdrawl', $request->bch_withdrawl);
            Setting::set('ltc_withdrawl', $request->ltc_withdrawl);
            Setting::set('bit_coin_price', $request->bit_coin_price);
            Setting::set('ethereum_price', $request->ethereum_price);
            Setting::set('ripple_price', $request->ripple_price);
            Setting::set('WIRE', $request->WIRE == 'on' ? 1 : 0);
            Setting::set('FIAT', $request->FIAT == 'on' ? 1 : 0);
            Setting::set('BTC', $request->BTC == 'on' ? 1 : 0);
            Setting::set('ETH', $request->ETH == 'on' ? 1 : 0);
            Setting::set('XRP', $request->XRP == 'on' ? 1 : 0);
            Setting::set('BCH', $request->BCH == 'on' ? 1 : 0);
            Setting::set('LTC', $request->LTC == 'on' ? 1 : 0);

            Setting::set('eth_api_key', $request->eth_api_key);



            Setting::save();

            return back()->with('flash_success', trans('api.setting_status'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function historySuccess($id)
    {
        try {

            $History = WithdrawHistory::findOrFail($id);

            $History->status = "SUCCESS";
            $History->save();

            $User = User::where('id', $History->user_id)->first();

            $maildata = $History;
            $email = $User->email;

            if (env('MAIL_STATUS', true)) {
                Mail::to($email)->send(new UsdWithdraw($maildata));
            }

            return back()->with('flash_success', trans('api.success_status'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function historyFailed($id)
    {
        try {

            $History = WithdrawHistory::findOrFail($id);

            $History->status = "FAILED";
            $History->save();

            $User = User::where('id', $History->user_id)->first();
            $User->wallet += $History->amount;
            $User->save();

            $User = User::where('id', $History->user_id)->first();

            $maildata = $History;
            $email = $User->email;

            if (env('MAIL_STATUS', true)) {

                Mail::to($email)->send(new UsdWithdraw($maildata));
            }


            WalletPassbook::create([
                'user_id' => $History->user_id,
                'amount' => $History->amount,
                'status' => 'CREDITED',
                'via' => "WITHDRAW - FAILED",
            ]);



            return back()->with('flash_success', trans('api.success_status'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function stripeHistory(){
        try {

            $payments = UserTokenTransaction::where('payment_type', 'stripe')->latest()->get();
            return view('admin.pages.stripe-history', compact('payments'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Unable to get Token lists. Please try again later');
        }
    }

    public function accrediteddoc($id)
    {
        try {
            $Doc = AccreditedKycDocument::where('user_id', $id)->with('document')->get();
            $user = User::where('id', $id)->first();
            return view('admin.user.accrediteddocument', compact('Doc', 'user'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.user.user_not_found'));
        }
    }

    public function useraccrediteddocument_approve(Request $request)
    {
        try {

            $KycCount = AccreditedKycDocument::where('user_id', $request->user_id)->count();
            $DocCount = AccreditedDocument::count();

            $Kyc = AccreditedKycDocument::where('user_id', $request->user_id)->where('accredited_document_id', $request->doc_id)->first();
            $Kyc->status = $request->status;
            $Kyc->save();

            $user = User::findOrFail($Kyc->user_id);
            if ($request->status == 'APPROVED') {
                // $user->update(['kyc' => 1]);

                $kyc_user_count = \DB::table('accredited_kyc_documents')->join('accredited_documents', 'accredited_kyc_documents.accredited_document_id', '=', 'accredited_documents.id')->where('accredited_kyc_documents.user_id', $user->id)->where('accredited_kyc_documents.status', "!=", "APPROVED")->select('accredited_kyc_documents.id')->get();

                //dd($kyc_user_count->count());

                if ($kyc_user_count->count() == 0) {

                    //dd($user->accredited_status);
                    if ($user->accredited_status == 0) {
                        $user->update(['accredited_status' => 1]);
                    }
                }
            } elseif ($request->status == 'REJECTED') {
                // $user->update(['kyc' => 0]);

                $doc = AccreditedDocument::where('id', $request->doc_id)->first();
                //$user = User::where('id',$Kyc->user_id)->first();

                if ($user->accredited_status == 1) {
                    $user->update(['accredited_status' => 0]);
                }

                $maildata = $doc;
                $email = $user->email;
                //Mail::to($email)->send(new KycDocRejected($maildata));
            }

            return back()->with('flash_success', trans('api.success_status'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function useraccrediteddocument_reject(Request $request)
    {
        try {

            $Kyc = AccreditedKycDocument::where('user_id', $request->user_id)
                ->where('accredited_document_id', $request->doc_id)
                ->first();

            $Kyc->status = $request->status;

            $Kyc->save();

            $doc = AccreditedDocument::where('id', $request->doc_id)->first();

            $user = User::where('id', $Kyc->user_id)->first();

            $maildata = $doc;
            $email = $user->email;

            //Mail::to($email)->send(new KycDocRejected($maildata));

            return back()->with('flash_success', trans('api.success_status'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        try {
            return view('admin.account.profile');
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile_update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:admins,email,' . Auth::guard('admin')->user()->id,
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:70000',
        ]);

        try {
            $admin = Auth::guard('admin')->user();
            $admin->name = $request->name;
            $admin->email = $request->email;

            if ($request->hasFile('picture')) {
                $admin->picture = $request->picture->store('admin/profile');
            }
            $admin->save();

            return redirect()->back()->with('flash_success', 'Profile Updated');
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        try {

            if (Auth::guard('admin')->user()->role == 1) {
                return view('errors.404');
            }

            return view('admin.account.change-password');
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password_update(Request $request)
    {

        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            $Admin = Admin::find(Auth::guard('admin')->user()->id);
            $status = password_verify($request->old_password, $Admin->password);
            if (password_verify($request->old_password, $Admin->password)) {
                $Admin->password = bcrypt($request->password);
                $Admin->save();

                return redirect()->back()->with('flash_success', 'Password Updated');
            }else{
                return back()->with('flash_error', "Current password doesn't matches");
            }
        } catch (\Exception $e) {
            dd("asdf",$e->getMessage());
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function privacy()
    {
        try {

            if (Auth::guard('admin')->user()->role == 1) {
                return view('errors.404');
            }

            return view('admin.pages.privacy')
                ->with('title', "Privacy Page")
                ->with('page', "privacy");
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function privacy_store(Request $request){
        if(request('content')){
            Setting::set('page_privacy',request('content'));
            Setting::save();
            return back()->with('flash_success','Privacy Updated Successfully..');
        }else{
            return back()->with('flash_error','Please Enter Policy');

        }
    }
    public function terms_store(Request $request){
        if(request('content')){
            Setting::set('page_terms',request('content'));
            Setting::save();
            return back()->with('flash_success','Terms Updated Successfully..');
        }else{
            return back()->with('flash_error','Please Enter Terms');

        }
    }
    public function about_store(Request $request){
        if(request('content')){
            Setting::set('page_about',request('content'));
            Setting::save();
            return back()->with('flash_success','About Updated Successfully..');
        }else{
            return back()->with('flash_error','Please Enter About Us');

        }
    }
    

    public function about()
    {

        try {

            if (Auth::guard('admin')->user()->role == 1) {
                return view('errors.404');
            }

            return view('admin.pages.aboutus')
                ->with('title', "About Page")
                ->with('page', "about");
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }


    public function terms()
    {

        try {

            if (Auth::guard('admin')->user()->role == 1) {
                return view('errors.404');
            }

            return view('admin.pages.terms')
                ->with('title', "Terms Page")
                ->with('page', "terms");
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }


    public function support()
    {

        try {

            $support = Support::orderBy('id', 'desc')->get();

            return view('admin.support.index', compact('support'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }



    public function sendmailforkycrejection()
    {

        $User = User::get();

        foreach ($User as $user) {
            $doc = KycDocument::where('user_id', $user['id'])
                ->where('status', 'REJECTED')
                ->where('updated_at', '>', Carbon::now()->subDays(3))
                ->first();

            if ($doc) {
                $email = $user['email'];
                $maildata = $user;
                if (env('MAIL_STATUS', true)) {

                    Mail::to($email)->send(new Kycmail($maildata));
                }
            }
        }
    }

    public function getValidateToken()
    {

        try {

            $url_param = $_GET['route_url'];
            $user = Auth::user();
            if ($user->google2fa_secret) {

                //$request->session()->put('2fa:admin:id', $user->id);
                session(['2fa:admin:id' => $user->id]);
                //\Session::set('2fa:admin:id', $user->id);

                if (session('2fa:admin:id')) {
                    return view('admin.2fa.urlform', compact('url_param'));
                }
            } else {
                $url = str_replace("'", "", $url_param);
                return redirect()->route($url);
                //return redirect()->route($url_param);
            }
            //return view('admin.home');

        } catch (Exception $e) {
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    // kycmail
    public function kycmail()
    {
        (new AdminController)->sendmailforkycrejection();

        return back()->with('flash_success', 'Mail Alert with send to all users whose document is rejected and not updated again more than 72 hours');
    }

    public function GetWhiteListRequest(){
        $users = User::where('white_listed', null)->get();
        return view('admin.whitelist.index', compact('users'));
    }

    public function UpdateWhiteListRequest($id, $status){
        try{
            \Log::info($id);
            \Log::info($status);
            $user = User::where('id', $id)->first();
            $user->white_listed = $status;
            $user->save();

            return 1;
        }catch(Exception $e){
            \Log::info($e);
            return 0;
        }
    }

    public function WithdrawRequest(){
        $requests = WithdrawEth::where('coin', 'USD')->where('status', 'pending')->orderBy('id','desc')->get();
        return view('admin.withdraw_usd.index', compact('requests'));
    }

    public function UpdateWithdrawRequest($id, $status){
        try{
            $withdraw = WithdrawEth::where('id', $id)->first();
            $withdraw->status = $status;
            $withdraw->save();

            $user = User::where('id', $withdraw->user_id)->first();
            $user->USD -= $withdraw->amount;
            $user->save();

            $message = $status == 'success' ? 'Approved' : 'Declined';
            return back()->with('flash_success', 'Deposit '.$message.' successfully');
        }catch(Exception $e){
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function WithdrawHistory(){
        $withdraws = WithdrawEth::where('coin', 'USD')->where('status', 'success')->get();
        return view('admin.withdraw_usd.history', compact('withdraws'));
    }

    public function CryptoDepositRequest(){
        $deposit_requests = DepositHistory::with('user')->where('status', 'pending')->orderBy('id', 'desc')->get();
        return view('admin.deposit_crypto.index', compact('deposit_requests'));
    }

    public function UpdateCryptoDepositRequest($id, $status){
        try{
            $deposit_history = DepositHistory::where('id', $id)->first();
            if($deposit_history){
                $user = User::where('id', $deposit_history->user_id)->first();
                $coin = $deposit_history->type;
                if($coin == 'USDC' || $coin == 'USDT' || $coin == 'DIE'){
                    $user->USD += $deposit_history->amount;
                }else{
                    $user->$coin += $deposit_history->amount;
                }
                $user->save();

                $deposit_history->status = $status;
                $deposit_history->save();
                
                return back()->with('flash_success', 'Deposit status updated successfully');
            }else{
                return back()->with('flash_error', 'Unable to find the record');
            }
        }catch(Exception $e){
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function CryptoDepositHistory(){
        try{
            $deposit_history = DepositHistory::with('user')->whereIn('status', ['success','failed'])->orderBy('id','desc')->get();
            return view('admin.deposit_crypto.history', compact('deposit_history'));
        }catch(Exception $e){
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function GasRequest(){
        $gas_requests = WithdrawEth::where('reason', 'Need gas for deploy')->where('status', 'pending')->orderBy('id','desc')->get();
        return view('admin.gas.index', compact('gas_requests'));
    }

    public function UpdateGasRequest($id, $status){
        try{
            $gas_request = WithdrawEth::where('id', $id)->first();
            $gas_request->status = $status;
            if($status == 'success'){
                $coin = $gas_request->coin;
                $user = User::where('id', $gas_request->user_id)->first();
                $user->$coin -= $gas_request->amount;
                $user->save();
            }
            $gas_request->save();
            return back()->with('flash_success', 'Gas amount sent successfully');
        }catch(Exception $e){
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function CryptoWithdrawRequest(){
        $withdraw_requests = WithdrawEth::where('coin', '!=', 'USD')->where('reason', '!=', 'Need gas for deploy')->where('status', 'pending')->orderBy('id','desc')->get();
        return view('admin.withdraw_crypto.index', compact('withdraw_requests'));
    }

    public function UpdateCryptoWithdrawRequest($id, $status){
        try{
            $withdraw = WithdrawEth::where('id', $id)->first();
            $withdraw->status = $status;
            
            $coin = $withdraw->coin;
            $user = User::where('id', $withdraw->user_id)->first();
            $user->$coin -= $withdraw->amount;
            $user->save();

            $withdraw->save();
            $message = $status == 'success' ? 'Approved' : 'Declined';
            return back()->with('flash_success', 'Withdraw '.$message.' successfully');
        }catch(Exception $e){
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function CryptoWithdrawHistory(){
        $withdraw_requests = WithdrawEth::where('coin', '!=', 'USD')->where('reason', '!=', 'Need gas for deploy')->where('status', '!=', 'pending')->orderBy('id','desc')->get();
        return view('admin.withdraw_crypto.history', compact('withdraw_requests'));
    }

    public function ApproveCryptoWithdrawRequest(Request $request){
        try{
            $withdraw = WithdrawEth::where('id', $request->id)->first();
            $withdraw->status = 'success';
            $withdraw->tx_hash = $request->hash;
            
            $coin = $withdraw->coin;
            $user = User::where('id', $withdraw->user_id)->first();
            $user->$coin -= $withdraw->amount;
            $user->save();

            $withdraw->save();
            return back()->with('flash_success', 'Withdraw approved successfully');
        }catch(Exception $e){
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }
    }

    public function NodeDetails(){
        return view('admin.node_list');
    }

    public function AdminWalletTransactions($address, $type, $chain){
        try {
            $client      = new Client();
            if($type == 'COIN'){
                if($chain == 'ETH'){
                    $requestdata = $client->get('https://api-sepolia.etherscan.io/api?module=account&action=txlist&address='.$address.'&startblock=0&endblock=99999999&page=1&offset=10&sort=desc&apikey=BDHRTUUN9UQ9VQPFQ1X9YCJS3GFPWX9UWY');
                    $recentTransactions    = json_decode($requestdata->getBody(), 1); 
                }elseif ($chain == 'BNB') {
                    $requestdata = $client->get('https://api-testnet.bscscan.com/api?module=account&action=txlist&address='.$address.'&startblock=0&endblock=99999999&page=1&offset=10&sort=desc&apikey=BDHRTUUN9UQ9VQPFQ1X9YCJS3GFPWX9UWY');
                    $recentTransactions    = json_decode($requestdata->getBody(), 1);
                }else{
                    $requestdata = $client->get('https://api-amoy.polygonscan.com/api?module=account&action=txlist&address='.$address.'&startblock=0&endblock=latest&page=1&offset=10&sort=desc&apikey=BDHRTUUN9UQ9VQPFQ1X9YCJS3GFPWX9UWY');
                    $recentTransactions    = json_decode($requestdata->getBody(), 1); 
                }
            }else{
                $requestdata = $client->get('https://api-sepolia.etherscan.io/api?module=account&action=txlist&address='.$address.'&startblock=0&endblock=99999999&page=1&offset=10&sort=desc&apikey=BDHRTUUN9UQ9VQPFQ1X9YCJS3GFPWX9UWY');
                $recentTransactions    = json_decode($requestdata->getBody(), 1);
            }
            if($recentTransactions['message'] == 'OK' || $recentTransactions['message'] == 'No transactions found'){
                $recentTransactions = $recentTransactions['result'];            
            }else{
                return back()->with('flash_error', 'Unable to get the transaction data');
            }
            return view('admin.admin_wallet', compact('recentTransactions'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function UserDepositHistory($user_type){
        try {
            $type = $user_type == 'investor' ? 1 : 2;
            $deposits = DepositHistory::with('user')->where('status', 'success')
            ->whereHas('user', function($query) use ($type){
                $query->where('user_type', $type);
            })
            ->orderBy('id', 'desc')->get();
            return view('admin.user_deposits', compact('deposits'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function tokenDetails($id){
        $admin = Auth::guard('admin')->user();
        if(empty($admin))
            abort(404);
        $data['property'] = Property::where('id', $id)->first();
        if($data['property']->status == 'block'){
            abort(404);
        }


        $data['keystores'] = $keystores = KeystoreModel::where('user_id',$data['property']->user_id)->pluck('title','id');
        $data['propertyComparable'] = PropertyComparable::where('property_id', $id)->first();
        $data['IssuerTokenRequest'] = IssuerTokenRequest::where('property_id', $id)->first();
        $data['ManagementMembers'] = ManagementMembers::where('property_id', $id)->first();
        $data['assetType'] = AssetType::orderBy('type', 'asc')->get();
        
        return view('admin.property.view', $data);
    }
}
