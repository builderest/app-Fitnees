<?php
namespace App\Models;

class WorkoutSessionExercise extends BaseModel
{
    protected static string $file = 'session_exercises.json';

    public int $session_id;
    public int $exercise_id;
    public int $sets_completed = 0;
    public array $records = [];
}
