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
                   value="{{ old('name', $tag->name ?? '') }}"
                   maxlength="255" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('name') border-red-500 text-red-600 @enderror">

            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Colour -->
        <div>
            <label for="colour" class="block text-sm font-medium text-gray-700">{{ __('Colour') }}</label>
            <input type="text" name="colour" id="colour"
                   value="{{ old('colour', $tag->colour ?? '') }}"
                   maxlength="10" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('colour') border-red-500 text-red-600 @enderror">

            @error('colour')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Active -->
        <div class="flex items-center">
            <input type="checkbox" name="active" id="active"
                   class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                   @if (old('active') == 'on' && !$tag->exists) checked
                   @elseif (old('active') == null && !$tag->exists && $errors->isEmpty()) checked
                   @elseif (old('active') == 'on' && $tag->exists) checked
                   @elseif ($tag->exists && $tag->active && old('active') == null && $errors->isEmpty()) checked
                   @elseif ($tag->exists && old('active') == 'on') checked @endif>
            <label for="active" class="ml-2 text-sm text-gray-700">{{ __('Active') }}</label>
        </div>

        <!-- Submit -->
        <div>
            @if (auth()->user()->can($policy, App\Tag::class))
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{ $button }}
                </button>
            @endif
        </div>
    </form>
</div
@endsection
