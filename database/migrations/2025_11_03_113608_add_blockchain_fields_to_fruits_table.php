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
        Schema::table('fruits', function (Blueprint $table) {
             // IPFS CID (e.g. Qm... ) or short storage id; nullable until published
            $table->string('metadata_cid')->nullable()->after('is_spoiled');

            // Keccak256 or sha256 hex string ("0x" + 64 hex chars) - used for verification
            $table->string('metadata_hash', 66)->nullable()->after('metadata_cid');

            // Transaction hash for the on-chain call (0x...)
            $table->string('tx_hash', 66)->nullable()->after('metadata_hash');

            // Timestamp when record was written on-chain
            $table->timestamp('onchain_at')->nullable()->after('contract_address');

            // Bool flag to indicate whether this fruit has an on-chain anchor
            $table->boolean('is_onchain')->default(false)->after('onchain_at');

            // Version counter for metadata (v1, v2, etc.). Increment when you publish a new metadata JSON.
            $table->unsignedInteger('metadata_version')->default(1)->after('is_onchain');

            // Optional: small index to speed up lookups by cid/hash/tx
            $table->index('metadata_cid');
            $table->index('metadata_hash');
            $table->index('tx_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fruits', function (Blueprint $table) {
            $table->dropIndex(['metadata_cid']);
            $table->dropIndex(['metadata_hash']);
            $table->dropIndex(['tx_hash']);

            $table->dropColumn([
                'metadata_cid',
                'metadata_hash',
                'tx_hash',
                'onchain_at',
                'is_onchain',
                'metadata_version',
            ]);
        });
    }
};
