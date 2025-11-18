<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class ProgressService
{
    public function weightSeries(User $user): Collection
    {
        return $user->progressEntries()->orderBy('date')->get()->map(fn ($entry) => [
            'date' => $entry->date->format('Y-m-d'),
            'weight' => $entry->weight,
        ]);
    }
}
