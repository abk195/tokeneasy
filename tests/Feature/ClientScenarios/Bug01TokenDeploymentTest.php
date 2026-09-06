<?php

namespace Tests\Feature\ClientScenarios;

use App\IssuerTokenRequest;
use App\Property;
use App\Services\TokenizerService;
use App\UserContract;
use Tests\Support\FakeNodeServer;
use Tests\Support\ScenarioTestCase;

/**
 * BUG 1 — "Issuer can deploy directly without admin approval, but after deploy
 * the token is stuck in Pending Assets. Make sure the token is actually deployed
 * on the selected blockchain and configured in the platform. Smart contract
 * address is assigned to the deployed token and is visible."
 *
 * The four things that have to be true after a successful deploy:
 *   1. the deploy really went to the selected chain,
 *   2. a UserContract exists carrying the returned contract address,
 *   3. the IssuerTokenRequest leaves "pending" (so it drops off Pending Assets),
 *   4. the property goes active and the address is visible to the issuer.
 */
class Bug01TokenDeploymentTest extends ScenarioTestCase
{
    /** @test */
    public function deploy_sends_the_request_to_the_blockchain_selected_on_the_property()
    {
        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY);
        $request  = $this->makeTokenRequest($issuer, $property, [
            'name'    => 'Harbour Tower',
            'symbol'  => 'HTWR',
            'supply'  => 5000,
            'decimal' => 18,
        ]);

        $result = (new TokenizerService())->deployToken($request->id);

        $this->assertFalse((bool) $result['hasError'], $result['message'] ?? '');

