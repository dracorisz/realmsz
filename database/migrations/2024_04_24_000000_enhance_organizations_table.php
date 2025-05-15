<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('description')->nullable()->after('name');
            $table->string('address')->nullable()->after('website');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');
            $table->string('postal_code')->nullable()->after('country');
            $table->string('industry')->nullable()->after('postal_code');
            $table->string('size')->nullable()->after('industry');
            $table->integer('founded_year')->nullable()->after('size');
            $table->json('social_media')->nullable()->after('founded_year');
            $table->json('settings')->nullable()->after('social_media');
            $table->renameColumn('founder', 'founder_id');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'address',
                'city',
                'state',
                'country',
                'postal_code',
                'industry',
                'size',
                'founded_year',
                'social_media',
                'settings'
            ]);
            $table->renameColumn('founder_id', 'founder');
        });
    }
}; 