<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-full items-center justify-center bg-slate-950 text-white">
<x-card class="w-full max-w-md">
    <h1 class="text-2xl font-bold">Iniciar sesión</h1>
    <form method="POST" action="{{ route('login') }}" class="mt-4 space-y-4">
        @csrf
        <x-input label="Email" name="email" type="email" />
        <x-input label="Contraseña" name="password" type="password" />
        <label class="flex items-center space-x-2 text-sm text-slate-300">
            <input type="checkbox" name="remember" class="rounded border-slate-700 bg-slate-900">
            <span>Recordarme</span>
        </label>
        <a href="{{ route('password.request') }}" class="block text-sm text-rose-400">¿Olvidaste la contraseña?</a>
        <x-button class="w-full">Entrar</x-button>
    </form>
    <p class="mt-4 text-sm text-slate-400">¿Sin cuenta? <a href="{{ route('register') }}" class="text-rose-400">Regístrate</a></p>
</x-card>
</body>
</html>
