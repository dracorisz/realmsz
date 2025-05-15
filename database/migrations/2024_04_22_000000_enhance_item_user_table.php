<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_user', function (Blueprint $table) {
            $table->string('role')->default('viewer')->after('user_id');
            $table->timestamp('shared_at')->nullable()->after('role');
            $table->json('permissions')->nullable()->after('shared_at');
        });
    }

    public function down(): void
    {
        Schema::table('item_user', function (Blueprint $table) {
            $table->dropColumn(['role', 'shared_at', 'permissions']);
        });
    }
}; 