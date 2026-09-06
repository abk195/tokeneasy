<?php

namespace Tests\Support;

use App\BlockchainModel as Blockchain;
use App\BlockchainStablecoin;
use App\IssuerTokenRequest;
use App\KeystoreModel;
use App\Property;
use App\Stablecoin;
use App\User;
use App\UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Base class for the client acceptance scenarios.
 *
 * Every test runs inside a transaction against the pre-migrated tokeneasy_test
 * schema, and talks to a stub node service rather than a live chain.
 */
abstract class ScenarioTestCase extends TestCase
{
    use DatabaseTransactions;

    /** Token type discriminators used on properties/contracts. */
    const TYPE_PROPERTY = 1;
    const TYPE_RWA      = 2;
    const TYPE_UTILITY  = 3;

    /** @var \App\BlockchainModel */
    protected $blockchain;

    /** @var \App\BlockchainStablecoin */
    protected $blockchainStablecoin;

    protected function setUp(): void
    {
        parent::setUp();

        FakeNodeServer::reset();

        config([
            'app.nodeApp'  => FakeNodeServer::baseUrl(),
            'app.is_demo'  => true,
        ]);

        $this->seedChainReferenceData();
    }

    /**
     * Blockchain + the platform stablecoin that storeDefaultPayments() expects
     * to find. config('token.token.name') is the title it looks up.
     */
    protected function seedChainReferenceData()
    {
        $this->blockchain = Blockchain::create([
            'blockchain_name' => 'Ethereum',
            'abbreviation'    => 'ETH',
            'chain_id'        => 11155111,
            'link'            => 'https://sepolia.etherscan.io/',
        ]);

        $stablecoin = Stablecoin::create(['title' => config('token.token.name')]);

        $this->blockchainStablecoin = BlockchainStablecoin::create([
            'blockchain_id' => $this->blockchain->id,
            'stablecoin_id' => $stablecoin->id,
            'address'       => config('token.token.contract_address'),
            'decimals'      => 18,
        ]);
    }

    protected function makeIssuer(array $attributes = []): User
    {
        return User::create(array_merge([
            'name'        => 'Issuer ' . uniqid(),
            'email'       => 'issuer' . uniqid() . '@example.test',
            'password'    => bcrypt('secret1234'),
            'user_type'   => User::USER_TYPE_ISSUER,
            'kyc'         => 1,
            'verified'    => 1,
            'approved'    => 1,
            'eth_address' => '0xIssuerEthAddress0000000000000000000001',
        ], $attributes));
    }

    protected function makeInvestor(array $attributes = []): User
    {
        return User::create(array_merge([
            'name'        => 'Investor ' . uniqid(),
            'email'       => 'investor' . uniqid() . '@example.test',
            'password'    => bcrypt('secret1234'),
            'user_type'   => User::USER_TYPE_INVESTOR,
            'kyc'         => 1,
            'verified'    => 1,
            'approved'    => 1,
            'eth_address' => '0xInvestorEthAddress00000000000000000001',
        ], $attributes));
    }

    protected function makeKeystore(User $issuer, array $attributes = []): KeystoreModel
    {
        return KeystoreModel::create(array_merge([
            'user_id'            => $issuer->id,
            'title'              => 'Deployment key',
            'keystore_file_path' => 'keystore-' . uniqid() . '.json',
            'encrypted_password' => 'keystore-password',
            'public_address'     => '0xIssuerPublicAddress00000000000000000001',
        ], $attributes));
    }

    /**
     * A property in the state storeProperty() leaves it in: created, still
     * "pending", with a keystore and blockchain attached.
     */
    protected function makeProperty(User $issuer, int $tokenType, KeystoreModel $keystore = null, array $attributes = []): Property
    {
        $keystore = $keystore ?: $this->makeKeystore($issuer);

        $property = Property::create(array_merge([
            'user_id'          => $issuer->id,
            'keystore_id'      => $keystore->id,
            'propertyName'     => 'Asset ' . uniqid(),
            'propertyLocation' => 'Test City',
            'propertyType'     => 'Commercial',
            'totalDealSize'    => '1000000',
            'dividend'         => 5,
            'token_type'       => $tokenType,
            'status'           => 'pending',
        ], $attributes));

        $property->blockchain_id = $this->blockchain->id;
        $property->save();

        return $property->fresh();
    }

    /**
     * The IssuerTokenRequest row storeProperty() writes just before it calls
     * TokenizerService::deployToken().
     */
    protected function makeTokenRequest(User $issuer, Property $property, array $attributes = []): IssuerTokenRequest
    {
        $request = new IssuerTokenRequest();
        $request->user_id             = $issuer->id;
        $request->property_id         = $property->id;
        $request->blockchain_id       = $this->blockchain->id;
        $request->coin                = $this->blockchain->blockchain_name;
        $request->name                = $attributes['name'] ?? 'Test Token';
        $request->symbol              = $attributes['symbol'] ?? 'TSTK';
        $request->usdvalue            = $attributes['usdvalue'] ?? 10;
        $request->supply              = $attributes['supply'] ?? 1000;
        $request->decimal             = $attributes['decimal'] ?? 18;
        $request->security_type       = $attributes['security_type'] ?? 'erc20';
        $request->status              = $attributes['status'] ?? 'pending';
        $request->token_deploy_status = (int) ($attributes['token_deploy_status'] ?? 0);
        $request->save();

        return $request->fresh();
    }

    /**
     * Runs a full deployment through TokenizerService and returns the resulting
     * live contract. Used by the scenarios that start from a deployed asset.
     */
    protected function deployAsset(User $issuer, int $tokenType, array $tokenAttributes = []): UserContract
    {
        $property = $this->makeProperty($issuer, $tokenType);
        $request  = $this->makeTokenRequest($issuer, $property, $tokenAttributes);

        $result = (new \App\Services\TokenizerService())->deployToken($request->id);

        $this->assertFalse(
            (bool) $result['hasError'],
            'Fixture deployment failed: ' . ($result['message'] ?? '')
        );

        return UserContract::where('property_id', $property->id)->firstOrFail();
    }

    /**
     * Assert that a transfer for $amount of $contract's token reached the chain
     * addressed to $to.
     */
    protected function assertOnChainTransfer(UserContract $contract, string $to, $amount, string $because = '')
    {
        $matches = array_filter(FakeNodeServer::requests('/transfer'), function ($request) use ($contract, $to, $amount) {
            $body = $request['body'] ?? [];

            return ($body['contract_address'] ?? null) === $contract->contract_address
                && ($body['to'] ?? null) === $to
                && (float) ($body['amount'] ?? 0) === (float) $amount;
        });

        $this->assertNotEmpty($matches, $because ?: sprintf(
            'Expected an on-chain transfer of %s of %s to %s. Transfers seen: %s',
            $amount,
            $contract->contract_address,
            $to,
            json_encode(array_column(FakeNodeServer::requests('/transfer'), 'body'))
        ));
    }
}
