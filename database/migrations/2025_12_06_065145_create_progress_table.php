<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('session_id');

            $table->boolean('completed')->default(false);

            $table->timestamps();

            // Foreign keys
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('course_id')->references('course_id')->on('course')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('session')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
