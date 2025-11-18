<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_program_id',
        'title',
        'day_order',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(WorkoutProgram::class, 'workout_program_id');
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}
