<?php

/**
 * Bootstrap for the client acceptance suite.
 *
 * The real work happens in tests/prepend.php, which must be loaded before
 * Composer's autoloader pulls in app/helper.php. This file only verifies that it
 * was, so a misconfigured run fails with an explanation instead of silently
 * depending on a live crypto price feed.
 */

require __DIR__ . '/prepend.php';
require __DIR__ . '/../vendor/autoload.php';

if (!defined('TOKENEASY_TEST_PRICES_STUBBED')) {
    fwrite(STDERR, PHP_EOL . implode(PHP_EOL, [
        'The client acceptance suite was started without tests/prepend.php.',
        '',
        'app/helper.php was loaded first, so currentCryptoPrices() will call',
        'min-api.cryptocompare.com during every buy-flow test. Re-run with:',
        '',
        '    php -d auto_prepend_file=tests/prepend.php vendor/bin/phpunit -c phpunit.client-scenarios.xml',
        '',
    ]) . PHP_EOL);

    exit(1);
}
