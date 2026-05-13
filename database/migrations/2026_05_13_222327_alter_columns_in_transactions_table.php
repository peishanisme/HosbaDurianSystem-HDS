<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['buyer_uuid']);
            $table->dropColumn(['buyer_uuid','blockchain_tx_hash','blockchain_status','synced_at','total_price']);
            $table->decimal('total_weight', 15, 2)->nullable()->after('date');
            $table->decimal('total_amount', 15, 2)->nullable()->after('total_weight');
            $table->json('grade_breakdown')->nullable()->after('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->uuid('buyer_uuid');
            $table->string('blockchain_tx_hash')->nullable()->after('grade_breakdown');
            $table->string('blockchain_status')->nullable()->after('blockchain_tx_hash');
            $table->timestamp('synced_at')->nullable()->after('blockchain_status');
            $table->decimal('total_price', 15, 2)->nullable()->after('total_amount');
             $table->dropColumn(['total_weight','total_amount','grade_breakdown']);
             $table->foreign('buyer_uuid')
                ->references('uuid')
                ->on('buyers')
                ->onDelete('cascade');
        });
    }
};
