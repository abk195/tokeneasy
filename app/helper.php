<?php

use GuzzleHttp\Client;
use App\IssuerBankAccounts;
use App\IssuerStablecoinWalletAddress;
use App\BlockchainModel as Blockchain;
use App\BlockchainStablecoin;
use Illuminate\Support\Facades\Log;




if (!function_exists('callNodeOperations')) {
    function callNodeOperations($operation, $data = []) {
        $client = new Client();
        $host = config('app.nodeApp');
        $headers = ['Accept' => 'application/json'];
        $url = null;
        $response = null;

        try {
            switch ($operation) {
                case 'generate':
                    $url = $host . '/generateNewPrivateKey';
                    $response = $client->get($url, ['headers' => $headers]);
                    break;

                case 'save':
                    $url = $host . '/savePrivateKeytodisk';
                    $response = $client->post($url, [
                        'json' => $data,
                        'headers' => $headers,
                    ]);
                    break;

                case 'read':
                    $url = $host . '/readPrivateKeyFromDisk';
                    $response = $client->post($url, [
                        'json' => $data,
                        'headers' => $headers,
                    ]);
                    break;

                case 'getPublicKey':
                    $url = $host . '/getPublicAddressFromPrivateKey';
                    $response = $client->post($url, [
                        'json' => $data,
                        'headers' => $headers,
                    ]);
                    break;

                case 'getBalance':
                    $url = $host . '/native_balance';
                    $response = $client->post($url, [
                        'json' => $data,
                        'headers' => $headers,
                    ]);
                    break;

                case 'deploy':
                    $url = $host . '/deploySecurityToken';
                    $response = $client->post($url, [
                        'json' => $data,
                        'headers' => $headers
                    ]);
                    break;

                case 'deployUtilityToken':
                    $url = $host . '/deployUtilityToken';
                    $response = $client->post($url, [
                        'json' => $data,
                        'headers' => $headers
                    ]);
                    break;

                case 'getTokenDetails':
                    if (!isset($data['chain']) || !isset($data['contractAddress'])) {
                        logInfo("callNodeOperations error: Missing chain or contractAddress", ['operation' => $operation, 'data' => $data]);
                        return ['error' => 'Missing chain or contractAddress'];
                    }

                    $queryParams = http_build_query([
                        'chain' => $data['chain'],
                        'contractAddress' => $data['contractAddress']
                    ]);
                    $url = $host . '/tokendetails?' . $queryParams;
                    $response = $client->get($url, ['headers' => $headers]);
                    break;

                case 'getKey':
                    $url = $host . '/getKey';
                    if (!isset($data['address']) || !isset($data['string'])) {
                        logInfo("callNodeOperations error: Missing address or string", ['operation' => $operation, 'data' => $data]);
                        return ['error' => 'Missing address or string'];
                    }
                    $response = $client->post($url, [
                        'json' => $data,
                        'headers' => $headers,
                    ]);
                    break;

                case 'whitelist':
                    $url = $host . '/whitelist';
                    $response = $client->post($url, [
                        'headers' => $headers,
                        'json' => $data,
                    ]);
                    break;

                case 'transfer':
                    $url = $host . '/transfer';
                    $response = $client->post($url, [
                        'headers' => $headers,
                        'json' => $data,
                    ]);
                    break;
                
                case 'getTokenBalance':
                    $url = $host . '/balance';
                    $response = $client->post($url, [
                        'headers' => $headers,
                        'json' => $data,
                    ]);
                    break;

                default:
                    logInfo("callNodeOperations: Invalid operation", ['operation' => $operation, 'data' => $data]);
                    return ['error' => 'Invalid operation'];
            }

            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            logInfo('callNodeOperations executed', [
                'operation' => $operation,
                'url' => $url,
                'request_data' => $data,
                'response' => $body
            ]);

            if (!isset($body['status']) || $body['status'] !== 'success') {
                throw new \Exception(
                    'Error from the node server for operation: ' . $operation .
                    ' | Request Data: ' . json_encode($data) .
                    ' | Response: ' . json_encode($body)
                );
            }

            return $body;

        } catch (\Exception $e) {
            logInfo('callNodeOperations exception', [
                'operation' => $operation,
                'url' => $url,
                'request_data' => $data,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}

if (!function_exists('fetchCryptoPrices')) {
    /**
     * USD price per coin from cryptocompare, or null when unavailable.
     *
     * Callers used to index the response directly, which raised
     * "Illegal string offset 'USD'" whenever the API answered with an error or
     * rate-limit body instead of prices. Because Laravel promotes that notice to
     * a thrown ErrorException, a third-party hiccup took down the investor
     * dashboard and the buy flow.
     *
     * Results are cached briefly: this is called on every dashboard load and
     * every buy request, which is what invites rate limiting in the first place.
     *
     * @return array<string,float>|null
     */
    function fetchCryptoPrices()
    {
        return \Illuminate\Support\Facades\Cache::remember('crypto_prices', now()->addMinutes(2), function () {
            // config(), not env(): env() returns null once config:cache has run.
            $apiKey = config('services.cryptocompare.key');

            $url = 'https://min-api.cryptocompare.com/data/pricemulti?fsyms=ETH,BNB,MATIC,USD&tsyms=USD';

            if (!empty($apiKey)) {
                $url .= '&api_key=' . urlencode($apiKey);
            }

            try {
                $response = (new Client())->get($url, ['timeout' => 8, 'connect_timeout' => 5]);
                $body     = json_decode($response->getBody()->getContents(), true);
            } catch (\Throwable $e) {
                logError('Crypto price lookup failed', ['error' => $e->getMessage()]);

                return null;
            }

            if (!is_array($body)) {
                logError('Crypto price lookup returned an unreadable body', ['body' => $body]);

                return null;
            }

            $prices = [];
            foreach ($body as $symbol => $quote) {
                // An error body looks like {"Response":"Error","Message":"..."},
                // so anything that is not a quote array is skipped.
                if (is_array($quote) && isset($quote['USD']) && is_numeric($quote['USD'])) {
                    $prices[$symbol] = (float) $quote['USD'];
                }
            }

            if (empty($prices)) {
                logError('Crypto price lookup returned no usable quotes', ['body' => $body]);

                return null;
            }

            return $prices;
        });
    }
}

if (!function_exists('currentCryptoPrices')) {
    /**
     * Prices for the buy flow.
     *
     * Throws rather than defaulting to zero: these values price a real purchase,
     * and a silent 0 would record a deal amount of nothing.
     *
     * @throws \RuntimeException
     */
    function currentCryptoPrices(){
        $prices = fetchCryptoPrices();

        if (empty($prices)) {
            throw new \RuntimeException(
                'Live crypto prices are unavailable right now, so this request cannot be priced. Please try again in a few minutes.'
            );
        }

        return $prices;
    }
}

if (!function_exists('getPaymentMethodsJson')) {
    function getPaymentMethodsJson($paymentMethods,$issuerId,$contractId){
        $paymentConfig = [];
        foreach($paymentMethods as $key =>$value){
            $config = getPaymentDetails($key, $issuerId,$contractId);
            if(empty($config))
                continue;

            $paymentConfig[$key] = [
                'label' => $value,
                'config'=> $config
            ];
        }
        return $paymentConfig;
    }
}

if(!function_exists('getPaymentDetails')){
    function getPaymentDetails($paymentMode, $issuerId, $contractId){
        $env = config('app.env');
        switch($paymentMode){

            case 'bank_transfer':
                $banks = IssuerBankAccounts::where('issuer_id', $issuerId)->where('user_contract_id',$contractId)->get()->keyBy('id')->toArray();
                return $banks;
            break; 
            case 'crypto_transfer':
                $cryptos = IssuerStablecoinWalletAddress::with([
                    'blockchainStablecoin',
                ])->where('issuer_id', $issuerId)
                ->where('user_contract_id', $contractId)
                ->whereNotNull('address')
                ->where('address', '!=', '')
                ->get();

                // Get the list of all blockchain_stablecoin_id
                $blockchainStablecoinIds = $cryptos->pluck('blockchain_stablecoin_id')->unique();

                // Get the related BlockchainStablecoin records
                $blockchainStableCoins = BlockchainStablecoin::with(['blockchain','stablecoin'])->whereIn('id', $blockchainStablecoinIds)->get()->keyBy('id')->toArray();
                $data = [];
                // Attach the BlockchainStablecoin record to each crypto
                foreach ($cryptos as $crypto) {
                    $crypto->bcStableCoin = $blockchainStableCoins[$crypto->blockchain_stablecoin_id];
                    $crypto = $crypto->toArray();
                    $data [$crypto['id']]=[
                            'id' => $crypto['id'],
                            'blockchain' => [
                                'id' => $crypto['bcStableCoin']['blockchain']['id'],
                                'blockchain_name' => $crypto['bcStableCoin']['blockchain']['blockchain_name'],
                                'chain_id' => $crypto['bcStableCoin']['blockchain']['chain_id'] ,
                                'link' => $crypto['bcStableCoin']['blockchain']['link']
                            ],
                            'stablecoin' => [
                                'id' => $crypto['bcStableCoin']['stablecoin']['id'],
                                'title' =>  $crypto['bcStableCoin']['stablecoin']['title'],
                                'token_address' => $crypto['bcStableCoin']['address'] ,
                                'decimals' => $crypto['bcStableCoin']['decimals'] 
                            ],
                            'address' =>  $crypto['address'],
                        ];
                }
                return $data;
            break;
                
        }
    }
}

if(!function_exists('logException')){
    function logException(\Throwable $e, array $context = []): void
    {
        $defaultContext = [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            // 'trace' => $e->getTraceAsString(),
        ];
        Log::error('Exception caught:', array_merge($defaultContext, $context));
    }
}


if (!function_exists('logInfo')) {
    function logInfo(string $message, array $context = []): void
    {
        Log::info($message, $context);
    }
}

if (!function_exists('logError')) {
    function logError(string $message, array $context = []): void
    {
        Log::error($message, $context);
    }
}

if (!function_exists('getProcessId')) {
    function getProcessId(): string
    {
        return app()->has('process_id') ? app('process_id') : 'no-process-id';
    }
}

if (!function_exists('checkGasFee')) {
    function checkGasFee($publicKey, $chain): bool
    {
        try {
            $response = callNodeOperations('getBalance', [
                'address' => $publicKey,
                'chain'   => $chain
            ]);
            // Return true only if status is 'success' and balance > 0
            return (isset($response['status']) && $response['status'] === 'success' && ($response['balance'] ?? 0) > 0);
        } catch (\Throwable $th) {
            // On any exception, return false
            return false;
        }
    }
}
