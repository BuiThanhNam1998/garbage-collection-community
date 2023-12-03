<?php

use App\Enums\User\GarbagePost\Status;
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
            $table->string('verification_status')->default(Status::PENDING)->change();
            $table->string('ai_verification_status')->default(Status::PENDING)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('garbage_posts', function (Blueprint $table) {
            $table->boolean('verification_status')->default(false)->change();
            $table->boolean('ai_verification_status')->default(false)->change();
        });
    }
};
