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
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->after('date')->default(0);
            $table->decimal('discount', 10, 2)->after('subtotal')->default(0)->nullable();
            $table->string('payment_method')->nullable()->after('total_price');
            $table->text('remark')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
            if (Schema::hasColumn('transactions', 'discount')) {
                $table->dropColumn('discount');
            }
            if (Schema::hasColumn('transactions', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if (Schema::hasColumn('transactions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('transactions', 'remark')) {
                $table->dropColumn('remark');
            }
        });
    }
};
