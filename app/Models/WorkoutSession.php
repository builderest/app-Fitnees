<?php
namespace App\Models;

class WorkoutSession extends BaseModel
{
    protected static string $file = 'sessions.json';

    public int $user_id;
    public string $date;
    public string $status = 'planned';
    public array $exercises = [];
    public int $completed_exercises = 0;
    public int $total_exercises = 0;
    public int $intensity = 1;
}
