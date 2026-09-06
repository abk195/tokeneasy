<?php

/**
 * Deterministic stand-in for the one helper that reaches a third-party service
 * unrelated to the behaviour under test.
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
 * Only fetchCryptoPrices() is replaced — the HTTP boundary itself. The callers
 * that interpret its result (currentCryptoPrices() throwing for the buy flow,
 * HomeController::getCryptoPrices() degrading to zero for display) are real
 * application logic and stay under test. Tests simulate an outage by setting
 * $GLOBALS['__test_crypto_prices'] to null.
 *
 * callNodeOperations() is deliberately NOT stubbed: the node calls are the thing
 * being verified, and they are served by the local stub server in
 * tests/Support/Stub/node-stub-server.php.
 */

if (!function_exists('fetchCryptoPrices')) {
    /**
     * The real implementation calls min-api.cryptocompare.com.
     *
     * @return array<string,float>|null
     */
    function fetchCryptoPrices()
    {
        if (array_key_exists('__test_crypto_prices', $GLOBALS)) {
            // Lets a test simulate an unexpected failure inside a caller, rather
            // than the handled "feed unavailable" case that null represents.
            if ($GLOBALS['__test_crypto_prices'] === '__throw__') {
                throw new \RuntimeException('Simulated price feed failure.');
            }

            return $GLOBALS['__test_crypto_prices'];
        }

        return testCryptoPriceDefaults();
    }

    function testCryptoPriceDefaults()
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
