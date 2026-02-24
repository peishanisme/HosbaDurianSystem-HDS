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
        Schema::create('tree_observations', function (Blueprint $table) {
            $table->id();
            $table->uuid('tree_uuid');
            $table->uuid('harvest_uuid')->nullable();
            $table->string('flowering_status', 1)->nullable()->comment('A, B, C, D, X');
            $table->timestamps();

            $table->foreign('tree_uuid')->references('uuid')->on('trees')->onDelete('cascade');
            $table->foreign('harvest_uuid')->references('harvest_uuid')->on('harvest_records')->onDelete('cascade');
            $table->unique(['tree_uuid', 'harvest_uuid']);
            $table->index('tree_uuid');
            $table->index('harvest_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tree_observations');
    }
};
