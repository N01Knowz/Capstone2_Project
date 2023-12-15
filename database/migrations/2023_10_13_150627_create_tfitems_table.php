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
        Schema::create('tfitems', function (Blueprint $table) {
            $table->id('itmID');
            $table->unsignedBigInteger('tfID');
            $table->foreign('tfID')->references('tfID')->on('tftests')->onDelete('cascade');
            $table->longText('itmQuestion');
            $table->longText('itmOption1')->default('True');
            $table->longText('itmOption2')->default('False');
            $table->integer('choices_number')->default(2);
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
        Schema::dropIfExists('tfitems');
    }
};
