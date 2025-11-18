<?php

namespace App\Http\Controllers;

use App\Models\WorkoutProgram;
use App\Models\WorkoutSession;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        $activeProgram = WorkoutProgram::where('user_id', $user->id)
            ->orWhere('is_global', true)
            ->with('days.exercises.exercise')
            ->orderByDesc('is_active')
            ->latest()
            ->first();

        $recentSessions = WorkoutSession::where('user_id', $user->id)
            ->latest('performed_at')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('user', 'activeProgram', 'recentSessions'));
    }
}
