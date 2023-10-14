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
        Schema::create('essays', function (Blueprint $table) {
            $table->id('essID');
            $table->longText('essTitle');
            $table->longText('essQuestion');
            $table->longText('essInstruction')->nullable();
            $table->longText('essCriteria1')->nullable()->default(null);
            $table->decimal('essScore1', 10, 2)->default(0.00);
            $table->longText('essCriteria2')->nullable()->default(null);
            $table->decimal('essScore2', 10, 2)->default(0.00);
            $table->longText('essCriteria3')->nullable()->default(null);
            $table->decimal('essScore3', 10, 2)->default(0.00);
            $table->longText('essCriteria4')->nullable()->default(null);
            $table->decimal('essScore4', 10, 2)->default(0.00);
            $table->longText('essCriteria5')->nullable()->default(null);
            $table->decimal('essScore5', 10, 2)->default(0.00);
            $table->decimal('essScoreTotal', 10, 2)->default(0.00);
            $table->boolean('essIsPublic');
            $table->string('essImage')->nullable();
            $table->unsignedBigInteger('subjectID')->nullable();
            $table->foreign('subjectID')->references('subjectID')->on('subjects');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('essays');
    }
};
