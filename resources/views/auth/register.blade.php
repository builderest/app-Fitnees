<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-full items-center justify-center bg-slate-950 text-white">
<x-card class="w-full max-w-md">
    <h1 class="text-2xl font-bold">Crear cuenta</h1>
    <form method="POST" action="{{ route('register') }}" class="mt-4 space-y-4">
        @csrf
        <x-input label="Nombre" name="name" />
        <x-input label="Email" name="email" type="email" />
        <x-input label="Contraseña" name="password" type="password" />
        <x-input label="Confirmar" name="password_confirmation" type="password" />
        <x-button class="w-full">Registrar</x-button>
    </form>
    <p class="mt-4 text-sm text-slate-400">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-rose-400">Inicia sesión</a></p>
</x-card>
</body>
</html>
