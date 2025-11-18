<?php
require __DIR__ . '/../../vendor/autoload.php';

use App\Models\User;
use App\Models\Exercise;
use App\Models\WorkoutProgram;
use App\Models\WorkoutSession;
use App\Models\UserProgress;

$admin = new User([
    'name' => 'Admin Coach',
    'email' => 'admin@example.com',
    'password' => password_hash('password', PASSWORD_BCRYPT),
    'role' => 'admin',
    'plan' => 'premium'
]);
$admin->save();

$coach = new User([
    'name' => 'Coach Prime',
    'email' => 'coach@example.com',
    'password' => password_hash('password', PASSWORD_BCRYPT),
    'role' => 'coach',
    'plan' => 'premium'
]);
$coach->save();

$sampleUser = new User([
    'name' => 'Athlete',
    'email' => 'user@example.com',
    'password' => password_hash('password', PASSWORD_BCRYPT),
    'role' => 'user',
    'training_goal' => 'muscle_gain',
    'training_level' => 'intermediate',
    'plan' => 'premium'
]);
$sampleUser->save();

$exerciseData = json_decode(file_get_contents(__DIR__ . '/exercise_seed.json'), true);
foreach ($exerciseData as $exercise) {
    (new Exercise($exercise))->save();
}

$program = new WorkoutProgram([
    'title' => 'Push Pull Legs',
    'type' => 'push_pull_legs',
    'owner_type' => 'global',
    'days' => [
        ['title' => 'Push', 'exercises' => ['Press banca', 'Fondos']],
        ['title' => 'Pull', 'exercises' => ['Dominadas', 'Remo con barra']],
        ['title' => 'Legs', 'exercises' => ['Sentadilla frontal', 'Peso muerto rumano']],
    ]
]);
$program->save();

$session = new WorkoutSession([
    'user_id' => $sampleUser->id,
    'date' => date('Y-m-d'),
    'status' => 'completed',
    'exercises' => [['name' => 'Press banca'], ['name' => 'Dominadas']],
    'completed_exercises' => 2,
    'total_exercises' => 2,
    'intensity' => 3
]);
$session->save();

for ($i = 0; $i < 6; $i++) {
    $progress = new UserProgress([
        'user_id' => $sampleUser->id,
        'date' => date('Y-m-d', strtotime("-{$i} week")),
        'weight' => 80 - $i,
        'body_fat' => 18 - ($i * 0.2)
    ]);
    $progress->save();
}

echo "Seed completado\n";
