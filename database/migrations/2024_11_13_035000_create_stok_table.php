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
        Schema::create('stok', function (Blueprint $table) {
            $table->id();
            $table->string('item_number');
            $table->string('part_number');
            $table->string('product_name');
            $table->string('lt')->nullable();
            $table->string('spl');
            $table->string('li');
            $table->string('type');
            $table->integer('stok');
            $table->integer('qty_buffer');
            $table->integer('percentage');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};
