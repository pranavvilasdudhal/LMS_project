<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_progress', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('session_id');

            $table->boolean('video_completed')->default(false);
            $table->boolean('pdf_completed')->default(false);
            $table->boolean('unlocked')->default(false); // next session unlock flag

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('session')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_progress');
    }
};
