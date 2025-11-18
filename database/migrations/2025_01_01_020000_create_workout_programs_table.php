<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('workout_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->enum('owner_type', ['global', 'user']);
            $table->foreignId('user_id')->nullable();
            $table->boolean('is_active')->default(false);
            $table->json('days')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_programs');
    }
};
