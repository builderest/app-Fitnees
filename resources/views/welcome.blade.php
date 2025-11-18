<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-full items-center justify-center bg-slate-950 text-white">
    <div class="max-w-xl text-center space-y-6">
        <h1 class="text-4xl font-bold">Entrena con inteligencia</h1>
        <p class="text-slate-300">Plataforma fitness con generador de entrenamientos, rutinas y progreso visual tipo GitHub.</p>
        <div class="flex justify-center space-x-3">
            <a href="{{ route('register') }}" class="rounded-xl bg-rose-600 px-6 py-3 font-semibold">Comenzar</a>
            <a href="{{ route('login') }}" class="rounded-xl border border-slate-600 px-6 py-3">Iniciar sesión</a>
        </div>
    </div>
</body>
</html>
