<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tree_growth_logs', function (Blueprint $table) {
            // First, drop the foreign key constraint before dropping the column
            if (Schema::hasColumn('tree_growth_logs', 'tree_id')) {
                $table->dropForeign(['tree_id']);
                $table->dropColumn('tree_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tree_growth_logs', function (Blueprint $table) {
            // Re-add the column in case you need to roll back
            $table->foreignId('tree_id')
                ->nullable()
                ->constrained('trees')
                ->onDelete('cascade');
        });
    }
};
