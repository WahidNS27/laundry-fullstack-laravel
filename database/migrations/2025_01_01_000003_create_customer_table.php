<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 50);
            $table->string('phone', 13);
            $table->text('address');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
