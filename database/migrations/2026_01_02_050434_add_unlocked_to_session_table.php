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
    Schema::table('session', function (Blueprint $table) {
        $table->boolean('unlocked')->default(false)->after('exam');
    });
}

public function down(): void
{
    Schema::table('session', function (Blueprint $table) {
        $table->dropColumn('unlocked');
    });
}

};
