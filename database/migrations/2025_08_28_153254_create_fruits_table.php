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
        Schema::create('fruits', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignUuid('tree_uuid')
                ->constrained('trees', 'uuid')
                ->onDelete('cascade');

            $table->foreignUuid('harvest_uuid')
                ->constrained('harvest_events', 'uuid')
                ->onDelete('cascade');

            $table->double('weight');
            $table->string('grade');
            $table->date('harvested_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fruits');
    }
};
