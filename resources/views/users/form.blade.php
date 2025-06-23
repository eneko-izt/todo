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

        <div class="field">
            <label class="label" for="password">Password</label>
            <div class="control">
                <input 
                    class="input @error('password') is-danger @enderror"
                    type="password" 
                    name="password" 
                    id="password" 
                    value=""
                    maxlength="255" 
                    style="@error('password') color:#d8000c @enderror"
                    @if($routeMethod == 'POST') required @endif
                >

                @error('password')
                    <p class="help is-danger" style="color:#d8000c">{{ $errors->first('password') }}</p>
                @enderror
            </div>
        </div>

        <div class="field">
            <label class="label" for="password_confirmation">Password confirmation</label>
            <div class="control">
                <input 
                    class="input @error('password_confirmation') is-danger @enderror"
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    value=""
                    maxlength="255" 
                    style="@error('password_confirmation') color:#d8000c @enderror"
                >

                @error('password_confirmation')
                    <p class="help is-danger" style="color:#d8000c">{{ $errors->first('password_confirmation') }}</p>
                @enderror
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input 
                    type="checkbox" 
                    name="active" 
                    @if (old('active') == 'on' && !$user->exists)
                        checked
                    @elseif (old('active') == null && !$user->exists && $errors->isEmpty())
                        checked
                    @elseif (old('active') == 'on' && $user->exists)
                        checked
                    @elseif ($user->exists && $user->active && old('active') == null && $errors->isEmpty())
                        checked
                    @elseif ($user->exists && old('active') == 'on' )
                        checked
                    @endif
                >
                <label class="label" for="active">Active</label>
            </div>
        </div>

        <label for="input">Roles:</label>
        <select 
            class="form-control select2"
            name="roles[]"
            id="roles"
            style="width: 100%;"
            multiple
        >
            @foreach($roles as $role)
                <option 
                    value="{{ $role->id }}"
                    @if (in_array($role->id, old('roles', [])) || ($errors->isEmpty() && $user->hasRoleId($role->id)))
                        selected
                    @endif
                >{{ $role->name }}
                </option>
            @endforeach
        </select>

        <div class="field is-grouped">
            <div class="control">
                <button class="btn btn-primary is-link" type="submit" title={{$button}}>{{$button}}</button>
            </div>
        </div>

    </form>

@endsection