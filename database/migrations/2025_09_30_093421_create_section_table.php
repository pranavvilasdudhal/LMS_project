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
       Schema::create('section', function (Blueprint $table) {
            $table->id('section_id');
            $table->string('sec_title');
            $table->string('sec_description');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('subject_id')->on('subject')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section');
    }
};
