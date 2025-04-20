<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('users', 'show_in_network')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('show_in_network')->default(true)->after('role');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'show_in_network')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('show_in_network');
            });
        }
    }
}; 