<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expertise_videos', function (Blueprint $table) {
            $table->id();
            $table->string('video_url')->nullable();
            $table->string('poster_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('expertise_video_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expertise_video_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('tag');
            $table->string('title')->nullable();
            $table->timestamps();

            $table->unique(['expertise_video_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expertise_video_translations');
        Schema::dropIfExists('expertise_videos');
    }
};
