<?php

namespace App\Services;

use App\UserToken;
use App\UserContract;
use App\WhiteListedWalletAddress;
use App\InvestorShares;
use App\UserTokenTransaction;
use App\Notification;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AppFlowLog;


class TokenPaymentService
{
    /**
     * Full Flow Handler (calls stages as per currentStep)
     */
    public function handleUpsertRequest($user, $userContract, array $requestData){
        try{
            $userToken = UserToken::where('user_id', $user->id)
            ->where('user_contract_id', $userContract->id)
            ->whereIn('status', ['inProgress', 'inReview'])
            ->first();

            if (!$userToken) {
                if((float) $userContract->tokenbalance < (float)$requestData['tokens']){
                    throw new Exception("Tokens Supply not available");
                }
                // New token creation — equivalent to stage 1
                $this->setTokenAmount($user, $userContract, $requestData);

                return;
            }

            if ($userToken->current_stage == 3) {
                throw new Exception("Request can't be discarded after payment is done.");
            }
            $this->isTokenAvailable($userToken, $userContract);
            $userToken->current_stage = $requestData['currentStep'];
            $userToken->save();
            // Route to correct stage handler
            switch ($requestData['currentStep']) {
                case 1:
                    $result = $this->setTokenAmount($user, $userContract, $requestData, $userToken);
                    break;
                case 2:
                    $this->setCustody($userContract, $userToken, $requestData);
                    break;
                case 3:
                    $this->savePaymentDetails($userToken,$requestData,$userContract);
                    if(config('app.is_demo')){
                        $result = $this->updateBuyRequestStatus($userToken->id, 'Approved', 'Approved Automatically');
                    }
                    break;
                default:
                    throw new Exception('Invalid Operation');
            }
        }catch(\Exception $e){
            throw $e;
        }
    }
    /**
     * Stage 1: Token Request Creation / Update
     *
     * @param \App\Models\User $user
     * @param \App\Models\UserContract $userContract
     * @param array $requestData
     * @param \App\Models\UserToken|null $userToken
     *
     * @return mixed
     */
    public function setTokenAmount($user, $userContract, array $requestData, $userToken = null){
        try {
            if (!$userToken) {
                // New token request
                return $this->createUserTokenForBuyProcess($requestData, $user, $userContract);
            }

            // Update existing token request in stage 1
            return $this->updateUserTokenForBuyProcess($requestData, $user, $userContract, $userToken);
        } catch (\Throwable $e) {
            logException($e, [
                'user_id' => $user->id ?? null,
                'user_contract_id' => $userContract->id ?? null,
                'user_token_id' => $userToken->id ?? null,
                'stage' => 'setTokenAmount'
            ]);
            throw $e;
        }
    }

    /**
     * Stage 2: Wallet Selection & Custody Type
     */
    public function setCustody($userContract, UserToken $userToken, array $requestData){
        if ($requestData['custody'] === 'external') {
            if ($userContract->property->token_type == 3) {
                $userToken->receiver_wallet_address = $requestData['contract_address'];
                $userToken->wallet_id = null;
            } else {
                $wallet = WhiteListedWalletAddress::findOrFail($requestData['whitelisted_wallet_id']);
                $userToken->receiver_wallet_address = $wallet->wallet_address;
                $userToken->wallet_id = $wallet->id;
            }
        } else {
            $userToken->receiver_wallet_address = $userContract->contract_address;
            $userToken->wallet_id = null;
        }

        $userToken->wallet_type = $requestData['custody'];
        $userToken->current_stage = 2;
        $userToken->save();
        return $userToken;
    }

