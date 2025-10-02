@extends('layouts.app')

@section('content')

    @forelse ($columns as $column)

        <div class="w-80 rounded-xl p-4" style="background-color: {{ $column->colour }};">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold">{{ $column->name }}</h2>
                <button class="bg-blue-500 text-white text-sm px-2 py-1 rounded"
                    onclick="document.getElementById('modal-new-{{ $column->id }}').classList.remove('hidden')">
                    + Task
                </button>
            </div>

            @foreach ($column->viewableTasks() as $task)
                @include('tasks.task', ['task' => $task])
            @endforeach

            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                @method('POST')

                <!-- Modal overlay -->
                <div id="modal-new-{{ $column->id }}"  
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">

                    <!-- Modal card (like task card) -->
                    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-2 border border-gray-200"  style="background-color: {{ $column->colour }};">
                        
                        <!-- Header -->
                        <div class="flex justify-between items-center border-b px-4 py-2">
                            <h3 class="text-lg font-bold text-gray-800">New Task</h3>
                            <button type="button" 
                                    class="text-gray-500 hover:text-gray-800"
                                    onclick="document.getElementById('modal-new-{{ $column->id }}').classList.add('hidden')">
                                &times;
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="px-4 py-4">
                            @include('tasks.new', ['column' => $column])
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end gap-2 border-t px-4 py-2">
                            <button type="button" 
                                    class="bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200"
                                    onclick="document.getElementById('modal-new-{{ $column->id }}').classList.add('hidden')">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                Create New Task
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>

    @empty
        <p>No columns found.</p>
    @endforelse


@endsection