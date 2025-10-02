@extends('layouts.app')

@section('breadcrumb')
    <li class="text-sm text-gray-600">
        Tags ({{ $tags->count() }})
    </li>
@endsection

@section('create_trash')
    <div class="flex justify-between space-x-2 mb-4">
        @can('createTag', App\Tag::class)
            <a href="{{ route('tags.create') }}"
               class="px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700"
               title="Create a tag">
               New
            </a>
        @endcan
        @can('deleteTag', App\Tag::class)
            <a href="{{ route('tags.trash') }}"
               class="px-3 py-1 bg-gray-600 text-white text-sm font-medium rounded-md shadow hover:bg-gray-700"
               title="View deleted tags">
               Trash
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
        @if (session('error'))
            <p class="text-sm text-red-600 font-medium mb-3">
                {{ session('error') }}
            </p>
        @endif

        <table class="min-w-full border border-gray-200 rounded-lg divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700 text-left">
                <tr>
                    <th class="px-4 py-2 font-semibold">Tag</th>
                    <th class="px-4 py-2 font-semibold">Colour</th>
                    <th class="px-4 py-2 font-semibold">Active</th>
                    <th class="px-4 py-2 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($tags as $tag)
                    <tr>
                        <td class="px-4 py-2">
                            {{ $tag->getUpperName() }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-white text-xs font-medium"
                                  style="background-color: {{ $tag->colour }};">
                                {{ $tag->colour }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            {{ $tag->active ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-4 py-2 flex space-x-2 justify-end">
                            @can('editTag', App\Tag::class)
                                <a href="{{ route('tags.edit', $tag->id) }}"
                                   class="px-3 py-1 bg-indigo-600 text-white text-xs font-medium rounded-md shadow hover:bg-indigo-700"
                                   title="Edit this tag">
                                   Edit
                                </a>
                            @endcan

                            @if ($tag->tasks_count == 0 && auth()->user()->can('deleteTag', App\Tag::class))
                                <form action="{{ route('tags.delete', $tag->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this tag?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded-md shadow hover:bg-red-700"
                                        title="Delete this tag">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                            No tags found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
