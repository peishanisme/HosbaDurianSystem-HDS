<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakePlantedAtNullableInTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Try Postgres ALTER first, fall back to MySQL syntax if needed
        try {
            DB::statement('ALTER TABLE trees ALTER COLUMN planted_at DROP NOT NULL');
        } catch (\Throwable $e) {
            try {
                DB::statement('ALTER TABLE trees MODIFY planted_at DATETIME NULL');
            } catch (\Throwable $e) {
                // If neither works, throw so developer can install doctrine/dbal or adjust manually
                throw $e;
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            DB::statement('ALTER TABLE trees ALTER COLUMN planted_at SET NOT NULL');
        } catch (\Throwable $e) {
            try {
                DB::statement('ALTER TABLE trees MODIFY planted_at DATETIME NOT NULL');
            } catch (\Throwable $e) {
                throw $e;
            }
        }
    }
}
