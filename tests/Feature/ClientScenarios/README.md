# Client acceptance suite

Covers the token-deployment bug report and the seven follow-up scenarios the
client sent, against the `token_deployment_fix` branch.

## Running

```
mysql -uroot -e "CREATE DATABASE tokeneasy_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
DB_DATABASE=tokeneasy_test php artisan migrate

php -d auto_prepend_file=tests/prepend.php vendor/bin/phpunit -c phpunit.client-scenarios.xml
```

The schema is migrated once; every test runs inside a transaction that is rolled
back afterwards, so the database stays clean and the suite is re-runnable.

`-d auto_prepend_file=tests/prepend.php` is required. Without it the suite exits
with an explanation instead of running — see below.

## How the blockchain is handled

`callNodeOperations()` builds its own Guzzle client, so it cannot be mocked.
Instead `tests/Support/FakeNodeServer.php` starts a real HTTP server
(`tests/Support/Stub/node-stub-server.php`) on a free port and points
`config('app.nodeApp')` at it. That means:

* the genuine request/response path is exercised, including the helper's
  behaviour of throwing when the node reports anything but `status=success`;
* every request the platform sends to the chain is recorded, so a test can
  assert *"a transfer of 40 tokens of this contract went to this address"*
  rather than just checking a database row;
* each endpoint's response can be scripted per test — see
  `FakeNodeServer::setResponse()` and `queueResponses()`.

`tests/prepend.php` is the one other stub. `currentCryptoPrices()` calls
min-api.cryptocompare.com on every buy request; it is pinned to fixed rates so
the suite does not depend on a third-party feed. It has to be loaded via
`auto_prepend_file` because Composer's binary proxy requires `vendor/autoload.php`
— and therefore `app/helper.php` — before PHPUnit reads its own bootstrap, and
the helpers are declared behind `function_exists()` guards.

## Files

| File | Client item |
| --- | --- |
| `Bug01TokenDeploymentTest.php` | Bug 1 — direct deploy, contract address assigned and visible, token leaves Pending Assets |
| `Scenario01TokenTypesVisibilityTest.php` | 1 — all three token types deploy and are visible to investors |
| `Scenario02KeystoreAndPaymentSetupTest.php` | 2 — keystores, bank accounts and crypto wallets attached to an asset |
| `Scenario03BuyProcessTest.php` | 3 — manual bank and automated crypto buy routes end in a real token transfer |
| `Scenario04CustodyAndWhitelistTest.php` | 4 — internal vs external custody, whitelisting, utility tokens exempt from whitelisting |
| `Scenario05InternalWalletTransferTest.php` | 5 — internal custodian → external wallet transfers |
| `Scenario06RegistrationKycAndAlertsTest.php` | 6 — registrations, admin KYC review, emails, dashboard alerts |
| `Scenario07DashboardStatisticsTest.php` | 7 — issuer and investor dashboard statistics |

## Reading the results

A failing test here is a report, not a broken test. Each failure message names
the behaviour that is missing and, where the cause is a specific line of
application code, the docblock above the test explains it.
