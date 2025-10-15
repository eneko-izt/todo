@extends('layouts.app')

@section('content')

    <div x-data="taskModal()" 
        x-init="
            @if (old('column_id') || session('open_modal'))
                openNewTask(
                    {{ session('open_modal') }},
                    @json(session('modal_column_colour'))
                );
            @endif
        "
        x-cloak>
        <div class="flex gap-4 overflow-x-auto px-4">

            @forelse ($columns as $column)

                {{-- Alpine component per column --}}
                <div class="w-80 rounded-xl p-4" 
                    style="background-color: {{ $column->colour }};">

                    {{-- Column header --}}
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="font-bold">{{ $column->name }}</h2>
                        <button class="bg-blue-500 text-white text-sm px-2 py-1 rounded"
                                @click="openNewTask({{ $column->id }}, '{{ $column->colour }}')">
                            + Task
                        </button>
                    </div>

                    {{-- Tasks --}}
                    @foreach ($column->viewableTasks() as $task)
                        @include('tasks.task', ['task' => $task])
                    @endforeach

                </div>

            @empty
                <p>No columns found.</p>
            @endforelse
        
        </div>

        @include('tasks.modal') <!-- shared modal here -->

    </div>

    <script>
        function taskModal() {
            return {
                open: false,
                isEditing: false,
                taskId: null,
                columnId: null,
                columnName: '',
                columnColour: '',
                taskText: '',
                taskOrder: '',
                taskTags: [],

                openNewTask(columnId, columnColour) {
                    this.isEditing = false;
                    this.taskId = null;
                    this.columnId = columnId;
                    this.columnColour = columnColour;
                    this.taskText = '';
                    this.taskOrder = '';
                    this.taskTags = [];
                    this.open = true;
                },

                openEditTask(task) {
                    this.isEditing = true;
                    this.taskId = task.id;
                    this.columnId = task.column_id;
                    this.columnColour = task.column_colour;
                    this.taskText = task.text;
                    this.taskOrder = task.order;
                    this.taskTags = task.tags;
                    this.open = true;
                }
            }
        }
    </script>
@endsection
