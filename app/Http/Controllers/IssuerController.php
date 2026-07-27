<?php

namespace App\Http\Controllers;

use Auth;
use Crypt;
use Google2FA;
use App\Trade;
use App\User;
use App\Country;
use App\CountryCode;
use App\Document;
use App\Property;
use App\Bank;
use App\Coins;
use App\Admin;
use App\AssetType;
use App\UserToken;
use App\KycDocument;
use App\WithdrawEth;
use App\UserContract;
use App\UserIdentity;
use App\PropertyImage;
use App\DepositFiat;
use GuzzleHttp\Client;
use App\DepositHistory;
use App\PropertyUpdate;
use App\PropertyLandmark;
use App\WithdrawShare;
use App\ManagementMembers;
use App\IssuerTokenRequest;
use App\Mail\Withdrawalotp;
use App\PropertyComparable;
use App\UserCompanyDetails;
use App\InvestorWhitelist;
use Illuminate\Http\Request;
use App\UserTokenTransaction;
use App\AccreditedKycDocument;
use \ParagonIE\ConstantTime\Base32;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommonController;
use App\KeystoreModel;
use App\BlockchainModel as Blockchain;
use App\BlockchainStablecoin;
use App\InvestorShares;
use App\IssuerBankAccounts;
use App\IssuerStablecoinWalletAddress;
use App\Notification;
use App\Services\TokenizerService;
use App\Services\TokenPaymentService;
use Illuminate\Support\Facades\DB;

class IssuerController extends Controller {
    /**
     * Used to show Issuer Dashboard
     */
    public function dashboard(){
        try {
            // Auth::logout();
            // return redirect('login');
            $user = Auth::user();
            (new CommonController)->updateAddress($user);
            $issuer_token = IssuerTokenRequest::where('user_id', $user->id)->where('status','pending')->whereHas('property', function ($q) {
                $q->where('deleted_at', null);
            });

            $tokenCounts = Property::select('token_type', DB::raw('COUNT(*) as total'))
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->groupBy('token_type')
                ->pluck('total', 'token_type');

            $totalDealSize=Property::where('user_id',$user->id)->sum('totalDealSize');

            // $tokens=UserContract::select(\DB::raw("COUNT(*) as count"))->where('user_id',$user->id)->where('status',1)
            // ->whereYear('created_at', date('Y'))
            // ->groupBy(\DB::raw("Month(created_at)"))
            // ->pluck('count');
            $users =UserContract::getGroupingByMonths($user->id);
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
            $request_token = $issuer_token->count();

            $rejected_token = $issuer_token->where('status', 'rejected')->count();

            $tokenCounts = [
                'property' => $tokenCounts[1] ?? 0,
                'deployed_assets' => $tokenCounts[2] ?? 0,
                'utility' => $tokenCounts[3] ?? 0
            ];
            // Get all the notifications
            $notifications  = Notification::where('user_id',$user->id)->where('is_viewed',0)->get();

            // Delete  notifications as they're delivered
            Notification::where('user_id', $user->id)->delete();
            return view('issuer.dashboard', compact('totalDealSize', 'request_token', 'tokens', 'rejected_token','tokenCounts','notifications'));
        } catch (\Throwable $th) {
            dd($th->getMessage(),$th->getLine());
            return back()->with('flash_error', 'Unable to get dashboard details. Please try again later');
        }
    }

