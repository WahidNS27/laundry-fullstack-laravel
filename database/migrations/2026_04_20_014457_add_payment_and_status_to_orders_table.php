<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('trans_order', function (Blueprint $table) {

        if (!Schema::hasColumn('trans_order', 'payment_status')) {
            $table->integer('payment_status')->default(0);
        }

        if (!Schema::hasColumn('trans_order', 'order_status')) {
            $table->integer('order_status')->default(0);
        }

        if (!Schema::hasColumn('trans_order', 'order_pay')) {
            $table->integer('order_pay')->nullable();
        }

        if (!Schema::hasColumn('trans_order', 'order_change')) {
            $table->integer('order_change')->nullable();
        }

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
