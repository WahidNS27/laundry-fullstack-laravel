<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_level');
            $table->string('name', 50);
            $table->string('email', 50)->unique();
            $table->string('password', 100);
            $table->timestamps();

            $table->foreign('id_level')->references('id')->on('level')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
