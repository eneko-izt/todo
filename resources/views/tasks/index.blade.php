@extends('layouts.app')

@section('content')
<div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">All tasks</h1>

    @if (session('error'))
        <p class="text-sm text-red-600 font-medium mb-3">
            {{ session('error') }}
        </p>
    @endif

    <table id="myTable" class="min-w-full text-sm" role="grid">
        <thead class="bg-gray-100 text-left text-gray-700">
            <tr>
                <th class="px-4 py-2 font-semibold">Column</th>
                <th class="px-4 py-2 font-semibold">Task</th>
                <th class="px-4 py-2 font-semibold">Tags</th>
                <th class="px-4 py-2 font-semibold">Owner</th>
                <th class="px-4 py-2 font-semibold">Shared with</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr class="px-4 py-2">
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
                            <span class="text-gray-400">No tags</span>
                        @endforelse
                    </td>
                    <td class="px-4 py-2">
                        {{ $task->user->name }}
                    </td>
                    <td class="px-4 py-2">
                        @forelse ($task->sharingUsers as $user)
                            @if (!$loop->first)/@endif{{ $user->name }}
                        @empty
                            <span class="text-gray-400">No shared users</span>
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
