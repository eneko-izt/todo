@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}" alt="Users">Users</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection

@section('content')

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
                    value="{{ old('name', $user->name ?? '') }}"
                    maxlength="255"
                    style="@error('name') color:#d8000c @enderror"
                    required>

                @error('name')
                    <p class="help is-danger" style="color:#d8000c">{{ $errors->first('name') }}</p>
                @enderror
            </div>
        </div>
        <div class="field">
            <label class="label" for="email">Email</label>
            <div class="control">
                <input 
                    class="input @error('email') is-danger @enderror"
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email', $user->email ?? '') }}"
                    maxlength="255" 
                    style="@error('email') color:#d8000c @enderror"
                    required>

                @error('email')
                    <p class="help is-danger" style="color:#d8000c">{{ $errors->first('email') }}</p>
                @enderror
            </div>
        </div>
        <div class="field is-grouped">
            <div class="control">
                <button class="btn btn-primary is-link" type="submit" title={{$button}}>{{$button}}</button>
            </div>
        </div>
    </form>

@endsection