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
        Schema::create('mtfitems', function (Blueprint $table) {
            $table->id('itmID');
            $table->unsignedBigInteger('mtfID');
            $table->foreign('mtfID')->references('mtfID')->on('mtftests');
            $table->longText('itmQuestion');
            $table->longText('itmOption1')->default('True');
            $table->longText('itmOption2')->default('False');
            $table->integer('itmAnswer');
            $table->integer('choices_number')->default(0.00);
            $table->decimal('itmPoints1', 10, 2)->default(0.00);
            $table->decimal('itmPoints2', 10, 2)->default(0.00);
            $table->decimal('itmPointsTotal', 10, 2)->default(0.00);
            $table->longText('itmImage')->nullable();
            $table->boolean('labeled')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mtfitems');
    }
};
