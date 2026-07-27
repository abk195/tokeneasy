<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestTokenRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_token_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('wallet_address');
            $table->unsignedInteger('blockchain_id'); // Reference to blockchains table
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->decimal('tokens_sent', 15, 8)->default(2000); // Fixed 2000 tokens
            $table->string('transaction_hash')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'wallet_address']);
            $table->index('status');

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('blockchain_id')->references('id')->on('blockchains')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_token_requests');
    }
}