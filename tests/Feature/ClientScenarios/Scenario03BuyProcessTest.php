<?php

namespace Tests\Feature\ClientScenarios;

use App\Enums\PaymentMethod;
use App\InvestorShares;
use App\Models\CryptoTransfer;
use App\Notification;
use App\Services\TokenPaymentService;
use App\User;
use App\UserContract;
use App\UserToken;
use App\UserTokenTransaction;
use App\WhiteListedWalletAddress;
use Tests\Support\FakeNodeServer;
use Tests\Support\ScenarioTestCase;

/**
 * SCENARIO 3 — "Test the buy process from the investor dashboard, both manual
 * bank transfers and automated crypto transfers. Make sure at the end of both
 * buying routes tokens are actually transferred to investor's external wallet."
 *
 * Manual route:  purchaseRequest steps 1 -> 2 -> 3; in demo mode step 3 is
 *                auto-approved, which triggers the token transfer.
 * Automated route: CryptoController@store records the payment, then
 *                CryptoController@updateStatus('completed') triggers the transfer
 *                via CryptoTransfer::setStatus().
 */
class Scenario03BuyProcessTest extends ScenarioTestCase
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
        $this->contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY, [
            'supply'   => 1000,
            'usdvalue' => 10,
        ]);
    }

    // ---------------------------------------------------------------- manual

    /** @test */
    public function the_manual_bank_transfer_route_moves_tokens_to_the_investors_external_wallet()
    {
        $wallet = $this->whitelistedWallet('0xInvestorExternalWallet0000000000000001');

        $this->runManualBuy($wallet, 40);

        $this->assertOnChainTransfer(
            $this->contract,
            $wallet->wallet_address,
            40,
            'No on-chain transfer reached the investor external wallet after a manual bank purchase.'
        );

        $shares = InvestorShares::where('user_id', $this->investor->id)
            ->where('user_contract_id', $this->contract->id)
            ->first();

        $this->assertNotNull($shares);
        $this->assertEquals(40, $shares->external_wallet);
        $this->assertEquals(0, $shares->internal_wallet);
        $this->assertEquals(40, $wallet->fresh()->balance);
        $this->assertEquals(960, $this->contract->fresh()->tokenbalance, 'Issuer supply was not reduced.');
    }

    /** @test */
    public function the_manual_route_records_the_purchase_and_the_transaction_hash()
    {
        $wallet = $this->whitelistedWallet();

        $userToken = $this->runManualBuy($wallet, 25);

        $userToken->refresh();
        $this->assertSame('success', $userToken->status);
        $this->assertSame(4, (int) $userToken->current_stage);

        $txn = UserTokenTransaction::where('user_token_id', $userToken->id)->first();

        $this->assertNotNull($txn, 'No transaction record was written for the purchase.');
        $this->assertEquals(25, $txn->number_of_token);
        $this->assertSame('0xtransfer-tx', $txn->token_txn_hash, 'The on-chain transfer hash was not recorded.');
    }

    /** @test */
    public function an_investor_is_told_when_their_tokens_have_been_transferred()
    {
        $wallet = $this->whitelistedWallet();

        $this->runManualBuy($wallet, 10);

        $this->assertTrue(
            Notification::where('user_id', $this->investor->id)
                ->where('notification_type', 'success')
                ->where('title', 'like', '%Completed%')
                ->exists(),
            'The investor was never notified that their purchase completed and tokens were transferred.'
        );
    }

    /** @test */
    public function a_manual_purchase_into_the_internal_wallet_credits_the_custodian_balance()
    {
        $userToken = $this->buyRequestAtPaymentStage('internal', null, 30);

        (new TokenPaymentService())->updateBuyRequestStatus($userToken->id, 'Approved', 'Approved by issuer');

        $shares = InvestorShares::where('user_id', $this->investor->id)
            ->where('user_contract_id', $this->contract->id)
            ->first();

        $this->assertEquals(30, $shares->internal_wallet);
        $this->assertEquals(0, $shares->external_wallet);
        $this->assertSame(
            0,
            FakeNodeServer::callCount('/transfer'),
            'An internal custodian purchase should not move tokens on-chain.'
        );
    }

    /** @test */
    public function an_issuer_can_reject_a_buy_request_and_no_tokens_move()
    {
        $wallet    = $this->whitelistedWallet();
        $userToken = $this->buyRequestAtPaymentStage('external', $wallet, 20);

        (new TokenPaymentService())->updateBuyRequestStatus($userToken->id, 'Cancelled', 'Payment not received');

        $userToken->refresh();
        $this->assertSame('reject', $userToken->status);
        $this->assertSame('Payment not received', $userToken->note);
        $this->assertSame(0, FakeNodeServer::callCount('/transfer'));
        $this->assertEquals(1000, $this->contract->fresh()->tokenbalance);

        $this->assertTrue(
            Notification::where('user_id', $this->investor->id)
                ->where('notification_type', 'danger')
                ->exists(),
            'The investor was not told their buy request was rejected.'
        );
    }

    // ------------------------------------------------------------- automated

    /** @test */
    public function the_automated_crypto_route_moves_tokens_to_the_investors_external_wallet()
    {
        $wallet    = $this->whitelistedWallet('0xInvestorExternalWallet0000000000000002');
        $userToken = $this->buyRequestAtCustodyStage('external', $wallet, 50);

        $store = $this->actingAs($this->investor)->postJson(route('crypto.transfer.store'), [
            'user_token_id'            => (string) $userToken->id,
            'sender_address'           => '0xInvestorPayingWallet000000000000000001',
            'recipient_address'        => config('token.token.address'),
            'amount'                   => 500,
            'blockchain_stablecoin_id' => (string) $this->blockchainStablecoin->id,
            'transaction_hash'         => '0xstablecoin-payment-tx',
        ]);

        $store->assertStatus(200)->assertJson(['status' => 'success']);

        $transfer = CryptoTransfer::where('user_token_id', $userToken->id)->firstOrFail();
        $this->assertSame(CryptoTransfer::STATUS_PENDING, $transfer->status);

        $update = $this->actingAs($this->investor)->putJson(route('crypto.transfer.update', $transfer->id), [
            'status'           => 'completed',
            'transaction_hash' => '0xstablecoin-payment-tx',
        ]);

        $update->assertStatus(200)->assertJson(['status' => 'success']);

        $this->assertOnChainTransfer(
            $this->contract,
            $wallet->wallet_address,
            50,
            'No on-chain transfer reached the investor external wallet after an automated crypto purchase.'
        );

        $shares = InvestorShares::where('user_id', $this->investor->id)
            ->where('user_contract_id', $this->contract->id)
            ->first();

        $this->assertEquals(50, $shares->external_wallet);
        $this->assertEquals(950, $this->contract->fresh()->tokenbalance);
        $this->assertSame('success', $userToken->fresh()->status);
    }

    /** @test */
    public function the_crypto_payment_reference_is_stored_against_the_buy_request()
    {
        $wallet    = $this->whitelistedWallet();
        $userToken = $this->buyRequestAtCustodyStage('external', $wallet, 15);

        $this->actingAs($this->investor)->postJson(route('crypto.transfer.store'), [
            'user_token_id'            => (string) $userToken->id,
            'sender_address'           => '0xInvestorPayingWallet000000000000000001',
            'recipient_address'        => config('token.token.address'),
            'amount'                   => 150,
            'blockchain_stablecoin_id' => (string) $this->blockchainStablecoin->id,
            'transaction_hash'         => '0xstablecoin-payment-tx',
        ]);

        $userToken->refresh();

        $this->assertSame('inReview', $userToken->status);
        $this->assertSame(PaymentMethod::CRYPTO_TRANSFER, $userToken->payment_mode);
        $this->assertSame('0xstablecoin-payment-tx', $userToken->payment_reference_id);
    }

    /** @test */
    public function an_investor_cannot_complete_someone_elses_crypto_transfer()
    {
        $wallet    = $this->whitelistedWallet();
        $userToken = $this->buyRequestAtCustodyStage('external', $wallet, 10);

        $this->actingAs($this->investor)->postJson(route('crypto.transfer.store'), [
            'user_token_id'            => (string) $userToken->id,
            'sender_address'           => '0xa',
            'recipient_address'        => config('token.token.address'),
            'amount'                   => 100,
            'blockchain_stablecoin_id' => (string) $this->blockchainStablecoin->id,
            'transaction_hash'         => '0xtx',
        ]);

        $transfer = CryptoTransfer::where('user_token_id', $userToken->id)->firstOrFail();
        $other    = $this->makeInvestor();

        $response = $this->actingAs($other)->putJson(route('crypto.transfer.update', $transfer->id), [
            'status'           => 'completed',
            'transaction_hash' => '0xtx',
        ]);

        $response->assertStatus(403);
        $this->assertSame(0, FakeNodeServer::callCount('/transfer'));
    }

    // ------------------------------------------------------------ safeguards

    /** @test */
    public function a_purchase_larger_than_the_remaining_supply_is_refused()
    {
        $service = new TokenPaymentService();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Tokens Supply not available');

        $service->handleUpsertRequest($this->investor, $this->contract, [
            'currentStep' => 1,
            'tokens'      => 5000,
            'payby'       => 'ETH',
        ]);
    }

    /**
     * handleUpsertRequest() guards every step on an existing request with
     * isTokenAvailable(). If the supply is sold out from under a request that is
     * already in progress, the investor must be stopped at the next step rather
     * than carried through to payment.
     *
     * @test
     */
    public function the_supply_guard_fires_when_a_request_exceeds_the_remaining_supply()
    {
        $service = new TokenPaymentService();

        $service->handleUpsertRequest($this->investor, $this->contract, [
            'currentStep' => 1, 'tokens' => 900, 'payby' => 'ETH',
        ]);

        // Another investor takes most of what is left.
        $this->contract->tokenbalance = 100;
        $this->contract->save();

        $threw = false;
        try {
            // Advancing to custody selection must not be allowed.
            $service->handleUpsertRequest($this->investor, $this->contract->fresh(), [
                'currentStep' => 2,
                'custody'     => 'internal',
            ]);
        } catch (\Throwable $e) {
            $threw = true;
        }

        $this->assertTrue(
            $threw,
            'The supply guard passed a request for 900 tokens against a remaining supply of 100.'
        );
    }

    /**
     * The deeper guard inside transferTokens() is the one that actually protects
     * the supply, and it must hold.
     *
     * @test
     */
    public function an_oversized_request_is_refused_before_any_tokens_move()
    {
        $userToken = $this->buyRequestAtPaymentStage('internal', null, 900);

        $this->contract->tokenbalance = 100;
        $this->contract->save();

        try {
            (new TokenPaymentService())->transferTokens($userToken->fresh(), $this->investor, $this->contract->fresh());
            $this->fail('An oversized purchase was allowed to complete.');
        } catch (\Throwable $e) {
            $this->assertContains('Insufficient token supply', $e->getMessage());
        }

        $this->assertSame(0, FakeNodeServer::callCount('/transfer'), 'Tokens moved on-chain for a refused purchase.');
    }

    /**
     * The insufficient-supply branch calls DB::rollBack() and then throws, and the
     * surrounding catch calls DB::rollBack() a second time. The extra rollback
     * unwinds whatever transaction the caller had open, so transferTokens() cannot
     * be safely wrapped in one — a batch approval would lose its own work.
     *
     * @test
     */
    public function a_refused_transfer_does_not_roll_back_the_callers_transaction()
    {
        $userToken = $this->buyRequestAtPaymentStage('internal', null, 900);

        $this->contract->tokenbalance = 100;
        $this->contract->save();

        $contractId = $this->contract->id;

        try {
            (new TokenPaymentService())->transferTokens($userToken->fresh(), $this->investor, $this->contract->fresh());
        } catch (\Throwable $e) {
            // expected
        }

        $this->assertNotNull(
            UserContract::find($contractId),
            'transferTokens() rolled back one transaction too many and discarded the caller\'s own work.'
        );
    }

    /**
     * When the transfer fails the request has to be recorded as failed. The
     * user_tokens.status column is enum('inProgress','inReview','reject',
     * 'success') and has no 'failed' member, so the write is rejected under
     * MySQL strict mode and the real error is masked.
     *
     * @test
     */
    public function a_failed_transfer_is_recorded_against_the_buy_request()
    {
        $wallet    = $this->whitelistedWallet();
        $userToken = $this->buyRequestAtPaymentStage('external', $wallet, 10);

        FakeNodeServer::setResponse('/transfer', ['status' => 'failed', 'message' => 'chain rejected']);

        try {
            (new TokenPaymentService())->transferTokens($userToken->fresh());
        } catch (\Throwable $e) {
            // The transfer is expected to fail; what matters is what was recorded.
        }

        $status = \DB::table('user_tokens')->where('id', $userToken->id)->value('status');

        $this->assertNotSame('success', $status, 'A failed transfer was recorded as a successful purchase.');
        $this->assertSame('failed', $status, "Buy request status was recorded as '{$status}' instead of 'failed'.");
    }

    /** @test */
    public function a_failed_on_chain_transfer_does_not_consume_the_issuers_supply()
    {
        $wallet    = $this->whitelistedWallet();
        $userToken = $this->buyRequestAtPaymentStage('external', $wallet, 10);

        FakeNodeServer::setResponse('/transfer', ['status' => 'failed', 'message' => 'chain rejected']);

        try {
            (new TokenPaymentService())->transferTokens($userToken->fresh());
        } catch (\Throwable $e) {
            // expected
        }

        $this->assertEquals(1000, $this->contract->fresh()->tokenbalance, 'Supply was debited for a transfer that never happened.');
        $this->assertEquals(0, $wallet->fresh()->balance);
    }

    /** @test */
    public function an_external_purchase_is_blocked_when_the_issuer_wallet_has_no_gas()
    {
        $wallet    = $this->whitelistedWallet();
        $userToken = $this->buyRequestAtPaymentStage('external', $wallet, 10);

        FakeNodeServer::setResponse('/native_balance', ['status' => 'success', 'balance' => 0]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Gas fee not available');

        (new TokenPaymentService())->transferTokens($userToken->fresh());
    }

    // ----------------------------------------------------------------- setup

    private function whitelistedWallet(string $address = '0xInvestorExternalWallet0000000000000001'): WhiteListedWalletAddress
    {
        return WhiteListedWalletAddress::create([
            'user_id'        => $this->investor->id,
            'whitelisted_by' => $this->issuer->id,
            'status'         => 1,
            'tx_hash'        => '0xwhitelist-tx',
            'contract_id'    => $this->contract->id,
            'wallet_address' => $address,
        ]);
    }

    /**
     * Drives the real buy flow through steps 1 and 2 and leaves the request ready
     * for payment.
     */
    private function buyRequestAtCustodyStage(string $custody, $wallet, $tokens): UserToken
    {
        $service = new TokenPaymentService();

        $service->handleUpsertRequest($this->investor, $this->contract, [
            'currentStep' => 1,
            'tokens'      => $tokens,
            'payby'       => 'ETH',
        ]);

        $userToken = UserToken::where('user_id', $this->investor->id)
            ->where('user_contract_id', $this->contract->id)
            ->firstOrFail();

        $service->handleUpsertRequest($this->investor, $this->contract, [
            'currentStep'          => 2,
            'custody'              => $custody,
            'whitelisted_wallet_id' => $wallet ? $wallet->id : null,
            'contract_address'     => $wallet ? $wallet->wallet_address : null,
            'tokens'               => $tokens,
            'payby'                => 'ETH',
        ]);

        return $userToken->fresh();
    }

    /**
     * As above, then submits payment details without the demo auto-approval, so
     * the test can drive approval itself.
     */
    private function buyRequestAtPaymentStage(string $custody, $wallet, $tokens): UserToken
    {
        $userToken = $this->buyRequestAtCustodyStage($custody, $wallet, $tokens);

        (new TokenPaymentService())->savePaymentDetails($userToken, [
            'payment_method'      => PaymentMethod::BANK_TRANSFER,
            'selected_payment_id' => 1,
            'payment_reference'   => 'BANK-REF-001',
            'payment_proof'       => null,
        ], $this->contract);

        return $userToken->fresh();
    }

    /**
     * The complete manual route, including demo auto-approval.
     */
    private function runManualBuy(WhiteListedWalletAddress $wallet, $tokens): UserToken
    {
        $service   = new TokenPaymentService();
        $userToken = $this->buyRequestAtCustodyStage('external', $wallet, $tokens);

        $service->handleUpsertRequest($this->investor, $this->contract, [
            'currentStep'         => 3,
            'payment_method'      => PaymentMethod::BANK_TRANSFER,
            'selected_payment_id' => 1,
            'payment_reference'   => 'BANK-REF-001',
            'payment_proof'       => null,
        ]);

        return $userToken->fresh();
    }
}
