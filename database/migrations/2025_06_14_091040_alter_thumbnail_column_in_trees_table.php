<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterThumbnailColumnInTreesTable extends Migration
{
   public function up()
    {
        Schema::table('trees', function (Blueprint $table) {
            $table->longText('thumbnail')->nullable()->change();
        });
    }


    public function down()
    {
        Schema::table('trees', function (Blueprint $table) {
            $table->string('thumbnail', 255)->change(); // revert if needed
        });
    }
}
