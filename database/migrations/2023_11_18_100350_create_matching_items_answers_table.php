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
        Schema::create('matching_items_answers', function (Blueprint $table) {
            $table->id('mtiaID');
            $table->unsignedBigInteger('mtttID');
            $table->foreign('mtttID')->references('mtttID')->on('matching_tests_takens')->onDelete('cascade');
            $table->unsignedBigInteger('itmID');
            $table->foreign('itmID')->references('itmID')->on('mtitems')->onDelete('cascade');
            $table->longText('mtStudentItemAnswer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matching_items_answers');
    }
};
