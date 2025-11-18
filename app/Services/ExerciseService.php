<?php
namespace App\Services;

use App\Models\Exercise;

class ExerciseService
{
    public function filter(array $filters): array
    {
        return Exercise::filter(function (Exercise $exercise) use ($filters) {
            if (!empty($filters['muscle']) && $exercise->primary_muscle !== $filters['muscle']) {
                return false;
            }
            if (!empty($filters['equipment']) && $exercise->equipment !== $filters['equipment']) {
                return false;
            }
            if (!empty($filters['difficulty']) && $exercise->difficulty !== $filters['difficulty']) {
                return false;
            }
            if (!empty($filters['search'])) {
                $needle = strtolower($filters['search']);
                if (!str_contains(strtolower($exercise->name), $needle) && !str_contains(strtolower($exercise->description), $needle)) {
                    return false;
                }
            }
            return true;
        });
    }
}
