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
        Schema::create('garbage_posts', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('date')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('verification_status')->default(false);
            $table->boolean('ai_verification_status')->default(false);
            $table->dateTime('manual_verification_date')->nullable();
            $table->dateTime('ai_verification_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garbage_posts');
    }
};
