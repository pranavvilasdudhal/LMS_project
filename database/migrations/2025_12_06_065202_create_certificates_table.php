<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');

            $table->string('certificate_url'); // stored image/pdf path
            
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('course_id')->references('course_id')->on('course')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
