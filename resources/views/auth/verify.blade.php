@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-md rounded-2xl overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold text-gray-700">
                    {{ __('Verify Your Email Address') }}
                </h2>
            </div>

            <!-- Card Body -->
            <div class="p-6 text-sm text-gray-600">
                @if (session('resent'))
                    <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-2 text-sm">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                <p class="mb-2">
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                </p>
                <p class="mb-4">
                    {{ __('If you did not receive the email') }},
                </p>

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit"
                        class="text-blue-600 hover:text-blue-800 font-medium underline">
                        {{ __('click here to request another') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
