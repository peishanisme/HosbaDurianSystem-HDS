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
        Schema::table('fruit_feedback', function (Blueprint $table) {
            //change the foreign key to reference the tree_uuid instead of fruit_uuid
            $table->dropForeign(['fruit_uuid']);
            $table->dropColumn('fruit_uuid');
            $table->uuid('tree_uuid');
            $table->foreign('tree_uuid')
                ->references('uuid')
                ->on('trees')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fruit_feedback', function (Blueprint $table) {
            //
            $table->dropForeign(['tree_uuid']);
            $table->dropColumn('tree_uuid');
            $table->uuid('fruit_uuid');
            $table->foreign('fruit_uuid')
                ->references('uuid')
                ->on('fruits')
                ->onDelete('cascade');
        });
    }
};
