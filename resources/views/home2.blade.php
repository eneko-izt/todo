@extends('layouts.app2')

@section('content')

    @forelse ($columns as $column)

        <div class="w-80 rounded-xl p-4" style="background-color: {{ $column->colour }};">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold">{{ $column->name }}</h2>
                <button class="bg-blue-500 text-white text-sm px-2 py-1 rounded">+ Task</button>
            </div>

            @foreach ($column->viewableTasks() as $task)
                @include('tasks.task2', ['task' => $task])
            @endforeach

        </div>

    @empty
        <p>No columns found.</p>
    @endforelse


@endsection