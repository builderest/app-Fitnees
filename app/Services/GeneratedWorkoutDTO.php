<?php
namespace App\Services;

class GeneratedWorkoutDTO
{
    public array $exercises = [];
    public array $settings = [];

    public function __construct(array $exercises, array $settings)
    {
        $this->exercises = $exercises;
        $this->settings = $settings;
    }
}
