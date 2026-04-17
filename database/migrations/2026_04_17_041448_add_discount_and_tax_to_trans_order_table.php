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
        Schema::table('trans_order', function (Blueprint $table) {
            $table->integer('subtotal')->default(0)->after('order_change');
            $table->integer('tax')->default(0)->after('subtotal');
            $table->integer('discount_member')->default(0)->after('tax');
            $table->integer('discount_voucher')->default(0)->after('discount_member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_order', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'tax', 'discount_member', 'discount_voucher']);
        });
    }
};
