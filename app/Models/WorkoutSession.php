<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workout_program_id',
        'workout_day_id',
        'performed_at',
        'notes',
        'intensity',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(WorkoutProgram::class);
    }

    public function day(): BelongsTo
    {
        return $this->belongsTo(WorkoutDay::class);
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(WorkoutSessionExercise::class);
    }
}
