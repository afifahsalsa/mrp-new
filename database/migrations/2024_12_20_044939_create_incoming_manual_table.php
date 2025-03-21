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
        Schema::create('incoming_manual', function (Blueprint $table) {
            $table->id();
            $table->string('spl');
            $table->date('tgl_kedatangan');
            $table->string('item_number');
            $table->string('part_number');
            $table->string('purchase_order');
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
        Schema::dropIfExists('incoming_manual');
    }
};