    /**
     * Used to show Issuer KYC
     */
    public function kyc()
    {
        try {
            $documents = Document::with('kycdocument')->orderBy('order', 'asc')->get();
            return view('issuer.kyc', compact('documents'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Unable to get KYC details. Please try again later');
        }
    }

    /**
     * Used to update KYC
     */
    public function updateKyc(Request $request)
    {
        $this->validate($request, [
            'documentid' => 'required',
            'image'      => 'required|mimes:jpeg,png,jpg|max:5120',
        ]);
        try {
            $user = Auth::user();
            KycDocument::where(['user_id' => $user->id, 'document_id' => $request->documentid])
                ->delete();

            KycDocument::create([
                'url'         => $request->image->store('kyc/documents'),
                'user_id'     => $user->id,
                'document_id' => $request->documentid,
                'status'      => 'PENDING',
            ]);

            return back()->with('flash_success', 'Docuement Updated Successfully');
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            return back()->with('flash_error', 'Unable to update KYC. Please try again later');
        }
    }

    /**
     * Used to show Issuer Profile
     */
    public function profile(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $country = Country::all();
        $accredited_documents = Document::get();
        $kyc_doc = AccreditedKycDocument::where('user_id', $user->id)->get();
        // dd($accredited_documents);
        // ->leftJoin('accredited_kyc_documents', function ($join) use($user_id) {
        //     $join->on('accredited_documents.id', '=', 'accredited_kyc_documents.accredited_document_id')
        //     ->where('accredited_kyc_documents.user_id', '=', $user_id);
        // })
        // ->orderBy('accredited_documents.order','asc')
        // ->select('accredited_documents.*','accredited_kyc_documents.status')
        // ->get();
        return view('issuer.profile', compact('user', 'country', 'accredited_documents', 'kyc_doc'));
    }
    public function get_country_ph_code(Request $request){
        try{
             if($request->country_id==''){
                return['status'=>0,'message'=>'Please Choose Country'];
             }
             $ph_code=Country::where('id',$request->country_id)->first();
             if(!$ph_code){
                return['status'=>0,'message'=>'Please Choose Country'];

             }
             $ph_codes=CountryCode::where('code',$ph_code->code)->first();
             if(!$ph_codes){
                return ['status'=>0,'message'=>'please Choose Country'];

             }
             return ['status'=>1,'message'=>$ph_codes->dial_code];



        }catch(\Exception $e){
            dd($e);
        }
    }

    private function generateSecret()
    {
        $randomBytes = random_bytes(10);
        return Base32::encodeUpper($randomBytes);
    }

    public function usercompanydetail(Request $request)
    {
        // dd($request->all());
        if ($request->accredited_kyc_select) {
            $this->validate($request, [
                "image" => 'required'
            ]);
        }
        if (!$request->updateKYC && auth()->user()->account_type == 'company') {

            $this->validate($request, [
                'company_name' => 'required',
                'headquarters' => 'required',
                'company_date' => 'required',
                'signing_authority' => 'required|mimes:jpg,jpeg,png',
                'team_size' => 'required|numeric',
                'company_url' => 'required',
                'social_channels' => 'required',
                'incorporation_certificate' => 'required|mimes:jpg,jpeg,png',
                'partnership_deed' => 'required|mimes:jpg,jpeg,png',
                'trust_deed' => 'required|mimes:jpg,jpeg,png',
                'register_socities' => 'required|mimes:jpg,jpeg,png',
                //'fund_type' => 'required',
                //'other_fund_type' => 'required',
            ]);
        }

        try {

            $user = Auth::user();
            $user_id = $user->id;

            $user_details_temp = UserCompanyDetails::where('user_id', $user_id)->first();

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
        } catch (Exception $e) {
            return back()->with('flash_error', "Whoops! Something went wrong");
        }
    }


    /**
     * Used to Update Payment Details
     */
    public function profile_update(Request $request)
    {
        // dd($request->all());

        try {
            $user = Auth::user();
            $mobile = $request->country_code . '-' . $request->mobileno;

            UserIdentity::updateOrCreate(['user_id' => $user->id], [
                'user_id'       =>  $user->id,
                'fname'    =>  $request->fname,
                'lname'     =>  $request->lname ?? ' ',
                'primary_phone' =>  $mobile,
                'city_id'       =>  0
            ]);
            $name = $request->fname.' '.$request->lname;
            // $userIdentity                = new UserIdentity;
            // $userIdentity->user_id       = $user->id;
            // $userIdentity->first_name    = $request->fname;
            // $userIdentity->last_name     = $request->lname;
            // $userIdentity->primary_phone = $request->mobileno;
            // $userIdentity->city_id       = 0;
            // $userIdentity->save();
            $user->name    = $name;
            $user->country_id = $request->country_id;
            $user->save();

            return back()->with('flash_success', 'Profile Has been updated successfully');
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Oops, Unable to update Profile');
        }
    }

    /**
     * Used to show Property
     */
    public function property(Request $request)
    {
        $user         = Auth::user();
        $propertylist = Property::with('userContract', 'blockchain')->where('user_id', $user->id)->where('status','active')->orderBy('created_at', 'desc')->get();


        foreach ($propertylist as $property) {
            if (!empty($property->userContract) && !empty($property->blockchain)) {
                $property->contract_address = $property->userContract->contract_address;
                $property->coin = $property->blockchain->blockchain_name;
                $url = $property->blockchain->link;
                $property->contract_link = $url .'token/'. $property->contract_address;
            }
        }

        $propertylist = (new CommonController)->calculatePercentage($propertylist);
        return view('issuer.property', ["properties" => $propertylist, 'property_id' => $request->property_id]);
    }

    public function propertyDetail($id)
    {
        try {

            $user     = Auth::user();
            $property = Property::with('issuerToken','userContract','blockchain')->where('id', $id)->where('user_id', auth()->id())->first();
            if (!$property) {
                abort(404);
            }


            if(!empty($property->userContract) && !empty($property->blockchain)){
                $property->contract_address = $property->userContract->contract_address;
                $property->coin = $property->blockchain->blockchain_name;
                $url = $property->blockchain->link;
                $property->contract_link = $url .'token/'. $property->contract_address;
            }

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

            return view('issuer.propertyDetail', compact('user', 'property'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Unable to Get Property Details');
        }
    }


    /**
     * Used to show Token
     */
    public function token()
    {
        $user = Auth::user();
        $assetType = AssetType::orderBy('type', 'asc')->get();
        $coins = Coins::where('status', 1)->get();
        $keystores = KeystoreModel::where('user_id', $user->id)->get();
        $isKeystoreAvailable = $keystores->count() > 0;
        $blockchains = Blockchain::all();
        $isDemo = config('app.is_demo');
        return view('issuer.token', compact('assetType','coins','keystores','isKeystoreAvailable','blockchains','isDemo'));
    }

    public function asset_fund()
    {

        $user = Auth()->user();
        $assetType = AssetType::orderBy('type', 'asc')->get();
        $coins = Coins::where('status', 1)->get();
        $keystores = KeystoreModel::where('user_id', $user->id)->get();
        $isKeystoreAvailable = $keystores->count() > 0;
        $blockchains = BlockChain::all();
        return view('issuer.asset_fund', compact('assetType','coins','keystores','isKeystoreAvailable','blockchains'));
    }

    public function utilityToken()
    {
        $user = Auth()->user();
        $assetType = AssetType::orderBy('type', 'asc')->get();
        $coins = Coins::where('status', 1)->get();
        $keystores = KeystoreModel::where('user_id', $user->id)->get();
        $isKeystoreAvailable = $keystores->count() > 0;
        $blockchains = Blockchain::all();
        return view('issuer.utility-token', compact('assetType','coins','keystores','isKeystoreAvailable','blockchains'));
    }

    /**
     * Used to show Token List
     */
    public function tokenList()
    {
        try {
            $tokens = UserContract::where('user_id', auth()->id())->where('status', 1)->latest()->get();
            return view('issuer.tokenlist', compact('tokens'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Unable to get Token lists. Please try again later');
        }
    }

    public function tokenUsers()
    {
        try {
            $users = UserToken::with('user','usercontract','withdraw_share_amount')->whereHas('usercontract', function ($q) {
                $q->where('user_id', auth()->id());
            })->latest()->get();
            $usersList = UserTokenTransaction::whereHas('usercontract', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->selectRaw('payment_type, SUM(payment_amount) as total_amount, MAX(created_at) as latest_date')
            ->groupBy('payment_type')
            ->orderBy('latest_date', 'desc') // Order by the latest transaction date for each payment type
            ->get();
            return view('issuer.tokenUsers', compact('users', 'usersList'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Unable to get Token lists. Please try again later');
        }
    }

    public function TokenHistory($id){
        try {
            $token_transaction = UserTokenTransaction::with('user', 'usercontract')->where('user_contract_id', $id)->get();
            return view('issuer.token_transaction', compact('token_transaction'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    /**
     * Used to show Token Requests
     */
    public function tokenRequest()
    {
        try {
            $tokens = IssuerTokenRequest::where('user_id', auth()->id())->where('status', 'pending')->get();
            return view('issuer.tokenrequest', compact('tokens'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Unable to get Token Request List. Please try again later');
        }
    }

    /**
     * Used to Store Property
     */
    public function storeProperty(Request $request){
        // Check if user can create more property tokens
        $user = Auth::user();
        if(!$user->canCreatePropertyToken()){
            return redirect()->route('platform.purchase');
        }

        // The property overview may not be greater than 600 characters.
        // The property location overview may not be greater than 600 characters.
        $isDemo = config('app.is_demo');
        $validationRule = [
            'propertyName'           => 'required|max:320',
            'propertyLogo'           => !$isDemo ? 'required|mimes:jpeg,jpg,png|max:5120' : 'nullable|mimes:jpeg,jpg,png|max:5120',
            'holdingPeriod'          => 'required|min:1',
            'token_chain'            => 'required|exists:blockchains,id',
            'token_name'             => 'required|max:100',
            'token_symbol'           => 'required|max:4',
            'token_decimal'          => 'required|digits_between:1,2',
            'tokentype'              => 'required',
            'token_image'            => !$isDemo ? 'required|mimes:jpeg,jpg,png|max:5120' : 'nullable|mimes:jpeg,jpg,png|max:5120',
            'investor'              => 'max:5120|mimes:pdf',
            'brochure'              => 'max:5120|mimes:pdf',
            'comparabledetails'     => 'max:5120|mimes:pdf',
            'titlereport'     => 'max:5120|mimes:pdf',
            'termsheet'     => 'max:5120|mimes:pdf',
            'propertyUpdatesDoc'     => 'max:5120|mimes:pdf',
            'expectedIrr'  => 'gte:0',
            'initialInvestment'      => 'required|gte:0',
            'totalDealSize'  => 'required',
            'token_value'            => 'required|gte:0',
            'token_supply' => 'required|gte:0',
            'propertyDetailsHighlights' => 'max:10000',
            'ManagementTeamDescription' => 'max:10000',
            'propertyOverview'  => 'max:10000',
            'propertyLocationOverview'  => 'max:10000',
            'fundedMembers' => 'gte:0',
            'yearOfConstruction' => 'nullable|digits:4',
            'keystore_id'            => 'required|integer',
            'enable_internal_wallet' => 'required|boolean',
            'propertyHighlights'    => 'nullable|string'
        ];
        $blockchain = Blockchain::find($request->token_chain);

        if ($request->token_type == 1) {
            $validationRuleProp = [
                'propertyLocation'          => 'max:320',
                'total_sft'                 => 'min:0',
                'propertyOverview'  => 'max:10000',
                'propertyLocationOverview'  => 'max:10000',
                'locality'                  => 'max:600',
                'floorplan'                 => 'max:5120|mimes:pdf',
            ];

            $validationRule = array_merge($validationRule, $validationRuleProp);
            // $request->token_value = number_format((int)$request->totalDealSize / (int)$request->total_sft, 6, '.', '');
            // $request->token_supply = (int)$request->total_sft;
        }

        $this->validate($request, $validationRule, [
            'investor.max' => 'Prospectus file should not be greater than 5120 kilobytes',
            'titlereport.max' => 'Reports file should not be greater than 5120 kilobytes',
            'termsheet.max' => 'Agreements file should not be greater than 5120 kilobytes',
            'propertyUpdatesDoc.max' => 'Additional Information file should not be greater than 5120 kilobytes',
            'propertyOverview' => 'Fund Overview should not be greater than 300 characters',
            'propertyLocationOverview' => 'Fund Highlights should not be greater than 300 characters',
        ]);


        if ($request->totalDealSize < $request->initialInvestment) {
            $request->initialInvestment = (int)$request->totalDealSize / 2;
        }
        if ($request->token_value > $request->initialInvestment) {
            $request->token_value = (int)$request->initialInvestment / 2;
        }
        try {
            $data = $request->all();

            $data['user_id'] = $user->id;
            $data['property_state'] = $request->property_state;
            // if ($request->token_type == 1) {
            //     if ($request->has('token_value')) {
            //         $data['totalDealSize'] = $request->total_sft / $request->token_value;  //$request->totalDealSize;
            //     }
            // } else {
            $data['totalDealSize'] = $request->totalDealSize;
            $data['keystore_id'] = $request->keystore_id;
            // }
            $data['fundedMembers'] = $data['fundedMembers'] ?? 0;
            if ($request->hasFile('propertyVideo'))
                $data['propertyVideo'] = $request->propertyVideo->store('propertyVideo');

            if ($request->hasFile('propertyLogo')){
                $data['propertyLogo'] = $request->propertyLogo->store('propertyLogo');
            }else{
                if($isDemo){
                    $files = ['1.jpg','2.jpeg','3.jpg','4.jpg','5.jpeg'];
                    $randomFile = $files[array_rand($files)];
                    $filePath = public_path('templates/prop/'.$randomFile);

                    // Check if file exists before copying
                    if (file_exists($filePath)) {
                        $destinationPath = 'propertyLogo/' . $randomFile;
                        // Use file_get_contents and Storage::put instead of Storage::copy
                        $fileContents = file_get_contents($filePath);
                        Storage::put($destinationPath, $fileContents);
                        $data['propertyLogo'] = $destinationPath;
                    } else {
                        // Fallback to a default image or null
                        $data['propertyLogo'] = null;
                    }
                }
            }

            if ($request->hasFile('floorplan'))
                $data['floorplan'] = $request->floorplan->store('floorplan');

            if ($request->hasFile('investor'))
                $data['investor'] = $request->investor->store('investor');

            if ($request->hasFile('titlereport'))
                $data['titlereport'] = $request->titlereport->store('titlereport');

            if ($request->hasFile('termsheet'))
                $data['termsheet'] = $request->termsheet->store('termsheet');

            if ($request->hasFile('brochure'))
                $data['brochure'] = $request->brochure->store('brochure');

            if ($request->hasFile('propertyManagementTeam')) {
                $data['propertyManagementTeam'] = $request->propertyManagementTeam->store('propertyManagementTeam');
            }
            if ($request->hasFile('propertyUpdatesDoc')) {
                $data['propertyUpdatesDoc'] = $request->propertyUpdatesDoc->store('propertyUpdatesDoc');
            }
            $data['blockchain_id'] = $blockchain->id;
            $property = Property::create($data);

            // Increment the property tokens created counter
            $user->incrementPropertyTokensCreated();

            if (!empty($request['comparables'])) {
                PropertyComparable::where('property_id', $property->id)->delete();
                foreach ($request['comparables'] as $key => $value) {
                    $property_comparables                     = new PropertyComparable;
                    $property_comparables->property_id        = $property->id;
                    $property_comparables->property           = $value['property'];
                    $property_comparables->type               = $value['type'];
                    $property_comparables->location           = $value['location'];
                    $property_comparables->distance           = $value['distance'] ? $value['distance'] : 0.00;
                    $property_comparables->rent               = @$value['rent'] ? @$value['rent'] : $request->total_sft;
                    $property_comparables->saleprice          = $value['saleprice'] ? $value['saleprice'] : 0.00;
                    if ($request->hasFile('map')) {
                        $property_comparables->map                = $request->map->store('map');
                    }
                    if ($request->hasFile('comparabledetails')) {
                        $property_comparables->comparable_details = $request->comparabledetails->store('comparabledetails');
                    }
                    $property_comparables->save();
                }
            }

            if (!empty($request->member)) {
                foreach ($request->member as $key => $value) {
                    $member                    = new ManagementMembers;
                    $member->property_id       = $property->id;
                    $member->memberName        = $value['name'];
                    $member->memberPosition    = $value['position'];
                    $member->memberDescription = $value['description'];
                    $member->memberPic         = isset($value['pic']) ? $value['pic']->store('memberPic') : null;
                    $member->save();
                }
            }

            if (!empty($request->updates)) {
                foreach ($request->updates as $key => $value) {
                    $updates = new PropertyUpdate;
                    $updates->property_id = $property->id;
                    $updates->date = $value['date'] ?? null;
                    $updates->description = $value['description'];
                    $updates->save();
                }
            }

            if ($request->hasFile('propertyimages')) {
                // $files = $request->file('propertyimages');

                $productImage = new PropertyImage;
                $productImage->property_id = $property->id;
                $productImage->image = $request->propertyimages->store('propertyimages');
                $productImage->save();
                // foreach ($files as $key => $file) {
                //     $productImage              = new PropertyImage;
                //     $productImage->property_id = $property->id;
                //     $productImage->image       = $file->store('propertyImage');
                //     $productImage->save();
                // }
            }else{
                if($isDemo){
                    $files = ['1.jpg','2.jpeg','3.jpg','4.jpg','5.jpeg'];
                    $randomFile = $files[array_rand($files)];
                    $filePath = public_path('templates/prop/'.$randomFile);

                    $productImage = new PropertyImage;
                    $productImage->property_id = $property->id;
                    // Check if file exists before copying
                    if (file_exists($filePath)) {
                        $destinationPath = 'propertyimages/' . $randomFile;
                        // Use file_get_contents and Storage::put instead of Storage::copy
                        $fileContents = file_get_contents($filePath);
                        Storage::put($destinationPath, $fileContents);
                        $productImage->image = $destinationPath;
                    } else {
                        $productImage->image = null;
                    }
                    $productImage->save();
                }
            }
            $token                = new IssuerTokenRequest;
            $token->user_id       = $user->id;
            $token->property_id   = $property->id;
            $token->coin          = $blockchain->blockchain_name;
            $token->blockchain_id          = $blockchain->id;
            $token->name          = $request->token_name;
            $token->symbol        = strtoupper($request->token_symbol);
            $token->usdvalue      = $request->token_value;
            $token->decimal       = $request->token_decimal;
            $token->security_type = $request->tokentype;

            if($request->hasFile('token_image')){
                $token->token_image   = $request->token_image->store('token_image');
            }else{
                if($isDemo){
                    $tokenImagePath = public_path('templates/token.png');
                    // Check if file exists before copying
                    if (file_exists($tokenImagePath)) {
                        $destinationPath = 'token_image/token.png';
                        // Use file_get_contents and Storage::put instead of Storage::copy
                        $fileContents = file_get_contents($tokenImagePath);
                        Storage::put($destinationPath, $fileContents);
                        $token->token_image = $destinationPath;
                    } else {
                        $token->token_image = null;
                    }
                }
            }
            $token->supply = $request->token_supply;
            $token->save();

            // //Estimate Gas for deploy
            // $client = new Client();
            // $headers = [
            //     'Content-Type' => 'application/json',
            // ];
            // if($request->token_chain == 'MATIC'){
            //     $provider = 'https://polygon-amoy.g.alchemy.com/v2/2Kv-NfqEzDGJYHd7mVtCwa-W1TxBpS3-';
            // }elseif ($request->token_chain == 'BNB') {
            //     $provider = 'https://data-seed-prebsc-1-s1.binance.org:8545';
            // }elseif ($request->token_chain == 'ETH') {
            //     $provider = 'https://sepolia.infura.io/v3/348e7786d20b43dab6b6038d43d85900';
            // }else{
            //     $provider = 'https://polygon-amoy.g.alchemy.com/v2/2Kv-NfqEzDGJYHd7mVtCwa-W1TxBpS3-';
            // }

            // $body = ["jsonrpc" => "2.0", "method" => "eth_gasPrice", "params" => [], "id" => 1];
            // $res = $client->post($provider, [
            //     'headers' => $headers,
            //     'body' => json_encode($body),
            // ]);
            // $response = json_decode($res->getBody(), true);
            // $wei = hexdec($response['result']);
            // $gasPrice = 2000000 * $wei;
            // $priceIneth = $gasPrice / 10 ** 18;

            // $withdraw_model = new WithdrawEth;
            // $withdraw_model->user_id = $user->id;
            // $withdraw_model->sender = 'Admin';
            // $withdraw_model->receiver = $user->eth_address ?? "";
            // $withdraw_model->amount = $priceIneth;
            // $withdraw_model->coin = $request->token_chain;
            // $withdraw_model->tx_hash = "***";
            // $withdraw_model->reason = "Need gas for deploy";
            // $withdraw_model->status = "pending";
            // $withdraw_model->save();

            if(config('app.is_demo')){
                $tokenizerService = new TokenizerService();
                $deployResult = $tokenizerService->deployToken($token->id);

                if (!empty($deployResult['hasError'])) {
                    $message = $deployResult['message'] ?? 'Token deployment failed.';
                    if (request()->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message,
                        ], 422);
                    }
                    return back()->with('flash_error', $message);
                }

                $this->storeDefaultPayments($token);

                // Get the deployed contract information
                $userContract = UserContract::where('property_id', $token->property_id)
                    ->where('blockchain_id', $token->blockchain_id)
                    ->first();

                if ($userContract && $userContract->contract_address) {
                    $blockchain = Blockchain::find($token->blockchain_id);
                    $explorerUrl = $blockchain ? $blockchain->link . 'token/' : 'https://etherscan.io/token/';

                    return response()->json([
                        'success' => true,
                        'message' => 'Token deployed successfully',
                        'contract_address' => $userContract->contract_address,
                        'blockchain_explorer' => $explorerUrl,
                        'token_name' => $token->name,
                        'token_symbol' => $token->symbol,
                        'redirect_url' => url('/issuer/property').'?property_id='.$token->property_id
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Token deployment completed but contract details were not found.',
                    'redirect_url' => url('/issuer/tokenRequest')
                ], 422);
            }

            // For non-demo mode, return JSON response for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Property created successfully. Token deployment request submitted.',
                    'redirect_url' => '/issuer/tokenRequest'
                ]);
            }

            return redirect('/issuer/tokenRequest')->with('flash_success', 'Property created successfully');
        } catch (\Throwable $th) {
            \Log::info($th);

            // Return JSON response for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please attach Valid files!!',
                    'error' => $th->getMessage()
                ], 422);
            }

            return back()->with('flash_error', 'Please attach Valid files!!');
        }
    }

    public function storeDefaultPayments($token){
        $userContract = UserContract::where('property_id', $token->property_id)->where('blockchain_id', $token->blockchain_id)->first();
        if (!$userContract) {
            throw new \RuntimeException('User contract not found after token deployment.');
        }

        $blockchainStablecoin = BlockchainStablecoin::where('blockchain_id', $token->blockchain_id)
            ->whereHas('stablecoin', function($query) use ($token) {
                $query->where('title', config('token.token.name'));
            })->first();
        if (!$blockchainStablecoin) {
            throw new \RuntimeException('Default blockchain stablecoin configuration not found.');
        }

        $issuerStablecoinWalletAddress = new IssuerStablecoinWalletAddress();
        $issuerStablecoinWalletAddress->issuer_id = Auth::user()->id;
        $issuerStablecoinWalletAddress->blockchain_stablecoin_id = $blockchainStablecoin->id;
        $issuerStablecoinWalletAddress->user_contract_id = $userContract->id;
        $issuerStablecoinWalletAddress->address = config('token.token.address');
        $issuerStablecoinWalletAddress->save();

        $issuerBankAccounts = new IssuerBankAccounts();
        $issuerBankAccounts->user_contract_id = $userContract->id;
        $issuerBankAccounts->issuer_id = Auth::user()->id;
        $issuerBankAccounts->bank_name = 'Bank of America';
        $issuerBankAccounts->bank_location = 'Anytown, USA';
        $issuerBankAccounts->bank_address = '123 Main St, Anytown, USA';
        $issuerBankAccounts->bank_account_name = 'John Doe';
        $issuerBankAccounts->routing_details = '1234567890';
        $issuerBankAccounts->beneficiary_name = 'John Doe';
        $issuerBankAccounts->save();
    }
    public function tokenEdit($id){
        $user = Auth()->user();
        $data['property'] = Property::with('keystore')->where('id', $id)->first();
        if($data['property']->status == 'block'){
            abort(404);
        }

        $data['property']->isEditable= $data['property']->status != 'active';
        $data['keystores'] = $keystores = KeystoreModel::where('user_id',$user->id)->get();
        $data['propertyComparable'] = PropertyComparable::where('property_id', $id)->first();
        $data['IssuerTokenRequest'] = IssuerTokenRequest::where('property_id', $id)->first();
        if(!empty($data['IssuerTokenRequest'])){
            $data['property']->coin = $data['IssuerTokenRequest']->coin;
        }
        $data['ManagementMembers'] = ManagementMembers::where('property_id', $id)->first();
        $data['assetType'] = AssetType::orderBy('type', 'asc')->get();
        $data['blockchains'] =  BlockChain::all();
        $data['propertyImages'] = PropertyImage::where('property_id',$id)->get();
        return view('issuer.token-edit', $data);
    }

    public function tokenUpdate($id, Request $request){

        $property = Property::where('id', $id)->first();
        $notDeployed= $property->status != 'active';
        $validationRule = [
            'propertyName'           => 'required|max:320',
            'propertyLogo'           => 'mimes:jpeg,jpg,png|max:5120',
            'holdingPeriod'          => 'required|min:1',
            'tokentype'              => 'required',
            'investor'              => 'max:5120|mimes:pdf',
            'brochure'              => 'max:5120|mimes:pdf',
            'comparabledetails'     => 'max:5120|mimes:pdf',
            'titlereport'     => 'max:5120|mimes:pdf',
            'termsheet'     => 'max:5120|mimes:pdf',
            'propertyUpdatesDoc'     => 'max:5120|mimes:pdf',
            'expectedIrr'  => 'gte:0',
            'initialInvestment'      => 'required|gte:0',
            'totalDealSize'  => 'required',

            'propertyDetailsHighlights' => 'max:100000',
            'ManagementTeamDescription' => 'max:100000',
            'propertyOverview'  => 'max:100000',
            'propertyLocationOverview'  => 'max:100000',
            'fundedMembers' => 'gte:0',
            'yearOfConstruction' => 'nullable|digits:4',
            'enable_internal_wallet' => 'required|boolean'

        ];

        if($notDeployed){
            $validationRule = array_merge($validationRule, [
                'token_name'     => 'required|max:100',
                'token_symbol'   => 'required|max:4',
                'token_decimal'  => 'required|digits_between:1,2',
                'token_value'    => 'required|gte:0',
                'token_supply'   => 'required|gte:0',
                // 'token_image'    => 'required|mimes:jpeg,jpg,png|max:5120',
                'keystore_id'    => 'required|integer',
                'token_chain'            => 'required|exists:blockchains,id',


            ]);

        }

        if ($request->token_type == 1) {
            $validationRuleProp = [
                'propertyLocation'          => 'max:320',
                // 'propertyType'              => 'required',
                'total_sft'                 => 'min:0',
                // 'propertyOverview'  => 'max:320',
                // 'propertyLocationOverview'  => 'max:320',
                'locality'                  => 'max:600',
                // 'yearOfConstruction'        => 'required|numeric|digits:4',
                // 'propertyTotalBuildingArea' => 'required|numeric|min:0',
                // 'propertyDetailsHighlights' => 'required',
                'floorplan'                 => 'max:5120|mimes:pdf',
            ];

            $validationRule = array_merge($validationRule, $validationRuleProp);
            // $request->token_value = number_format((int)$request->totalDealSize / (int)$request->total_sft, 6, '.', '');
            // $request->token_supply = (int)$request->total_sft;
        } else {
            $validationRule['propertyHighlights'] = 'nullable';
        }

        $this->validate($request, $validationRule, [
            'investor.max' => 'Prospectus file should not be greater than 5120 kilobytes',
            'titlereport.max' => 'Reports file should not be greater than 5120 kilobytes',
            'termsheet.max' => 'Agreements file should not be greater than 5120 kilobytes',
            'propertyUpdatesDoc.max' => 'Additional Information file should not be greater than 5120 kilobytes',
            'propertyOverview' => 'Fund Overview should not be greater than 100000 characters',
            'propertyLocationOverview' => 'Fund Highlights should not be greater than 100000 characters',
        ]);


        if ($request->totalDealSize < $request->initialInvestment) {
            $request->initialInvestment = (int)$request->totalDealSize / 2;
        }
        // if ($request->token_value > $request->initialInvestment) {
        //     $request->token_value = (int)$request->initialInvestment / 2;
        // }
        try {
            $user = Auth::user();
            $data = $request->all();
            $data['user_id'] = $user->id;
            $data['property_state'] = $request->property_state;
            // if ($request->token_type == 1) {
            //     if ($request->has('token_value')) {
            //         $data['totalDealSize'] = $request->total_sft / $request->token_value;  //$request->totalDealSize;
            //     }
            // } else {
            $data['totalDealSize'] = $request->totalDealSize;
            // }
            if ($request->hasFile('propertyVideo'))
                $data['propertyVideo'] = $request->propertyVideo->store('propertyVideo');

            if ($request->hasFile('propertyLogo'))
                $data['propertyLogo'] = $request->propertyLogo->store('propertyLogo');

            if ($request->hasFile('floorplan'))
                $data['floorplan'] = $request->floorplan->store('floorplan');

            if ($request->hasFile('investor'))
                $data['investor'] = $request->investor->store('investor');

            if ($request->hasFile('titlereport'))
                $data['titlereport'] = $request->titlereport->store('titlereport');

            if ($request->hasFile('termsheet'))
                $data['termsheet'] = $request->termsheet->store('termsheet');

            if ($request->hasFile('brochure'))
                $data['brochure'] = $request->brochure->store('brochure');

            if ($request->hasFile('propertyManagementTeam')) {
                $data['propertyManagementTeam'] = $request->propertyManagementTeam->store('propertyManagementTeam');
            }
            if ($request->hasFile('propertyUpdatesDoc')) {
                $data['propertyUpdatesDoc'] = $request->propertyUpdatesDoc->store('propertyUpdatesDoc');
            }

            if ($request->has('token_chain')) {
                $blockchain = Blockchain::find($request->token_chain);
                $data['coin'] = $blockchain->abbreviation;
            }

            if ($request->filled('removed_image_ids')) {
                $idsToRemove = explode(',', $request->input('removed_image_ids'));

                $imagesToDelete = PropertyImage::whereIn('id', $idsToRemove)->get();

                foreach ($imagesToDelete as $image) {
                    if (Storage::exists($image->image)) {
                        Storage::delete($image->image);
                    }
                    $image->delete();
                }
            }
            $property->update($data);
            if (!empty($request['comparables'])) {
                PropertyComparable::where('property_id', $property->id)->delete();
                foreach ($request['comparables'] as $key => $value) {
                    $property_comparables                     = new PropertyComparable;
                    $property_comparables->property_id        = $property->id;
                    $property_comparables->property           = $value['property'];
                    $property_comparables->type               = $value['type'];
                    $property_comparables->location           = $value['location'];
                    $property_comparables->distance           = $value['distance'] ? $value['distance'] : 0.00;
                    $property_comparables->rent               = $value['rent'] ? $value['rent'] : 0.00;
                    $property_comparables->saleprice          = $value['saleprice'] ? $value['saleprice'] : 0.00;
                    if ($request->hasFile('map')) {
                        $property_comparables->map                = $request->map->store('map');
                    }
                    if ($request->hasFile('comparabledetails')) {
                        $property_comparables->comparable_details = $request->comparabledetails->store('comparabledetails');
                    }
                    $property_comparables->save();
                }
            }

            if (!empty($request->member)) {
                foreach ($request->member as $key => $value) {
                    $member                    = ManagementMembers::where('property_id', $id)->first() ?? new ManagementMembers();
                    $member->property_id       = $property->id;
                    $member->memberName        = $value['name'];
                    $member->memberPosition    = $value['position'];
                    $member->memberDescription = $value['description'];
                    $member->memberPic         = isset($value['pic']) ? $value['pic']->store('memberPic') : null;
                    $member->save();
                }
            }

            if ($request->hasFile('propertyimages')) {
                $files = $request->file('propertyimages');
                foreach ($files as $key => $file) {
                    $productImage              = new PropertyImage;
                    $productImage->property_id = $property->id;
                    $productImage->image       = $file->store('propertyImage');
                    $productImage->save();
                }
            }


            if($notDeployed){
                $token                = IssuerTokenRequest::where('property_id', $id)->first();
                $token->user_id       = $user->id;
                $token->property_id   = $property->id;
                $token->name          = $request->token_name;
                $token->symbol        = strtoupper($request->token_symbol);
                $token->usdvalue      = $request->token_value;
                $token->decimal       = $request->token_decimal;
                $token->coin          = $blockchain->abbreviation;
                $token->blockchain_id = $request->id;
                $token->usdvalue      = $request->token_value;
                $token->supply        = $request->token_supply;
                $token->blockchain_id = $blockchain->id;



                // $token->security_type = $request->tokentype;
                // $token->token_image   = $request->token_image->store('token_image');

                // if ($request->token_type == 2) {
                // $token->supply = $request->token_supply;
                // } else {
                //     $token->supply = $request->total_sft / $request->token_value; //$request->token_supply;
                // }

                $token->save();
            }


            $user_contract = UserContract::where('property_id', $id)->first();
            if($notDeployed && !empty($user_contract )){
                $user_contract->tokenvalue = $request->token_value;
                $user_contract->save();
            }

            return back()->with('flash_success', 'Property Updated successfully');
        } catch (\Exception $e) {
            logException($e, [
                'data' => $request->all,
                'Api' => 'Token Edit api'
            ]);
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    /**
     * Used to Create or Update Product
     */
    public function productcrud($request)
    {
        try {

            return $property ?? [];
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Something went wrong');
        }
    }
    public function wallet()
    {
        $user = Auth::user();
        $coins = Coins::where('status', 1)->pluck('symbol')->toArray();
        // $coins = ['ETH','BNB','MATIC'];
        // foreach ($coins as $key => $value) {
        //     // if($value != 'USD'){
        //     //     $wallet = json_decode((new HomeController)->getETHbalance($user->eth_address,$value), TRUE);
        //     //     $user->$value = $wallet['balance'];
        //     //     $user->save();

        //     //     (new HomeController)->updateDepositHistory($user->id, $user->eth_address, $value);
        //     // }
        // }
        $admin = Admin::where('id', 1)->first();
        //$history = \App\DepositHistory::whereuser_id($user->id)->orderBy('created_at', 'DESC');
        $history_eth = DepositHistory::where('user_id', $user->id)->where('amount', '>', 0)->orderBy('created_at', 'DESC')->take(3)->get();
        $fields = Bank::where('status', 'Active')->get();
        $deposit_history = DepositFiat::where('user_id', $user->id)->take(4)->orderBy('created_at', 'DESC')->get();
        $blockchain = Blockchain::orderBy('id', 'asc')->first();
        $transaction_link = config('app.env') == 'production' ? $blockchain->production_link : $blockchain->test_link;
        return view('issuer.wallet', compact('coins','user', 'history_eth', 'fields', 'deposit_history','admin','transaction_link'));
    }

    public function GetBankDetails($id){
        try{
            \Log::info($id);
            $bank = Bank::where('id', $id)->first();
            \Log::info($bank);
            return $bank;
        }catch(Exception $e){
            \Log::info($e);
            return null;
        }
    }

    public function security(Request $request)
    {
        $user = Auth::user();

        $secret = $this->generateSecret();

        //get user
        //$user = $request->user();

        //encrypt and then save secret
        //$user->google2fa_secret = Crypt::encrypt($secret);
        $user->g2f_temp = Crypt::encrypt($secret);
        $user->save();

        //$google2fa=Google2FA::setAllowInsecureCallToGoogleApis(true);
        $image = Google2FA::getQRCodeInline(
            $request->getHttpHost(),
            $user->email,
            $secret,
            200
        );
        return view('issuer.security', compact('user', 'image', 'secret'));
    }
    //ETH WITHDRAWAL FLOW
    public function generateWithdrawOTP(Request $request)
    {
        try {
            $user = Auth::user();
            $data = rand(100000, 999999);
            $user->eth_otp = Hash::make($data);
            $user->save();

            $to_email = $user->email;
            //$to_email = 'test12345671@mailinator.com';

            logger()->info('otp: ' . $data);

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
    public function withdrawETHBalance()
    {
        $user = Auth::user();
        $history = WithdrawEth::whereuser_id($user->id)->where('coin', '!=', 'USD')->orderBy('created_at', 'DESC')->get();
        $fiat_history = WithdrawEth::whereuser_id($user->id)->where('coin', 'USD')->orderBy('created_at', 'DESC')->get();

        $coins = Coins::where('status', 1)->pluck('symbol')->toArray();
        return view('ethwithdraw_issuer', compact('user', 'history', 'fiat_history','coins'));
    }

    public function sendETH(Request $request)
    {
        $this->validate($request, [
            'coin'      =>  'required',
            'amount'    =>  'required|numeric|min:0',
            'address'   =>  'required_unless:coin,USD',
            'account'   =>  'required_if:coin,USD',
            'ifsc_code'   =>  'required_if:coin,USD',
            'bank_name'   =>  'required_if:coin,USD',
            'account_name'   =>  'required_if:coin,USD',
        ]);

        try {

            $user = Auth::user();
            $coin = $request->coin;
            if($request->amount > $user->$coin){
                return back()->with('flash_error', 'Insufficient funds');
            }
            if($request->coin == 'USD'){
                $receiver_data = [$request->account, $request->ifsc_code, $request->bank_name, $request->account_name];

                $withdraw_model = new WithdrawEth;
                $withdraw_model->user_id = $user->id;
                $withdraw_model->sender = $user->id;
                $withdraw_model->receiver = json_encode($receiver_data);
                $withdraw_model->amount = $request->amount;
                $withdraw_model->coin = $request->coin;
                $withdraw_model->tx_hash = "***";
                $withdraw_model->reason = "Transaction initiated";
                $withdraw_model->status = "pending";
                $withdraw_model->save();

                return back()->with('flash_success', 'Withdraw request sent successfully.');
            }else{

                $withdraw_model = new WithdrawEth;
                $withdraw_model->user_id = $user->id;
                $withdraw_model->sender = $user->eth_address;
                $withdraw_model->receiver = $request->address;
                $withdraw_model->amount = $request->amount;
                $withdraw_model->coin = $request->coin;
                $withdraw_model->tx_hash = "***";
                $withdraw_model->reason = "Transaction initiated";
                $withdraw_model->status = "pending";
                $withdraw_model->save();
                return back()->with('flash_success', 'Withdraw request sent successfully.');
            }
        } catch (\Throwable $th) {
            \Log::critical('withdrawETH', ['message' => $th->getMessage()]);
            $withdraw_model->reason = "Unable to withdraw ".$request->coin;
            $withdraw_model->status = "failed";
            $withdraw_model->save();
            return back()->with('flash_error', 'Unable to withdraw '.$request->coin.'. Please try again later');
        }
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
            return $eth_balance->status == 1 ? $eth_balance->result->ProposeGasPrice : 48;
        } catch (\Throwable $th) {
            return 3165;
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

    public function GetTradeList(){
        try{
            $user = Auth::user();
            $trades = Trade::with('property')->where('user_id','!=',$user->id)->where('status', 'Pending')->get();
            return view('issuer.trade.index', compact('trades'));
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function GetInvestTokens(){
        try{
            $user = Auth::user();
            $user_contract = UserContract::where('user_id', $user->id)->pluck('id')->toArray();
            $user_token = UserToken::where('user_id', $user->id)->get();
            $invested_tokens = [];
            if($user_token){
                foreach($user_token as $index => $value){
                    if(!in_array($value->user_contract_id, $user_contract)){
                        array_push($user_contract, $value->id);
                    }
                }
            }
            $user_contract = UserContract::with('property')->whereIn('id', $user_contract)->get();
            $coins = Coins::where('status', 1)->pluck('symbol')->toArray();
            return view('issuer.trade.investment', compact('user_contract','coins'));
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function OpenTrade(){
        try{
            $user = Auth::user();
            $trades = Trade::with('property')->where('user_id',$user->id)->where('status', 'Pending')->get();
            return view('issuer.trade.open', compact('trades'));
        }catch(Exception $e){
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

    public function PlaceTrade(Request $request){
        try{
            $user = Auth::user();

            $checkExistingTrade = Trade::where('user_id', $user->id)->where('status', 'Pending')->first();
            if($checkExistingTrade){
                return back()->with('flash_error', 'Currently you have a open trade. You can initiate a new trade after the existing trade completed or cancelled.');
            }

            $property = Property::where('id', $request->property_id)->first();
            $user_contract = UserContract::where('id', $request->contract_id)->first();
            if($property->user_id == $user->id){
                $balance = $user_contract->tokenbalance;
            }else{
                $user_token = UserToken::where('user_id', $user->id)->where('user_contract_id', $property->userContract->id)->first();
                $balance = $user_token->token_acquire;
            }
            if($request->no_of_tokens > $balance){
                return back()->with('flash_error', 'Insufficient token balance for trade');
            }

            $trade = new Trade();
            $trade->user_id = $user->id;
            $trade->property_id = $request->property_id;
            $trade->user_contract_id = $request->contract_id;
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
        try{
            $user = Auth::user();
            $trades = Trade::with('property','finish_user')->where('user_id', $user->id)->where('status', 'Finished')->get();
            return view('issuer.trade.history', compact('trades'));
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function WalletConnect(){
        return view('wallet_connect');
    }

    public function WhitelistRequest(){
        $user = Auth::user();

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
        if($res['status'] == 'success'){
            $key = $res['key'];
        }else{
            return back()->with('flash_error', 'Unable to get the key');
        }
        $whitelistRequests = InvestorWhitelist::with('user','property')->where('issuer', $user->id)->where('status', 'Pending')->where('type','withdraw')->get();
        return view('issuer.whitelist_request', compact('whitelistRequests','key','user'));
    }

    public function UpdateWhitelistRequest($id, $hash, $status){
        try{
            $whiteList = InvestorWhitelist::where('id', $id)->first();
            $whiteList->status = $status;
            $whiteList->hash = $hash;
            $whiteList->save();

            return 1;
        }catch(Exception $e){
            return 0;
        }
    }

    public function WhitelistUsers(){
        try {
            $user = Auth::user();
            $whitelistRequests = InvestorWhitelist::with('user','property')->where('issuer', $user->id)->where('status', 'Approved')->where('type','withdraw')->orderBy('id', 'desc')->get();
            return view('issuer.whitelisted_user', compact('whitelistRequests'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function PurchaseRequest(){
        $user = Auth::user();
        $whitelistRequests = InvestorWhitelist::with('user','property','identity','user.country')->where('issuer', $user->id)->where('status', 'Pending')->where('type','purchase')->get();
        return view('issuer.purchase_request', compact('whitelistRequests', 'user'));
    }

    public function UpdatePurchaseRequest($id, $status , Request $request){
        try{
            $purpose_request = InvestorWhitelist::where('id', $id)->first();
            $purpose_request->status = $status;
            $purpose_request->note = $request->note ?? null;
            $purpose_request->save();
            return back()->with('flash_success', 'Request status updated successfully');
        }catch(Exception $e){
            return back()->with('flash_error', 'Something went wrong');
        }
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

    public function ApproveWithdrawShare($id){
        try{
            $user = Auth::user();

            $invest_request = InvestorWhitelist::with('property')->where('id', $id)->first();

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
            if($res['status'] == 'success'){
                $user_contract = UserContract::where('property_id', $invest_request->property_id)->first();

                $transfer_url = env('BASE_NODE_URL', 'http://localhost:3000') . '/transfer';
                $headers = [
                    'Content-Type' => 'application/json',
                ];
                $transfer_res = $client->post($transfer_url, [
                    'headers' => $headers,
                    'body' => json_encode([
                        'contract_address' => $user_contract->contract_address,
                        'to'               => $invest_request->wallet_address,
                        'amount'           => $invest_request->amount,
                        'senderAddress'    => $user->eth_address,
                        'key' => $res['key'],
                        'decimal'          => $user_contract->decimal,
                        'chain' => $user_contract->coin,
                    ]),
                ]);
                $transfer_res = json_decode($transfer_res->getBody(), true);
                if($transfer_res['status'] == 'success'){
                    $user_token = UserToken::where('user_contract_id', $user_contract->id)->where('user_id', $invest_request->user_id)->first();
                    $user_token->token_acquire -= $invest_request->amount;
                    $user_token->save();

                    $withdraw_share = new WithdrawShare();
                    $withdraw_share->user_id = $invest_request->user_id;
                    $withdraw_share->user_token_id = $user_token->id;
                    $withdraw_share->trx_hash = $transfer_res['txHash'];
                    $withdraw_share->amount = $invest_request->amount;
                    $withdraw_share->address = $invest_request->wallet_address;
                    $withdraw_share->save();

                    return 'success';
                }else{
                    return 'Failed';
                }
            }else{
                return 'Key error';
            }
        }catch(Exception $e){
            \Log::info($e);
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function DepositCrypto(Request $request){
        try{
            \Log::info($request->all());
            $this->validate($request,[
                'coin' => 'required',
                'amount' => 'required',
                'proof' => 'required',
                'hash' => 'required'
            ]);
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
        }catch(\Exception $e){
            dd($e->getMessage());
            \Log::info($e);
            if($request->ajax()){
                return response()->json(['status'=>'error']);
            }
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function ViewInvestorKYC($id){
        $user = User::where('id', $id)->first();
        $documents = AccreditedKycDocument::where('user_id', $id)->first();
        return view('issuer.investor_kyc', compact('user', 'documents'));
    }

    public function getPurchaseRequest(){
        $user = Auth::user();
        // Investor details, asset, quantity, total amount, payment method + evidence.
        $requests = UserToken::with(['property','user','usercontract'])->where('issuer_id',$user->id)->where('status', 'inReview')->get();
        return view('issuer.buy_request', compact('requests', 'user'));
    }


    public function updateBuyRequest(Request $request, $id, $status, TokenPaymentService $service){
        try {
            $result = $service->updateBuyRequestStatus($id, $status, $request->note);
            if (isset($result['error'])) {
                return back()->with('flash_error', $result['error']);
            }
            return back()->with('flash_success', $result['success']);
        } catch (\Throwable $e) {
            logException($e, [
                'user_token_id' => $id,
                'status' => $status,
                'note' => $request->note ?? null,
                'stage' => 'updateBuyRequest'
            ]);

            return back()->with('flash_error', 'Something went wrong. Please try again later.');
        }
    }




    public function getShareCertificate($id)
    {
        try {
            $user = auth()->user();

            $shares = InvestorShares::where('user_id', $user->id)
                ->where('user_contract_id', $id)
                ->firstOrFail();

            $data = [
                'name' => $user->name,
                'token_count' => $shares->internal_wallet + $shares->external_wallet,
                'propertyName' => $shares->userContract->property->propertyName,
                'created_at' => $shares->updated_at,
            ];

            return view('issuer.certificate', compact('data'));

        } catch (\Exception $e) {
            return back()->with('flash_error', 'Something went wrong');
        }
    }



}
