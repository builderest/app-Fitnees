<?php
namespace App\Http\Controllers\Api;

use App\Core\AuthManager;
use App\Models\UserProgress;
use App\Models\WorkoutSession;

class StatsController
{
    public function weight(): void
    {
        $user = AuthManager::user();
        $entries = UserProgress::filter(fn ($progress) => $progress->user_id === $user->id);
        $payload = array_map(fn ($entry) => ['date' => $entry->date, 'weight' => $entry->weight], $entries);
        header('Content-Type: application/json');
        echo json_encode($payload);
    }

    public function contributions(): void
    {
        $user = AuthManager::user();
        $sessions = WorkoutSession::filter(fn ($session) => $session->user_id === $user->id);
        $payload = array_map(fn ($session) => ['date' => $session->date, 'value' => $session->completed_exercises], $sessions);
        header('Content-Type: application/json');
        echo json_encode($payload);
    }
}
