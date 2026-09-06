<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * user_tokens.status was enum('inProgress','inReview','reject','success').
 *
 * TokenPaymentService writes 'failed' in two places — the transferTokens() catch
 * block and validateTokenTransfer() — but the enum has no such member, so under
 * MySQL strict mode the write was rejected. A purchase that failed on-chain was
 * left sitting at 'inReview', indistinguishable from one waiting on the issuer,
 * and the write error masked the original failure.
 *
 * 'failed' is appended rather than inserted so the existing members keep their
 * indices and no stored value changes.
 */
class AddFailedStatusToUserTokens extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `user_tokens`
            MODIFY `status` ENUM('inProgress', 'inReview', 'reject', 'success', 'failed')
            NOT NULL DEFAULT 'inProgress'");
    }

    public function down()
    {
        // Nothing can be left on a member the old column cannot hold.
        DB::statement("UPDATE `user_tokens` SET `status` = 'reject' WHERE `status` = 'failed'");

        DB::statement("ALTER TABLE `user_tokens`
            MODIFY `status` ENUM('inProgress', 'inReview', 'reject', 'success')
            NOT NULL DEFAULT 'inProgress'");
    }
}
