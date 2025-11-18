<?php

namespace App\Services;

use App\Models\WorkoutSession;
use Illuminate\Support\Facades\Auth;

class SessionService
{
    public function start(array $data): WorkoutSession
    {
        return WorkoutSession::create(array_merge($data, [
            'user_id' => Auth::id(),
            'performed_at' => now(),
        ]));
    }
}
