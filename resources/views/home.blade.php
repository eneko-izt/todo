@extends('layouts.app')

@section('content')
    @forelse ($columns as $column)

        {{-- Alpine component per column --}}
        <div class="w-80 rounded-xl p-4" 
             style="background-color: {{ $column->colour }};"
             x-data="{ 
                 open: {{ (old('column_id') == $column->id || session('open_modal') == $column->id) ? 'true' : 'false' }} 
             }">

            {{-- Column header --}}
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold">{{ $column->name }}</h2>
                <button class="bg-blue-500 text-white text-sm px-2 py-1 rounded"
                        @click="open = true">
                    {{ __('+ Task') }}
                </button>
            </div>

            {{-- Tasks --}}
            @foreach ($column->viewableTasks() as $task)
                @include('tasks.task', ['task' => $task])
            @endforeach

            {{-- Modal form --}}
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="column_id" value="{{ $column->id }}">

                {{-- Modal overlay --}}
                <div x-show="open"
                     x-transition.opacity
                     x-cloak
                     @click.self="open = false"
                     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    
                    {{-- Modal card --}}
                    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-2 border border-gray-200"
                         style="background-color: {{ $column->colour }};"
                    >
                        {{-- Header --}}
                        <div class="flex justify-between items-center border-b px-4 py-2">
                            <h3 class="text-lg font-bold text-gray-800">{{ __('New Task') }}</h3>
                            <button type="button"
                                    class="text-gray-500 hover:text-gray-800"
                                    @click="open = false">
                                &times;
                            </button>
                        </div>

                        {{-- Body --}}
                        <div class="px-4 py-4">
                            @include('tasks.new', ['column' => $column])
                        </div>

                        {{-- Footer --}}
                        <div class="flex justify-end gap-2 border-t px-4 py-2">
                            <button type="button"
                                    class="bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200"
                                    @click="open = false">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                {{ __('Create New Task') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>

    @empty
        <p>{{ __('No columns found.') }}</p>
    @endforelse
@endsection
