<?php
namespace App\Models;

class WorkoutDay extends BaseModel
{
    protected static string $file = 'days.json';

    public int $program_id;
    public string $title;
    public array $exercises = [];
}
