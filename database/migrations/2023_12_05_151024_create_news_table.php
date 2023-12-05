<?php

use App\Enums\News\Status;
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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->text('title'); 
            $table->text('description'); 
            $table->text('content'); 
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('status')->default(Status::DRAFT);
            $table->date('publish_at')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('news_categories')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
