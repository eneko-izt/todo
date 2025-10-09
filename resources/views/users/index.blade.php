@extends('layouts.app')

@section('content')
<div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">All users</h1>

    @if (session('error'))
        <p class="text-sm text-red-600 font-medium mb-3">
            {{ session('error') }}
        </p>
    @endif

    <table id="myTable" class="min-w-full text-sm" role="grid">
        <thead class="bg-gray-100 text-left text-gray-700">
            <tr>
                <th class="px-4 py-2 font-semibold w-1/5">Name</th>
                <th class="px-4 py-2 font-semibold w-1/5">Email</th>
                <th class="px-4 py-2 font-semibold w-1/5">Language</th>
                <th class="px-4 py-2 font-semibold w-1/10">Active</th>
                <th class="px-4 py-2 font-semibold w-1/5">Roles</th>
                <th class="px-4 py-2 font-semibold w-1/10 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->language }}</td>
                    <td class="px-4 py-2">{{ $user->active ? 'Yes' : 'No' }}</td>
                    <td class="px-4 py-2">
                        @forelse ($user->roles as $role)
                            @if (!$loop->first)/@endif{{ $role->name }}
                        @empty
                            <span class="text-gray-400">No roles</span>
                        @endforelse
                    </td>
                    <td class="px-4 py-2 text-right">
                        @can('editUser', App\User::class)
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="px-3 py-1 bg-indigo-600 text-white text-xs font-medium rounded-md shadow hover:bg-indigo-700"
                                title="Edit this user">
                                Edit
                            </a>
                        @endcan
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
