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
        Schema::create('incoming_non_manual', function (Blueprint $table) {
            $table->id();
            $table->string('item_number');
            $table->string('purchase_order');
            $table->string('name');
            $table->date('date');
            $table->dateTime('created_date_and_time');
            $table->string('product_receipt');
            $table->string('internal_product_receipt');
            $table->string('product_reference');
            $table->integer('ordered');
            $table->integer('received');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_non_manual');
    }
};
