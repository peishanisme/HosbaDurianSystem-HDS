<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFloweringStatusToTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trees', function (Blueprint $table) {
            if (!Schema::hasColumn('trees', 'flowering_status')) {
                $table->string('flowering_status')->nullable()->after('water_valve');
            }
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
            if (Schema::hasColumn('trees', 'flowering_status')) {
                $table->dropColumn('flowering_status');
            }
        });
    }
}
