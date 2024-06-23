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
        Schema::create('bowl_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bowl_id')->constrained()->onDelete('cascade');
            $table->foreignId('fish_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('quantity');
            $table->unsignedBigInteger('sub_total');
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bowl_items');
    }
};
