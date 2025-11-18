<?php
namespace App\Policies;

use App\Models\User;
use App\Models\WorkoutProgram;

class WorkoutProgramPolicy
{
    public function manage(User $user, WorkoutProgram $program): bool
    {
        if (in_array($user->role, ['admin', 'coach'], true)) {
            return true;
        }
        return $program->owner_type === 'user' && $program->user_id === $user->id;
    }
}
