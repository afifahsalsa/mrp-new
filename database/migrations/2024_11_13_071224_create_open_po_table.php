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
            $table->string('purchase_order');
            $table->string('item_number');
            $table->string('product_name');
            $table->string('purchase_requisition');
            $table->float('tpqty');
            $table->string('tpunit');
            $table->string('tpsite');
            $table->string('tpvendor');
            $table->dateTime('delivery_date');
            $table->float('delivery_reminder');
            $table->string('old_number_format');
            $table->dateTime('created_date_and_time');
            $table->string('tpstatus');
            $table->string('line_status');
            $table->string('supplier_name');
            $table->string('standar_datang');
            $table->string('bulan_datang');
            $table->string('lt');
            $table->string('ket_late');
            $table->string('ket_lt');
            $table->float('price');
            $table->float('amount');
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
