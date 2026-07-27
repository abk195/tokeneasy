<?php

namespace App\Services;

use App\IssuerTokenRequest;
use App\KeystoreModel;
use App\User;
use App\UserContract;
use App\Property;

class TokenizerService
{
    /**
     * Resolve contract address from node deploy response shapes used across the app.
     *
     * @param  array  $details
     * @return string|null
     */
    public static function extractContractAddress(array $details)
    {
        $candidates = [
            data_get($details, 'contract.contract.address'),
            data_get($details, 'contract.address'),
            data_get($details, 'contract_address'),
            data_get($details, 'address'),
        ];

        foreach ($candidates as $address) {
            if (is_string($address) && trim($address) !== '') {
                return trim($address);
            }
        }

        return null;
    }

    public function deployToken($id)
    {
        try {
            $issuer_token = IssuerTokenRequest::with('property', 'blockchain')->findOrFail($id);

            $token_network = $issuer_token->coin;
            $token_name = $issuer_token->name;
            $token_symbol = $issuer_token->symbol;
            $token_value = $issuer_token->usdvalue;
            $token_supply = $issuer_token->supply;
            $token_decimal = $issuer_token->decimal;
            $user_id = $issuer_token->user_id;

            $user = User::findOrFail($user_id);

            if (!$issuer_token->property || !$issuer_token->property->keystore_id) {
                return ['hasError' => true, 'message' => 'Keystore not linked to this property.'];
            }

            if (!$issuer_token->blockchain) {
                return ['hasError' => true, 'message' => 'Chain Type not found'];
            }

            $keystore = KeystoreModel::find($issuer_token->property->keystore_id);
            if (!$keystore) {
                return ['hasError' => true, 'message' => 'Keystore not found.'];
            }

            $payload = [
                "filename" => $keystore->keystore_file_path,
                "password" => $keystore->getPassward()
            ];
            $result = callNodeOperations('read', $payload);
            if ($result['status'] !== 'success') {
                return ['hasError' => true, 'message' => 'Failed to retrieve private key.'];
            }

            $response = callNodeOperations('getBalance', [
                'address' => $keystore->public_address,
                'chain' => $issuer_token->blockchain->abbreviation
            ]);

            if (isset($response['status']) && $response['status'] !== 'success') {
                return ['hasError' => true, 'message' => 'Failed to retrieve balance.'];
            } elseif ($response['balance'] <= 0) {
                return ['hasError' => true, 'message' => 'Insufficient balance.'];
            }

            $payload = [
                "chain" => $issuer_token->blockchain ? $issuer_token->blockchain->abbreviation : null,
                "name" => $token_name,
                "decimals" => $token_decimal,
                "symbol" => $token_symbol,
                "totalSupply" => $token_supply,
                "privateKey" => $result['privatekey']
            ];

            if ($issuer_token->property->token_type == 3) {
                $details = callNodeOperations('deployUtilityToken', $payload);
            } else {
                $details = callNodeOperations('deploy', $payload);
            }

            if (!isset($details['status']) || $details['status'] !== 'success') {
                if (isset($details['status']) && $details['status'] === 'failed') {
                    return ['hasError' => true, 'message' => 'Insufficient ' . $token_network . ' balance!!'];
                }
                return ['hasError' => true, 'message' => 'Node server error'];
            }

            $contractAddress = self::extractContractAddress($details);
            if (!$contractAddress) {
                logInfo('Token deploy missing contract address in node response', [
                    'issuer_token_id' => $id,
                    'response' => $details,
                ]);
                return [
                    'hasError' => true,
                    'message' => 'Deployment succeeded but contract address was missing from node response.',
                ];
            }

            $token = new UserContract();
            $token->property_id = $issuer_token->property_id;
            $token->user_id = $user->id;
            $token->coin = $issuer_token->blockchain ? $issuer_token->blockchain->blockchain_name : null;
            $token->blockchain_id = $issuer_token->blockchain ? $issuer_token->blockchain->id : null;
            $token->issued_by = $issuer_token->user_id;
            $token->tokenname = $token_name;
            $token->tokensymbol = $token_symbol;
            $token->tokenvalue = $token_value;
            $token->tokensupply = $token_supply;
            $token->tokenbalance = $token_supply;
            $token->contract_address = $contractAddress;
            $token->decimal = $token_decimal;
            $token->token_image = $issuer_token->token_image;
            $token->banner_image = $issuer_token->banner_image;
            $token->token_type = $issuer_token->token_type;
            $token->status = 1;
            $token->save();

            Property::where('id', $issuer_token->property_id)->update(['status' => 'active']);
            $issuer_token->status = 'live';
            $issuer_token->token_deploy_status = 1;
            $issuer_token->save();

            return [
                'hasError' => false,
                'message' => 'Token added successfully',
                'contract_address' => $contractAddress,
            ];
        } catch (\Throwable $e) {
            logException($e, ['issuer_token_id' => $id]);

            return ['hasError' => true, 'message' => 'Something went wrong. Please try again later.'];
        }
    }
}
