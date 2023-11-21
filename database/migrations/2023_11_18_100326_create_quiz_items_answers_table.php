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
        Schema::create('quiz_items_answers', function (Blueprint $table) {
            $table->id('qziaID');
            $table->unsignedBigInteger('qzttID');
            $table->foreign('qzttID')->references('qzttID')->on('quiz_tests_takens')->onDelete('cascade');
            $table->unsignedBigInteger('itmID');
            $table->foreign('itmID')->references('itmID')->on('quizitems')->onDelete('cascade');
            $table->integer('qzStudentItemAnswer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_items_answers');
    }
};
