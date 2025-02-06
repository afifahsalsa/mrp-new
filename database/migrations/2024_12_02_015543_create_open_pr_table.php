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
        Schema::create('open_pr', function (Blueprint $table) {
            $table->id();
            $table->string('pr_id');
            $table->string('item_id');
            $table->string('part');
            $table->string('old_name');
            $table->date('pr_date');
            $table->date('request_date');
            $table->float('qty');
            $table->string('pr_status');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_pr');
    }
};
