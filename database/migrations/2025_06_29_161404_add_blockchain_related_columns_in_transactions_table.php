<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('blockchain_tx_hash')->nullable()->after('total_price');
            $table->string('blockchain_status')->nullable()->default('pending')->after('blockchain_tx_hash');
            $table->timestamp('synced_at')->nullable()->after('blockchain_status');
            $table->string('reference_id')->after('uuid')->unique();
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'blockchain_tx_hash',
                'blockchain_status',
                'synced_at',
                'reference_id'
            ]);
        });
    }
};
