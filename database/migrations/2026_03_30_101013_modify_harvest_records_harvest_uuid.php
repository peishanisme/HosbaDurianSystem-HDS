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
        // We must drop dependent foreign keys first (tree_observations.harvest_uuid)
        Schema::table('tree_observations', function (Blueprint $table) {
            try {
                $table->dropForeign('tree_observations_harvest_uuid_foreign');
            } catch (\Throwable $e) {
                // ignore if not present
            }
        });

        Schema::table('harvest_records', function (Blueprint $table) {
            // Drop unique constraint if present. The default Laravel-generated
            // index name for a unique on `harvest_uuid` is
            // `harvest_records_harvest_uuid_unique`.
            try {
                $table->dropUnique('harvest_records_harvest_uuid_unique');
            } catch (\Throwable $e) {
                // ignore if unique not present
            }

            // Add foreign key referencing harvest_events.uuid
            try {
                $table->foreign('harvest_uuid')->references('uuid')->on('harvest_events')->onDelete('cascade');
            } catch (\Throwable $e) {
                // ignore if FK already exists
            }
        });

        // Recreate tree_observations foreign key to reference harvest_events.uuid
        Schema::table('tree_observations', function (Blueprint $table) {
            try {
                $table->foreign('harvest_uuid')->references('uuid')->on('harvest_events')->onDelete('cascade');
            } catch (\Throwable $e) {
                // ignore if FK already exists
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop FKs pointing to harvest_events and restore original state
        Schema::table('tree_observations', function (Blueprint $table) {
            try {
                $table->dropForeign(['harvest_uuid']);
            } catch (\Throwable $e) {
                // ignore
            }
        });

        Schema::table('harvest_records', function (Blueprint $table) {
            try {
                $table->dropForeign(['harvest_uuid']);
            } catch (\Throwable $e) {
                // ignore
            }

            try {
                $table->unique('harvest_uuid');
            } catch (\Throwable $e) {
                // ignore
            }
        });

        Schema::table('tree_observations', function (Blueprint $table) {
            // Restore foreign key to point to harvest_records.harvest_uuid
            try {
                $table->foreign('harvest_uuid')->references('harvest_uuid')->on('harvest_records')->onDelete('cascade');
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }
};
