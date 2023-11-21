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
        Schema::create('tm_mts', function (Blueprint $table) {
            $table->id('tmmtID');
            $table->unsignedBigInteger('tmID');
            $table->foreign('tmID')->references('tmID')->on('tmtests')->onDelete('cascade');
            $table->unsignedBigInteger('mtID');
            $table->foreign('mtID')->references('mtID')->on('mttests')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tm_mts');
    }
};
