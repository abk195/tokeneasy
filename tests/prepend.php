<?php

/**
 * Deterministic stand-ins for the helpers in app/helper.php that reach
 * third-party services unrelated to the behaviour under test.
 *
 * app/helper.php declares its helpers behind function_exists() guards, so
 * whichever definition loads first wins. Composer's binary proxy requires
 * vendor/autoload.php before PHPUnit reads its own bootstrap, so this file has
 * to be loaded earlier still, via auto_prepend_file:
 *
 *     php -d auto_prepend_file=tests/prepend.php vendor/bin/phpunit -c phpunit.client-scenarios.xml
 *
 * tests/bootstrap.php checks that this actually happened and fails loudly if it
 * did not, rather than letting the suite depend on a live price feed.
 *
 * callNodeOperations() is deliberately NOT stubbed here: the node calls are the
 * thing being verified, and they are served by the local stub server in
 * tests/Support/Stub/node-stub-server.php.
 */

if (!function_exists('currentCryptoPrices')) {
    /**
     * The real implementation calls min-api.cryptocompare.com on every buy
     * request, with no handling for a failed or rate-limited response.
     */
    function currentCryptoPrices()
    {
        return [
            'ETH'   => 2000.0,
            'BNB'   => 500.0,
            'MATIC' => 0.5,
            'USD'   => 1.0,
        ];
    }

    define('TOKENEASY_TEST_PRICES_STUBBED', true);
}
