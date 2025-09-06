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
        Schema::create('agrochemical', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignUuid('agrochemical_uuid')
                ->constrained('agrochemicals', 'uuid')
                ->onDelete('cascade');

            $table->foreignUuid('tree_uuid')
                ->constrained('trees', 'uuid')
                ->onDelete('cascade');

            $table->string('description');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agrochemical');
    }
};
