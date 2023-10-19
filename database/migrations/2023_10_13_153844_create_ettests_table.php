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
        Schema::create('ettests', function (Blueprint $table) {
            $table->id('etID');
            $table->longText('etTitle');
            $table->longText('etDescription')->nullable();
            $table->decimal('etTotal', 10, 2)->default(0.00);
            $table->boolean('etIsPublic');
            $table->unsignedBigInteger('subjectID')->nullable();
            $table->foreign('subjectID')->references('subjectID')->on('subjects');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('labeled')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ettests');
    }
};
