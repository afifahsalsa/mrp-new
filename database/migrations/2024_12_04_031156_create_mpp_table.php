<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mpp', function (Blueprint $table) {
            $table->string('customer');
            $table->string('model');
            $table->string('kodefgs');
            $table->string('partnumber');
            $table->string('kategori');
            $table->integer('ori_cust_bulan_1');
            $table->integer('ori_cust_bulan_2');
            $table->integer('ori_cust_bulan_3');
            $table->integer('ori_cust_bulan_4');
            $table->integer('ori_cust_bulan_5');
            $table->integer('ori_cust_bulan_6');
            $table->integer('ori_cust_bulan_7');
            $table->integer('ori_cust_bulan_8');
            $table->integer('ori_cust_bulan_9');
            $table->integer('ori_cust_bulan_10');
            $table->integer('ori_cust_bulan_11');
            $table->integer('ori_cust_bulan_12');
            $table->integer('prod_plan_bulan_1');
            $table->integer('prod_plan_bulan_2');
            $table->integer('prod_plan_bulan_3');
            $table->integer('prod_plan_bulan_4');
            $table->integer('prod_plan_bulan_5');
            $table->integer('prod_plan_bulan_6');
            $table->integer('prod_plan_bulan_7');
            $table->integer('prod_plan_bulan_8');
            $table->integer('prod_plan_bulan_9');
            $table->integer('prod_plan_bulan_10');
            $table->integer('prod_plan_bulan_11');
            $table->integer('prod_plan_bulan_12');
            $table->integer('max_bulan_1');
            $table->integer('max_bulan_2');
            $table->integer('max_bulan_3');
            $table->integer('max_bulan_4');
            $table->integer('max_bulan_5');
            $table->integer('max_bulan_6');
            $table->integer('max_bulan_7');
            $table->integer('max_bulan_8');
            $table->integer('max_bulan_9');
            $table->integer('max_bulan_10');
            $table->integer('max_bulan_11');
            $table->integer('max_bulan_12');
            $table->string('bulan');
            $table->string('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpp');
    }
};
