<?php
namespace App\Models;

class WorkoutExercise extends BaseModel
{
    protected static string $file = 'workout_exercises.json';

    public int $day_id;
    public int $exercise_id;
    public int $sets;
    public int $reps;
    public ?float $weight = null;
    public int $rest = 60;
}
