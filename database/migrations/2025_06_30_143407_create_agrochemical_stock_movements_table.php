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
        Schema::create('agrochemical_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
             $table->uuid('agrochemical_uuid');
            $table->foreign('agrochemical_uuid')->references('uuid')->on('agrochemicals')->onDelete('cascade');
            $table->string('movement_type');
            $table->integer('quantity')->default(0);
            $table->dateTime('date');
            $table->longText('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agrochemical_stock_movements');
    }
};
