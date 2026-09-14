<?php

namespace Tests\Feature\ClientScenarios;

use App\Property;
use Tests\Support\ScenarioTestCase;

/**
 * Internal custody is being withdrawn. New assets are external-wallet only: the
 * issuer is no longer offered the choice when setting up an asset, and any asset
 * created without the setting defaults to external as well.
 */
class ExternalWalletDefaultTest extends ScenarioTestCase
{
    /** @var \App\User */
    private $issuer;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.is_demo' => false]);
        view()->share('isDemo', false);

        $this->issuer = $this->makeIssuer();
        $this->makeKeystore($this->issuer);
    }

    /**
     * @test
     * @dataProvider createForms
     */
    public function the_asset_setup_form_does_not_offer_an_internal_wallet(string $path)
    {
        $response = $this->actingAs($this->issuer)->get($path);
        $response->assertStatus(200);

        $this->assertSame(
            ['0'],
            $this->submittedValues($response->getContent()),
            "{$path} should always submit enable_internal_wallet = 0 and offer no choice."
        );
        // Only visible labels count: the page's sample-data script keeps a code
        // comment mentioning the field.
        $labels = [];
        foreach ($this->xpath($response->getContent())->query('//label') as $label) {
            $labels[] = trim(preg_replace('/\s+/', ' ', $label->textContent));
        }
        $this->assertEmpty(
            preg_grep('/Internal Wallet/i', $labels),
            "{$path} still shows the internal wallet option."
        );
    }

    public function createForms(): array
    {
        return [
            'create property token' => ['/issuer/token'],
            'create asset token'    => ['/issuer/asset_fund'],
            'create utility token'  => ['/issuer/utility-token'],
        ];
    }

    /** @test */
    public function the_asset_edit_form_does_not_offer_an_internal_wallet()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);

        $response = $this->actingAs($this->issuer)->get('/issuer/token/' . $contract->property_id);
        $response->assertStatus(200);

        $this->assertSame(['0'], $this->submittedValues($response->getContent()));
    }

    /**
     * Assets created without the setting — the admin property screens, for one —
     * used to fall back to the column default of 1.
     *
     * @test
     */
    public function an_asset_created_without_the_setting_is_external_wallet_only()
    {
        $property = new Property();
        $property->user_id      = $this->issuer->id;
        $property->propertyName = 'Admin-created asset';
        $property->token_type   = self::TYPE_PROPERTY;
        $property->save();

        $this->assertSame(0, (int) $property->fresh()->enable_internal_wallet);
    }

    /** @test */
    public function investors_buying_an_external_only_asset_are_not_offered_internal_custody()
    {
        $contract = $this->deployAsset($this->issuer, self::TYPE_PROPERTY);
        $investor = $this->makeInvestor();

        // Outside demo mode an investor reaches the buy screen only once the
        // issuer has approved their purchase request.
        \App\InvestorWhitelist::create([
            'user_id'        => $investor->id,
            'property_id'    => $contract->property_id,
            'issuer'         => $this->issuer->id,
            'type'           => 'purchase',
            'status'         => 'Approved',
            'wallet_address' => 'Purchase purpose',
            'amount'         => 0,
        ]);

        $response = $this->actingAs($investor)->get('/applyInvest/' . $contract->property_id);
        $response->assertStatus(200);

        $xpath = $this->xpath($response->getContent());

        $internalCard = $xpath->query("//input[@name='custody'][@value='internal']/ancestor::div[contains(@class,'col-md-6')][1]")->item(0);
        $this->assertNotNull($internalCard);
        $this->assertContains('display: none', $internalCard->getAttribute('style'), 'The internal wallet option is visible to investors.');

        $external = $xpath->query("//input[@name='custody'][@value='external']")->item(0);
        $this->assertTrue($external->hasAttribute('checked'), 'External custody is not pre-selected.');
    }

    // ----------------------------------------------------------------- setup

    /** Values of every live (not commented-out) enable_internal_wallet field. */
    private function submittedValues(string $html): array
    {
        $xpath  = $this->xpath($html);
        $values = [];

        foreach ($xpath->query("//input[@name='enable_internal_wallet']") as $input) {
            $type = strtolower($input->getAttribute('type'));
            if ($type === 'hidden' || ($type === 'radio' && $input->hasAttribute('checked'))) {
                $values[] = $input->getAttribute('value');
            }
            if ($type === 'radio') {
                $values[] = 'choice:' . $input->getAttribute('value');
            }
        }

        return $values;
    }

    private function xpath(string $html): \DOMXPath
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8"?>' . $html);
        libxml_clear_errors();

        return new \DOMXPath($dom);
    }
}