    /**
     * Stage 3: Payment Submission
     *
     * @param  \App\Models\UserToken  $userToken
     * @param  array  $requestData  The payment details for submission.
     * @param  \App\Models\UserContract|null  $userContract
     *
     * Required structure of $requestData:
     * [
     *     'payment_method'       => (string) Payment method key (e.g. 'crypto_transfer', 'upi', etc.),
     *     'selected_payment_id'  => (int) ID of the payment method selected by the user,
     *     'payment_reference'    => (string) Unique reference number provided by user or bank,
     *     'payment_proof'        => (UploadedFile|null) Optional: file object of receipt (jpg, jpeg, png, pdf, max 5MB),
     * ]
     *
     * @return array
     */
    public function savePaymentDetails($userToken, array $requestData, $userContract = null){
        $isAutomaticPayment = empty($userContract) ? true :false ;

        $userContract = empty($userContract) ? $userToken->usercontract : $userContract;

        if (!$userContract) {
            throw new Exception("UserContract associated with token request not found.");
        }

        if ($userToken->current_stage < 2) {
            throw new Exception("Token Custody hasn't set yet !!");
        }
        $this->addNonWhitelistedWallet($userToken);

        $userToken->current_stage = 3;
        $userToken->payment_mode = $requestData['payment_method'];
        $userToken->payment_mode_id = $requestData['selected_payment_id'];
        $userToken->payment_reference_id = $requestData['payment_reference'];
        $userToken->status = 'inReview';

        if (!empty($requestData['payment_proof'])) {
            $path = $requestData['payment_proof']->store('buy_process_payment_proofs/documents');
            $userToken->payment_proof_url = $path;
        }

        $userToken->save();

        // Add the wallet to the whitelisted table as utlity token's wallet need to be saved in : Ex-wallet case.
        $this->addNonWhitelistedWallet($userToken);
        $user = $userToken->user;
        $user->incrementInvestmentsMade();
        // Creat payment notification
        $this->notifySavePaymentEvent($isAutomaticPayment,$userContract,$userToken);
        return $userToken;
    }

    private function addNonWhitelistedWallet($userToken){
        //Check if the token is Utility Type Token
        if($userToken->property->token_type == 3){
            $wallet = WhitelistedWalletAddress::updateOrCreate(
                [
                    'user_id' => $userToken->user_id,
                    'contract_id' => $userToken->user_contract_id,
                    'wallet_address' => $userToken->receiver_wallet_address,
                ],
                [
                    'whitelisted_by' => $userToken->userContract->issued_by,
                    'status' => 1,
                    'tx_hash' => '0',
                ]
            );

            $userToken->wallet_id = $wallet->id;
            $userToken->save();
        }


        return;
    }

    /**
     * Business Logic to Create New Token Request
     */
    private function createUserTokenForBuyProcess(array $requestData, $user,  $userContract){
        $coinPrices = currentCryptoPrices();
        $chain = $requestData['payby'];
        $coinPrice = $coinPrices[$chain];

        $tokens = floatval($requestData['tokens']);
        $tokenValue = floatval($userContract->tokenvalue);

        // Calculate token equivalent
        $tokenEquValue = $this->calculateTokenEquivalent(
            $tokens,
            $tokenValue,
            $coinPrice
        );

        $ethBalance = $user->$chain;

        return UserToken::create([
            'user_id' => $user->id,
            'user_contract_id' => $userContract->id,
            'token_acquire' => $tokens,
            'payment_by' => $chain,
            'commission' => 0,
            'deal_amount' => $tokenEquValue,
            'issuer_id' => $userContract->issued_by,
            'property_id' => $userContract->property_id,
            'current_stage' => 1,
            'status' => 'inProgress',
        ]);
    }

    private function updateUserTokenForBuyProcess(array $requestData, $user, $userContract, UserToken $userToken){
            $chain = $requestData['payby'];
            $coinPrices = currentCryptoPrices();
            $coinPrice = $coinPrices[$chain];
            $ethBalance = $user->$chain;

            // Recalculate token equivalent in crypto
            $tokenEquValue = $this->calculateTokenEquivalent(
                $requestData['tokens'],
                $userContract->tokenvalue,
                $coinPrice
            );

            if (!$userToken) {
                throw new \Exception('User token not found');
            }

            // Update user token
            $userToken->update([
                'token_acquire' => $requestData['tokens'],
                // 'commission' => $adminCommission,
                'deal_amount' => $tokenEquValue,
                'payment_by' => $chain,
            ]);

            return $userToken;
    }

