<?php
namespace App\Services;

use App\UserToken;
use App\UserContract;
use App\InvestorShares;
use App\Exceptions\CustomException;
use App\WhiteListedWalletAddress;
use App\Models\EWTransferLogsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\AppFlowLog;

class InvestorService
{
    public function whitelistAddress($user_id, $contract_id, $token_id, $address){
        $user = Auth()->user();

        $userToken = UserToken::find($token_id);
        $userContract = UserContract::find($contract_id);

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

        $privateKey = $property->getPrivateKey();

        $existing = WhiteListedWalletAddress::where('user_id', $user->id)
            ->where('contract_id', $userContract->id)
            ->where('wallet_address', 'like', '%' . $address . '%')
            ->first();

        if ($existing) {
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
            'address' => $address,
            'amount' => 100,
            'receiveDayLimit' => 100000000,
            'sendDayLimit' => 100000000
        ];

        $result = callNodeOperations('whitelist', $payload);

        if (isset($result['status']) && $result['status'] == 'success') {
            WhiteListedWalletAddress::create([
                "user_id" => $user->id,
                "whitelisted_by" => $property->user_id,
                "status" => 1,
                "tx_hash" => $result['txHash'],
                "contract_id" => $userContract->id,
                "wallet_address" => $address
            ]);

            $wallets = $this->getWhitelistedAddresses($user->id, $userContract->id);

            return [
                'status' => true,
                'message' => 'Address successfully whitelisted.',
                'data' => $wallets,
                'code' => 201
            ];
        }

        return [
            'status' => false,
            'message' => 'Failed to whitelist address. Node server response: ' . ($result['message'] ?? json_encode($result)),
            'code' => 500
        ];
    }

    public function getWhitelistedAddresses($user_id, $contract_id){
        $wallets =  WhiteListedWalletAddress::where('user_id', $user_id)
            ->where('contract_id', $contract_id)
            ->get(['id', 'wallet_address']);

        return $wallets;
    }

    public function transferTokensToEW($user_id, $wallet, $userContract, $tokenCount){
        $this->logInvestorTransferEvent('ex_wallet_token_transfer_started', [
            'user_id' => $user_id,
            'wallet' => $wallet,
            'userContract' => $userContract,
            'tokenCount' => $tokenCount,
        ]);
    
        $txLogData = [
            'user_id' => $user_id,
            'wallet_id' => $wallet->id,
            'user_contract_id' => $userContract->id,
            'token_count' => $tokenCount,
            'status' => 'pending',
            'note' => 'Initial token transfer',
        ];
    
        DB::beginTransaction();
    
        try {
            $investorTokenWallets = InvestorShares::where('user_id', $user_id)
                ->where('user_contract_id', $userContract->id)
                ->lockForUpdate()
                ->first();
    
            // CustomException is (message, array $data, int $statusCode) — the
            // code is the third argument, not the second.
            if (!$investorTokenWallets) {
                throw new CustomException('Investor token wallet not found.', [], 404);
            }

            if ($investorTokenWallets->internal_wallet < $tokenCount) {
                throw new CustomException(
                    'Insufficient token in the Internal Wallet. Available: ' . $investorTokenWallets->internal_wallet,
                    [],
                    400
                );
            }
    
            $payload = [
                "amount"           => $tokenCount,
                "chain"            => $userContract->blockchain->abbreviation,
                "privateKey"       => $userContract->property->getPrivateKey(),
                "contract_address" => $userContract->contract_address,
                "to"               => $wallet->wallet_address,
            ];
    
            $response = callNodeOperations('transfer', $payload);
    
            if (!empty($response['status']) && $response['status'] === 'success') {
                $txLogData['transaction_hash'] = $response['txHash'];
                $txLogData['status'] = 'success';
            }
    
            $txLog = EWTransferLogsModel::create($txLogData);
    
            $investorTokenWallets->internal_wallet -= $tokenCount;
            $investorTokenWallets->external_wallet += $tokenCount;
            $investorTokenWallets->save();
    
            $wallet->balance += $tokenCount;
            $wallet->save();
    
            DB::commit();
    
            $this->logInvestorTransferEvent('ex_wallet_token_transfer_success', [
                'user_id' => $user_id,
                'wallet' => $wallet,
                'userContract' => $userContract,
                'tokenCount' => $tokenCount,
                'txHash' => $txLog->transaction_hash ?? null,
                'status' => $txLog->status ?? null,
            ]);
    
            return [
                'status' => 'success',
                'userTokenTransactionData' => $txLog
            ];
    
        } catch (\Throwable $e) {
            DB::rollBack();
    
            $this->logInvestorTransferEvent('ex_wallet_token_transfer_failed', [
                'user_id' => $user_id,
                'wallet' => $wallet,
                'userContract' => $userContract,
                'tokenCount' => $tokenCount,
                'exception' => $e,
            ]);
    
            throw $e;
        }
    }
    

    private function logInvestorTransferEvent(string $event, array $data){
        $logData = null;

        switch ($event) {
            case 'ex_wallet_token_transfer_started':
                $logData = [
                    "event" => $event,
                    "investor_id" => $data['user_id'] ?? null,
                    "wallet_id" => $data['wallet']->id ?? null,
                    "user_contract_id" => $data['userContract']->id ?? null,
                    "token_count" => $data['tokenCount'] ?? null,
                    "timestamp" => now()->setTimezone('GMT'),
                    "note" => "External wallet token transfer process initiated.",
                    "type" => "info"
                ];
                logInfo($event, $logData);
                break;

            case 'ex_wallet_token_transfer_success':
                $logData = [
                    "event" => $event,
                    "investor_id" => $data['user_id'] ?? null,
                    "wallet_id" => $data['wallet']->id ?? null,
                    "user_contract_id" => $data['userContract']->id ?? null,
                    "token_count" => $data['tokenCount'] ?? null,
                    "transaction_hash" => $data['txHash'] ?? null,
                    "status" => $data['status'] ?? 'success',
                    "timestamp" => now()->setTimezone('GMT'),
                    "note" => "Tokens successfully transferred to external wallet.",
                    "type" => "info"
                ];
                logInfo($event, $logData);
                break;

            case 'ex_wallet_token_transfer_failed':
                $logData = [
                    "event" => $event,
                    "investor_id" => $data['user_id'] ?? null,
                    "wallet_id" => $data['wallet']->id ?? null,
                    "user_contract_id" => $data['userContract']->id ?? null,
                    "token_count" => $data['tokenCount'] ?? null,
                    "error_message" => $data['exception']->getMessage() ?? null,
                    "file" => $data['exception']->getFile() ?? null,
                    "line" => $data['exception']->getLine() ?? null,
                    "trace" => $data['exception']->getTraceAsString() ?? null,
                    "timestamp" => now()->setTimezone('GMT'),
                    "note" => "Token transfer to external wallet failed due to an exception.",
                    "type" => "error"
                ];
                logError($event, $logData);
                break;
        }

        if (!empty($logData)) {
            AppFlowLog::create([
                'user_id' => $logData['investor_id'],
                'title' => $event,
                'type' => $logData['type'],
                'logtext' => $logData['note'],
                'meta' => json_encode($logData),
                'process_id' => getProcessId() ?? null
            ]);
        }
    }
}
