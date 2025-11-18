@extends('layouts.app')

@section('content')
<x-card class="text-center">
    <h1 class="text-4xl font-bold">404</h1>
    <p class="mt-2 text-slate-400">Página no encontrada.</p>
    <a href="{{ route('dashboard') }}" class="mt-4 inline-flex rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold">Volver</a>
</x-card>
@endsection
