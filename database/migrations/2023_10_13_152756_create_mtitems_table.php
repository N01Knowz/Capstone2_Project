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
        Schema::create('mtitems', function (Blueprint $table) {
            $table->id('itmID');
            $table->unsignedBigInteger('mtID');
            $table->foreign('mtID')->references('mtID')->on('mttests')->onDelete('cascade');
            $table->longText('itmQuestion')->nullable();
            $table->longText('itmAnswer');
            $table->decimal('itmPoints', 10, 2)->default(0.00);
            $table->boolean('labeled')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mtitems');
    }
};
