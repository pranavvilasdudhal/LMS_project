<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');        // from users table
            $table->unsignedBigInteger('course_id');      // from course table
            $table->unsignedBigInteger('enrollment_id')->nullable(); // from enrollments table
            $table->integer('quantity')->default(1);

            $table->timestamps();

            // ========== FIXED FOREIGN KEYS ==========

            // Users table → OK
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Course table → FIXED
            $table->foreign('course_id')
                  ->references('course_id')
                  ->on('course')
                  ->onDelete('cascade');

            // Enrollments table → FIXED
            $table->foreign('enrollment_id')
                  ->references('enrollment_id')
                  ->on('enrollments')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
