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
        Schema::create('etitems', function (Blueprint $table) {
            $table->id('itmID');
            $table->unsignedBigInteger('etID');
            $table->foreign('etID')->references('etID')->on('ettests');
            $table->longText('itmAnswer');
            $table->boolean('itmIsCaseSensitive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etitems');
    }
};
