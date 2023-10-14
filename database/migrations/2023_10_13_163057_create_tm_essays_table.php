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
        Schema::create('tm_essays', function (Blueprint $table) {
            $table->id('tmessID');
            $table->unsignedBigInteger('tmID');
            $table->foreign('tmID')->references('tmID')->on('tmtests');
            $table->unsignedBigInteger('essID');
            $table->foreign('essID')->references('essID')->on('essays');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tm_essays');
    }
};
