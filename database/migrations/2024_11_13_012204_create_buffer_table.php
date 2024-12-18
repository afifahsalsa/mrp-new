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
        Schema::create('buffer', function (Blueprint $table) {
            $table->id();
            $table->string('item_number');
            $table->string('part_number');
            $table->string('product_name');
            $table->integer('lt');
            $table->string('supplier');
            $table->string('li');
            $table->string('type');
            $table->integer('qty');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buffer');
    }
};
