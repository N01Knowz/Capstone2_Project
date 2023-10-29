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
        Schema::create('tm_ets', function (Blueprint $table) {
            $table->id('tmetID');
            $table->unsignedBigInteger('tmID');
            $table->foreign('tmID')->references('tmID')->on('tmtests')->onDelete('cascade');
            $table->unsignedBigInteger('etID');
            $table->foreign('etID')->references('etID')->on('ettests')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tm_ets');
    }
};
