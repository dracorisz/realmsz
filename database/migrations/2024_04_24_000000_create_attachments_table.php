<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}; 