<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Setting;
use Auth;
use Hash;
use Input;
use Crypt;
use Mail;
use Carbon\Carbon;
use GuzzleHttp\Client;

use App\UserTrasaction;
use App\Http\Controllers\simple_crypt;
use App\Sendiad;
use App\Helpers\Helper;
use App\User;
use App\UserContract;
use App\TokenType;
use App\Country;

use App\UserToken;
use App\UserTokenTransaction;
use App\IssuerTokenRequest;
use App\AddressBook;
use App\IssuerPresaleDiscounts;
use App\Property;
use App\KeystoreModel;
use App\BlockchainModel as Blockchain;
use App\Services\TokenizerService;


class TokenizerController extends Controller
{

    public function tokenizerindex()
    {
        $contracts = UserContract::get();
        return view('admin.tokenizer.index', compact('contracts'));
    }

    public function tokenizer()
    {
        $country = Country::get();
        return view('admin.tokenizer.tokenizer', compact('country'));
    }

    /*public function contractcreate(Request $request){

        try{

            $usercontract = $request->all();           

            if($request->hasFile('token_image')) {
                $usercontract['token_image'] = $request->token_image->store('token_image');
            }

            if($request->hasFile('banner_image')) {
                $usercontract['banner_image'] = $request->banner_image->store('banner_image');
            }
        
            //$usercontract['user_id'] = Auth::user()->id;
            $usercontract['status'] = 0;

            if(isset($request->bonus)){
                $usercontract['bonus'] = $request->bonus;
            }else{
                $usercontract['bonus'] = 0;
            }

            $userdata = UserContract::create($usercontract);

            return $userdata;
        }
        catch(Exception $e){
            return back()->with('flash_error', trans('api.something_went_wrong'));
        }


    }*/

    public function contractcreate(Request $request){

        $this->validate($request, [
            'tokenname' => 'required',
            'tokensymbol' => 'required',
            'tokenvalue' => 'required',
            'tokensupply' => 'required',
            'decimal' => 'required',
            'pre_seed_sale' => 'required',
            'seed_sale' => 'required',
            'private_sale' => 'required',
            'main_sale' => 'required',
            'investor_token_usa_type' => 'required',
            'token_type' => 'required',
            'token_image' => 'required|mimes:jpg,jpeg,png',
            'banner_image' => 'required|mimes:jpg,jpeg,png',
            'discount_value' => 'required_if:presale_discount_date,',
        ]);

        try {

            $token_name = $request->tokenname;
            $token_symbol = $request->tokensymbol;
            $token_value = $request->tokenvalue;
            $token_supply = $request->tokensupply;
            $token_decimal = $request->decimal;

            $contractABI = Setting::get('contract_abi');
            $byteCode = Setting::get('byte_code');

            $address = Auth::user()->eth_address;


            $client = new Client;
            $url = "http://localhost:8085/createtoken";
            $headers = [
                'Content-Type' => 'application/json',
            ];
            $body = ["eth_pvt" => $eth_pvt_key ?? null, "eth_address" => $address, "abi" => $contractABI, "bytecode" => $byteCode, 'tokenname' => $token_name, 'tokensymbol' => $token_symbol, 'tokenvalue' => $token_value, 'tokensupply' => $token_supply, 'tokendecimal' => $token_decimal];

            $res = $client->post($url, [
                'headers' => $headers,
                'body' => json_encode($body),
            ]);

            $details = json_decode($res->getBody(), true);

            //return $details;

            if ($details['result'] == 1) {
                if ($details['status'] == 1) {

                    $token = new UserContract;
                    $token->tokenname = $request->tokenname;
                    $token->tokensymbol = $request->tokensymbol;
                    $token->tokenvalue = $request->tokenvalue;
                    $token->tokensupply = $request->tokensupply;
                    $token->contract_address = $details['contract'];
                    $token->decimal = $request->decimal;
                    if ($request->hasFile('token_image')) {
                        $token->token_image = $request->token_image->store('token_image');
                    }

                    if ($request->hasFile('banner_image')) {
                        $token->banner_image = $request->banner_image->store('banner_image');
                    }
                    $token->title = $request->title;
                    $token->content = $request->content;

                    $pre_seed_sale_temp = explode('-', $request->pre_seed_sale);
                    $pre_seed_sale_fdate = trim($pre_seed_sale_temp[0]);
                    $pre_seed_sale_tdate = trim($pre_seed_sale_temp[1]);
                    $token->pre_seed_sale_fdate = date('Y-m-d', strtotime($pre_seed_sale_fdate));
                    $token->pre_seed_sale_tdate = date('Y-m-d', strtotime($pre_seed_sale_tdate));

                    $seed_sale_temp = explode('-', $request->seed_sale);
                    $seed_sale_fdate = trim($seed_sale_temp[0]);
                    $seed_sale_tdate = trim($seed_sale_temp[1]);
                    $token->seed_sale_fdate = date('Y-m-d', strtotime($seed_sale_fdate));
                    $token->seed_sale_tdate = date('Y-m-d', strtotime($seed_sale_tdate));

                    $private_sale_temp = explode('-', $request->private_sale);
                    $private_sale_fdate = trim($private_sale_temp[0]);
                    $private_sale_tdate = trim($private_sale_temp[1]);
                    $token->private_sale_fdate = date('Y-m-d', strtotime($private_sale_fdate));
                    $token->private_sale_tdate = date('Y-m-d', strtotime($private_sale_tdate));

                    $main_sale_temp = explode('-', $request->main_sale);
                    $main_sale_fdate = trim($main_sale_temp[0]);
                    $main_sale_tdate = trim($main_sale_temp[1]);
                    $token->main_sale_fdate = date('Y-m-d', strtotime($main_sale_fdate));
                    $token->main_sale_tdate = date('Y-m-d', strtotime($main_sale_tdate));


                    $country = '';
                    foreach ($request->country as $key => $value) {
                        $country = $country . $value . ',';
                    }
                    $country = rtrim($country, ',');
                    $token->banned_countries = $country;


                    $token->investor_token_usa_type = $request->investor_token_usa_type;

                    $token->vesting_period = $request->vesting_period;
                    $token->token_type = $request->token_type;

                    if (isset($request->trade_locked)) {
                        $token->trade_locked = 1;
                    }

                    if (isset($request->trade_burn)) {
                        $token->trade_burn = 1;
                    }

                    $token->status = 1;
                    $token->save();


                    $presale = new IssuerPresaleDiscounts;
                    $presale_discount_date_temp = explode('-', $request->presale_discount_date);
                    $presale_discount_date_fdate = trim($presale_discount_date_temp[0]);
                    $presale_discount_date_tdate = trim($presale_discount_date_temp[1]);
                    $presale->fdate = date('Y-m-d', strtotime($presale_discount_date_fdate));
                    $presale->tdate = date('Y-m-d', strtotime($presale_discount_date_tdate));
                    $presale->discount_value = $request->discount_value;
                    $presale->issuer_request_id = $token->id;
                    $presale->user_id = 0;
                    $presale->save();

                    return back()->with('flash_success', "Token added successfully");
                }
            } elseif ($details['result'] == 0) {
                return back()->with('flash_error', $details['error']);
            } else {
                return back()->with('flash_error', $details['msg']);
            }
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something Went Wrong');
        }
    }

