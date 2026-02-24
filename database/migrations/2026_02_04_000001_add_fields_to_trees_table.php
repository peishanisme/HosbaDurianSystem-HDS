<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trees', function (Blueprint $table) {
            $table->string('area', 1)->nullable()->after('flowering_period');
            $table->integer('terrace')->nullable()->after('area');
            $table->integer('water_valve')->nullable()->after('terrace');
            $table->string('flowering_status')->nullable()->after('water_valve');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trees', function (Blueprint $table) {
            $table->dropColumn(['area', 'terrace', 'water_valve', 'flowering_status']);
        });
    }
}
