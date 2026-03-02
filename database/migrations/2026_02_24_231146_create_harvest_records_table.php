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
        Schema::create('harvest_records', function (Blueprint $table) {
            $table->id();
            $table->uuid('harvest_uuid')->unique();
            $table->uuid('tree_uuid');
            $table->date('harvest_date');
            $table->integer('num_of_fruits')->default(0);
            $table->decimal('weight', 10, 2)->nullable();
            $table->boolean('spoilt')->default(false);
            $table->timestamps();

            $table->foreign('tree_uuid')->references('uuid')->on('trees')->onDelete('cascade');
            $table->index('harvest_uuid');
            $table->index('tree_uuid');
            $table->index('harvest_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harvest_records');
    }
};