    public function contractupdate(Request $request){

        $usercontract = UserContract::find($request->contractid);
        if ($request->has('ico')) {
            $usercontract->contract_address = $request->ico;
        }

        $usercontract->save();

        $token = new TokenType;
        $token->name = $request->tokenname;
        $token->token = $request->tokensupply;
        $token->type = 'erc-20';
        $token->symbol = $request->tokensymbol;
        $token->buy_price = $request->tokenvalue;
        $token->sell_price = $request->tokenvalue;
        $token->image = $request->token_image;
        $token->holder_address = '0x49b09d21a9d7f7b984022d4cdb85199537a24e28';

        $token->contract_address = $request->ico;
        $token->private_key = 'Null';
        $token->decimal = $request->decimal;
        //$token->decimal = 10;
        $token->save();

        return ['status' => 1];
    }

    public function tokenizeredit($id){
        $token = UserContract::findOrFail($id);
        $country = Country::get();
        $token = IssuerTokenRequest::findOrFail($id);

        $banned_country = explode(',', $token->banned_countries);
        $usa_type = explode(',', $token->investor_token_usa_type);

        $presale = IssuerPresaleDiscounts::where('issuer_request_id', $token->id)->latest()->first();

        return view('admin.tokenizer.edit', compact('country', 'token', 'presale', 'banned_country', 'usa_type'));
    }

