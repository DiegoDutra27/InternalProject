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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('federal_document');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('zip_code');
            $table->string('state');
            $table->string('city');
            $table->string('address');
            $table->boolean('is_active');
            $table->dateTime('create');
            $table->dateTime('update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
