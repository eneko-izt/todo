@extends('layouts.app')

@section('content')

    <div id="new-task-accordion" class="d-lg-flex justify-content-between w-100 mt-5">
        <div class="w-full max-w-md bg-white rounded-xl shadow-md p-6">
            
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
                {{ __('Login') }}
            </h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('E-Mail Address') }}
                    </label>
                    <input id="email" type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required autocomplete="email" autofocus
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 @error('email') border-red-500 @enderror">

                    @error('email')
                        <p class="text-sm text-red-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Password') }}
                    </label>
                    <input id="password" type="password" 
                        name="password" 
                        required autocomplete="current-password"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 @error('password') border-red-500 @enderror">

                    @error('password')
                        <p class="text-sm text-red-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-4">
                    <input id="remember" type="checkbox" 
                        name="remember" 
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="ml-2 text-sm text-gray-700">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-400">
                        {{ __('Login') }}
                    </button>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                        class="text-sm text-blue-600 hover:text-blue-800">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
