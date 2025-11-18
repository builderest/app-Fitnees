<?php
namespace App\Services;

use App\Models\WorkoutSession;
use App\Models\WorkoutSessionExercise;

class SessionService
{
    public function activeSession(int $userId): ?WorkoutSession
    {
        return WorkoutSession::where('user_id', $userId);
    }

    public function createSession(int $userId, array $payload): WorkoutSession
    {
        $session = new WorkoutSession($payload);
        $session->user_id = $userId;
        $session->status = 'in_progress';
        $session->total_exercises = count($payload['exercises'] ?? []);
        $session->completed_exercises = 0;
        $session->save();
        return $session;
    }

    public function completeExercise(WorkoutSession $session): void
    {
        $session->completed_exercises++;
        if ($session->completed_exercises >= $session->total_exercises) {
            $session->status = 'completed';
        }
        $session->save();
    }
}
