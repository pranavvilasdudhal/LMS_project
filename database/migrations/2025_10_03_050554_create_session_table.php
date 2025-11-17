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
        Schema::create('session', function (Blueprint $table) {
            $table->id();
            $table->string('titel');
            $table->string('type');
            $table->string('video');
            $table->string('pdf');
            $table->string('task');
            $table->string('exam');

            // 👇 section_id column
            $table->unsignedBigInteger('section_id');

            // 👇 foreign key
            $table->foreign('section_id')
                ->references('section_id')
                ->on('section')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session');
    }
};
