<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PricingController extends Controller
{
    public function index(): View
    {
        return view('pricing.index');
    }

    public function activate(): RedirectResponse
    {
        $user = Auth::user();
        $user->update([
            'plan' => 'premium',
            'premium_until' => now()->addMonth(),
        ]);

        return back()->with('status', 'Plan premium activado por 30 días.');
    }
}
