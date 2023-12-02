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
        Schema::table('garbage_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('locationable_id')->nullable()->after('description');
            $table->string('locationable_type')->nullable()->after('locationable_id');
            $table->dropColumn('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('garbage_posts', function (Blueprint $table) {
            $table->dropColumn('locationable_id');
            $table->dropColumn('locationable_type');
            $table->string('location')->nullable();
        });
    }
};
