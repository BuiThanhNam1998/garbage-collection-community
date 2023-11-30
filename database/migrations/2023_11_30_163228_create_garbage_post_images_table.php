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
        Schema::create('garbage_post_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('garbage_post_id');
            $table->foreign('garbage_post_id')->references('id')->on('garbage_posts')->onDelete('cascade');
            $table->text('image_path');
            $table->string('type'); 
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garbage_post_images');
    }
};
