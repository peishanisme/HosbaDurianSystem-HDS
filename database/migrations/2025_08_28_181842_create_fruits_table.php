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

            $table->string('fruit_tag')->unique();

            $table->foreignUuid('tree_uuid')
                ->constrained('trees', 'uuid')
                ->onDelete('cascade');

            $table->foreignUuid('harvest_uuid')
                ->constrained('harvest_events', 'uuid')
                ->onDelete('cascade');

            $table->foreignUuid('transaction_uuid')
                ->nullable()
                ->constrained('transactions', 'uuid')
                ->onDelete('set null');

            $table->double('weight');
            $table->string('grade');
            $table->date('harvested_at');
            $table->boolean('is_spoiled')->default(false);
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
