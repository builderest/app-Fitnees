<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'description_en',
        'primary_muscle',
        'secondary_muscles',
        'equipment',
        'difficulty',
        'video_url',
        'thumbnail_url',
        'tags',
    ];

    protected $casts = [
        'secondary_muscles' => 'array',
        'tags' => 'array',
    ];

    public function workoutExercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}
