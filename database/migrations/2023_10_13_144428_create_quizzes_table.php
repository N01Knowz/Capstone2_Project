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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id('qzID');
            $table->longText('qzTitle');
            $table->longText('qzDescription')->nullable();
            $table->decimal('qzTotal', 10, 2)->default(0.00);
            $table->boolean('qzIsPublic')->default(0);
            $table->boolean('IsHidden')->default(0);
            $table->unsignedBigInteger('subjectID')->nullable();
            $table->foreign('subjectID')->references('subjectID')->on('subjects');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
