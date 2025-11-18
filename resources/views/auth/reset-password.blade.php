<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer contraseña</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-full items-center justify-center bg-slate-950 text-white">
<x-card class="w-full max-w-md">
    <h1 class="text-2xl font-bold">Nueva contraseña</h1>
    <form method="POST" action="{{ route('password.store') }}" class="mt-4 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <x-input label="Email" name="email" type="email" value="{{ old('email', $request->email) }}" />
        <x-input label="Contraseña" name="password" type="password" />
        <x-input label="Confirmar" name="password_confirmation" type="password" />
        <x-button class="w-full">Restablecer</x-button>
    </form>
</x-card>
</body>
</html>
