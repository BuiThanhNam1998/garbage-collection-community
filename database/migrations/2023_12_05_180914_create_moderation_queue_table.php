<?php

use App\Enums\ModerationQueue\Status;
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
        Schema::create('moderation_queue', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('object_id');
            $table->string('object_type');
            $table->string('status')->default(Status::PENDING);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admins'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderation_queue');
    }
};
