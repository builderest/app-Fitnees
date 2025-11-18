<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('body_fat', 5, 2)->nullable();
            $table->unsignedSmallInteger('chest')->nullable();
            $table->unsignedSmallInteger('waist')->nullable();
            $table->unsignedSmallInteger('arms')->nullable();
            $table->unsignedSmallInteger('legs')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
