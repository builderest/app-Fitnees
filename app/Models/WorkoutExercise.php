<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_day_id',
        'exercise_id',
        'order',
        'sets',
        'reps',
        'weight',
        'rest_seconds',
        'notes',
    ];

    public function day(): BelongsTo
    {
        return $this->belongsTo(WorkoutDay::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
