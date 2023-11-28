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
        Schema::create('tm_quiz_items_answers', function (Blueprint $table) {
            $table->id('tmqziaID');
            $table->unsignedBigInteger('tmttID');
            $table->foreign('tmttID')->references('tmttID')->on('tm_tests_takens')->onDelete('cascade');
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
        Schema::dropIfExists('tm_quiz_items_answers');
    }
};
