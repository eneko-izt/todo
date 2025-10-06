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
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('tasks.data') }}",
                type: "GET"
            },
            columns: [
                { data: 'column_name', name: 'column.name' },
                { data: 'text', name: 'text' },
                { data: 'tags', name: 'tags', orderable: false, searchable: false },
                { data: 'user_name', name: 'user.name' },
                { data: 'sharing_users', name: 'sharing_users', orderable: false, searchable: false },
            ]
        });
    });
</script>
@endsection
