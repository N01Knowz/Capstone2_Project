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
        Schema::create('analyticmttags', function (Blueprint $table) {
            $table->id('mttgID');
            $table->unsignedBigInteger('mtID');
            $table->foreign('mtID')->references('mtID')->on('mttests')->onDelete('cascade');
            $table->unsignedBigInteger('tagID');
            $table->foreign('tagID')->references('tagID')->on('analytictags');
            $table->decimal('similarity', 10, 2)->default(0.00);
            $table->boolean('isActive')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analyticmttags');
    }
};
