<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-full items-center justify-center bg-slate-950 text-white">
<x-card class="w-full max-w-md">
    <h1 class="text-2xl font-bold">Olvidé mi contraseña</h1>
    <form method="POST" action="{{ route('password.email') }}" class="mt-4 space-y-4">
        @csrf
        <x-input label="Email" name="email" type="email" />
        <x-button class="w-full">Enviar enlace</x-button>
    </form>
</x-card>
</body>
</html>
