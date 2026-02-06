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

            // 🔗 Relations
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('session_id');

            // 📄 PDF Info
            $table->string('pdf');

            // 🔐 Status Fields
            $table->boolean('approved')->default(false);
            $table->boolean('rejected')->default(false);
            $table->text('reject_reason')->nullable();

            // 🔒 Lock
            $table->string('pdf_lock')->default('locked');

            $table->timestamps();

            // 🔗 Foreign Keys
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('course_id')
                ->references('course_id')->on('course')
                ->onDelete('cascade');

            $table->foreign('subject_id')
                ->references('subject_id')->on('subject')
                ->onDelete('cascade');

            $table->foreign('section_id')
                ->references('section_id')->on('section')
                ->onDelete('cascade');

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
