<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('workout_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->date('date');
            $table->string('status');
            $table->json('exercises')->nullable();
            $table->integer('completed_exercises')->default(0);
            $table->integer('total_exercises')->default(0);
            $table->integer('intensity')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_sessions');
    }
};
