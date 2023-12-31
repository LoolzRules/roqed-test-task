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
        Schema::create('user_files', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->unsignedBigInteger('size');
            $table->string('extension');
            $table->string('path');
            $table->string('preview_path')->nullable();
            $table->timestamps();

            $table->index(['slug']);
            $table->index(['title']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_files');
    }
};
