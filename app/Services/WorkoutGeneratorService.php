<?php

namespace App\Services;

use App\Models\Exercise;
use Illuminate\Support\Collection;

class WorkoutGeneratorService
{
    public function generate(array $equipment, array $muscles, int $count): Collection
    {
        $query = Exercise::query()
            ->whereIn('equipment', $equipment)
            ->whereIn('primary_muscle', $muscles);

        $exercises = $query->inRandomOrder()->take($count)->get();

        return $exercises->values()->map(fn ($exercise, $index) => [
            'order' => $index + 1,
            'exercise' => $exercise,
            'sets' => 3,
            'reps' => 12,
            'rest_seconds' => 60,
        ]);
    }
}
