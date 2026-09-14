<?php

namespace Tests\Feature\ClientScenarios;

use Illuminate\Http\Request;
use Tests\Support\ScenarioTestCase;

/**
 * The issuer sidebar: which entry is highlighted for each page, and that Pending
 * Assets is no longer offered now that tokens deploy without admin review.
 *
 * The menu partial is rendered directly against a request for each path, so
 * every page can be checked without having to satisfy that page's own data.
 */
class IssuerMenuTest extends ScenarioTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.is_demo' => false]);
        view()->share('isDemo', false);
    }

    /**
     * @test
     * @dataProvider pages
     */
    public function each_page_highlights_exactly_its_own_menu_entry(string $path, array $expected)
    {
        $this->assertSame(
            $expected,
            $this->activeEntriesFor($path),
            "Wrong menu entries highlighted on /{$path}."
        );
    }

    public function pages(): array
    {
        return [
            'dashboard'              => ['issuer/dashboard', ['Dashboard']],
            'wallet deposit'         => ['issuer/wallet', ['Wallet', 'Deposit']],
            'wallet withdraw'        => ['issuer/withdrawETH', ['Wallet', 'Withdraw']],
            'create asset token'     => ['issuer/asset_fund', ['Create Asset', 'Create Asset token']],
            'create property token'  => ['issuer/token', ['Create Asset', 'Create Property token']],
            'create utility token'   => ['issuer/utility-token', ['Create Asset', 'Create Utility token']],

            // Used to light up Create Property token as well, via issuer/token*.
            'pending assets page'    => ['issuer/tokenRequest', ['Create Asset']],
            'token list'             => ['issuer/tokenList', ['Deployed Assets', 'Assets List']],
            'token edit'             => ['issuer/token/5', ['Deployed Assets', 'Assets List']],
            'token history'          => ['issuer/token_history/5', ['Deployed Assets', 'Assets List']],

            'assets list'            => ['issuer/property', ['Deployed Assets', 'Assets List']],
            'asset detail'           => ['issuer/propertydetails/5', ['Deployed Assets', 'Assets List']],
            'purchase requests'      => ['issuer/purchase_request', ['Deployed Assets', 'Property Purchase Request']],

            'keystore'               => ['issuer/keystore', ['Manage Keystore']],
            'keystore create'        => ['issuer/keystore/create', ['Manage Keystore']],
            'keystore edit'          => ['issuer/keystore/editForm/5', ['Manage Keystore']],

            'banks'                  => ['issuer/payments/settings', ['Payments', 'Add Banks']],
            'add bank'               => ['issuer/payments/settings/addBank', ['Payments', 'Add Banks']],
            'edit bank'              => ['issuer/payments/settings/editBank/5', ['Payments', 'Add Banks']],
            'view bank'              => ['issuer/payments/settings/view/5', ['Payments', 'Add Banks']],
            'crypto addresses'       => ['issuer/payments/settings/crypto', ['Payments', 'Manage Crypto Address']],
            'pending payments'       => ['issuer/propertyBuyRequest', ['Payments', 'Pending Payments']],
            'payment history'        => ['issuer/buy_requests', ['Payments', 'Payment History']],

            'report capital'         => ['issuer/report/capital', ['Reports', 'Capital']],
            'report sales'           => ['issuer/report/sales', ['Reports', 'Sales']],
            'report investors'       => ['issuer/report/investors', ['Reports', 'Investors']],
        ];
    }

    /** @test */
    public function pending_assets_is_not_offered_in_the_menu()
    {
        $this->app->instance('request', Request::create('/issuer/dashboard'));

        $this->assertNotContains(
            'Pending Assets',
            $this->menuLabels(view('issuer.layout.menu')->render()),
            'Pending Assets is still in the issuer menu.'
        );
    }

    /**
     * Create Property token used to carry a gradient call-to-action class left
     * over from demo mode, where it was the only create option enabled. All
     * three create options are now styled alike.
     *
     * @test
     * @dataProvider demoModes
     */
    public function the_three_create_options_are_styled_alike(bool $demo)
    {
        view()->share('isDemo', $demo);
        $this->app->instance('request', Request::create('/issuer/dashboard'));

        $xpath   = $this->xpath(view('issuer.layout.menu')->render());
        $classes = [];

        foreach (['Create Asset token', 'Create Property token', 'Create Utility token'] as $label) {
            $link = $xpath->query("//aside[@id='layout-menu']//a[div[normalize-space(.)='{$label}']]")->item(0);
            $this->assertNotNull($link, "{$label} is missing from the menu.");
            $classes[$label] = trim(preg_replace('/\s+/', ' ', $link->getAttribute('class')));
        }

        $this->assertSame(
            ['menu-link'],
            array_values(array_unique($classes)),
            'The create options are not styled alike: ' . json_encode($classes)
        );
    }

    public function demoModes(): array
    {
        return ['live' => [false], 'demo' => [true]];
    }

    /**
     * The navbar's Profile and Security links pointed at /profile and /security,
     * the investor pages. Their investor middleware sends anyone else to
     * issuer/token-demo, so both links bounced issuers to the demo token page.
     *
     * @test
     */
    public function no_issuer_panel_link_leads_to_an_investor_only_page()
    {
        $issuer = $this->makeIssuer();
        $html   = $this->actingAs($issuer)->get('/issuer/dashboard')->getContent();
        $xpath  = $this->xpath($html);

        $offenders = [];
        $query = "//aside[@id='layout-menu']//a[@href] | //nav[contains(@class,'layout-navbar')]//a[@href]";

        foreach ($xpath->query($query) as $link) {
            $path = parse_url($link->getAttribute('href'), PHP_URL_PATH);
            if (!$path || strpos($link->getAttribute('href'), 'javascript:') === 0) {
                continue;
            }

            try {
                $route = app('router')->getRoutes()->match(Request::create($path, 'GET'));
            } catch (\Throwable $e) {
                continue; // not a GET page (e.g. logout)
            }

            if (in_array('investor', $route->gatherMiddleware(), true)) {
                $offenders[] = trim(preg_replace('/\s+/', ' ', $link->textContent)) . ' -> ' . $path;
            }
        }

        $this->assertSame([], $offenders, 'Issuer panel links that send issuers to investor-only pages.');
    }

    /**
     * @test
     * @dataProvider accountPages
     */
    public function an_issuer_can_open_their_account_pages_from_the_navbar(string $label, string $expectedPath)
    {
        $issuer = $this->makeIssuer();
        $xpath  = $this->xpath($this->actingAs($issuer)->get('/issuer/dashboard')->getContent());

        $link = $xpath->query("//a[contains(@class,'dropdown-item')][span[normalize-space(.)='{$label}']]")->item(0);
        $this->assertNotNull($link, "No {$label} link in the issuer navbar.");

        $path = parse_url($link->getAttribute('href'), PHP_URL_PATH);
        $this->assertSame($expectedPath, $path);

        $response = $this->actingAs($issuer)->get($path);
        $this->assertSame(200, $response->getStatusCode(), "{$label} redirected to " . $response->headers->get('Location'));
    }

    public function accountPages(): array
    {
        return [
            'profile'  => ['Profile', '/issuer/profile'],
            'security' => ['Security', '/issuer/security'],
        ];
    }

    /** @test */
    public function the_highlight_is_the_same_in_a_full_page_render()
    {
        $issuer = $this->makeIssuer();

        $response = $this->actingAs($issuer)->get('/issuer/keystore');
        $response->assertStatus(200);

        $this->assertSame(['Manage Keystore'], $this->activeEntries($response->getContent()));
    }

    // ----------------------------------------------------------------- setup

    private function activeEntriesFor(string $path): array
    {
        $this->app->instance('request', Request::create('/' . $path));

        return $this->activeEntries(view('issuer.layout.menu')->render());
    }

    private function activeEntries(string $html): array
    {
        $xpath = $this->xpath($html);
        $found = [];

        $query = "//aside[@id='layout-menu']//li[contains(concat(' ', normalize-space(@class), ' '), ' active ')]";
        foreach ($xpath->query($query) as $item) {
            $label = $xpath->query('./a/div', $item)->item(0);
            $found[] = $label ? trim($label->textContent) : '?';
        }

        return $found;
    }

    private function menuLabels(string $html): array
    {
        $xpath  = $this->xpath($html);
        $labels = [];

        foreach ($xpath->query("//aside[@id='layout-menu']//li/a/div") as $label) {
            $labels[] = trim($label->textContent);
        }

        return $labels;
    }

    private function xpath(string $html): \DOMXPath
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        return new \DOMXPath($dom);
    }
}
