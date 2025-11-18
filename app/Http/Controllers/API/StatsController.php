<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WorkoutSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatsController extends Controller
{
    public function contributionCalendar()
    {
        $sessions = WorkoutSession::where('user_id', Auth::id())
            ->selectRaw('date(performed_at) as day, count(*) as total')
            ->groupBy('day')
            ->get();

        return response()->json($sessions);
    }
}
