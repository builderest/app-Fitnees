<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkoutProgram;

class WorkoutProgramPolicy
{
    public function update(User $user, WorkoutProgram $program): bool
    {
        return $user->id === $program->user_id || in_array($user->role, ['admin', 'coach'], true);
    }

    public function delete(User $user, WorkoutProgram $program): bool
    {
        return $this->update($user, $program);
    }
}
