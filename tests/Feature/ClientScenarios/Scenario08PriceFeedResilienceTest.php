<?php

namespace Tests\Feature\ClientScenarios;

use App\InvestorShares;
use App\UserToken;
use Tests\Support\ScenarioTestCase;

/**
 * The investor dashboard and the buy flow both price in USD from a third-party
 * feed, and both used to index the response without checking it.
 *
 * When the feed answered with an error body instead of quotes, `$value['USD']`
 * raised "Illegal string offset 'USD'". Laravel promotes that notice to a thrown
 * exception, the dashboard's catch called back(), the referer after login is
 * /login, and /login bounces an authenticated investor to /home — an infinite
 * redirect loop, reported from production as ERR_TOO_MANY_REDIRECTS.
 *
 * These tests pin both halves: the display path degrades, the buy path refuses
 * clearly, and neither loops.
 */
class Scenario08PriceFeedResilienceTest extends ScenarioTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.is_demo' => false]);
    }

    /** fetchCryptoPrices() returns null when the feed is unusable. */
    private function breakPriceFeed()
    {
        $GLOBALS['__test_crypto_prices'] = null;
    }

    private function workingPriceFeed()
    {
        $GLOBALS['__test_crypto_prices'] = testCryptoPriceDefaults();
    }

    /** @test */
    public function the_investor_dashboard_still_loads_when_the_price_feed_is_down()
    {
        $this->breakPriceFeed();

        $investor = $this->makeInvestor();

        $response = $this->actingAs($investor)->get('/home');

        $response->assertStatus(200);
        $this->assertArrayHasKey('balance', $response->original->getData());
        $this->assertEquals(0, $response->original->getData()['balance']);
    }

    /**
     * The specific production symptom: /home must never redirect to somewhere
     * that redirects back to /home.
     *
     * @test
     */
    public function a_failing_dashboard_does_not_redirect_back_to_itself()
    {
        // Force the dashboard to fail outright, so the catch block runs.
        $GLOBALS['__test_crypto_prices'] = '__throw__';

        $investor = $this->makeInvestor();

        // After login the referer is /login, which is where back() would send them.
        $response = $this->actingAs($investor)->from('/login')->get('/home');

        $this->assertSame(302, $response->getStatusCode(), 'Expected the dashboard error path to redirect.');

        $path = parse_url($response->headers->get('Location'), PHP_URL_PATH);

        $this->assertNotContains(
            $path,
            ['/home', '/login', '/dashboard'],
            'The dashboard error redirected to a page that sends the investor straight back to /home. '
            . 'That is the ERR_TOO_MANY_REDIRECTS loop.'
        );
    }

    /**
     * The other half of the loop: /login bounces an authenticated investor to
     * /home, which is why back() from the dashboard never terminates.
     *
     * @test
     */
    public function the_login_page_sends_an_authenticated_investor_to_the_dashboard()
    {
        $investor = $this->makeInvestor();

        $response = $this->actingAs($investor)->get('/login');

        $response->assertStatus(302);
        $this->assertSame('/home', parse_url($response->headers->get('Location'), PHP_URL_PATH));
    }

    /** @test */
    public function an_orphaned_holding_does_not_break_the_dashboard()
    {
        $this->workingPriceFeed();

        $issuer   = $this->makeIssuer();
        $investor = $this->makeInvestor();
        $contract = $this->deployAsset($issuer, self::TYPE_PROPERTY);

        UserToken::create([
            'user_id'          => $investor->id,
            'user_contract_id' => 999999,   // no such contract
            'token_acquire'    => 10,
            'issuer_id'        => $issuer->id,
            'property_id'      => $contract->property_id,
            'status'           => 'success',
            'current_stage'    => 4,
            'payment_by'       => 'ETH',
        ]);

        $response = $this->actingAs($investor)->get('/home');

        $response->assertStatus(200);
    }

    /**
     * The buy flow prices a real purchase, so an unavailable feed has to stop the
     * request rather than quietly value it at zero.
     *
     * @test
     */
    public function a_purchase_is_refused_rather_than_priced_at_zero_when_the_feed_is_down()
    {
        $this->breakPriceFeed();

        $issuer   = $this->makeIssuer();
        $investor = $this->makeInvestor();
        $contract = $this->deployAsset($issuer, self::TYPE_PROPERTY, ['supply' => 1000, 'usdvalue' => 10]);

        try {
            (new \App\Services\TokenPaymentService())->handleUpsertRequest($investor, $contract, [
                'currentStep' => 1,
                'tokens'      => 10,
                'payby'       => 'ETH',
            ]);
            $this->fail('A purchase was accepted while prices were unavailable.');
        } catch (\Throwable $e) {
            $this->assertContains('unavailable', strtolower($e->getMessage()));
        }

        $this->assertSame(
            0,
            UserToken::where('user_id', $investor->id)->count(),
            'A buy request was created without a usable price.'
        );
    }

    /** @test */
    public function a_purchase_is_priced_normally_when_the_feed_is_working()
    {
        $this->workingPriceFeed();

        $issuer   = $this->makeIssuer();
        $investor = $this->makeInvestor();
        $contract = $this->deployAsset($issuer, self::TYPE_PROPERTY, ['supply' => 1000, 'usdvalue' => 10]);

        (new \App\Services\TokenPaymentService())->handleUpsertRequest($investor, $contract, [
            'currentStep' => 1,
            'tokens'      => 10,
            'payby'       => 'ETH',
        ]);

        $userToken = UserToken::where('user_id', $investor->id)->firstOrFail();

        // 10 tokens x $10 / $2000 per ETH
        $this->assertEquals(0.05, (float) $userToken->deal_amount);
    }
}
