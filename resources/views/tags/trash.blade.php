@extends('layouts.app')

@section('breadcrumb')
    <nav class="flex text-sm text-gray-600 mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2">
            <li>
                <a href="{{ route('tags.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Tags
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
                <th class="px-4 py-2 font-semibold">Tag</th>
                <th class="px-4 py-2 font-semibold">Colour</th>
                <th class="px-4 py-2 font-semibold">Active</th>
                <th class="px-4 py-2 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tags as $tag)
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
                        @if (auth()->user()->can('deleteTag', App\Tag::class))
                            <!-- Restore -->
                            <form action="{{ route('tags.restore', $tag->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to restore this tag?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded-md shadow hover:bg-green-700"
                                    title="Restore this tag">
                                    Restore
                                </button>
                            </form>

                            <!-- Permanent Delete -->
                            <form action="{{ route('tags.destroy', $tag->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to completely delete this tag?')">
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
