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
        Schema::create('ai_post_queue', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('garbage_post_id');
            $table->unsignedBigInteger('admin_id');
            $table->timestamps();

            $table->foreign('garbage_post_id')->references('id')->on('garbage_posts')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_post_queue');
    }
};
