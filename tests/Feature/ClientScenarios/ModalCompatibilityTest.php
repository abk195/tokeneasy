<?php

namespace Tests\Feature\ClientScenarios;

use App\InvestorShares;
use Tests\Support\ScenarioTestCase;

/**
 * Modal open/close controls must work under Bootstrap 5.
 *
 * The investor, issuer, front and auth layouts load Bootstrap 5, which only reacts
 * to data-bs-dismiss / data-bs-toggle / data-bs-target. Much of the markup was
 * written for Bootstrap 4 (data-dismiss / data-toggle / data-target), so close
 * buttons did nothing and some "open" buttons never opened their modal. Reported
 * first on the investor /investment page, where neither the × nor the Cancel
 * button closed the transfer or external-wallet modals.
 *
 * The fix keeps each Bootstrap 4 attribute and adds its Bootstrap 5 twin, so the
 * same markup works on every layout. Admin views are excluded: they load
 * Bootstrap 3, where data-dismiss is correct.
 */
class ModalCompatibilityTest extends ScenarioTestCase
{
    /** @test */
    public function the_investment_page_modals_can_be_closed()
    {
        $issuer   = $this->makeIssuer();
        $investor = $this->makeInvestor();
        $contract = $this->deployAsset($issuer, self::TYPE_PROPERTY);

        InvestorShares::create([
            'user_id'          => $investor->id,
            'user_contract_id' => $contract->id,
            'internal_wallet'  => 10,
            'external_wallet'  => 0,
        ]);

        $response = $this->actingAs($investor)->get('/investment');
        $response->assertStatus(200);

        $xpath = $this->xpath($response->getContent());

        foreach (['transferModal', 'viewExternalWalletsModal'] as $modalId) {
            $modal = $xpath->query("//*[@id='{$modalId}']")->item(0);
            $this->assertNotNull($modal, "#{$modalId} is not on the investment page.");

            $closers = $xpath->query(".//*[@data-bs-dismiss='modal']", $modal);
            $this->assertGreaterThan(
                0,
                $closers->length,
                "#{$modalId} has no control Bootstrap 5 will treat as a close button."
            );
        }
    }

    /**
     * Every modal control outside the Bootstrap 3 admin area carries the
     * Bootstrap 5 attribute, so no page can regress to an inert close button.
     *
     * @test
     */
    public function no_view_uses_a_bootstrap_4_modal_control_without_its_bootstrap_5_twin()
    {
        $offenders = [];

        foreach ($this->nonAdminViews() as $path) {
            $source = file_get_contents($path);

            // Tags are bounded by the next '<' rather than '>', because Blade
            // expressions such as {{ $item->id }} contain '>'.
            if (!preg_match_all('/<[a-zA-Z][^<]*/', $source, $tags, PREG_OFFSET_CAPTURE)) {
                continue;
            }

            foreach ($tags[0] as list($tag, $offset)) {
                $line = substr_count(substr($source, 0, $offset), "\n") + 1;

                if (preg_match('/(?<!\[)data-dismiss\s*=\s*"modal"/', $tag) && strpos($tag, 'data-bs-dismiss') === false) {
                    $offenders[] = $this->relative($path) . ":{$line}  close control without data-bs-dismiss";
                }

                if (preg_match('/(?<!\[)data-toggle\s*=\s*"modal"/', $tag)) {
                    if (strpos($tag, 'data-bs-toggle') === false) {
                        $offenders[] = $this->relative($path) . ":{$line}  open control without data-bs-toggle";
                    }
                    if (strpos($tag, 'data-target') !== false && strpos($tag, 'data-bs-target') === false) {
                        $offenders[] = $this->relative($path) . ":{$line}  open control without data-bs-target";
                    }
                }
            }
        }

        $this->assertSame(
            [],
            $offenders,
            "These modal controls do nothing under Bootstrap 5:\n  " . implode("\n  ", $offenders)
        );
    }

    // ----------------------------------------------------------------- setup

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
        $dom->loadHTML($html);
        libxml_clear_errors();

        return new \DOMXPath($dom);
    }
}