    public function tokenizerupdate(Request $request){

        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'token_image' => 'mimes:jpg,jpeg,png',
            'banner_image' => 'mimes:jpg,jpeg,png',
            'investor_token_usa_type' => 'required',
            'discount_value' => 'required_if:presale_discount_date,',
        ]);

        try {

            $id = $request->id;

            $token = UserContract::findOrFail($id);

            if ($request->hasFile('token_image')) {
                $token->token_image = $request->token_image->store('token_image');
            }
            if ($request->hasFile('banner_image')) {
                $token->banner_image = $request->banner_image->store('banner_image');
            }

            $token->title = $request->title;
            $token->content = $request->content;
            $country = '';
            foreach ($request->country as $key => $value) {
                $country = $country . $value . ',';
            }
            $country = rtrim($country, ',');
            $token->banned_countries = $country;
            $token->investor_token_usa_type = $request->investor_token_usa_type;
            $token->save();


            $presale_discount_date_temp = explode('-', $request->presale_discount_date);
            $presale_discount_date_fdate = trim($presale_discount_date_temp[0]);
            $presale_discount_date_tdate = trim($presale_discount_date_temp[1]);

            $pre_fdate_temp = explode('/', $presale_discount_date_fdate);
            $presale_fdate = $pre_fdate_temp[2] . '-' . $pre_fdate_temp[1] . '-' . $pre_fdate_temp[0];


            $pre_tdate_temp = explode('/', $presale_discount_date_tdate);
            $presale_tdate = $pre_tdate_temp[2] . '-' . $pre_tdate_temp[1] . '-' . $pre_tdate_temp[0];

            //dd($presale_discount_date_fdate,$presale_discount_date_tdate,$presale_fdate,$presale_tdate);

            $presale_old = IssuerPresaleDiscounts::where('issuer_request_id', $token->id)->where('fdate', $presale_fdate)->where('tdate', $presale_tdate)->first();

            if (!$presale_old) {

                $presale = new IssuerPresaleDiscounts;
                $presale->fdate = $presale_fdate;
                $presale->tdate = $presale_tdate;
                $presale->discount_value = $request->discount_value;
                $presale->issuer_request_id = $token->id;
                $presale->user_id = $user_id ?? null;
                $presale->save();
            }


            return back()->with('flash_success', 'Token Updated Successfully');
        } catch (Exception $e) {

            return $e;
        }
    }

    public function requestedtoken(Request $request){
        $contracts = IssuerTokenRequest::with(['usercontract', 'property'])
        ->where('status', '!=', 'live')
        ->when($request->has('type'), function ($query) use ($request) {
            $query->whereHas('property', function ($q) use ($request) {
                $q->where('token_type', $request->type);
            });
        })
        ->latest()
        ->get();
    
        return view('admin.tokenizer.request', compact('contracts'));
    }

    public function GetPropertyDetails($id){
        $property = Property::with('userContract')->where('id', $id)->first();
        return view('admin.tokenizer.property_detail', compact('property'));
    }

    public function UpdateInterest(Request $request){
        try {
            $contracts = IssuerTokenRequest::where('id', $request->id)->first();
            $property = Property::where('id', $contracts->property_id)->first();
            $property->interest = $request->interest;
            $property->save();

            return back()->with('flash_success', 'Interest updated successfully');
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Something went wrong');
        }
    }


    public function tokenhistory()
    {

        $history = UserTokenTransaction::with('usercontract')->where('status', 1)->latest()->get();
        return view('admin.tokenizer.history', compact('history'));
    }


    /*public function issuertokencontract($id){        
        $issuer_token=IssuerTokenRequest::findOrFail($id);

        $token=new UserContract;
        $token->issued_by=$issuer_token->user_id;
        $token->tokenname=$issuer_token->tokenname;
        $token->tokensymbol=$issuer_token->tokensymbol;
        $token->tokenvalue=$issuer_token->tokenvalue;
        $token->tokensupply=$issuer_token->tokensupply;
        $token->decimal=$issuer_token->decimal;                       
        $token->token_image = $issuer_token->token_image;
        $token->banner_image = $issuer_token->banner_image;
        $token->title=$issuer_token->title;
        $token->content=$issuer_token->content;
        $token->status=1;
        $token->save();

        $issuer_token->status=1;
        $issuer_token->save();                        

        return back()->with('flash_success',"Token added successfully");        
    }*/

    public function issuertokenreject($id)
    {
        $issuer_token = IssuerTokenRequest::findOrFail($id);

        $issuer_token->status = 'rejected';
        $issuer_token->save();
        $property = Property::where('id', $issuer_token->property_id)->update(['status' => 'block']);

        return back()->with('flash_error', "Token Rejected");
    }

    public function issuertokencontracttest($id)
    {
        try {
            $result = (new TokenizerService())->deployToken($id);

            if (!empty($result['hasError'])) {
                return back()->with('flash_error', $result['message'] ?? 'Token deployment failed.');
            }

            return back()->with('flash_success', $result['message'] ?? 'Token added successfully');
        } catch (\Throwable $e) {
            logException($e, ['issuer_token_id' => $id]);
            
            return back()->with('flash_error', 'Something went wrong. Please try again later.');
        }
    }
    
    
    public function getBalance($publicAddress,Request $request){
        $user = auth()->user();
        try {
            $request->validate([
                'chain' => 'required|exists:blockchains,id',
            ], [
                'chain.required' => 'Chain is required.',
                'chain.exists' => 'The selected chain is invalid.',
            ]);
            
            $blockchain = Blockchain::find($request->chain);
            $chain = $request->chain;

            $result = callNodeOperations('getBalance',['address' => $publicAddress ,'chain'=>$blockchain->abbreviation]);
            return $result;
        }catch (\Exception $e) {
            // Log and return error
            \Log::error('Error fetching balance', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => optional(auth()->user())->id,
                'public_address' => $publicAddress,
                'chain' => $request->chain,
            ]);
    
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch balance.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
