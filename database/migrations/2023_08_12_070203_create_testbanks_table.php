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
        Schema::create('testbanks', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('test_type');
            $table->longText('test_title');
            $table->longText('test_question');
            $table->longText('test_instruction');
            $table->string('test_image')->nullable();
            $table->integer('test_total_points');
            $table->boolean('test_visible');
            $table->boolean('test_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testbanks');
    }
};
