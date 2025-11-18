<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('user');
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('training_goal')->nullable();
            $table->string('training_level')->nullable();
            $table->string('plan')->default('free');
            $table->timestamp('premium_until')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
