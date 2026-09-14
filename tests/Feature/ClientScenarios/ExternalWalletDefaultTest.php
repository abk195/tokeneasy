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

        $this->assertPostsExternalOnly($response->getContent(), $path);
        $this->assertNoCustodyWording($response->getContent(), $path);
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

        $this->assertPostsExternalOnly($response->getContent(), 'the edit form');
        $this->assertNoCustodyWording($response->getContent(), 'the edit form');
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

    /**
     * What the form actually submits for the setting.
     *
     * The create-property form posts new FormData(form) and the others call
     * form.submit(), so only fields inside form#property-create are sent. There
     * must be exactly one, fixed at 0, with no choice offered.
     */
    private function assertPostsExternalOnly(string $html, string $where)
    {
        $xpath = $this->xpath($html);

        $form = $xpath->query("//form[@id='property-create']")->item(0);
        $this->assertNotNull($form, "{$where} has no form#property-create.");

        $posted = $xpath->query(".//input[@name='enable_internal_wallet']", $form);
        $this->assertSame(1, $posted->length, "{$where} should post exactly one enable_internal_wallet field.");
        $this->assertSame('hidden', $posted->item(0)->getAttribute('type'), "{$where} still offers a choice.");
        $this->assertSame('0', $posted->item(0)->getAttribute('value'), "{$where} does not post external-only.");

        $this->assertSame(
            1,
            $xpath->query("//input[@name='enable_internal_wallet']")->length,
            "{$where} has an enable_internal_wallet field outside the form."
        );
    }

    /** Visible text only: the sample-data script keeps a code comment naming the field. */
    private function assertNoCustodyWording(string $html, string $where)
    {
        $text = '';
        foreach ($this->xpath($html)->query('//body//text()[not(ancestor::script) and not(ancestor::style)]') as $node) {
            $text .= ' ' . $node->textContent;
        }
        $text = preg_replace('/\s+/', ' ', $text);

        $this->assertNotRegExp('/Wallet Custody/i', $text, "{$where} still shows the Wallet Custody heading.");
        $this->assertNotRegExp('/internal wallet/i', $text, "{$where} still describes the internal wallet.");
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
