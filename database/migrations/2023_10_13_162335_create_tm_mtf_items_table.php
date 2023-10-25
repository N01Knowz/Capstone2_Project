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
        Schema::create('tm_mtf_items', function (Blueprint $table) {
            $table->id('tmmtfID');
            $table->unsignedBigInteger('tmID');
            $table->foreign('tmID')->references('tmID')->on('tmtests');
            $table->unsignedBigInteger('itmID');
            $table->foreign('itmID')->references('itmID')->on('mtfitems');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tm_mtf_items');
    }
};
