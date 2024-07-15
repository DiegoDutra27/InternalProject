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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->string('name');
            $table->string('brand');
            $table->unsignedInteger('quantity');
            $table->float('weight');
            $table->enum('weight_unit', ['mg', 'g', 'kg', 't']);
            $table->decimal('price', total: 8, places: 2);
            $table->text('description')->nullable();
            $table->dateTime('create');
            $table->dateTime('update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
