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
        Schema::create('health_records', function (Blueprint $table) {
        $table->id();
        $table->uuid('tree_uuid');
        $table->foreignId('disease_id')->constrained('diseases')->onDelete('cascade');
        $table->string('status');
        $table->date('recorded_at')->nullable();
        $table->text('treatment')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
