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
        Schema::table('post_reactions', function (Blueprint $table) {
            $table->dropForeign(['garbage_post_id', 'user_id']);
            $table->dropUnique('post_reactions_user_id_garbage_post_id_type_unique');
            $table->dropColumn('garbage_post_id');
            $table->dropColumn('type');

            $table->unsignedBigInteger('reactable_id')->after('user_id');
            $table->string('reactable_type')->after('reactable_id');

            $table->unsignedBigInteger('type_id')->after('reactable_type');
            $table->foreign('type_id')->references('id')->on('reaction_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_reactions', function (Blueprint $table) {
            $table->unsignedBigInteger('garbage_post_id');
            $table->string('type'); 
            $table->dropColumn('reactable_id');
            $table->dropColumn('reactable_type');
            $table->dropColumn('type_id');

            $table->foreign('garbage_post_id')->references('id')->on('garbage_posts')->onDelete('cascade');
            $table->unique(['user_id', 'garbage_post_id', 'type']); 
        });
    }
};
