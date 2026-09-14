<?php

namespace Tests\Feature\ClientScenarios;

use Tests\Support\ScenarioTestCase;

/**
 * Tabs must be visible and switchable under Bootstrap 5.3 and the Sneat theme.
 *
 * The theme's core.css renders every .tab-pane inside .tab-content at opacity 0
 * unless it also has .show. Much of the markup was written for Bootstrap 3/4: the
 * pane that starts open was marked "active" or "active in" but never "show", and
 * the tab links used data-toggle="tab", which Bootstrap 5 ignores. The result was
 * a tab strip over an empty box — reported on the issuer profile, where Profile
 * Update, KYC and Change Password all appeared blank. The investor profile had
 * the same fault.
 *
 * Bootstrap 5.3 adds .show to a pane when it is switched to, so only the pane that
 * starts open needs it in the markup. Admin views load Bootstrap 3 and are
 * excluded.
 */
class TabVisibilityTest extends ScenarioTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.is_demo' => false]);
        view()->share('isDemo', false);
    }

    /** @test */
    public function the_issuer_profile_opens_on_a_visible_tab_and_every_tab_can_be_switched_to()
    {
        $html = $this->actingAs($this->makeIssuer())->get('/issuer/profile')->getContent();

        $this->assertSame(['home-b2'], $this->visiblePanes($html), 'The issuer profile does not open on a visible Profile Update tab.');
        $this->assertTabsSwitchable($html, ['home-b2', 'kyc', 'profile-b2']);
    }

    /** @test */
    public function the_investor_profile_opens_on_the_identity_tab_for_individuals()
    {
        $investor = $this->makeInvestor(['account_type' => 'individual']);

        $html = $this->actingAs($investor)->get('/profile')->getContent();

        $this->assertContains('identity', $this->visiblePanes($html), 'The investor Identity tab is not visible.');
    }

    /** @test */
    public function the_investor_profile_opens_on_the_company_tab_for_companies()
    {
        $investor = $this->makeInvestor(['account_type' => 'company']);

        $html = $this->actingAs($investor)->get('/profile')->getContent();

        $this->assertContains('company-user', $this->visiblePanes($html), 'The investor Company tab is not visible.');
    }

    /**
     * Every tab outside the Bootstrap 3 admin area can be switched to, and every
     * pane that starts open is visible.
     *
     * @test
     */
    public function no_view_has_a_tab_that_bootstrap_5_cannot_show()
    {
        $offenders = [];

        foreach ($this->nonAdminViews() as $path) {
            // Blade comments never reach the browser.
            $source = preg_replace_callback('/\{\{--[\s\S]*?--\}\}/', function ($m) {
                return str_repeat("\n", substr_count($m[0], "\n"));
            }, file_get_contents($path));

            foreach (explode("\n", $source) as $i => $line) {
                $where = $this->relative($path) . ':' . ($i + 1);

                if (preg_match('/(?<!\[)data-toggle\s*=\s*"(tab|pill)"/', $line) && strpos($line, 'data-bs-toggle') === false) {
                    $offenders[] = "{$where}  tab link without data-bs-toggle";
                }

                if (preg_match_all('/class\s*=\s*"([^"]*\btab-pane\b[^"]*)"/', $line, $panes)) {
                    foreach ($panes[1] as $classes) {
                        if (preg_match('/\bactive\b/', $classes) && !preg_match('/\bshow\b/', $classes)) {
                            $offenders[] = "{$where}  pane starts active without show (invisible under the theme)";
                        }
                    }
                }
            }
        }

        $this->assertSame([], $offenders, "These tabs cannot be shown under Bootstrap 5:\n  " . implode("\n  ", $offenders));
    }

    // ----------------------------------------------------------------- setup

    /** Ids of panes inside .tab-content that the theme will actually display. */
    private function visiblePanes(string $html): array
    {
        $xpath   = $this->xpath($html);
        $visible = [];

        $query = "//*[contains(concat(' ', normalize-space(@class), ' '), ' tab-content ')]"
               . "//*[contains(concat(' ', normalize-space(@class), ' '), ' tab-pane ')]";

        foreach ($xpath->query($query) as $pane) {
            $classes = ' ' . preg_replace('/\s+/', ' ', $pane->getAttribute('class')) . ' ';
            if (strpos($classes, ' active ') !== false && strpos($classes, ' show ') !== false) {
                $visible[] = $pane->getAttribute('id');
            }
        }

        return $visible;
    }

    private function assertTabsSwitchable(string $html, array $expectedPanes)
    {
        $xpath   = $this->xpath($html);
        $targets = [];

        foreach ($xpath->query("//a[@data-bs-toggle='tab']") as $link) {
            $target = ltrim($link->getAttribute('data-bs-target') ?: $link->getAttribute('href'), '#');
            $this->assertNotNull(
                $xpath->query("//*[@id='{$target}']")->item(0),
                "Tab link points at #{$target}, which is not on the page."
            );
            $targets[] = $target;
        }

        $this->assertSame($expectedPanes, $targets, 'Not every tab can be switched to.');
    }

    private function nonAdminViews(): array
    {
        $root  = resource_path('views');
        $files = [];

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
            if (substr($relative, -4) === '.php' && strpos($relative, 'admin/') !== 0) {
                $files[] = $file->getPathname();
            }
        }

        sort($files);

        return $files;
    }

    private function relative(string $path): string
    {
        return str_replace('\\', '/', substr($path, strlen(base_path()) + 1));
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
