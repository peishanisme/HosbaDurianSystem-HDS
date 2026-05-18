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
        Schema::create('harvest_grade', function (Blueprint $table) {
            $table->id();
            $table->uuid('harvest_uuid');
            $table->date('date');
            $table->string('grade')->nullable();

            // Added species_id foreign key
            $table->unsignedBigInteger('species_id')->nullable()->after('grade');
            $table->foreign('species_id')
                  ->references('id')
                  ->on('species')
                  ->onDelete('set null');

            $table->decimal('weight', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harvest_grade');
    }
};