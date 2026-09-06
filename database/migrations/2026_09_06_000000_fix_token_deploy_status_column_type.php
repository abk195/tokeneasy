<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * issuer_token_requests.token_deploy_status was enum('0','1').
 *
 * MySQL reads a bound integer against an ENUM as a 1-based index rather than a
 * value, so the application's integer 1 stored the enum's *first* member — '0' —
 * and a successful deploy was recorded as never deployed. On the read side
 * where('token_deploy_status', 0) asked for index 0, the invalid member, and
 * matched no rows at all, which left the recovery cron unable to see anything.
 *
 * The column is a boolean flag, so it becomes TINYINT(1) and the integer
 * comparisons in the application become correct as written.
 *
 * Converting an ENUM straight to a numeric type converts by index too: '0' would
 * become 1 and '1' would become 2, flipping every existing row to "deployed".
 * Going via VARCHAR first preserves the stored strings, and the numeric
 * conversion then parses them by value. The same hazard applies in reverse, so
 * down() takes the same route back.
 */
class FixTokenDeployStatusColumnType extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `issuer_token_requests`
            MODIFY `token_deploy_status` VARCHAR(1) NOT NULL DEFAULT '0'");

        // Guard against anything that was already written as an enum index.
        DB::statement("UPDATE `issuer_token_requests`
            SET `token_deploy_status` = '0'
            WHERE `token_deploy_status` NOT IN ('0', '1')");

        DB::statement("ALTER TABLE `issuer_token_requests`
            MODIFY `token_deploy_status` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `issuer_token_requests`
            MODIFY `token_deploy_status` VARCHAR(1) NOT NULL DEFAULT '0'");

        DB::statement("UPDATE `issuer_token_requests`
            SET `token_deploy_status` = '0'
            WHERE `token_deploy_status` NOT IN ('0', '1')");

        DB::statement("ALTER TABLE `issuer_token_requests`
            MODIFY `token_deploy_status` ENUM('0', '1') NOT NULL DEFAULT '0'");
    }
}
