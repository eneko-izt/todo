@extends('layouts.app')

@section('content')
<div class="overflow-x-auto bg-white shadow-md rounded-md p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">All columns</h1>

    @if (session('error'))
        <p class="text-sm text-red-600 font-medium mb-3">
            {{ session('error') }}
        </p>
    @endif

    <table id="myTable" class="min-w-full text-sm" role="grid">
        <thead class="bg-gray-100 text-left text-gray-700">
            <tr>
                <th class="px-4 py-2 font-semibold">Column</th>
                <th class="px-4 py-2 font-semibold">Colour</th>
                <th class="px-4 py-2 font-semibold">Active</th>
                <th class="px-4 py-2 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($columns as $column)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $column->getUpperName() }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-white text-xs font-medium rounded"
                              style="background-color: {{ $column->colour }};">
                            {{ $column->colour }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $column->active ? 'Yes' : 'No' }}</td>
                    <td class="px-4 py-2 flex space-x-2 justify-end">
                        @can('editColumn', App\Column::class)
                            <a href="{{ route('columns.edit', $column->id) }}"
                               class="px-3 py-1 bg-indigo-600 text-white text-xs font-medium rounded-md shadow hover:bg-indigo-700"
                               title="Edit this column">Edit</a>
                        @endcan
                        @if ($column->tasks_count == 0 && auth()->user()->can('deleteColumn', App\Column::class))
                            <form action="{{ route('columns.delete', $column->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this column?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded-md shadow hover:bg-red-700"
                                        title="Delete this column">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            paging: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true,
            language: {
                paginate: {
                    emptyTable: "No columns found.",
                    previous: 'Previous',
                    next: 'Next'
                },
                lengthMenu: "Show _MENU_ entries"
            },
            dom: '<"flex justify-between items-center mb-2"<"flex items-center space-x-2"l><"ml-auto"f>>t<"flex justify-between items-center mt-2"ip>'
        });
    });
</script>
@endsection