    private function calculateTokenEquivalent($tokens, $token_value, $coin_price){
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

    public function updateBuyRequestStatus(int $userTokenId, string $status, ?string $note = null){
        $buyRequest = UserToken::find($userTokenId);
        if (!$buyRequest) {
            return ['error' => 'Buy request not found'];
        }

        $investor = $buyRequest->user;
        $userContract = $buyRequest->usercontract;

        if (!$userContract || $userContract->tokenbalance <= 0) {
            $buyRequest->update([
                'status' => 'reject',
                'note' => 'Tokens not available'
            ]);

            return ['error' => 'Tokens not available, Request Rejected...'];
        }

        switch ($status) {
            case 'Cancelled':
                $buyRequest->update([
                    'status' => 'reject',
                    'note' => $note
                ]);

                $this->sendNotification(
                    $buyRequest->user_id,
                    'Buy Request Rejected',
                    "You've Buy request for asset: {$userContract->property->propertyName} is Rejected, Issuer's Note: {$note}",
                    'danger'
                );
                break;

            case 'Approved':
                $result = $this->approveBuyRequest($buyRequest, $note);
                if (isset($result['error'])) {
                    return ['error' => $result['error']];
                }

                break;

            default:
                return ['error' => 'Invalid status'];
        }

        return ['success' => 'Request status updated successfully'];
    }

    public function approveBuyRequest(UserToken $buyRequest, ?string $note = null){

        $user = $buyRequest->user;
        $userContract = $buyRequest->usercontract;

        if (!$userContract || $userContract->tokenbalance <= 0) {
            return ['error' => 'Tokens not available'];
        }
        // Add the logging info
        $this->addLoggingMessage('manual_payment_approved' ,['user' => $user , 'buyRequest' => $buyRequest, 'type' => 'info']);
        $transactionResult = $this->transferTokens($buyRequest, $user, $userContract, $note);
        return ['success' => 'Buy request approved successfully'];
    }

    public function addTokenTransferedNotification(UserToken $buyRequest,$isAutomaticPayment = false){
        $userContract = $buyRequest->usercontract;
        $propertyName = $userContract->property->propertyName;

        if($isAutomaticPayment){
            $this->sendNotification(
                $buyRequest->user_id,
                'Token Purchase Completed Successfully',
                "Congratulations !! Your Buy request is completed for asset: {$propertyName}",
                'success'
            );
        }else{
            $this->sendNotification(
                $buyRequest->user_id,
                'Buy Request Completed Successfully',
                "Congratulations !! Your Buy request is Approved and Tokens are tranferred for asset: {$propertyName}",
                'success'
            );
        }

    }

    /**
     * Transfers tokens to the investor based on the approved user token request.
     *
     * @param \App\Models\UserToken $userToken       The UserToken instance representing the buy request.
     * @param \App\Models\User|null $user            Optional. If not provided, fetched from $userToken.
     * @param \App\Models\UserContract|null $userContract  Optional. If not provided, fetched from $userToken.
     * @param string|null $note                      Optional note for logging or issuer reference.
     *
     * @return array {
     *     @type string $status                      'success' or 'error'
     *     @type array|null $userTokenTransactionData Data of the transaction (on success)
     *     @type string|null $message                Error message if status is 'error'
     * }
     *
     * @throws \Exception                            If any of the required data (user, contract, token) is missing.
     */
    public function transferTokens($userToken, $user = null, $userContract = null, $note = null){

        $validated = $this->validateTokenTransfer($user, $userContract, $userToken);
        $user = $validated['user'];
        $userContract = $validated['userContract'];
        $wallet = null;
        $keys = [];
        $isAutomaticPayment = $validated['isAutomaticPayment'];
        if ($userToken->wallet_type === 'external') {
            $wallet = $userToken->whitelistedWalletAddress;
            if (empty($wallet)) {
                throw new \Exception('External Wallet not found for token supply.');
            }

            $keys = $userContract->property->getPrivateKey('both');
            $isGasFeeAvailable = checkGasFee($keys['publicKey'],$userContract->blockchain->abbreviation);
            if(!$isGasFeeAvailable){
                // $userToken->note = 'Issuer Wallet Balance: Gas fee not available';
                // $userToken->status = 'failed';
                // $userToken->save();
                throw new \Exception('Issuer Wallet Balance: Gas fee not available');
            }
        }



        DB::beginTransaction();
        try {
            // Lock the user contract row for update to prevent race condition
            $userContract = UserContract::where('id', $userContract->id)->lockForUpdate()->first();

             // Now check with full safety
            if ($userContract->tokenbalance < $userToken->token_acquire) {
                // No rollback here: the catch below owns it. Rolling back twice
                // unwinds the caller's own transaction as well as this one.
                throw new \Exception('Insufficient token supply.');
            }

            $investorShares = InvestorShares::firstOrCreate([
                'user_id' => $user->id,
                'user_contract_id' => $userContract->id
            ]);

            $requestedToken = min($userContract->tokenbalance, $userToken->token_acquire);

            $txnData = [
                'user_id'           => $user->id,
                'user_token_id'     => $userToken->id,
                'user_contract_id'  => $userContract->id,
                'payment_type'      => $userToken->payment_by,
                'payment_amount'    => $userToken->deal_amount,
                'token_price'       => $userContract->tokenvalue,
                'number_of_token'   => $requestedToken,
                'txn_hash'          => 'Transferred',
                'bonus_value'       => $userContract->property->dividend,
                'bonus_token'       => 0,
                'total_token'       => $requestedToken,
                'status'            => 1,
                'admin_commission'  => $userToken->commission,
            ];

            $txn = UserTokenTransaction::create($txnData);

            $userContract->tokenbalance = price_format($userContract->tokenbalance - $requestedToken);
            $userContract->save();

            $userToken->update([
                'status' => 'success',
                'current_stage' => 4,
                'note' => $note
            ]);

            if ($userToken->wallet_type === 'external') {

                $payload = [
                    "amount"           => $requestedToken,
                    "chain"            => $userContract->blockchain->abbreviation,
                    "privateKey"       =>  $keys['privatekey'] ,
                    "contract_address" => $userContract->contract_address,
                    "to"               => $userToken->receiver_wallet_address
                ];

                $response = callNodeOperations('transfer', $payload);
                if (!empty($response['status']) && $response['status'] === 'success') {
                    $txn->update(['token_txn_hash' => $response['txHash']]);
                }

                $investorShares->external_wallet += $requestedToken;
                $wallet->balance +=  $requestedToken;
                $wallet->save();

            } else {

                $totalSold = InvestorShares::where('user_contract_id', $userContract->id)
                    ->sum(DB::raw('internal_wallet + external_wallet'));

                // Cross-check with what's in the contract record
                if (bccomp($userContract->tokensupply, bcadd(bcadd($totalSold, $userToken->token_acquire, 2), $userContract->tokenbalance, 2), 2) !== 0) {
                    // As above: the catch below performs the rollback.
                    throw new \Exception(
                        'Token balance mismatch: remaining tokens calculation failed. ' .
                        'Total Supply: ' . $userContract->tokensupply . ', ' .
                        'Total Sold: ' . $totalSold . ', ' .
                        'Token Acquire: ' . $userToken->token_acquire . ', ' .
                        'Token Balance: ' . $userContract->tokenbalance . ', ' .
                        'Calculated: ' . bcadd(bcadd($totalSold, $userToken->token_acquire, 2), $userContract->tokenbalance, 2)
                    );
                }
                $investorShares->internal_wallet += $requestedToken;
            }

            $investorShares->save();
            DB::commit();

            $this->addLoggingMessage('token_transfer_completed',[
                'user' => $user,
                'userContract' => $userContract,
                'userToken' => $userToken,
                'requested_tokens' => $requestedToken,
                'txn' => $txn->id,
                'type'=> 'info'
            ]);


            // Must stay before the return: this used to sit after it, so the
            // investor was never told their tokens had arrived.
            $this->addTokenTransferedNotification($userToken, $isAutomaticPayment);

            return [
                'status' => 'success',
                'userTokenTransactionData' => $txnData
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addLoggingMessage('token_transfer_failed', [
                'exception' => $e,
                'user' => $userToken->user ?? null,
                'userContract' => $userToken->userContract ?? null,
                'buyRequest' => $userToken ?? null,
                'isAutomaticPayment' => $isAutomaticPayment ?? null,
                'type' => 'error',
                'note' => $e->getMessage(),
                'userNote' => 'Internal Application Error'
            ]);
            $userToken->status = 'failed';
            $userToken->note = "Internal Application Error";
            $userToken->save();
            throw $e;
        }
    }

    public function isTokenAvailable($userToken,$userContract = null ){
        $userToken->refresh();
        $userContract = empty($userContract) ?  $userToken->userContract : $userContract;
        if (!$userContract) {
            throw new \Exception("User contract not found.");
        }
        $userContract->refresh();
        if( (float) $userToken->token_acquire  > (float) $userContract->tokenbalance)
            throw new \Exception("Issuficient Token supply.");
        return true;
    }

    protected function sendNotification($userId, $title, $description, $type = 'info'){
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'notification_type' => $type
        ]);
    }

