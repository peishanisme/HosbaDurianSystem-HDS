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
        Schema::create('trees', function (Blueprint $table) {
            $table->uuid();
            $table->string('tree_tag')->unique();
            $table->foreignId('species_id')
                ->constrained('species')
                ->onDelete('cascade');
            $table->date('planted_at');
            $table->string('thumbnail')->nullable();
            $table->decimal('latitude', 9, 6)->nullable();  
            $table->decimal('longitude', 9, 6)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trees');
    }
};
