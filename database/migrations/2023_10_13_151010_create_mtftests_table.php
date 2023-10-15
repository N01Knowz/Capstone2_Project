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
        Schema::create('mtftests', function (Blueprint $table) {
            $table->id('mtfID');
            $table->longText('mtfTitle');
            $table->longText('mtfDescription')->nullable();
            $table->decimal('itmPoints1', 10, 2)->default(0.00);
            $table->decimal('itmPoints2', 10, 2)->default(0.00);
            $table->decimal('mtfTotal', 10, 2)->default(0.00);
            $table->boolean('mtfIsPublic');
            $table->unsignedBigInteger('subjectID')->nullable();
            $table->foreign('subjectID')->references('subjectID')->on('subjects');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mtftests');
    }
};