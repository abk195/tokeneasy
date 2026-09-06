<?php

namespace Tests\Feature\ClientScenarios;

use App\Property;
use App\UserContract;
use Tests\Support\FakeNodeServer;
use Tests\Support\ScenarioTestCase;

/**
 * SCENARIO 1 — "Test all 3 types of token deployment and check they are visible
 * in the investor dashboard."
 *
 * The three types are the property token_type discriminators:
 *   1 = Property, 2 = RWA / asset fund, 3 = Utility.
 */
class Scenario01TokenTypesVisibilityTest extends ScenarioTestCase
{
    /**
     * @test
     * @dataProvider tokenTypes
     */
    public function each_token_type_deploys_and_gets_a_contract_address(int $tokenType, string $expectedEndpoint, string $expectedContract)
    {
        $issuer   = $this->makeIssuer();
        $contract = $this->deployAsset($issuer, $tokenType);

        $this->assertSame(1, FakeNodeServer::callCount($expectedEndpoint));
        $this->assertSame($expectedContract, $contract->contract_address);
        $this->assertSame('active', Property::find($contract->property_id)->status);
    }

    /**
     * @test
     * @dataProvider tokenTypes
     */
    public function each_deployed_token_type_is_listed_on_the_investor_property_list(int $tokenType)
    {
        $issuer   = $this->makeIssuer();
        $investor = $this->makeInvestor();
        $contract = $this->deployAsset($issuer, $tokenType);

        $response = $this->actingAs($investor)->get('/propertyList');

        $response->assertStatus(200);

        $listed = $response->original->getData()['property']->firstWhere('id', $contract->property_id);

        $this->assertNotNull($listed, "A token_type={$tokenType} asset is missing from the investor property list.");
        $this->assertSame($contract->contract_address, $listed->contract_address);
        $this->assertSame('Ethereum', $listed->coin);
    }

    /** @test */
    public function all_three_types_appear_together_for_an_investor()
    {
        $issuer   = $this->makeIssuer();
        $investor = $this->makeInvestor();

        $property = $this->deployAsset($issuer, self::TYPE_PROPERTY);
        $rwa      = $this->deployAsset($issuer, self::TYPE_RWA);
        $utility  = $this->deployAsset($issuer, self::TYPE_UTILITY);

        $response = $this->actingAs($investor)->get('/propertyList');
        $response->assertStatus(200);

        $listedIds = $response->original->getData()['property']->pluck('id')->all();

        foreach ([$property, $rwa, $utility] as $contract) {
            $this->assertContains($contract->property_id, $listedIds);
        }
    }

    /**
     * A property that reached "active" without a contract — the state the
     * original Pending Assets bug produced — must not take the whole investor
     * listing down with it.
     *
     * propertyList() dereferences $property->userContract->tokensupply before it
     * checks whether the contract exists.
     *
     * @test
     */
    public function an_asset_without_a_contract_does_not_break_the_investor_property_list()
    {
        $issuer   = $this->makeIssuer();
        $investor = $this->makeInvestor();

        $healthy = $this->deployAsset($issuer, self::TYPE_PROPERTY);

        // Half-deployed asset: active, but no UserContract row.
        $this->makeProperty($issuer, self::TYPE_PROPERTY, null, ['status' => 'active']);

        $response = $this->actingAs($investor)->get('/propertyList');

        $response->assertStatus(200);
        $this->assertArrayHasKey(
            'property',
            $response->original->getData(),
            'The investor property list fell back to the error page — one contract-less asset hid every asset.'
        );

        $listedIds = $response->original->getData()['property']->pluck('id')->all();
        $this->assertContains($healthy->property_id, $listedIds, 'A healthy asset disappeared from the listing.');
    }

    /**
     * getProperty() builds "...where token_type = 1 or token_type is null"
     * without grouping, so the OR escapes the status filter and pulls pending or
     * blocked assets into an investor-facing list.
     *
     * @test
     */
    public function the_asset_list_never_shows_pending_or_blocked_properties()
    {
        $issuer   = $this->makeIssuer();
        $investor = $this->makeInvestor();

        $this->deployAsset($issuer, self::TYPE_PROPERTY);

        $undeployed = $this->makeProperty($issuer, self::TYPE_PROPERTY, null, ['status' => 'pending']);
        $undeployed->token_type = null;
        $undeployed->save();

        $response = $this->actingAs($investor)->get('/property-asset-list/single');
        $response->assertStatus(200);

        $listedIds = collect($response->original->getData()['property'])->pluck('id')->all();

        $this->assertNotContains(
            $undeployed->id,
            $listedIds,
            'A pending, undeployed property is on sale in the investor asset list.'
        );
    }

    /** @test */
    public function a_deployed_asset_shows_its_full_supply_as_available_to_investors()
    {
        $issuer   = $this->makeIssuer();
        $investor = $this->makeInvestor();
        $contract = $this->deployAsset($issuer, self::TYPE_RWA, ['supply' => 2500, 'usdvalue' => 4]);

        $response = $this->actingAs($investor)->get('/propertyList');
        $listed   = $response->original->getData()['property']->firstWhere('id', $contract->property_id);

        $this->assertEquals(0, $listed->sold_percentage);
        $this->assertEquals(2500, UserContract::find($contract->id)->tokenbalance);
    }

    public function tokenTypes(): array
    {
        return [
            'property token' => [self::TYPE_PROPERTY, '/deploySecurityToken', FakeNodeServer::SECURITY_CONTRACT],
            'RWA token'      => [self::TYPE_RWA, '/deploySecurityToken', FakeNodeServer::SECURITY_CONTRACT],
            'utility token'  => [self::TYPE_UTILITY, '/deployUtilityToken', FakeNodeServer::UTILITY_CONTRACT],
        ];
    }
}
