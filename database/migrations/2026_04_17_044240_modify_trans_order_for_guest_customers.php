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
            // Drop foreign key to make it nullable
            $table->dropForeign(['id_customer']);
        });

        Schema::table('trans_order', function (Blueprint $table) {
            // Make id_customer nullable
            $table->unsignedBigInteger('id_customer')->nullable()->change();
            
            // Add foreign key back
            $table->foreign('id_customer')->references('id')->on('customer')->onDelete('cascade');
            
            // Add guest customer columns
            $table->string('customer_name')->nullable()->after('id_customer');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->text('customer_address')->nullable()->after('customer_phone');
        });
    }

    public function down(): void
    {
        Schema::table('trans_order', function (Blueprint $table) {
            $table->dropForeign(['id_customer']);
        });

        Schema::table('trans_order', function (Blueprint $table) {
            $table->unsignedBigInteger('id_customer')->nullable(false)->change();
            $table->foreign('id_customer')->references('id')->on('customer')->onDelete('cascade');
            $table->dropColumn(['customer_name', 'customer_phone', 'customer_address']);
        });
    }
};
