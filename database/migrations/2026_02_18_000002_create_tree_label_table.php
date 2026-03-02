<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreeLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tree_label', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tree_id');
            $table->unsignedBigInteger('label_id');
            $table->timestamps();

            $table->foreign('tree_id')->references('id')->on('trees')->onDelete('cascade');
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
            $table->unique(['tree_id', 'label_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tree_label');
    }
}
