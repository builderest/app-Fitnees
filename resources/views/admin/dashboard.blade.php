@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold">Panel admin</h1>
<div class="mt-6 grid gap-4 md:grid-cols-4">
    <x-card>
        <p class="text-sm text-slate-400">Usuarios</p>
        <p class="text-3xl font-bold">{{ $users }}</p>
    </x-card>
    <x-card>
        <p class="text-sm text-slate-400">Premium</p>
        <p class="text-3xl font-bold">{{ $premium }}</p>
    </x-card>
    <x-card>
        <p class="text-sm text-slate-400">Ejercicios</p>
        <p class="text-3xl font-bold">{{ $exercises }}</p>
    </x-card>
    <x-card>
        <p class="text-sm text-slate-400">Programas</p>
        <p class="text-3xl font-bold">{{ $programs }}</p>
    </x-card>
</div>

<x-card class="mt-6">
    <h2 class="text-lg font-semibold">Nuevo ejercicio</h2>
    <form method="POST" action="{{ route('admin.exercises.store') }}" class="mt-4 grid gap-4 md:grid-cols-2">
        @csrf
        <x-input label="Nombre" name="name" />
        <x-input label="Nombre EN" name="name_en" />
        <x-input label="Descripción" name="description" class="md:col-span-2" />
        <x-input label="Descripción EN" name="description_en" class="md:col-span-2" />
        <x-input label="Músculo" name="primary_muscle" />
        <x-input label="Equipo" name="equipment" />
        <x-input label="Dificultad" name="difficulty" />
        <x-input label="Video" name="video_url" class="md:col-span-2" />
        <x-input label="Thumbnail" name="thumbnail_url" class="md:col-span-2" />
        <x-button class="md:col-span-2">Guardar</x-button>
    </form>
</x-card>
@endsection
