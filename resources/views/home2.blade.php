@extends('layouts.app2')

@section('content')

    @forelse ($columns as $column)

        <div class="w-80 rounded-xl p-4 bg-blue-100">
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

    <!-- Column: To Do -->
    <div class="w-80 rounded-xl p-4 bg-blue-100">
        <div class="flex justify-between items-center mb-3">
            <h2 class="font-bold">To Do</h2>
            <button class="bg-blue-500 text-white text-sm px-2 py-1 rounded">+ Task</button>
        </div>
        <div class="bg-white shadow rounded-xl mb-3">
            <div class="p-3">
            <p class="font-medium mb-2">Design login page</p>
            <div class="flex flex-wrap gap-2 mb-2">
                <span class="bg-gray-200 px-2 py-1 rounded text-xs">UI</span>
                <span class="bg-gray-200 px-2 py-1 rounded text-xs">Frontend</span>
            </div>
            <div class="flex items-center gap-2 mb-2">
                <span class="bg-gray-300 w-6 h-6 flex items-center justify-center rounded-full text-xs">A</span>
                <span class="bg-gray-300 w-6 h-6 flex items-center justify-center rounded-full text-xs">B</span>
            </div>
            <div class="flex gap-2">
                <button class="border px-2 py-1 rounded text-xs">Edit</button>
                <button class="border px-2 py-1 rounded text-xs">+ User</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Column: In Progress -->
    <div class="w-80 rounded-xl p-4 bg-yellow-100">
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-bold">In Progress</h2>
        <button class="bg-yellow-500 text-white text-sm px-2 py-1 rounded">+ Task</button>
    </div>
    <div class="bg-white shadow rounded-xl mb-3">
        <div class="p-3">
        <p class="font-medium mb-2">Implement API endpoints</p>
        <div class="flex flex-wrap gap-2 mb-2">
            <span class="bg-gray-200 px-2 py-1 rounded text-xs">Backend</span>
        </div>
        <div class="flex items-center gap-2 mb-2">
            <span class="bg-gray-300 w-6 h-6 flex items-center justify-center rounded-full text-xs">C</span>
        </div>
        <div class="flex gap-2">
            <button class="border px-2 py-1 rounded text-xs">Edit</button>
            <button class="border px-2 py-1 rounded text-xs">+ User</button>
        </div>
        </div>
    </div>
    </div>

    <!-- Column: Done -->
    <div class="w-80 rounded-xl p-4 bg-green-100">
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-bold">Done</h2>
        <button class="bg-green-500 text-white text-sm px-2 py-1 rounded">+ Task</button>
    </div>
    <div class="bg-white shadow rounded-xl mb-3">
        <div class="p-3">
        <p class="font-medium mb-2">Write documentation</p>
        <div class="flex flex-wrap gap-2 mb-2">
            <span class="bg-gray-200 px-2 py-1 rounded text-xs">Docs</span>
        </div>
        <div class="flex items-center gap-2 mb-2">
            <span class="bg-gray-300 w-6 h-6 flex items-center justify-center rounded-full text-xs">D</span>
        </div>
        <div class="flex gap-2">
            <button class="border px-2 py-1 rounded text-xs">Edit</button>
            <button class="border px-2 py-1 rounded text-xs">+ User</button>
        </div>
        </div>
    </div>
    </div>


@endsection