@extends('layouts.app')

@section('breadcrumb')
    <li class="text-sm text-gray-600">
        Columns ({{ $columns->count() }})
    </li>
@endsection

@section('create_trash')
    <div class="flex justify-between space-x-2">
        @can('createColumn', App\Column::class)
            <a href="{{ route('columns.create') }}"
               class="px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700"
               title="Create a column">
               New
            </a>
        @endcan
        @can('deleteColumn', App\Column::class)
            <a href="{{ route('columns.trash') }}"
               class="px-3 py-1 bg-gray-600 text-white text-sm font-medium rounded-md shadow hover:bg-gray-700"
               title="View deleted columns">
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
                    <th class="px-4 py-2 font-semibold">Column</th>
                    <th class="px-4 py-2 font-semibold">Colour</th>
                    <th class="px-4 py-2 font-semibold">Active</th>
                    <th class="px-4 py-2 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($columns as $column)
                    <tr>
                        <td class="px-4 py-2">
                            {{ $column->getUpperName() }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-white text-xs font-medium"
                                  style="background-color: {{ $column->colour }};">
                                {{ $column->colour }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            {{ $column->active ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-4 py-2 flex space-x-2 justify-end">
                            @can('editColumn', App\Column::class)
                                <a href="{{ route('columns.edit', $column->id) }}"
                                   class="px-3 py-1 bg-indigo-600 text-white text-xs font-medium rounded-md shadow hover:bg-indigo-700"
                                   title="Edit this column">
                                   Edit
                                </a>
                            @endcan

                            @if ($column->tasks_count == 0 && auth()->user()->can('deleteColumn', App\Column::class))
                                <form action="{{ route('columns.delete', $column->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this column?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded-md shadow hover:bg-red-700"
                                        title="Delete this column">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                            No columns found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
