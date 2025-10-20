@extends('layouts.app')

@section('content')
<div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ __('All Tasks') }}</h1>

    <table id="myTable" class="min-w-full text-sm" role="grid">
        <thead class="bg-gray-100 text-left text-gray-700">
            <tr>
                <th class="px-4 py-2 font-semibold">{{ __('Column') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Task') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Tags') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Owner') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Shared with') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">
                        {{ $task->column->name }}
                    </td>
                    <td class="px-4 py-2">
                        {{ $task->text }}
                    </td>
                    <td class="px-4 py-2">
                        @forelse ($task->tags as $tag)
                            @if (!$loop->first)/@endif{{ $tag->name }}
                        @empty
                            <span class="text-gray-400">{{ __('No tags') }}</span>
                        @endforelse
                    </td>
                    <td class="px-4 py-2">
                        {{ $task->user->name }}
                    </td>
                    <td class="px-4 py-2">
                        @forelse ($task->sharingUsers as $user)
                            @if (!$loop->first)/@endif{{ $user->name }}
                        @empty
                            <span class="text-gray-400">{{ __('Empty') }}</span>
                        @endforelse
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
