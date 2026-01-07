<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('subject', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->after('subject_image');
            $table->foreign('course_id')->references('course_id')->on('course')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('subject', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
    }
};
