<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InvestorTypes;
use App\FundTypes;
use App\WorthStatus;
use App\UserContract;
use App\InvestorShares;
use App\UserToken;
use App\Models\EWTransferLogsModel;
use App\WhiteListedWalletAddress;
use App\Exceptions\CustomException;
use App\Services\InvestorService;
use Illuminate\Support\Facades\DB;
use Auth;





class InvestorController extends Controller
{
    public function index()
    {
        $investor_types = InvestorTypes::get();
        return view('admin.investor.index', compact('investor_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.investor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'investor_type' => 'required',
        ]);

        try {

            $investor_type = new InvestorTypes;
            $investor_type->investor_type = $request->investor_type;
            $investor_type->save();

            return back()->with('flash_success', 'Investor Type Added Successfully');
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Something went wrong. Please try again');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $investor_type = InvestorTypes::find($id);

            return view('admin.investor.edit', compact('investor_type'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Something went wrong. Please try again');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request);
        $this->validate($request, [
            'investor_type' => 'required',
            'status' => 'required',
        ]);

        try {

            $investor_type = InvestorTypes::find($id);
            $investor_type->investor_type = $request->investor_type;
            $investor_type->status = $request->status;
            $investor_type->save();

            return back()->with('flash_success', 'Investor Type Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Oops something went wrong. Please try again');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    // Type of Funds	
    public function indexfund()
    {
        $fund_types = FundTypes::get();
        return view('admin.fund.index', compact('fund_types'));
    }

    public function createfund()
    {
        return view('admin.fund.create');
    }

    public function storefund(Request $request)
    {
        $this->validate($request, [
            'fund_type' => 'required',
        ]);

        try {

            $fund_type = new FundTypes;
            $fund_type->fund_type = $request->fund_type;
            $fund_type->save();

            return back()->with('flash_success', 'Fund Type Added Successfully');
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Something went wrong. Please try again');
        }
    }

    public function editfund($id)
    {
        try {
            $fund_type = FundTypes::find($id);

            return view('admin.fund.edit', compact('fund_type'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Something went wrong. Please try again');
        }
    }

    public function updatefund(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'fund_type' => 'required',
            'status' => 'required',
        ]);

        try {

            $id = $request->id;

            $fund_type = FundTypes::find($id);
            $fund_type->fund_type = $request->fund_type;
            $fund_type->status = $request->status;
            $fund_type->save();

            return back()->with('flash_success', 'Fund Type Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Oops something went wrong. Please try again');
        }
    }

    //WorthStatus    
    public function indexworthstatus()
    {
        $worthstatus = WorthStatus::get();
        return view('admin.worthstatus.index', compact('worthstatus'));
    }

    public function createworthstatus()
    {
        return view('admin.worthstatus.create');
    }

    public function storeworthstatus(Request $request)
    {
        $this->validate($request, [
            'worth_status' => 'required',
        ]);

        try {

            $worthstatus = new WorthStatus;
            $worthstatus->worth_status = $request->worth_status;
            $worthstatus->save();

            return back()->with('flash_success', 'Worth Status Added Successfully');
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Something went wrong. Please try again');
        }
    }

    public function editworthstatus($id)
    {
        try {
            $worthstatus = WorthStatus::find($id);

            return view('admin.worthstatus.edit', compact('worthstatus'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Something went wrong. Please try again');
        }
    }

    public function updateworthstatus(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'worth_status' => 'required',
            'status' => 'required',
        ]);

        try {

            $id = $request->id;

            $worthstatus = WorthStatus::find($id);
            $worthstatus->worth_status = $request->worth_status;
            $worthstatus->status = $request->status;
            $worthstatus->save();

            return back()->with('flash_success', 'Worth Status Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('flash_error', 'Oops something went wrong. Please try again');
        }
    }

    public function whitelistAddress($user_id, $id, Request $request, InvestorService $service){

        try {
            $response = $service->whitelistAddress(
                $user_id,
                $id,
                $request->token_id,
                $request->address
            );
            return $response;
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

    public function getWhiteListedAddress($user_id, $id, Request $request, InvestorService $service){
        $user = Auth::user() ;
        
        try {
            $response = $service->getWhitelistedAddresses(
                $user->id,
                $id
            );
            return $response;
        } catch (\Exception $e) {
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
    
    public function tranferTokensToEW($user_id, $contract_id, Request $request, InvestorService $service){
        // Validated outside the try: the catch below turns everything into a 500,
        // so a ValidationException raised in here reached the investor as
        // "an unexpected error occurred" instead of telling them what to fix.
        $validated = $request->validate([
            'recipient_wallet_id' => 'required|integer|exists:whitelisted_wallet_addresses,id',
            'amount' => 'required|numeric|min:1'
        ]);

        try {
            $user = Auth::user();

            // Fetch investor share
            $investorShare = InvestorShares::where('user_id', $user->id)
                ->where('user_contract_id', $contract_id)
                ->first();
    
            if (!$investorShare) {
                return response()->json([
                    'success' => false,
                    'message' => 'Investor share record not found.'
                ], 404);
            }
    
            // Fetch recipient wallet
            $wallet = WhiteListedWalletAddress::where('id', $validated['recipient_wallet_id'])
                ->where('user_id', $user->id)
                ->first();
    
            if (!$wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recipient wallet not found.'
                ], 404);
            }
    
            // Check if internal wallet has enough tokens
            if ($investorShare->internal_wallet < $validated['amount']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient token transfer count. Available: ' . ($investorShare->internal_wallet ?? 0)
                ], 400);
            }
    
            // Fetch user contract
            $userContract = UserContract::find($contract_id);
            if (!$userContract) {
                return response()->json([
                    'success' => false,
                    'message' => 'User contract not found.'
                ], 404);
            }
    
            // Perform transfer
            $service->transferTokensToEW($user_id, $wallet, $userContract, $validated['amount']);
    
            return response()->json([
                'success' => true,
                'message' => 'Tokens transferred successfully.'
            ]);

        } catch (CustomException $e) {
            // The service re-checks the balance under a row lock, which is what
            // catches a second concurrent transfer. Report what it actually said.
            logError('Token transfer rejected', [
                'user_id' => $user_id,
                'contract_id' => $contract_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        } catch (\Throwable $e) {
            logError('Token transfer failed', [
                'user_id' => $user_id,
                'contract_id' => $contract_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred during the token transfer. Please try again later.'
            ], 500);
        }
    }
    
    

    public function getExternalWalletBalance($userId, $userContractId, Request $request)
    {
        $wallets = WhiteListedWalletAddress::where('user_id', $userId)
            ->where('contract_id', $userContractId)
            ->paginate(10); // Adjust per page count as needed

        return response()->json([
            'success' => true,
            'wallets' => $wallets->items(),
            'current_page' => $wallets->currentPage(),
            'last_page' => $wallets->lastPage(),
        ]);
    }

    
}
