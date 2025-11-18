<?php
namespace App\Services;

use App\Models\WorkoutProgram;
use App\Models\WorkoutDay;

class RoutineService
{
    public function userPrograms(int $userId): array
    {
        return WorkoutProgram::filter(fn ($program) => $program->owner_type === 'user' && $program->user_id === $userId);
    }

    public function createProgram(int $userId, array $data): WorkoutProgram
    {
        $program = new WorkoutProgram($data);
        $program->owner_type = 'user';
        $program->user_id = $userId;
        $program->days = $data['days'] ?? [];
        $program->save();
        return $program;
    }

    public function duplicate(WorkoutProgram $program): WorkoutProgram
    {
        $clone = new WorkoutProgram(get_object_vars($program));
        $clone->id = null;
        $clone->title .= ' (Copy)';
        $clone->is_active = false;
        $clone->save();
        return $clone;
    }

    public function activate(int $userId, WorkoutProgram $program): void
    {
        foreach ($this->userPrograms($userId) as $userProgram) {
            $userProgram->is_active = $userProgram->id === $program->id;
            $userProgram->save();
        }
    }
}
