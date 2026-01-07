<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('session_progress', function (Blueprint $table) {
            $table->boolean('pdf_approved')->default(false)->after('pdf_completed');
            $table->boolean('task_completed')->default(false)->after('pdf_approved');
        });
    }

    public function down(): void
    {
        Schema::table('session_progress', function (Blueprint $table) {
            $table->dropColumn(['pdf_approved', 'task_completed']);
        });
    }
};
