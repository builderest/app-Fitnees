@extends('layouts.app')

@section('content')
<div class="flex justify-between">
    <h1 class="text-2xl font-bold">Mis rutinas</h1>
    <a href="{{ route('routines.create') }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold">Nueva rutina</a>
</div>
<div class="mt-6 grid gap-4 md:grid-cols-2">
    @foreach($programs as $program)
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">{{ $program->title }}</h3>
                    <p class="text-sm text-slate-400">{{ $program->goal }} • {{ $program->level }}</p>
                </div>
                <form method="POST" action="{{ route('routines.duplicate', $program) }}">
                    @csrf
                    <x-button>Duplicar</x-button>
                </form>
            </div>
        </x-card>
    @endforeach
</div>
<div class="mt-6">{{ $programs->links() }}</div>
@endsection
