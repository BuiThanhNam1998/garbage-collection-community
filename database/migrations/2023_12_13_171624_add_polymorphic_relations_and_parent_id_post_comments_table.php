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
        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropForeign(['garbage_post_id']);
            $table->dropColumn('garbage_post_id');

            $table->unsignedBigInteger('commentable_id')->after('user_id');
            $table->string('commentable_type')->after('commentable_id');

            $table->unsignedBigInteger('parent_id')->after('commentable_type')->nullable();
            $table->foreign('parent_id')->references('id')->on('post_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('garbage_post_id');
            $table->dropColumn('commentable_id');
            $table->dropColumn('commentable_type');
            $table->dropColumn('parent_id');

            $table->foreign('garbage_post_id')->references('id')->on('garbage_posts')->onDelete('cascade');
        });
    }
};