        $deploy = FakeNodeServer::lastRequest('/deploySecurityToken');
        $this->assertNotNull($deploy, 'No deployment call reached the node service.');
        $this->assertSame($this->blockchain->abbreviation, $deploy['body']['chain']);
        $this->assertSame('Harbour Tower', $deploy['body']['name']);
        $this->assertSame('HTWR', $deploy['body']['symbol']);
        $this->assertEquals(5000, $deploy['body']['totalSupply']);
        $this->assertEquals(18, $deploy['body']['decimals']);
        $this->assertSame('0xissuer-private-key', $deploy['body']['privateKey']);
    }

    /** @test */
    public function successful_deploy_assigns_the_smart_contract_address_to_the_token()
    {
        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY);
        $request  = $this->makeTokenRequest($issuer, $property);

        $result = (new TokenizerService())->deployToken($request->id);

        $this->assertFalse((bool) $result['hasError'], $result['message'] ?? '');
        $this->assertSame(FakeNodeServer::SECURITY_CONTRACT, $result['contract_address']);

        $contract = UserContract::where('property_id', $property->id)->first();

        $this->assertNotNull($contract, 'No UserContract was created for the deployed token.');
        $this->assertSame(FakeNodeServer::SECURITY_CONTRACT, $contract->contract_address);
        $this->assertSame($this->blockchain->id, (int) $contract->blockchain_id);
        $this->assertSame($this->blockchain->blockchain_name, $contract->coin);
        $this->assertSame(1, (int) $contract->status);
        $this->assertEquals($request->supply, $contract->tokensupply);
        $this->assertEquals($request->supply, $contract->tokenbalance, 'Full supply should be available to sell.');
    }

    /** @test */
    public function successful_deploy_clears_the_token_out_of_pending_assets()
    {
        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY);
        $request  = $this->makeTokenRequest($issuer, $property);

        (new TokenizerService())->deployToken($request->id);

        $request->refresh();
        $property->refresh();

        $this->assertSame('live', $request->status, 'Token request still reads as pending.');
        $this->assertSame('active', $property->status, 'Property never became active.');

        // tokenRequest() — the "Pending Assets" screen — filters on status=pending.
        $pending = IssuerTokenRequest::where('user_id', $issuer->id)->where('status', 'pending')->get();
        $this->assertCount(0, $pending, 'Deployed token is still listed under Pending Assets.');
    }

    /** @test */
    public function the_contract_address_and_explorer_link_are_visible_on_the_issuer_property_screen()
    {
        $issuer   = $this->makeIssuer();
        $contract = $this->deployAsset($issuer, self::TYPE_PROPERTY);

        $response = $this->actingAs($issuer)->get('/issuer/property');

        $response->assertStatus(200);
        $response->assertSee(FakeNodeServer::SECURITY_CONTRACT);

        // Regression guard for the blockchain_nam typo the fix corrected.
        $property = Property::find($contract->property_id);
        $shown    = $response->original->getData()['properties']->firstWhere('id', $property->id);

        $this->assertNotNull($shown, 'Deployed property is missing from the issuer property list.');
        $this->assertSame(FakeNodeServer::SECURITY_CONTRACT, $shown->contract_address);
        $this->assertSame('Ethereum', $shown->coin, 'Chain name is blank — the blockchain_nam typo is back.');
        $this->assertSame(
            'https://sepolia.etherscan.io/token/' . FakeNodeServer::SECURITY_CONTRACT,
            $shown->contract_link
        );
    }

    /** @test */
    public function deployment_is_rejected_when_the_property_has_no_keystore()
    {
        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY);
        $property->keystore_id = null;
        $property->save();

        $request = $this->makeTokenRequest($issuer, $property);

        $result = (new TokenizerService())->deployToken($request->id);

        $this->assertTrue((bool) $result['hasError']);
        $this->assertSame('Keystore not linked to this property.', $result['message']);
        $this->assertSame(0, UserContract::where('property_id', $property->id)->count());
        $this->assertSame('pending', $request->fresh()->status);
    }

    /** @test */
    public function deployment_is_rejected_when_the_issuer_wallet_has_no_gas()
    {
        FakeNodeServer::setResponse('/native_balance', ['status' => 'success', 'balance' => 0]);

        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY);
        $request  = $this->makeTokenRequest($issuer, $property);

        $result = (new TokenizerService())->deployToken($request->id);

        $this->assertTrue((bool) $result['hasError']);
        $this->assertSame('Insufficient balance.', $result['message']);
        $this->assertSame(0, FakeNodeServer::callCount('/deploySecurityToken'));
        $this->assertSame(0, UserContract::where('property_id', $property->id)->count());
    }

    /**
     * The reported symptom: a deploy that the node reports as successful but
     * without a usable address must not leave the platform half-configured.
     *
     * @test
     */
    public function a_deploy_response_without_a_contract_address_does_not_half_configure_the_token()
    {
        FakeNodeServer::setResponse('/deploySecurityToken', [
            'status'   => 'success',
            'contract' => ['contract' => ['address' => '']],
        ]);

        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY);
        $request  = $this->makeTokenRequest($issuer, $property);

        $result = (new TokenizerService())->deployToken($request->id);

        $this->assertTrue((bool) $result['hasError']);
        $this->assertSame(
            'Deployment succeeded but contract address was missing from node response.',
            $result['message']
        );

        $this->assertSame(0, UserContract::where('property_id', $property->id)->count());
        $this->assertSame('pending', $request->fresh()->status, 'Request was marked live without a contract address.');
        $this->assertSame('pending', $property->fresh()->status, 'Property went active without a contract address.');
    }

    /**
     * The node has historically answered with several response shapes. All of
     * them must yield an address rather than a stuck deployment.
     *
     * @test
     * @dataProvider contractAddressShapes
     */
    public function the_contract_address_is_read_from_every_node_response_shape(array $response, string $expected)
    {
        $this->assertSame($expected, TokenizerService::extractContractAddress($response));
    }

    public function contractAddressShapes(): array
    {
        return [
            'nested contract.contract.address' => [
                ['contract' => ['contract' => ['address' => '0xAAA']]], '0xAAA',
            ],
            'contract.address' => [
                ['contract' => ['address' => '0xBBB']], '0xBBB',
            ],
            'flat contract_address (used by the deploy cron)' => [
                ['contract_address' => '0xCCC'], '0xCCC',
            ],
            'flat address' => [
                ['address' => '0xDDD'], '0xDDD',
            ],
            'whitespace is trimmed' => [
                ['contract_address' => '  0xEEE  '], '0xEEE',
            ],
        ];
    }

    /** @test */
    public function utility_tokens_are_deployed_through_the_utility_contract_endpoint()
    {
        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_UTILITY);
        $request  = $this->makeTokenRequest($issuer, $property);

        $result = (new TokenizerService())->deployToken($request->id);

        $this->assertFalse((bool) $result['hasError'], $result['message'] ?? '');
        $this->assertSame(1, FakeNodeServer::callCount('/deployUtilityToken'));
        $this->assertSame(0, FakeNodeServer::callCount('/deploySecurityToken'));
        $this->assertSame(
            FakeNodeServer::UTILITY_CONTRACT,
            UserContract::where('property_id', $property->id)->first()->contract_address
        );
    }

    /**
     * A successful deploy has to flip token_deploy_status to '1'.
     *
     * token_deploy_status is enum('0','1'). MySQL reads a bound *integer* against
     * an enum as a 1-based index, so writing the integer 1 stores the first
     * member — '0' — and the row still reads as "never deployed".
     *
     * @test
     */
    public function a_successful_deploy_marks_the_request_as_deployed()
    {
        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY);
        $request  = $this->makeTokenRequest($issuer, $property);

        (new TokenizerService())->deployToken($request->id);

        $stored = \DB::table('issuer_token_requests')->where('id', $request->id)->value('token_deploy_status');

        $this->assertSame('1', (string) $stored, 'token_deploy_status was not set to 1 after a successful deploy.');
    }

    /**
     * The recovery cron selects on token_deploy_status = 0. The same enum/integer
     * mismatch applies on the read side: an integer 0 is index 0, which is the
     * enum's invalid member, so the selector must still match a pending row.
     *
     * @test
     */
    public function the_deploy_cron_selects_tokens_that_have_not_been_deployed()
    {
        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY);
        $request  = $this->makeTokenRequest($issuer, $property);

        $this->assertTrue(
            IssuerTokenRequest::where('token_deploy_status', 0)->where('id', $request->id)->exists(),
            'The cron selector does not match an undeployed token request, so the recovery cron never runs.'
        );
    }

    /**
     * The deploy cron picks up anything left undeployed and must finish it the
     * same way the direct issuer flow does.
     *
     * @test
     */
    public function the_deploy_cron_completes_tokens_left_undeployed()
    {
        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY);
        $request  = $this->makeTokenRequest($issuer, $property);

        $this->artisan('token:deploy');

        $contract = UserContract::where('property_id', $property->id)->first();

        $this->assertNotNull($contract, 'The deploy cron did not deploy the pending token.');
        $this->assertSame(FakeNodeServer::SECURITY_CONTRACT, $contract->contract_address);
        $this->assertSame('live', $request->fresh()->status);
    }

    /**
     * With the deploy flag stuck at '0', a redeploy would mint a second contract
     * for the same property and spend gas again.
     *
     * @test
     */
    public function a_deployed_token_is_not_deployed_a_second_time_by_the_cron()
    {
        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY);
        $request  = $this->makeTokenRequest($issuer, $property);

        (new TokenizerService())->deployToken($request->id);
        $this->artisan('token:deploy');

        $this->assertSame(
            1,
            UserContract::where('property_id', $property->id)->count(),
            'The token was deployed twice — a duplicate contract now exists for one property.'
        );
        $this->assertSame(
            1,
            FakeNodeServer::callCount('/deploySecurityToken'),
            'A second deployment transaction was sent for an already deployed token.'
        );
    }
}
