@extends('layouts.app')

@section('breadcrumb')
    <nav class="flex text-sm text-gray-600 mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2">
            <li>
                <a href="{{ route('columns.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Columns
                </a>
            </li>
            <li class="text-gray-500">/</li>
            <li class="text-gray-700 font-semibold">
                Trash ({{ $columns->count() }})
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
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
                            @if (auth()->user()->can('deleteColumn', App\Column::class))
                                <!-- Restore -->
                                <form action="{{ route('columns.restore', $column->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to restore this column?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded-md shadow hover:bg-green-700"
                                        title="Restore this column">
                                        Restore
                                    </button>
                                </form>

                                <!-- Permanent Delete -->
                                <form action="{{ route('columns.destroy', $column->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to completely delete this column?')">
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
