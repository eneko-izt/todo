@extends('layouts.app')

@section('content')

<h3>{{ Date('Y-m-d') }}</h3>
<a href="{{ route('welcome') }}" class="btn btn-primary">Home</a>
<a href="{{ route('home') }}" class="btn btn-primary">Go to Dashboard</a>
<a href="{{ route('columns.index') }}" class="btn btn-primary">Columns</a>
<a href="{{ route('tags.index') }}" class="btn btn-primary">Tags</a>

<div class="d-flex justify-content-between mt-5">

    <div class="d-flex justify-content-between w-100">    
        @forelse ($columns as $column)

            <div class="p-2 text-white @if (!$loop->first) ml-2 @endif"
                style="width: {{ 100 / $columns->count() }}%; height:150px; background-color: {{ $column->colour }};">
                {{ $column->name }}
            </div>

        @empty
            <p>No columns found.</p>
        @endforelse
    </div>
   
</div>

@endsection
