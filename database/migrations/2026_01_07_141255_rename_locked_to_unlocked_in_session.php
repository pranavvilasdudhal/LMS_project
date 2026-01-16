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
    Schema::table('session', function (Blueprint $table) {
        if (Schema::hasColumn('session', 'locked')) {
            $table->renameColumn('locked', 'unlocked');
        }
    });
}

public function down()
{
    Schema::table('session', function (Blueprint $table) {
        if (Schema::hasColumn('session', 'unlocked')) {
            $table->renameColumn('unlocked', 'locked');
        }
    });
}

};
