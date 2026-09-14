<?php

namespace Tests\Feature\ClientScenarios;

use Illuminate\Http\Request;
use Tests\Support\ScenarioTestCase;

/**
 * The top-right account menu (resources/views/issuer/layout/navbar.blade.php) is
 * shared by the issuer and investor layouts, while each role's Profile and
 * Security pages sit behind that role's middleware:
 *
 *   /issuer/profile, /issuer/security  seller    -> others sent to /dashboard
 *   /profile, /security                investor  -> others sent to issuer/token-demo
 *
 * The links first pointed at the investor pages, which bounced issuers to the
 * demo token page. Pointing them at the issuer pages then bounced investors to
 * their dashboard. Both roles are tested together here so that fixing one side
 * cannot quietly break the other.
 */
class AccountLinksTest extends ScenarioTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.is_demo' => false]);
        view()->share('isDemo', false);
    }

    /**
     * @test
     * @dataProvider accountLinks
     */
    public function each_role_opens_its_own_account_pages_from_the_top_menu(string $role, string $label, string $expectedPath)
    {
        $user  = $this->userFor($role);
        $xpath = $this->xpath($this->actingAs($user)->get($this->homeFor($role))->getContent());

        $link = $xpath->query("//nav[@id='layout-navbar']//a[contains(@class,'dropdown-item')][span[normalize-space(.)='{$label}']]")->item(0);
        $this->assertNotNull($link, "No {$label} link in the {$role} top menu.");
        $this->assertSame($expectedPath, parse_url($link->getAttribute('href'), PHP_URL_PATH), "The {$role} {$label} link points at the wrong page.");

        $response = $this->actingAs($user)->get($expectedPath);
        $this->assertSame(
            200,
            $response->getStatusCode(),
            "An {$role} following {$label} was redirected to " . $response->headers->get('Location')
        );
    }

    public function accountLinks(): array
    {
        return [
            'issuer profile'    => ['issuer', 'Profile', '/issuer/profile'],
            'issuer security'   => ['issuer', 'Security', '/issuer/security'],
            'investor profile'  => ['investor', 'Profile', '/profile'],
            'investor security' => ['investor', 'Security', '/security'],
        ];
    }

    /**
     * No link in either panel's sidebar or top menu may lead to a page guarded for
     * the other role.
     *
     * @test
     * @dataProvider roles
     */
    public function no_link_sends_a_user_to_the_other_roles_pages(string $role, string $forbiddenMiddleware)
    {
        $html  = $this->actingAs($this->userFor($role))->get($this->homeFor($role))->getContent();
        $xpath = $this->xpath($html);

        $offenders = [];
        foreach ($xpath->query("//aside[@id='layout-menu']//a[@href] | //nav[@id='layout-navbar']//a[@href]") as $link) {
            $href = $link->getAttribute('href');
            $path = parse_url($href, PHP_URL_PATH);
            if (!$path || strpos($href, 'javascript:') === 0) {
                continue;
            }

            try {
                $route = app('router')->getRoutes()->match(Request::create($path, 'GET'));
            } catch (\Throwable $e) {
                continue; // not a GET page, e.g. logout
            }

            if (in_array($forbiddenMiddleware, $route->gatherMiddleware(), true)) {
                $offenders[] = trim(preg_replace('/\s+/', ' ', $link->textContent)) . ' -> ' . $path;
            }
        }

        $this->assertSame([], $offenders, "Links in the {$role} panel that lead to pages only the other role can open.");
    }

    public function roles(): array
    {
        return [
            'issuer'   => ['issuer', 'investor'],
            'investor' => ['investor', 'seller'],
        ];
    }

    /**
     * Several route names are registered by both panels — dashboard, profile,
     * profile_update and others — and Laravel keeps the last one, which is the
     * issuer's. route('dashboard') in the investor sidebar therefore built
     * /issuer/dashboard. Investor pages must link to these by URL instead.
     *
     * @test
     */
    public function investor_pages_do_not_link_by_a_route_name_the_issuer_panel_overrides()
    {
        $shadowed = [];
        foreach (app('router')->getRoutes()->getRoutesByName() as $name => $route) {
            if (strpos($route->uri(), 'issuer/') === 0 && app('router')->getRoutes()->getByName($name) === $route) {
                $shadowed[] = $name;
            }
        }

        // Count only live route definitions; commented-out lines do not register a name.
        $liveLines = array_filter(file(base_path('routes/web.php')), function ($line) {
            return strpos(ltrim($line), '//') !== 0;
        });
        $webFile = implode('', $liveLines);

        $shared = array_values(array_filter($shadowed, function ($name) use ($webFile) {
            return substr_count($webFile, "->name('{$name}')") > 1;
        }));

        $this->assertNotEmpty($shared, 'Expected to find route names shared by both panels.');

        $offenders = [];
        $root      = resource_path('views');
        $iterator  = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $file) {
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
            if (substr($relative, -4) !== '.php' || strpos($relative, 'issuer/') === 0 || strpos($relative, 'admin/') === 0) {
                continue;
            }

            $source = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', file_get_contents($file->getPathname()));
            foreach ($shared as $name) {
                if (preg_match("/route\\(\\s*['\"]" . preg_quote($name, '/') . "['\"]/", $source)) {
                    $offenders[] = "resources/views/{$relative} uses route('{$name}'), which resolves to /" . app('router')->getRoutes()->getByName($name)->uri();
                }
            }
        }

        $this->assertSame([], $offenders, 'Investor pages linking to issuer URLs through a shared route name.');
    }

    // ----------------------------------------------------------------- setup

    private function userFor(string $role)
    {
        return $role === 'issuer' ? $this->makeIssuer() : $this->makeInvestor();
    }

    /** A page in each panel that renders its layout without extra data. */
    private function homeFor(string $role): string
    {
        return $role === 'issuer' ? '/issuer/dashboard' : '/profile';
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
