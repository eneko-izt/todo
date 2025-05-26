@extends('layouts.app')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('columns.index') }}">Columns</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="container">
        <h1 class="title">{{ $title }}</h1>

        <form action="{{ $route }}" method="POST">
            @csrf
            @method( $routeMethod )
            
            <div class="field">
                <label class="label" for="name">Name</label>
                <div class="control">
                    <input 
                        class="input @error('name') help is-danger @enderror"
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $column->name ?? '') }}"
                        maxlength="255"
                        style="@error('name') color:#d8000c @enderror"
                        required>

                    @error('name')
                        <p class="help is-danger" style="color:#d8000c">{{ $errors->first('name') }}</p>
                    @enderror
                </div>
            </div>
            <div class="field">
                <label class="label" for="colour">Colour</label>
                <div class="control">
                    <input 
                        class="input @error('colour') is-danger @enderror"
                        type="text" 
                        name="colour" 
                        id="colour" 
                        value="{{ old('colour', $column->colour ?? '') }}"
                        maxlength="10" 
                        style="@error('colour') color:#d8000c @enderror"
                        required>

                    @error('colour')
                        <p class="help is-danger" style="color:#d8000c">{{ $errors->first('colour') }}</p>
                    @enderror
                </div>
            </div>
            <div class="field">
                <div class="control">
                    <input 
                        type="checkbox" 
                        name="active" 
                        @if (old('active') == 'on' && !$column->exists)
                            checked
                        @elseif (old('active') == null && !$column->exists && $errors->isEmpty())
                            checked
                        @elseif (old('active') == 'on' && $column->exists)
                            checked
                        @elseif ($column->exists && $column->active && old('active') == null && $errors->isEmpty())
                            checked
                        @elseif ($column->exists && old('active') == 'on' )
                            checked
                        @endif
                        
                    >
                    <label class="label" for="active">Active</label>
                </div>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-link" type="submit">{{$button}}</button>
                </div>
            </div>
        </form>
    </div>

@endsection