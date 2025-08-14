@extends('layouts.app')

@section('content')

    <h3>{{ Date('Y-m-d') }}</h3>
    <a href="{{ route('welcome') }}" class="btn btn-primary" title="Go to Welcome page">Home</a>
    <a href="{{ route('home') }}" class="btn btn-primary" title="Go to Dashboard">Go to Dashboard</a>
    @can('viewColumn', 'App\Column')
        <a href="{{ route('columns.index') }}" class="btn btn-primary" title="Go to Columns page">Columns</a>
    @endcan
    @can('viewTag', 'App\Tag')
        <a href="{{ route('tags.index') }}" class="btn btn-primary" title="Go to Tags page">Tags</a>
    @endcan
    @can('viewUser', 'App\User')
        <a href="{{ route('users.index') }}" class="btn btn-primary" title="Go to Users page">Users</a>
    @endcan
    <div class="d-lg-flex justify-content-between w-100 mt-5">
        @forelse ($columns as $column)
            <div class="p-2 text-white @if (!$loop->first) ml-lg-2 @endif"
                style="min-width: {{ 100 / $columns->count() }}%; min-height: 150px; background-color: {{ $column->colour }};">
                {{ $column->name }}

                @foreach ($column->viewableTasks as $task)
                    @include('tasks.task', ['task' => $task, 'users' => $users])
                @endforeach

                @include('tasks.new', ['column' => $column])

            </div>

        @empty
            <p>No columns found.</p>
        @endforelse
    </div>

@endsection
