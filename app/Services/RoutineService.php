<?php

namespace App\Services;

use App\Models\WorkoutProgram;
use Illuminate\Support\Facades\DB;

class RoutineService
{
    public function duplicate(WorkoutProgram $program, int $userId): WorkoutProgram
    {
        return DB::transaction(function () use ($program, $userId) {
            $copy = $program->replicate([
                'user_id', 'is_global', 'is_active'
            ]);
            $copy->user_id = $userId;
            $copy->is_global = false;
            $copy->is_active = false;
            $copy->title = $program->title.' (copy)';
            $copy->save();

            foreach ($program->days as $day) {
                $newDay = $day->replicate(['workout_program_id']);
                $newDay->workout_program_id = $copy->id;
                $newDay->save();

                foreach ($day->exercises as $exercise) {
                    $newDay->exercises()->create($exercise->only([
                        'exercise_id', 'order', 'sets', 'reps', 'weight', 'rest_seconds', 'notes'
                    ]));
                }
            }

            return $copy->fresh('days.exercises');
        });
    }
}
