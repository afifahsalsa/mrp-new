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
        Schema::create('open_po', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_account');
            $table->string('item_number');
            $table->string('name');
            $table->string('purchase_order');
            $table->integer('line_number');
            $table->string('purchase_requisition');
            $table->string('product_name');
            $table->integer('deliver_reminder');
            $table->date('delivery_date');
            $table->string('part_name');
            $table->string('part_number');
            $table->string('procurement_category');
            $table->string('site');
            $table->string('warehouse');
            $table->string('location');
            $table->float('qty');
            $table->string('bulan_datang');
            $table->integer('lt');
            $table->string('ket_late');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_po');
    }
};
