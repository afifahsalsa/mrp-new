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
        Schema::create('bom', function (Blueprint $table) {
            $table->id();
            $table->string('item_id_fgs');
            $table->string('item_id_rmi');
            $table->string('part_number');
            // $table->decimal('bomqty', 38, 8);
            $table->float('bomqty');
            $table->string('unit_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom');
    }
};
