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
        Schema::create('mttests', function (Blueprint $table) {
            $table->id('mtID');
            $table->longText('mtTitle');
            $table->longText('mtDescription')->nullable();
            $table->decimal('mtTotal', 10, 2)->default(0.00);
            $table->boolean('mtIsPublic')->default(0);
            $table->boolean('IsHidden')->default(0);
            $table->unsignedBigInteger('subjectID')->nullable();
            $table->foreign('subjectID')->references('subjectID')->on('subjects');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('labeled')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mttests');
    }
};
