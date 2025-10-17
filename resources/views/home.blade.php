@extends('layouts.app')

@section('content')

    <div x-data="taskModal()" 
        x-on:edit-task.window="openEditTask($event.detail.task)"
        x-init='
            @if ($errors->any())
                @if(old("task_id"))
                    // Edit task with old input
                    openEditTask({
                        id: {!! json_encode(old("task_id")) !!},
                        column_id: {!! json_encode(old("column_id")) !!},
                        column_colour: {!! json_encode(old("column_colour", "#ffffff")) !!},
                        text: {!! json_encode(old("text")) !!},
                        order: {!! json_encode(old("order")) !!},
                        tags: {!! json_encode(old("tags", [])) !!}
                    });
                @else
                    openNewTask(
                        {{ old("column_id") ?? "null" }},
                        {!! json_encode(old("column_colour", "#ffffff")) !!},
                    );
                @endif
            @endif
        '
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
                    this.taskText = '{{ old("text", "") }}';
                    this.taskOrder = '{{ old("order", "") }}';
                    this.taskTags = @json(old('tags', []));
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
