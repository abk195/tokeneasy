<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnsFromBlockchainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('blockchains')->delete();
        Schema::table('blockchains', function (Blueprint $table) {
            // Remove the test_link and production_link columns if they exist
            if (Schema::hasColumn('blockchains', 'test_link')) {
                $table->dropColumn('test_link');
            }
            if (Schema::hasColumn('blockchains', 'production_link')) {
                $table->dropColumn('production_link');
            }


            // Remove the test_link and production_link columns if they exist
            if (Schema::hasColumn('blockchains', 'production_chain_id')) {
                $table->dropColumn('production_chain_id');
            }

            if (Schema::hasColumn('blockchains', 'test_chain_id')) {
                $table->dropColumn('test_chain_id');
            }

            // Add the new link column
            $table->string('link')->nullable()->after('blockchain_name');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blockchains', function (Blueprint $table) {
            // Remove the 'link' column only if it exists
            if (Schema::hasColumn('blockchains', 'link')) {
                $table->dropColumn('link');
            }
        
            // Add 'test_link' only if it doesn't exist
            if (!Schema::hasColumn('blockchains', 'test_link')) {
                $table->string('test_link')->nullable()->after('blockchain_name');
            }
        
            // Add 'production_link' only if it doesn't exist
            if (!Schema::hasColumn('blockchains', 'production_link')) {
                $table->string('production_link')->nullable()->after('test_link');
            }
        });
        
    }
}
