<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ollama_models', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->string('display_name');
            $table->float('size_gb');
            $table->unsignedInteger('ram_required_gb');
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->enum('status', ['available', 'downloading', 'installed', 'error'])->default('available');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->timestamp('pulled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ollama_models');
    }
};
