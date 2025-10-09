@extends('layouts.app')

@section('content')
<div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ __('Tags Trash') }}</h1>

    @if (session('error'))
        <p class="text-sm text-red-600 font-medium mb-3">
            {{ session('error') }}
        </p>
    @endif

    <table id="myTable" class="min-w-full text-sm" role="grid">
        <thead class="bg-gray-100 text-left text-gray-700">
            <tr>
                <th class="px-4 py-2 font-semibold">{{ __('Tag') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Colour') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Active') }}</th>
                <th class="px-4 py-2 font-semibold text-right">{{ __('Actions') }}</th>
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
                        {{ $tag->active ? __('Yes') : __('No') }}
                    </td>
                    <td class="px-4 py-2 flex space-x-2 justify-end">
                        @if (auth()->user()->can('deleteTag', App\Tag::class))
                            <!-- Restore -->
                            <form action="{{ route('tags.restore', $tag->id) }}" method="POST"
                                    onsubmit="return confirm('{{ __(`Restore this tag?`) }}')">
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
                                    onsubmit="return confirm('{{ __(`Delete permanently?`) }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded-md shadow hover:bg-red-700">
                                    {{ __('Delete') }}
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
                    emptyTable: "{{ __('No columns found.') }}",
                    previous: "{{ __('Previous') }}",
                    next: "{{ __('Next') }}"
                },
                lengthMenu: "{{ __('Show _MENU_ entries') }}",
                zeroRecords: "{{ __('No matching records found') }}",
                info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries', ['START' => 1, 'END' => 10, 'TOTAL' => 100]) }}",
                infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
                infoFiltered: "{{ __('(filtered from _MAX_ total entries)', ['MAX' => 100]) }}",
                search: "{{ __('Search:') }}"
            },
            dom: '<"flex justify-between items-center mb-2"<"flex items-center space-x-2"l><"ml-auto"f>>t<"flex justify-between items-center mt-2"ip>'
        });
    });
</script>
@endsection
