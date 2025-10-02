@extends('layouts.app')

@section('breadcrumb')
    <nav class="flex text-sm text-gray-600 mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2">
            <li>
                <a href="{{ route('columns.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Columns
                </a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
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
                        @if(auth()->user()->can('deleteColumn', App\Column::class))
                            <div class="flex space-x-2">
                                <form action="{{ route('columns.restore', $column->id) }}" method="POST"
                                    onsubmit="return confirm('Restore this column?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded-md shadow hover:bg-green-700">
                                        Restore
                                    </button>
                                </form>

                                <form action="{{ route('columns.destroy', $column->id) }}" method="POST"
                                    onsubmit="return confirm('Delete permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded-md shadow hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
    <!-- Include DataTables CSS & JS -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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
                    emptyTable: "No columns found.",
                    paginate: { previous: 'Previous', next: 'Next' },
                    lengthMenu: "Show _MENU_ entries"
                },
                dom: '<"flex justify-between items-center mb-2"<"flex items-center space-x-2"l><"ml-auto"f>>t<"flex justify-between items-center mt-2"ip>'
            });
        });
    </script>
@endsection
