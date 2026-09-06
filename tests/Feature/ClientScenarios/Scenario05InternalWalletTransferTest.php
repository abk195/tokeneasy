<?php

namespace Tests\Feature\ClientScenarios;

use App\InvestorShares;
use App\Models\EWTransferLogsModel;
use App\Services\InvestorService;
use App\WhiteListedWalletAddress;
use Tests\Support\FakeNodeServer;
use Tests\Support\ScenarioTestCase;

/**
 * SCENARIO 5 — "Test investor can transfer tokens from internal custodian wallet
 * to external custodian wallet."
 *
 * The client's original wording said "internal ... to internal"; they confirmed
 * that was a typo for internal -> external, which is the transfer the application
 * implements (InvestorController@tranferTokensToEW /
 * InvestorService::transferTokensToEW).
 */
class Scenario05InternalWalletTransferTest extends ScenarioTestCase
{
    /** @var \App\User */
    private $issuer;

    /** @var \App\User */
    private $investor;

    /** @var \App\UserContract */
    private $contract;

    protected function setUp(): void
    {
        parent::setUp();

        $this->issuer   = $this->makeIssuer();
        $this->investor = $this->makeInvestor();
        $this->contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY, ['supply' => 1000]);
    }

    /** @test */
    public function an_investor_can_move_tokens_from_the_internal_custodian_wallet_to_a_whitelisted_wallet()
    {
        $shares = $this->giveInternalTokens(100);
        $wallet = $this->whitelistedWallet();

        $response = $this->actingAs($this->investor)->postJson(
            route('tranferTokensToEW', [$this->investor->id, $this->contract->id]),
            ['recipient_wallet_id' => $wallet->id, 'amount' => 40]
        );

        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertOnChainTransfer($this->contract, $wallet->wallet_address, 40);

        $shares->refresh();
        $this->assertEquals(60, $shares->internal_wallet);
        $this->assertEquals(40, $shares->external_wallet);
        $this->assertEquals(40, $wallet->fresh()->balance);
    }

    /** @test */
    public function the_transfer_is_written_to_the_transfer_log_with_its_transaction_hash()
    {
        $this->giveInternalTokens(100);
        $wallet = $this->whitelistedWallet();

        $this->actingAs($this->investor)->postJson(
            route('tranferTokensToEW', [$this->investor->id, $this->contract->id]),
            ['recipient_wallet_id' => $wallet->id, 'amount' => 25]
        );

        $log = EWTransferLogsModel::where('user_id', $this->investor->id)->first();

        $this->assertNotNull($log, 'The transfer was not logged.');
        $this->assertSame('success', $log->status);
        $this->assertEquals(25, $log->token_count);
        $this->assertSame('0xtransfer-tx', $log->transaction_hash);
    }

    /**
     * Boundary: both the controller and the service guard with "<", so moving the
     * entire internal balance out must be allowed.
     *
     * @test
     */
    public function an_investor_can_move_their_entire_internal_balance()
    {
        $shares = $this->giveInternalTokens(75);
        $wallet = $this->whitelistedWallet();

        $response = $this->actingAs($this->investor)->postJson(
            route('tranferTokensToEW', [$this->investor->id, $this->contract->id]),
            ['recipient_wallet_id' => $wallet->id, 'amount' => 75]
        );

        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertOnChainTransfer($this->contract, $wallet->wallet_address, 75);

        $shares->refresh();
        $this->assertEquals(0, $shares->internal_wallet);
        $this->assertEquals(75, $shares->external_wallet);
        $this->assertEquals(75, $wallet->fresh()->balance);
    }

    /** @test */
    public function an_investor_cannot_transfer_more_than_their_internal_balance()
    {
        $shares = $this->giveInternalTokens(30);
        $wallet = $this->whitelistedWallet();

        $response = $this->actingAs($this->investor)->postJson(
            route('tranferTokensToEW', [$this->investor->id, $this->contract->id]),
            ['recipient_wallet_id' => $wallet->id, 'amount' => 50]
        );

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);

        $shares->refresh();
        $this->assertEquals(30, $shares->internal_wallet);
        $this->assertEquals(0, $shares->external_wallet);
        $this->assertSame(0, FakeNodeServer::callCount('/transfer'));
    }

    /**
     * The controller checks the balance, but InvestorService re-checks it under a
     * row lock. That inner guard is the one that protects against two concurrent
     * transfers, and it must raise a clean error rather than a fatal.
     *
     * @test
     */
    public function the_locked_balance_check_inside_the_service_reports_a_clean_error()
    {
        $this->giveInternalTokens(10);
        $wallet = $this->whitelistedWallet();

        try {
            (new InvestorService())->transferTokensToEW($this->investor->id, $wallet, $this->contract, 50);
            $this->fail('An over-balance transfer was allowed.');
        } catch (\Throwable $e) {
            $this->assertNotInstanceOf(
                \Error::class,
                $e,
                'The insufficient-balance guard raised a PHP fatal instead of an exception: ' . $e->getMessage()
            );
            $this->assertContains('Insufficient token', $e->getMessage());
        }
    }

    /** @test */
    public function an_investor_cannot_transfer_to_another_investors_wallet()
    {
        $this->giveInternalTokens(100);

        $other       = $this->makeInvestor();
        $otherWallet = WhiteListedWalletAddress::create([
            'user_id'        => $other->id,
            'whitelisted_by' => $this->issuer->id,
            'status'         => 1,
            'tx_hash'        => '0xtx',
            'contract_id'    => $this->contract->id,
            'wallet_address' => '0xSomeoneElsesWallet00000000000000000001',
        ]);

        $response = $this->actingAs($this->investor)->postJson(
            route('tranferTokensToEW', [$this->investor->id, $this->contract->id]),
            ['recipient_wallet_id' => $otherWallet->id, 'amount' => 10]
        );

        $response->assertStatus(404);
        $this->assertSame(0, FakeNodeServer::callCount('/transfer'));
    }

    /** @test */
    public function a_failed_on_chain_transfer_leaves_the_internal_balance_untouched()
    {
        $shares = $this->giveInternalTokens(100);
        $wallet = $this->whitelistedWallet();

        FakeNodeServer::setResponse('/transfer', ['status' => 'failed', 'message' => 'chain rejected']);

        $response = $this->actingAs($this->investor)->postJson(
            route('tranferTokensToEW', [$this->investor->id, $this->contract->id]),
            ['recipient_wallet_id' => $wallet->id, 'amount' => 40]
        );

        $response->assertStatus(500);

        $shares->refresh();
        $this->assertEquals(100, $shares->internal_wallet, 'Tokens left the internal wallet even though the chain rejected the transfer.');
        $this->assertEquals(0, $shares->external_wallet);
        $this->assertEquals(0, $wallet->fresh()->balance);
        $this->assertSame(0, EWTransferLogsModel::where('user_id', $this->investor->id)->where('status', 'success')->count());
    }

    /** @test */
    public function a_transfer_of_zero_or_less_is_rejected()
    {
        $this->giveInternalTokens(100);
        $wallet = $this->whitelistedWallet();

        $response = $this->actingAs($this->investor)->postJson(
            route('tranferTokensToEW', [$this->investor->id, $this->contract->id]),
            ['recipient_wallet_id' => $wallet->id, 'amount' => 0]
        );

        $response->assertStatus(422);
        $this->assertSame(0, FakeNodeServer::callCount('/transfer'));
    }

    // ----------------------------------------------------------------- setup

    private function giveInternalTokens($amount): InvestorShares
    {
        return InvestorShares::create([
            'user_id'          => $this->investor->id,
            'user_contract_id' => $this->contract->id,
            'internal_wallet'  => $amount,
            'external_wallet'  => 0,
        ]);
    }

    private function whitelistedWallet(): WhiteListedWalletAddress
    {
        return WhiteListedWalletAddress::create([
            'user_id'        => $this->investor->id,
            'whitelisted_by' => $this->issuer->id,
            'status'         => 1,
            'tx_hash'        => '0xwhitelist-tx',
            'contract_id'    => $this->contract->id,
            'wallet_address' => '0xInvestorExternalWallet0000000000000001',
        ]);
    }
}
