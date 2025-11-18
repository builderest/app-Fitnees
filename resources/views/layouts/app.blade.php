<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'FitForge') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-950 text-slate-100">
<div class="min-h-full">
    <nav class="bg-slate-900 border-b border-slate-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-lg font-semibold">FitForge</a>
                    <x-nav-link href="{{ route('generator.index') }}" label="Generator" />
                    <x-nav-link href="{{ route('exercises.index') }}" label="Exercises" />
                    <x-nav-link href="{{ route('routines.index') }}" label="Routines" />
                    <x-nav-link href="{{ route('progress.index') }}" label="Progress" />
                    <x-nav-link href="{{ route('sessions.index') }}" label="Sessions" />
                </div>
                @auth
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-slate-300">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-md bg-rose-600 px-3 py-1 text-sm font-medium">Cerrar sesión</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>
</div>
@stack('scripts')
</body>
</html>
