@extends('layouts.app')

@section('content')
<div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ $title }}</h1>

    <form action="{{ $route }}" method="POST" class="space-y-6 bg-white shadow-md rounded-lg p-6">
        @csrf
        @method($routeMethod)

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $user->name ?? '') }}"
                   maxlength="255" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('name') border-red-500 text-red-600 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
            <input type="email" name="email" id="email"
                   value="{{ old('email', $user->email ?? '') }}"
                   maxlength="255" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('email') border-red-500 text-red-600 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Language -->
        <div>
            <label for="language" class="block text-sm font-medium text-gray-700">{{ __('Language') }}</label>
            <select name="language" id="language"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('language') border-red-500 text-red-600 @enderror">
                <option value="en" @if (old('language', $user->language ?? '') == 'en') selected @endif>English</option>
                <option value="eu" @if (old('language', $user->language ?? '') == 'eu') selected @endif>Euskera</option>
            </select>
            @error('language')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
            <input type="password" name="password" id="password"
                   maxlength="255" @if ($routeMethod == 'POST') required @endif
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('password') border-red-500 text-red-600 @enderror">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Confirmation -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Password Confirmation') }}</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   maxlength="255"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('password_confirmation') border-red-500 text-red-600 @enderror">
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Active -->
        <div class="flex items-center">
            <input type="checkbox" name="active" id="active"
                   class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                   @if (old('active') == 'on' && !$user->exists) checked
                   @elseif (old('active') == null && !$user->exists && $errors->isEmpty()) checked
                   @elseif (old('active') == 'on' && $user->exists) checked
                   @elseif ($user->exists && $user->active && old('active') == null && $errors->isEmpty()) checked
                   @elseif ($user->exists && old('active') == 'on') checked @endif>
            <label for="active" class="ml-2 text-sm text-gray-700">{{ __('Active') }}</label>
        </div>

        <!-- Roles -->
        <div>
            <label for="roles" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Roles') }}</label>
            <select name="roles[]" id="roles" multiple
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('roles') border-red-500 text-red-600 @enderror">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        @if (in_array($role->id, old('roles', [])) || ($errors->isEmpty() && $user->hasRoleId($role->id))) selected @endif>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            @error('roles')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <div>
            @if (auth()->user()->can($policy, App\User::class))
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{ $button }}
                </button>
            @endif
        </div>
    </form>
</div>
@endsection
