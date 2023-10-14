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
        Schema::create('analyticettags', function (Blueprint $table) {
            $table->id('ettgID');
            $table->unsignedBigInteger('etID');
            $table->foreign('etID')->references('etID')->on('ettests');
            $table->unsignedBigInteger('tagID');
            $table->foreign('tagID')->references('tagID')->on('analytictags');
            $table->boolean('isActive')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analyticettags');
    }
};
