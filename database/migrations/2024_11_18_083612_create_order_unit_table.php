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
        Schema::create('order_unit', function (Blueprint $table) {
            $table->string('customer');
            $table->string('model');
            $table->string('kodefgs');
            $table->string('partnumber');
            $table->string('kategori');
            $table->integer('bulan_1');
            $table->integer('bulan_2');
            $table->integer('bulan_3');
            $table->integer('bulan_4');
            $table->integer('bulan_5');
            $table->integer('bulan_6');
            $table->integer('bulan_7');
            $table->integer('bulan_8');
            $table->integer('bulan_9');
            $table->integer('bulan_10');
            $table->integer('bulan_11');
            $table->integer('bulan_12');
            $table->string('bulan');
            $table->integer('tahun');
            $table->float('total');
            $table->float('average');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_unit');
    }
};
