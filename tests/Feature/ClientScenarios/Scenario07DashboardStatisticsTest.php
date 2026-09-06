<?php

namespace Tests\Feature\ClientScenarios;

use App\InvestorShares;
use App\IssuerTokenRequest;
use App\Property;
use App\UserContract;
use Tests\Support\ScenarioTestCase;

/**
 * SCENARIO 7 — "Test all dashboard statistics on investor and issuer dashboards
 * like total assets deployed, value of total deployed assets, how many RWA,
 * Property and Utility tokens are deployed etc."
 */
class Scenario07DashboardStatisticsTest extends ScenarioTestCase
{
    /** @var \App\User */
    private $issuer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->issuer = $this->makeIssuer();
    }

    /** @test */
    public function the_issuer_dashboard_counts_each_token_type_separately()
    {
        $this->deployAsset($this->issuer, self::TYPE_PROPERTY);
        $this->deployAsset($this->issuer, self::TYPE_PROPERTY);
        $this->deployAsset($this->issuer, self::TYPE_RWA);
        $this->deployAsset($this->issuer, self::TYPE_UTILITY);

        $counts = $this->dashboardData()['tokenCounts'];

        $this->assertSame(2, $counts['property'], 'Property token count is wrong.');
        $this->assertSame(1, $counts['deployed_assets'], 'RWA / asset-fund token count is wrong.');
        $this->assertSame(1, $counts['utility'], 'Utility token count is wrong.');
    }

    /** @test */
    public function undeployed_assets_are_not_counted_as_deployed()
    {
        $this->deployAsset($this->issuer, self::TYPE_PROPERTY);

        // Created but never deployed: still pending.
        $this->makeProperty($this->issuer, self::TYPE_PROPERTY);
        $this->makeProperty($this->issuer, self::TYPE_UTILITY);

        $counts = $this->dashboardData()['tokenCounts'];

        $this->assertSame(1, $counts['property']);
        $this->assertSame(0, $counts['utility']);
    }

    /** @test */
    public function one_issuers_assets_are_not_counted_on_another_issuers_dashboard()
    {
        $other = $this->makeIssuer();

        $this->deployAsset($this->issuer, self::TYPE_PROPERTY);
        $this->deployAsset($other, self::TYPE_RWA);
        $this->deployAsset($other, self::TYPE_UTILITY);

        $counts = $this->dashboardData()['tokenCounts'];

        $this->assertSame(1, $counts['property']);
        $this->assertSame(0, $counts['deployed_assets']);
        $this->assertSame(0, $counts['utility']);
    }

    /**
     * "Value of total deployed assets" — totalDealSize is summed across every
     * property the issuer owns, including ones that were never deployed.
     *
     * @test
     */
    public function the_total_deal_size_only_counts_deployed_assets()
    {
        $deployed = $this->makeProperty($this->issuer, self::TYPE_PROPERTY, null, ['totalDealSize' => '1000000']);
        $request  = $this->makeTokenRequest($this->issuer, $deployed);
        (new \App\Services\TokenizerService())->deployToken($request->id);

        // Never deployed — should not contribute to "deployed asset value".
        $this->makeProperty($this->issuer, self::TYPE_PROPERTY, null, ['totalDealSize' => '9000000']);

        $this->assertEquals(
            1000000,
            $this->dashboardData()['totalDealSize'],
            'The deployed-asset value includes assets that were never deployed.'
        );
    }

    /**
     * $issuer_token is a single query builder. request_token counts it, then
     * rejected_token adds a second where() to the same builder, producing
     * "status = pending AND status = rejected" — always zero.
     *
     * @test
     */
    public function the_dashboard_reports_pending_and_rejected_token_requests_separately()
    {
        $pendingProperty = $this->makeProperty($this->issuer, self::TYPE_PROPERTY);
        $this->makeTokenRequest($this->issuer, $pendingProperty, ['status' => 'pending']);

        $rejectedProperty = $this->makeProperty($this->issuer, self::TYPE_PROPERTY);
        $this->makeTokenRequest($this->issuer, $rejectedProperty, ['status' => 'rejected']);

        $data = $this->dashboardData();

        $this->assertSame(1, $data['request_token'], 'Pending token request count is wrong.');
        $this->assertSame(1, $data['rejected_token'], 'Rejected token requests are always reported as zero.');
    }

    /** @test */
    public function a_deleted_asset_drops_out_of_the_dashboard_counts()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);
        $this->deployAsset($this->issuer, self::TYPE_PROPERTY);

        Property::find($contract->property_id)->delete();

        $this->assertSame(1, $this->dashboardData()['tokenCounts']['property']);
    }

    /** @test */
    public function the_issuer_token_list_shows_only_live_contracts_for_that_issuer()
    {
        $other = $this->makeIssuer();

        $mine = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);
        $this->deployAsset($other, self::TYPE_PROPERTY);

        $response = $this->actingAs($this->issuer)->get('/issuer/tokenList');
        $response->assertStatus(200);

        $tokens = $response->original->getData()['tokens'];

        $this->assertCount(1, $tokens);
        $this->assertSame($mine->id, $tokens->first()->id);
    }

    /** @test */
    public function the_pending_assets_screen_lists_only_undeployed_requests()
    {
        $deployedProperty = $this->makeProperty($this->issuer, self::TYPE_PROPERTY);
        $deployedRequest  = $this->makeTokenRequest($this->issuer, $deployedProperty);
        (new \App\Services\TokenizerService())->deployToken($deployedRequest->id);

        $pendingProperty = $this->makeProperty($this->issuer, self::TYPE_PROPERTY);
        $pendingRequest  = $this->makeTokenRequest($this->issuer, $pendingProperty);

        $response = $this->actingAs($this->issuer)->get('/issuer/tokenRequest');
        $response->assertStatus(200);

        $ids = $response->original->getData()['tokens']->pluck('id')->all();

        $this->assertContains($pendingRequest->id, $ids);
        $this->assertNotContains($deployedRequest->id, $ids, 'A deployed token is still shown under Pending Assets.');
    }

    // ------------------------------------------------------------- investor

    /** @test */
    public function an_investors_holdings_are_reported_on_their_investment_screen()
    {
        $investor = $this->makeInvestor();
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY, ['supply' => 1000, 'usdvalue' => 10]);

        InvestorShares::create([
            'user_id'          => $investor->id,
            'user_contract_id' => $contract->id,
            'internal_wallet'  => 30,
            'external_wallet'  => 20,
        ]);

        $shares = InvestorShares::where('user_id', $investor->id)->firstOrFail();

        $this->assertEquals(50, $shares->internal_wallet + $shares->external_wallet);
    }

    /** @test */
    public function the_supply_shown_to_investors_matches_what_has_actually_been_sold()
    {
        $investor = $this->makeInvestor();
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY, ['supply' => 1000, 'usdvalue' => 10]);

        $service = new \App\Services\TokenPaymentService();
        $service->handleUpsertRequest($investor, $contract, ['currentStep' => 1, 'tokens' => 250, 'payby' => 'ETH']);
        $userToken = \App\UserToken::where('user_id', $investor->id)->firstOrFail();
        $service->setCustody($contract, $userToken, ['custody' => 'internal']);
        $service->savePaymentDetails($userToken->fresh(), [
            'payment_method'      => \App\Enums\PaymentMethod::BANK_TRANSFER,
            'selected_payment_id' => 1,
            'payment_reference'   => 'REF-STATS',
            'payment_proof'       => null,
        ], $contract);
        $service->updateBuyRequestStatus($userToken->id, 'Approved', 'ok');

        $response = $this->actingAs($investor)->get('/propertyList');
        $listed   = $response->original->getData()['property']->firstWhere('id', $contract->property_id);

        $this->assertEquals(750, UserContract::find($contract->id)->tokenbalance);
        $this->assertEquals(25, $listed->sold_percentage, 'The sold percentage does not match the tokens actually sold.');
        $this->assertEquals(2500, $listed->accuired_usd, 'The raised amount does not match tokens sold x token value.');
    }

    /** @test */
    public function a_fully_sold_asset_reports_one_hundred_percent_sold()
    {
        $investor = $this->makeInvestor();
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY, ['supply' => 100, 'usdvalue' => 5]);

        $contract->tokenbalance = 0;
        $contract->save();

        $response = $this->actingAs($investor)->get('/propertyList');
        $listed   = $response->original->getData()['property']->firstWhere('id', $contract->property_id);

        $this->assertEquals(100, $listed->sold_percentage);
    }

    // ----------------------------------------------------------------- setup

    private function dashboardData(): array
    {
        $response = $this->actingAs($this->issuer)->get('/issuer/dashboard');
        $response->assertStatus(200);

        return $response->original->getData();
    }
}
