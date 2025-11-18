@extends('layouts.app')

@section('content')
<x-card>
    <h1 class="text-2xl font-bold">Crear rutina</h1>
    <form method="POST" action="{{ route('routines.store') }}" class="mt-4 grid gap-4 md:grid-cols-2">
        @csrf
        <x-input label="Título" name="title" class="md:col-span-2" />
        <x-input label="Descripción" name="description" class="md:col-span-2" />
        <x-input label="Objetivo" name="goal" />
        <x-input label="Nivel" name="level" />
        <x-input label="Tipo" name="type" />
        <x-button class="md:col-span-2">Guardar</x-button>
    </form>
</x-card>
@endsection