    private function notifySavePaymentEvent(bool $isAutomaticPayment, $userContract, $buyRequest = null){
        if ($isAutomaticPayment) {
            $this->sendNotification(
                $buyRequest->user->id,
                'Automatic Payment Successful',
                "Your buy request transaction was successful for {$userContract->property->propertyName}.",
                'success'
            );
            $this->addLoggingMessage('automatic_logged_on_db', [
                'buyRequest' => $buyRequest,
                'user'       => $buyRequest->user,
                'type'       => 'info'
            ]);
        } else {
            $this->sendNotification(
                $userContract->issued_by,
                'Pending Payments',
                "Your buy request is pending approval for {$userContract->property->propertyName}.",
                'info'
            );
            if($buyRequest){
                $this->addLoggingMessage('manaul_payment_completed', [
                    'buyRequest' => $buyRequest,
                    'user'       => $buyRequest->user,
                    'type'       => 'info'
                ]);
            }
        }
    }

    private function addLoggingMessage($event, $data){
        $logData = null;
        switch ($event) {
            case 'manual_payment_approved':
                $logData = [
                    "event" => "manual_payment_approved",
                    "investor_id" => $data['user']->id ?? null,
                    "investor_name" => $data['user']->name ?? null,
                    "buy_request_id" => $data['buyRequest']->id ?? null,
                    "amount" => $data['buyRequest']->deal_amount ?? null,
                    "currency" => $data['buyRequest']->payment_by ?? null,
                    "payment_ref" => $data['buyRequest']->payment_reference_id ?? null,
                    "wallet_type" => $data['buyRequest']->wallet_type ?? null,
                    "token_quantity" => $data['buyRequest']->token_acquired ?? null,
                    "status" => $data['buyRequest']->status ?? null,
                    "timestamp" => now()->setTimezone('GMT'),
                    "note" => "Issuer approved payment and triggered token transfer"
                ];
                logInfo('manual_payment_approved', $logData);

                break;

            case 'automatic_logged_on_db':
                $logData = [
                    "event" => "automatic_logged_on_db",
                    "investor_id" => $data['user']->id ?? null,
                    "investor_name" => $data['user']->name ?? null,
                    "buy_request_id" => $data['buyRequest']->id ?? null,
                    "amount" => $data['buyRequest']->deal_amount ?? null,
                    "currency" => $data['buyRequest']->payment_by ?? null,
                    "payment_ref" => $data['buyRequest']->payment_reference_id ?? null,
                    "wallet_type" => $data['buyRequest']->wallet_type ?? null,
                    "token_quantity" => $data['buyRequest']->token_acquired ?? null,
                    "status" => $data['buyRequest']->status ?? null,
                    "timestamp" => now()->setTimezone('GMT'),
                    "note" => "Automatic transaction successfully saved on DB"
                ];
                logInfo('automatic_logged_on_db', $logData);
                break;

            case 'manaul_payment_completed':
                $logData = [
                    "event" => "manaul_payment_completed",
                    "investor_id" => $data['user']->id ?? null,
                    "investor_name" => $data['user']->name ?? null,
                    "buy_request_id" => $data['buyRequest']->id ?? null,
                    "amount" => $data['buyRequest']->deal_amount ?? null,
                    "currency" => $data['buyRequest']->payment_by ?? null,
                    "payment_ref" => $data['buyRequest']->payment_reference_id ?? null,
                    "wallet_type" => $data['buyRequest']->wallet_type ?? null,
                    "token_quantity" => $data['buyRequest']->token_acquired ?? null,
                    "status" => $data['buyRequest']->status ?? null,
                    "timestamp" => now()->setTimezone('GMT'),
                    "note" => "Investor Raised the Buy Request via Manual Payment"
                ];
                logInfo('manaul_payment_completed', $logData);
                break;

            case 'token_transfer_validation_failed':
                $logData=[
                    'event' => 'token_transfer_validation_failed',
                    'error_message' => $data['exception']->getMessage() ?? null,
                    'line' => $data['exception']->getLine() ?? null,
                    'file' => $data['exception']->getFile() ?? null,
                    'trace' => $data['exception']->getTraceAsString() ?? null,
                    'investor_id' => $data['user']->id ?? null,
                    "investor_name" => $data['user']->name ?? null,
                    "buy_request_id" => $data['buyRequest']->id ?? null,
                    "amount" => $data['buyRequest']->deal_amount ?? null,
                    "currency" => $data['buyRequest']->payment_by ?? null,
                    "payment_ref" => $data['buyRequest']->payment_reference_id ?? null,
                    "wallet_type" => $data['buyRequest']->wallet_type ?? null,
                    "token_quantity" => $data['buyRequest']->token_acquired ?? null,
                    "status" => $data['buyRequest']->status ?? null,
                    'user_contract_id' => $data['userContract']->id ?? null,
                    'isAutomaticPayment' => $data['isAutomaticPayment'] ?? null,
                    'timestamp' => now()->setTimezone('GMT'),
                    'note' => 'Token transfer validation failed due to an exception.'
                ];
                logError('token_transfer_validation_failed', $logData);
                break;
            case 'token_transfer_started':
                $logData = [
                    'event' => 'token_transfer_started',
                    'investor_id' => $data['user']->id ?? null,
                    'investor_name' => $data['user']->name ?? null,
                    'user_contract_id' => $data['userContract']->id ?? null,
                    'user_token_id' => $data['userToken']->id ?? null,
                    'requested_tokens' => $data['userToken']->token_acquire ?? null,
                    'wallet_type' => $data['userToken']->wallet_type ?? null,
                    'timestamp' => now()->setTimezone('GMT'),
                    'note' => 'Token transfer process initiated.'
                ];
                logInfo('token_transfer_started', );
                break;

            case 'token_transfer_completed':
                $logData = [
                    'event' => 'token_transfer_completed',
                    'investor_id' => $data['user']->id ?? null,
                    'investor_name' => $data['user']->name ?? null,
                    'user_contract_id' => $data['userContract']->id ?? null,
                    'user_token_id' => $data['userToken']->id ?? null,
                    'tokens_transferred' => $data['requested_tokens'] ?? null,
                    'wallet_type' => $data['userToken']->wallet_type ?? null,
                    'transaction_id' => $data['txn']->id ?? null,
                    'timestamp' => now()->setTimezone('GMT'),
                    'note' => 'Token transfer process completed successfully.'
                ];

                logInfo('token_transfer_completed',$logData );
                break;

            case 'token_transfer_failed':
                $logData = [
                    'event' => 'token_transfer_failed',
                    'error_message' => $data['exception']->getMessage() ?? null,
                    'line' => $data['exception']->getLine() ?? null,
                    'file' => $data['exception']->getFile() ?? null,
                    'trace' => $data['exception']->getTraceAsString() ?? null,
                    'investor_id' => $data['user']->id ?? null,
                    'investor_name' => $data['user']->name ?? null,
                    'user_contract_id' => $data['userContract']->id ?? null,
                    'user_token_id' => $data['userToken']->id ?? null,
                    'requested_tokens' => $data['userToken']->token_acquire ?? null,
                    'timestamp' => now()->setTimezone('GMT'),
                    'note' => 'Token transfer process failed due to an exception.'
                ];
                logError('token_transfer_failed', $logData);
                break;
        }

        if (!empty($logData)) {
            AppFlowLog::create([
                'user_id' => $data['user']->id ?? null,
                'title' => $event,
                'process_id' => getProcessId() ?? null,
                'type' => $data['type'] ?? 'error',
                'logtext' => $logData['note'] ?? null,
                'meta' => json_encode($logData)
            ]);
        }
    }

