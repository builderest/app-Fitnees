<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('description_en');
            $table->string('primary_muscle');
            $table->json('secondary_muscles')->nullable();
            $table->string('equipment');
            $table->string('difficulty');
            $table->string('video_url');
            $table->string('thumbnail_url');
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
