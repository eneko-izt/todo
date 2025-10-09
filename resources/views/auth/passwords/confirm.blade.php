@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-md rounded-2xl overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold text-gray-700">
                    {{ __('Confirm Password') }}
                </h2>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <p class="mb-4 text-sm text-gray-600">
                    {{ __('Please confirm your password before continuing.') }}
                </p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            {{ __('Password') }}
                        </label>
                        <input id="password" type="password"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                                   focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                                   @error('password') border-red-500 @enderror"
                            name="password" required autocomplete="current-password">

                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg shadow-md
                                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Confirm Password') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                               href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
