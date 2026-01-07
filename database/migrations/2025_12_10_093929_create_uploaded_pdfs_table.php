<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void
      {
            Schema::create('uploaded_pdfs', function (Blueprint $table) {
                  $table->id();

                  $table->unsignedBigInteger('user_id');
                  $table->unsignedBigInteger('course_id');
                  $table->unsignedBigInteger('subject_id');
                  $table->unsignedBigInteger('section_id');
                  $table->unsignedBigInteger('session_id');

                  $table->boolean('approved')->default(false);

                  $table->string('pdf');
                  $table->string('pdf_lock')->default('locked');
                  $table->timestamps();

                  // Users table
                  $table->foreign('user_id')
                        ->references('id')->on('users')
                        ->onDelete('cascade');

                  // Course table (PK = course_id)
                  $table->foreign('course_id')
                        ->references('course_id')->on('course')
                        ->onDelete('cascade');

                  // Subject table (PK = subject_id)
                  $table->foreign('subject_id')
                        ->references('subject_id')->on('subject')
                        ->onDelete('cascade');

                  // Section table (PK = section_id)
                  $table->foreign('section_id')
                        ->references('section_id')->on('section')
                        ->onDelete('cascade');

                  // Session table (PK = id)
                  $table->foreign('session_id')
                        ->references('id')->on('session')
                        ->onDelete('cascade');
            });
      }

      public function down(): void
      {
            Schema::dropIfExists('uploaded_pdfs');
      }
};
