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
        Schema::create('address_book', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('country', 100);
            $table->string('city', 100);
            $table->string('street', 100);
            $table->string('email')->unique();
            $table->string('phone', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address_book');
    }
};
