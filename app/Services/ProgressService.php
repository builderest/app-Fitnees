<?php
namespace App\Services;

use App\Models\UserProgress;
use App\Models\WorkoutSession;

class ProgressService
{
    public function weightSeries(int $userId): array
    {
        $entries = UserProgress::filter(fn ($progress) => $progress->user_id === $userId);
        usort($entries, fn ($a, $b) => strcmp($a->date, $b->date));
        return array_map(fn ($entry) => ['date' => $entry->date, 'weight' => $entry->weight], $entries);
    }

    public function contributionMatrix(int $userId): array
    {
        $sessions = WorkoutSession::filter(fn ($session) => $session->user_id === $userId);
        $matrix = [];
        foreach ($sessions as $session) {
            $matrix[$session->date] = $session->completed_exercises;
        }
        return $matrix;
    }
}
