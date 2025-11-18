<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\User;
use App\Models\WorkoutDay;
use App\Models\WorkoutExercise;
use App\Models\WorkoutProgram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Coach',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'plan' => 'premium',
            'premium_until' => now()->addYear(),
        ]);

        $this->seedExercises();

        $program = WorkoutProgram::create([
            'user_id' => $admin->id,
            'title' => 'Full Body Starter',
            'description' => 'Rutina global para todos los usuarios.',
            'goal' => 'performance',
            'level' => 'beginner',
            'type' => 'full_body',
            'is_global' => true,
            'is_active' => true,
        ]);

        $day = WorkoutDay::create([
            'workout_program_id' => $program->id,
            'title' => 'Día 1 - Full Body',
            'day_order' => 1,
        ]);

        Exercise::inRandomOrder()->limit(5)->get()->each(function (Exercise $exercise, $index) use ($day) {
            WorkoutExercise::create([
                'workout_day_id' => $day->id,
                'exercise_id' => $exercise->id,
                'order' => $index + 1,
                'sets' => 3,
                'reps' => 12,
                'rest_seconds' => 60,
            ]);
        });
    }

    protected function seedExercises(): void
    {
        $data = json_decode(file_get_contents(database_path('seeders/exercises.json')), true);

        collect($data)->each(function (array $exercise) {
            Exercise::updateOrCreate(
                ['slug' => Str::slug($exercise['name_en'])],
                array_merge($exercise, [
                    'slug' => Str::slug($exercise['name_en']),
                    'secondary_muscles' => $exercise['secondary_muscles'] ?? [],
                    'tags' => $exercise['tags'] ?? [],
                ])
            );
        });
    }
}
