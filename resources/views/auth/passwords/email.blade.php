@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-md rounded-2xl overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold text-gray-700">
                    {{ __('Reset Password') }}
                </h2>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                @if (session('status'))
                    <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-2 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            {{ __('e-mail') }}
                        </label>
                        <input id="email" type="email"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                                   focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                                   @error('email') border-red-500 @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg shadow-md
                                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
