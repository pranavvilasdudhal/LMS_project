<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session', function (Blueprint $table) {
            if (!Schema::hasColumn('session', 'locked')) {
                $table->boolean('locked')->default(true)->after('section_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('session', function (Blueprint $table) {
            if (Schema::hasColumn('session', 'locked')) {
                $table->dropColumn('locked');
            }
        });
    }
};
