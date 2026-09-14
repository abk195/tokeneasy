<?php

namespace Tests\Feature\ClientScenarios;

use App\IssuerTokenRequest;
use App\Property;
use App\UserContract;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Support\FakeNodeServer;
use Tests\Support\ScenarioTestCase;

/**
 * BUG 1, at the entry point the issuer actually uses.
 *
 * Bug01TokenDeploymentTest drives TokenizerService directly, which proved the
 * deployment service correct but said nothing about whether the controller ever
 * calls it. It did not: storeProperty() wrapped the whole deployment in
 * `if (config('app.is_demo'))`, so on a production install with APP_IS_DEMO=false
 * no deploy was attempted, the request stayed 'pending', and the issuer landed on
 * Pending Assets — the exact symptom the client reported as fixed.
 *
 * These tests post to POST /issuer/property with demo mode OFF, which is the
 * configuration production runs in.
 */
class Bug01DirectDeployRouteTest extends ScenarioTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Production configuration: this is the case that was broken.
        config(['app.is_demo' => false]);

        Storage::fake('local');
        Storage::fake('public');
    }

    /** @test */
    public function creating_an_asset_deploys_the_token_immediately_without_admin_approval()
    {
        $issuer   = $this->makeIssuer();
        $keystore = $this->makeKeystore($issuer);

        $response = $this->actingAs($issuer)->post('/issuer/property', $this->propertyPayload($keystore));

        $this->assertNotEquals(
            '/issuer/tokenRequest',
            $this->redirectPath($response),
            'The issuer was sent to Pending Assets, so no deployment was attempted.'
        );

        $request = IssuerTokenRequest::where('user_id', $issuer->id)->firstOrFail();

        $this->assertSame('live', $request->status, 'The token request is still pending admin approval.');
        $this->assertSame(1, (int) $request->token_deploy_status);
        $this->assertSame(1, FakeNodeServer::callCount('/deploySecurityToken'), 'No deploy reached the chain.');
    }

    /** @test */
    public function the_deployed_token_gets_its_contract_address_and_goes_active()
    {
        $issuer   = $this->makeIssuer();
        $keystore = $this->makeKeystore($issuer);

        $this->actingAs($issuer)->post('/issuer/property', $this->propertyPayload($keystore));

        $property = Property::where('user_id', $issuer->id)->firstOrFail();
        $contract = UserContract::where('property_id', $property->id)->first();

        $this->assertNotNull($contract, 'No contract was created for the new asset.');
        $this->assertSame(FakeNodeServer::SECURITY_CONTRACT, $contract->contract_address);
        $this->assertSame('active', $property->status);
    }

    /** @test */
    public function the_new_asset_does_not_appear_under_pending_assets()
    {
        $issuer   = $this->makeIssuer();
        $keystore = $this->makeKeystore($issuer);

        $this->actingAs($issuer)->post('/issuer/property', $this->propertyPayload($keystore));

        $pending = $this->actingAs($issuer)->get('/issuer/tokenRequest');
        $pending->assertStatus(200);

        $this->assertCount(
            0,
            $pending->original->getData()['tokens'],
            'The freshly deployed asset is listed under Pending Assets.'
        );
    }

    /** @test */
    public function an_ajax_creation_returns_the_contract_address_to_the_browser()
    {
        $issuer   = $this->makeIssuer();
        $keystore = $this->makeKeystore($issuer);

        $response = $this->actingAs($issuer)->post(
            '/issuer/property',
            $this->propertyPayload($keystore),
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(200);
        $response->assertJson([
            'success'          => true,
            'contract_address' => FakeNodeServer::SECURITY_CONTRACT,
        ]);
    }

    /** @test */
    public function a_deploy_that_fails_for_lack_of_gas_reports_the_real_reason()
    {
        FakeNodeServer::setResponse('/native_balance', ['status' => 'success', 'balance' => 0]);

        $issuer   = $this->makeIssuer();
        $keystore = $this->makeKeystore($issuer);

        $response = $this->actingAs($issuer)->post(
            '/issuer/property',
            $this->propertyPayload($keystore),
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(422);
        $response->assertJson(['success' => false, 'message' => 'Insufficient balance.']);

        $this->assertSame(0, UserContract::where('user_id', $issuer->id)->count());
    }

    /**
     * The token is live on-chain before the default payment route is wired up, so
     * a missing platform stablecoin must not be reported as a failed deployment.
     *
     * @test
     */
    public function a_missing_default_stablecoin_does_not_fail_a_successful_deployment()
    {
        // Remove the platform stablecoin storeDefaultPayments() looks for.
        \App\BlockchainStablecoin::where('id', $this->blockchainStablecoin->id)->delete();

        $issuer   = $this->makeIssuer();
        $keystore = $this->makeKeystore($issuer);

        $response = $this->actingAs($issuer)->post(
            '/issuer/property',
            $this->propertyPayload($keystore),
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $property = Property::where('user_id', $issuer->id)->firstOrFail();
        $this->assertSame(
            FakeNodeServer::SECURITY_CONTRACT,
            UserContract::where('property_id', $property->id)->firstOrFail()->contract_address
        );
    }

    /** @test */
    public function utility_assets_also_deploy_directly()
    {
        $issuer   = $this->makeIssuer();
        $keystore = $this->makeKeystore($issuer);

        $this->actingAs($issuer)->post(
            '/issuer/property',
            $this->propertyPayload($keystore, ['token_type' => self::TYPE_UTILITY, 'tokentype' => 'erc20'])
        );

        $this->assertSame(1, FakeNodeServer::callCount('/deployUtilityToken'));

        $property = Property::where('user_id', $issuer->id)->firstOrFail();
        $this->assertSame(
            FakeNodeServer::UTILITY_CONTRACT,
            UserContract::where('property_id', $property->id)->firstOrFail()->contract_address
        );
    }

    // ----------------------------------------------------------------- setup

    private function redirectPath($response)
    {
        $location = $response->headers->get('Location');

        return $location ? parse_url($location, PHP_URL_PATH) : null;
    }

    /**
     * The minimum storeProperty() validates. propertyLogo and token_image are
     * required whenever demo mode is off.
     */
    private function propertyPayload($keystore, array $overrides = []): array
    {
        return array_merge([
            'propertyName'           => 'Harbour Tower',
            'propertyLocation'       => 'Dubai',
            'property_state'         => 'live',
            'propertyLogo'           => UploadedFile::fake()->image('logo.png'),
            'token_image'            => UploadedFile::fake()->image('token.png'),
            'holdingPeriod'          => 5,
            'token_chain'            => $this->blockchain->id,
            'token_name'             => 'Harbour Tower Token',
            'token_symbol'           => 'HTWR',
            'token_decimal'          => 18,
            'token_value'            => 10,
            'token_supply'           => 1000,
            'tokentype'              => 'erc20',
            'token_type'             => self::TYPE_PROPERTY,
            'initialInvestment'      => 100,
            'totalDealSize'          => 1000000,
            'expectedIrr'            => 8,
            'fundedMembers'          => 0,
            'keystore_id'            => $keystore->id,
            'enable_internal_wallet' => 0,   // the form now always submits 0
        ], $overrides);
    }
}
