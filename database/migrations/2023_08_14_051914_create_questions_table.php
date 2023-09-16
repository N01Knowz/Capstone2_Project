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
        Schema::create('questions', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('testbank_id');
            $table->foreign('testbank_id')->references('id')->on('testbanks');
            $table->boolean('question_active');
            $table->longText('item_question');
            $table->longText('question_image')->nullable();
            $table->integer('choices_number');
            $table->integer('question_answer');
            $table->integer('question_point');
            $table->integer('explanation_point')->nullable();
            $table->longText('option_1')->nullable();
            $table->longText('option_2')->nullable();
            $table->longText('option_3')->nullable();
            $table->longText('option_4')->nullable();
            $table->longText('option_5')->nullable();
            $table->longText('option_6')->nullable();
            $table->longText('option_7')->nullable();
            $table->longText('option_8')->nullable();
            $table->longText('option_9')->nullable();
            $table->decimal('Realistic')->nullable();
            $table->decimal('Investigative')->nullable();
            $table->decimal('Artistic')->nullable();
            $table->decimal('Social')->nullable();
            $table->decimal('Enterprising')->nullable();
            $table->decimal('Conventional')->nullable();
            $table->boolean('Unknown')->default(false);
            $table->boolean('Labeled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
