<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * New assets are external-wallet only.
 *
 * The issuer asset forms now always submit enable_internal_wallet = 0, but assets
 * created elsewhere — the admin PropertyController, for one — never send the
 * field and fell back to this column's default of 1, which offered investors
 * internal custody.
 *
 * Only the default changes. Existing rows keep their current setting; switching
 * existing assets off is a separate decision, because investors may already hold
 * internal balances in them.
 */
class DefaultPropertiesToExternalWalletOnly extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE `properties` MODIFY `enable_internal_wallet` TINYINT(1) NOT NULL DEFAULT 0');
    }

    public function down()
    {
        DB::statement('ALTER TABLE `properties` MODIFY `enable_internal_wallet` TINYINT(1) NOT NULL DEFAULT 1');
    }
}
