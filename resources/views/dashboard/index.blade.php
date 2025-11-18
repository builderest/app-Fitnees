@extends('layouts.app')

@section('content')
<div class="grid gap-6 md:grid-cols-2">
    <x-card>
        <h2 class="text-lg font-semibold">Hola {{ $user->name }}</h2>
        <p class="mt-2 text-sm text-slate-300">Objetivo: {{ ucfirst($user->training_goal ?? 'maintain') }} | Plan: {{ strtoupper($user->plan) }}</p>
        <div class="mt-4 flex space-x-3">
            <a href="{{ route('generator.index') }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold">Generar entrenamiento</a>
            <a href="{{ route('progress.index') }}" class="rounded-xl border border-slate-700 px-4 py-2 text-sm">Registrar progreso</a>
        </div>
    </x-card>
    <x-card>
        <h2 class="text-lg font-semibold">Sesiones recientes</h2>
        <ul class="mt-3 space-y-2">
            @forelse($recentSessions as $session)
                <li class="text-sm text-slate-300">{{ optional($session->performed_at)->format('d M Y') }} - {{ $session->intensity ?? 'N/A' }}</li>
            @empty
                <li class="text-sm text-slate-500">Sin sesiones registradas.</li>
            @endforelse
        </ul>
    </x-card>
</div>
@endsection
