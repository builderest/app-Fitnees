<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar email</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-full items-center justify-center bg-slate-950 text-white">
<x-card class="w-full max-w-md">
    <h1 class="text-2xl font-bold">Verifica tu correo</h1>
    <p class="mt-2 text-sm text-slate-300">Te enviamos un enlace de verificación.</p>
    <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
        @csrf
        <x-button class="w-full">Reenviar</x-button>
    </form>
</x-card>
</body>
</html>
