@extends('layouts.app')

@section('content')

<h3>{{ Date('Y-m-d') }}</h3>
<a href="{{ route('welcome') }}" class="btn btn-primary">Home</a>
<a href="{{ route('home') }}" class="btn btn-primary">Go to Dashboard</a>
<a href="{{ route('columns.index') }}" class="btn btn-primary">Columns</a>
<a href="{{ route('tags.index') }}" class="btn btn-primary">Tags</a>

    <div class="d-lg-flex justify-content-between w-100 mt-5" >    
        @forelse ($columns as $column)

            <div class="p-2 text-white @if (!$loop->first) ml-lg-2 @endif"
                style="min-width: {{ 100 / $columns->count() }}%; min-height: 150px; background-color: {{ $column->colour }};">
                {{ $column->name }}

                @foreach ( $column->tasks->where('active', true)->sortBy('order') as $task)
                    @include('tasks.task', ['task' => $task])
                @endforeach

                @include('tasks.new', ['column' => $column])

            </div>

        @empty
            <p>No columns found.</p>
        @endforelse
    </div>

@endsection
