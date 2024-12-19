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
        Schema::create('price', function (Blueprint $table) {
            $table->id();
            $table->string('item_id');
            $table->string('category_item');
            $table->string('part_name');
            $table->string('part_number');
            $table->string('search_name');
            $table->string('satuan');
            $table->integer('price');
            $table->string('currency');
            $table->integer('val_currency');
            $table->integer('price_idr');
            $table->dateTime('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price');
    }
};
