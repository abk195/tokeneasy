<?php

namespace Tests\Feature\ClientScenarios;

use App\Enums\PaymentMethod;
use App\InvestorShares;
use App\Services\InvestorService;
use App\Services\TokenPaymentService;
use App\UserToken;
use App\WhiteListedWalletAddress;
use Tests\Support\FakeNodeServer;
use Tests\Support\ScenarioTestCase;

/**
 * SCENARIO 4 — "Test buying with internal custodian wallet and external
 * custodian wallets. For external custodian wallets, investors can whitelist his
 * wallets. Also make sure while buying utility tokens then whitelist of the
 * address is not required."
 */
class Scenario04CustodyAndWhitelistTest extends ScenarioTestCase
{
    /** @var \App\User */
    private $issuer;

    /** @var \App\User */
    private $investor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->issuer   = $this->makeIssuer();
        $this->investor = $this->makeInvestor();
    }

    // ------------------------------------------------------------ whitelist

    /** @test */
    public function an_investor_can_whitelist_an_external_wallet_for_a_security_token()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);

        $response = $this->actingAs($this->investor)->post(
            route('whiteListedAddress', [$this->investor->id, $contract->id]),
            ['address' => '0xInvestorExternalWallet0000000000000001']
        );

        $response->assertStatus(200);

        $wallet = WhiteListedWalletAddress::where('user_id', $this->investor->id)
            ->where('contract_id', $contract->id)
            ->first();

        $this->assertNotNull($wallet, 'The wallet was not recorded as whitelisted.');
        $this->assertSame('0xInvestorExternalWallet0000000000000001', $wallet->wallet_address);
        $this->assertSame('0xwhitelist-tx', $wallet->tx_hash);
        $this->assertSame('1', (string) $wallet->status);
    }

    /** @test */
    public function whitelisting_registers_the_address_on_the_token_contract()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);

        $this->actingAs($this->investor);
        (new InvestorService())->whitelistAddress(
            $this->investor->id,
            $contract->id,
            null,
            '0xInvestorExternalWallet0000000000000001'
        );

        $call = FakeNodeServer::lastRequest('/whitelist');

        $this->assertNotNull($call, 'No whitelist transaction was sent to the chain.');
        $this->assertSame($contract->contract_address, $call['body']['contract_address']);
        $this->assertSame('0xInvestorExternalWallet0000000000000001', $call['body']['address']);
        $this->assertSame($this->blockchain->abbreviation, $call['body']['chain']);
        $this->assertSame('0xissuer-private-key', $call['body']['privateKey']);
    }

    /** @test */
    public function whitelisting_the_same_address_twice_does_not_send_a_second_transaction()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);
        $service  = new InvestorService();

        $this->actingAs($this->investor);
        $service->whitelistAddress($this->investor->id, $contract->id, null, '0xInvestorExternalWallet0000000000000001');
        $second = $service->whitelistAddress($this->investor->id, $contract->id, null, '0xInvestorExternalWallet0000000000000001');

        $this->assertTrue($second['status']);
        $this->assertSame(1, FakeNodeServer::callCount('/whitelist'));
        $this->assertSame(1, WhiteListedWalletAddress::where('contract_id', $contract->id)->count());
    }

    /**
     * The duplicate check uses  where('wallet_address', 'like', '%'.$address.'%'),
     * so an address that merely contains an already-whitelisted address as a
     * substring is treated as the same wallet and is never sent to the chain.
     *
     * @test
     */
    public function a_different_address_is_not_mistaken_for_an_already_whitelisted_one()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);
        $service  = new InvestorService();

        $this->actingAs($this->investor);
        $service->whitelistAddress($this->investor->id, $contract->id, null, '0xABC');
        $service->whitelistAddress($this->investor->id, $contract->id, null, '0xABC123');

        $this->assertSame(
            2,
            WhiteListedWalletAddress::where('contract_id', $contract->id)->count(),
            'A distinct wallet address was silently swallowed by the substring duplicate check.'
        );
    }

    /** @test */
    public function a_failed_whitelist_transaction_is_not_recorded_as_whitelisted()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);

        FakeNodeServer::setResponse('/whitelist', ['status' => 'failed', 'message' => 'rejected']);

        $response = $this->actingAs($this->investor)->post(
            route('whiteListedAddress', [$this->investor->id, $contract->id]),
            ['address' => '0xInvestorExternalWallet0000000000000001']
        );

        $response->assertStatus(200);

        $this->assertSame(
            0,
            WhiteListedWalletAddress::where('contract_id', $contract->id)->count(),
            'A wallet was marked whitelisted even though the chain rejected the transaction.'
        );
    }

    /** @test */
    public function an_investor_only_sees_their_own_whitelisted_wallets()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);
        $other    = $this->makeInvestor();

        WhiteListedWalletAddress::create([
            'user_id'        => $other->id,
            'whitelisted_by' => $this->issuer->id,
            'status'         => 1,
            'tx_hash'        => '0xtx',
            'contract_id'    => $contract->id,
            'wallet_address' => '0xSomeoneElsesWallet00000000000000000001',
        ]);

        $wallets = (new InvestorService())->getWhitelistedAddresses($this->investor->id, $contract->id);

        $this->assertCount(0, $wallets);
    }

    // ----------------------------------------------------- external custody

    /** @test */
    public function an_external_custody_purchase_is_bound_to_the_chosen_whitelisted_wallet()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY, ['supply' => 1000]);
        $wallet   = $this->whitelist($contract, '0xInvestorExternalWallet0000000000000001');

        $userToken = $this->buyThrough($contract, 'external', $wallet, 20);

        $this->assertSame('external', $userToken->wallet_type);
        $this->assertSame($wallet->id, (int) $userToken->wallet_id);
        $this->assertSame($wallet->wallet_address, $userToken->receiver_wallet_address);

        $this->assertOnChainTransfer($contract, $wallet->wallet_address, 20);
        $this->assertEquals(20, InvestorShares::where('user_id', $this->investor->id)->first()->external_wallet);
    }

    /** @test */
    public function a_security_token_cannot_be_sent_to_a_wallet_that_was_never_whitelisted()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);
        $service  = new TokenPaymentService();

        $service->handleUpsertRequest($this->investor, $contract, [
            'currentStep' => 1, 'tokens' => 10, 'payby' => 'ETH',
        ]);

        $userToken = UserToken::where('user_id', $this->investor->id)->firstOrFail();

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $service->setCustody($contract, $userToken, [
            'custody'               => 'external',
            'whitelisted_wallet_id' => 999999,
        ]);
    }

    // ----------------------------------------------------- internal custody

    /** @test */
    public function an_internal_custody_purchase_credits_the_platform_wallet_without_touching_the_chain()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY, ['supply' => 1000]);

        $userToken = $this->buyThrough($contract, 'internal', null, 35);

        $this->assertSame('internal', $userToken->wallet_type);
        $this->assertNull($userToken->wallet_id);
        $this->assertSame($contract->contract_address, $userToken->receiver_wallet_address);

        $shares = InvestorShares::where('user_id', $this->investor->id)->firstOrFail();
        $this->assertEquals(35, $shares->internal_wallet);
        $this->assertEquals(0, $shares->external_wallet);
        $this->assertSame(0, FakeNodeServer::callCount('/transfer'));
    }

    /** @test */
    public function internal_custody_does_not_require_a_whitelisted_wallet()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);

        $this->buyThrough($contract, 'internal', null, 10);

        $this->assertSame(0, FakeNodeServer::callCount('/whitelist'));
    }

    // ------------------------------------------------------- utility tokens

    /** @test */
    public function buying_a_utility_token_to_an_external_wallet_does_not_require_whitelisting()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_UTILITY, ['supply' => 1000]);
        $address  = '0xUnwhitelistedInvestorWallet00000000001';

        $service = new TokenPaymentService();

        $service->handleUpsertRequest($this->investor, $contract, [
            'currentStep' => 1, 'tokens' => 15, 'payby' => 'ETH',
        ]);

        $userToken = UserToken::where('user_id', $this->investor->id)->firstOrFail();

        // No whitelisted_wallet_id: the utility branch takes the raw address.
        $service->handleUpsertRequest($this->investor, $contract, [
            'currentStep'      => 2,
            'custody'          => 'external',
            'contract_address' => $address,
        ]);

        $userToken->refresh();

        $this->assertSame($address, $userToken->receiver_wallet_address);
        $this->assertSame(
            0,
            FakeNodeServer::callCount('/whitelist'),
            'A utility token purchase triggered a whitelist transaction.'
        );
    }

    /** @test */
    public function a_utility_token_purchase_transfers_to_the_investors_own_address()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_UTILITY, ['supply' => 1000]);
        $address  = '0xUnwhitelistedInvestorWallet00000000001';

        $service = new TokenPaymentService();

        $service->handleUpsertRequest($this->investor, $contract, ['currentStep' => 1, 'tokens' => 15, 'payby' => 'ETH']);
        $service->handleUpsertRequest($this->investor, $contract, [
            'currentStep' => 2, 'custody' => 'external', 'contract_address' => $address,
        ]);
        $service->handleUpsertRequest($this->investor, $contract, [
            'currentStep'         => 3,
            'payment_method'      => PaymentMethod::BANK_TRANSFER,
            'selected_payment_id' => 1,
            'payment_reference'   => 'REF-UTIL-1',
            'payment_proof'       => null,
        ]);

        $this->assertOnChainTransfer($contract, $address, 15);
        $this->assertSame(0, FakeNodeServer::callCount('/whitelist'));
    }

    /**
     * addNonWhitelistedWallet() registers the utility buyer's address locally so
     * the transfer step can resolve a wallet record for it.
     *
     * @test
     */
    public function a_utility_buyers_address_is_registered_locally_without_a_chain_whitelist()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_UTILITY, ['supply' => 1000]);
        $address  = '0xUnwhitelistedInvestorWallet00000000001';

        $service = new TokenPaymentService();
        $service->handleUpsertRequest($this->investor, $contract, ['currentStep' => 1, 'tokens' => 5, 'payby' => 'ETH']);
        $service->handleUpsertRequest($this->investor, $contract, [
            'currentStep' => 2, 'custody' => 'external', 'contract_address' => $address,
        ]);

        $userToken = UserToken::where('user_id', $this->investor->id)->firstOrFail();

        $service->savePaymentDetails($userToken, [
            'payment_method'      => PaymentMethod::BANK_TRANSFER,
            'selected_payment_id' => 1,
            'payment_reference'   => 'REF-UTIL-2',
            'payment_proof'       => null,
        ], $contract);

        $wallet = WhiteListedWalletAddress::where('user_id', $this->investor->id)
            ->where('contract_id', $contract->id)
            ->where('wallet_address', $address)
            ->first();

        $this->assertNotNull($wallet, 'The utility buyer address was not registered locally.');
        $this->assertSame('0', (string) $wallet->tx_hash, 'A local-only registration should carry no chain hash.');
        $this->assertSame($wallet->id, (int) $userToken->fresh()->wallet_id);
    }

    // ----------------------------------------------------------------- setup

    private function whitelist($contract, string $address): WhiteListedWalletAddress
    {
        return WhiteListedWalletAddress::create([
            'user_id'        => $this->investor->id,
            'whitelisted_by' => $this->issuer->id,
            'status'         => 1,
            'tx_hash'        => '0xwhitelist-tx',
            'contract_id'    => $contract->id,
            'wallet_address' => $address,
        ]);
    }

    private function buyThrough($contract, string $custody, $wallet, $tokens): UserToken
    {
        $service = new TokenPaymentService();

        $service->handleUpsertRequest($this->investor, $contract, [
            'currentStep' => 1, 'tokens' => $tokens, 'payby' => 'ETH',
        ]);

        $service->handleUpsertRequest($this->investor, $contract, [
            'currentStep'           => 2,
            'custody'               => $custody,
            'whitelisted_wallet_id' => $wallet ? $wallet->id : null,
            'contract_address'      => $wallet ? $wallet->wallet_address : null,
        ]);

        $service->handleUpsertRequest($this->investor, $contract, [
            'currentStep'         => 3,
            'payment_method'      => PaymentMethod::BANK_TRANSFER,
            'selected_payment_id' => 1,
            'payment_reference'   => 'REF-001',
            'payment_proof'       => null,
        ]);

        return UserToken::where('user_id', $this->investor->id)
            ->where('user_contract_id', $contract->id)
            ->firstOrFail();
    }
}
