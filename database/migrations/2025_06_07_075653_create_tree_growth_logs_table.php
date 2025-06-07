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
        Schema::create('tree_growth_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tree_id')
                ->constrained('trees')
                ->onDelete('cascade');
            $table->decimal('height', 8, 2);
            $table->decimal('diameter', 8, 2);
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tree_growth_logs');
    }
};
