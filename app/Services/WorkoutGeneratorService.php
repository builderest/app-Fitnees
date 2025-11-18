<?php
namespace App\Services;

use App\Models\Exercise;

class WorkoutGeneratorService
{
    private array $repScheme = [
        'easy' => ['sets' => 3, 'reps' => '12-15'],
        'medium' => ['sets' => 4, 'reps' => '8-12'],
        'hard' => ['sets' => 5, 'reps' => '5-8'],
    ];

    public function generate(array $equipment, array $muscles, int $count): GeneratedWorkoutDTO
    {
        $exercises = Exercise::filter(function (Exercise $exercise) use ($equipment, $muscles) {
            $equipmentMatch = empty($equipment) || in_array($exercise->equipment, $equipment, true);
            $muscleMatch = empty($muscles) || in_array($exercise->primary_muscle, $muscles, true);
            return $equipmentMatch && $muscleMatch;
        });

        shuffle($exercises);
        $selected = array_slice($exercises, 0, $count);
        $payload = [];
        foreach ($selected as $exercise) {
            $scheme = $this->repScheme[$exercise->difficulty] ?? ['sets' => 3, 'reps' => '10'];
            $payload[] = [
                'exercise' => $exercise,
                'sets' => $scheme['sets'],
                'reps' => $scheme['reps'],
            ];
        }

        return new GeneratedWorkoutDTO($payload, [
            'equipment' => $equipment,
            'muscles' => $muscles,
            'count' => $count,
        ]);
    }
}
