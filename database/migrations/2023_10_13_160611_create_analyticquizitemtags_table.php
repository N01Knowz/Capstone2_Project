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
        Schema::create('analyticquizitemtags', function (Blueprint $table) {
            $table->id('quiztgID');
            $table->unsignedBigInteger('itmID');
            $table->foreign('itmID')->references('itmID')->on('quizitems')->onDelete('cascade');
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
        Schema::dropIfExists('analyticquizitemtags');
    }
};
