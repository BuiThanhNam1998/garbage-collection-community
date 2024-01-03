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
        Schema::table('user_activity_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('loggable_id')->nullable()->after('description');
            $table->string('loggable_type')->nullable()->after('loggable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_activity_logs', function (Blueprint $table) {
            $table->dropColumn('loggable_id');
            $table->dropColumn('loggable_type');
        });
    }
};