    private function validateTokenTransfer($user, $userContract, $userToken){
        try {
            $isAutomaticPayment = (empty($user) && empty($userContract)) ? true : false;

            if (!$userToken) {
                throw new Exception("UserToken not found.");
            }

            $user = empty($user) ? $userToken->user : $user;
            if (!$user) {
                throw new Exception("User associated with token request not found.");
            }

            $userContract = empty($userContract) ? $userToken->usercontract : $userContract;
            if (!$userContract) {
                throw new Exception("UserContract associated with token request not found.");
            }

            // Refresh contract data and check token supply
            $userContract->refresh();
            $requestedToken = $userToken->token_acquire;
            if ($isAutomaticPayment && $userContract->tokenbalance < $requestedToken) {
                throw new Exception("Tokens supply not available.");
            }



            // Return validated data if all checks pass
            return [
                'user' => $user,
                'userContract' => $userContract,
                'userToken' => $userToken,
                'isAutomaticPayment' => $isAutomaticPayment
            ];

        } catch (\Throwable $e) {
            $this->addLoggingMessage('token_transfer_validation_failed', [
                'exception' => $e,
                'user' => $userToken->user ?? null,
                'userContract' => $userToken->userContract ?? null,
                'buyRequest' => $userToken ?? null,
                'isAutomaticPayment' => $isAutomaticPayment ?? null,
                'type' => 'failed'
            ]);
            $userToken->status = 'failed';
            $userToken->note = json_encode([
                'event' => 'token_transfer_validation_failed',
                'error' => $e->getMessage()
            ]);
            $userToken->save();
            throw $e;
        }
    }
}
