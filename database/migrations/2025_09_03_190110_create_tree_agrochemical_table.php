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
        Schema::create('tree_agrochemicals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignUuid('agrochemical_uuid')
                ->constrained('agrochemicals', 'uuid')
                ->onDelete('cascade');

            
            $table->date('applied_at');

            $table->foreignUuid('tree_uuid')
                ->constrained('trees', 'uuid')
                ->onDelete('cascade');

            $table->string('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tree_agrochemicals');
    }
};
