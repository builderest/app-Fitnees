<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->date('date');
            $table->float('weight');
            $table->float('body_fat')->nullable();
            $table->float('chest')->nullable();
            $table->float('waist')->nullable();
            $table->float('arms')->nullable();
            $table->float('legs')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
