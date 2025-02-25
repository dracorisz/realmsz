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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('item_id')->nullable();
            $table->foreignId('module_id')->nullable();
            $table->string('text');
            $table->string('previous_value');
            $table->enum('type', ['create', 'update', 'delete']);
            $table->enum('item_part', ['status', 'date', 'archive', 'priority', 'image', 'total']);
            $table->enum('profile_part', ['name', 'email']); // backwards compatibility
            $table->boolean('visible')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
