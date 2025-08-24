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
        Schema::create('tree_disease', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tree_uuid')->constrained()->onDelete('cascade');
        $table->foreignId('disease_id')->constrained()->onDelete('cascade');
        $table->timestamps();

        $table->unique(['tree_id', 'disease_id']); // prevent duplicates
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tree_disease');
    }
};
