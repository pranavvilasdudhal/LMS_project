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
    Schema::table('progress', function (Blueprint $table) {
        $table->boolean('video_completed')->default(false)->after('session_id');
        $table->boolean('pdf_completed')->default(false)->after('video_completed');
        $table->boolean('task_completed')->default(false)->after('pdf_completed');
        $table->boolean('exam_completed')->default(false)->after('task_completed');
    });
}

public function down()
{
    Schema::table('progress', function (Blueprint $table) {
        $table->dropColumn([
            'video_completed',
            'pdf_completed',
            'task_completed',
            'exam_completed'
        ]);
    });
}
};
