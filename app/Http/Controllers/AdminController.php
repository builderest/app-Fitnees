<?php
namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Exercise;
use App\Models\User;
use App\Models\WorkoutProgram;

class AdminController extends Controller
{
    public function dashboard(): void
    {
        $users = User::all();
        $exercises = Exercise::all();
        $programs = WorkoutProgram::all();
        $this->view('admin.dashboard', compact('users', 'exercises', 'programs'));
    }

    public function storeExercise(Request $request): void
    {
        $exercise = new Exercise($request->all());
        $exercise->slug = $request->input('slug') ?: strtolower(str_replace(' ', '-', $exercise->name));
        $exercise->secondary_muscles = $request->post['secondary_muscles'] ?? [];
        $exercise->tags = $request->post['tags'] ?? [];
        $exercise->save();
        redirect('/admin');
    }
}
