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
        Schema::create('quizitems', function (Blueprint $table) {
            $table->id('itmID');
            $table->unsignedBigInteger('qzID');
            $table->foreign('qzID')->references('qzID')->on('quizzes')->onDelete('cascade');
            $table->longText('itmQuestion');
            $table->longText('itmOption1')->nullable();
            $table->longText('itmOption2')->nullable();
            $table->longText('itmOption3')->nullable();
            $table->longText('itmOption4')->nullable();
            $table->longText('itmOption5')->nullable();
            $table->longText('itmOption6')->nullable();
            $table->longText('itmOption7')->nullable();
            $table->longText('itmOption8')->nullable();
            $table->longText('itmOption9')->nullable();
            $table->longText('itmOption10')->nullable();
            $table->integer('choices_number');
            $table->integer('itmAnswer');
            $table->decimal('itmPoints', 10, 2)->default(0.00);
            $table->longText('itmImage')->nullable();
            $table->boolean('labeled')->default(0);
            $table->boolean('inTM')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizitems');
    }
};
