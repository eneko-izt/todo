@extends('layouts.app')

@section('content')
<div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ __('All Users') }}</h1>

    @if (session('error'))
        <p class="text-sm text-red-600 font-medium mb-3">
            {{ session('error') }}
        </p>
    @endif

    <table id="myTable" class="min-w-full text-sm" role="grid">
        <thead class="bg-gray-100 text-left text-gray-700">
            <tr>
                <th class="px-4 py-2 font-semibold w-1/5">{{ __('Name') }}</th>
                <th class="px-4 py-2 font-semibold w-1/5">{{ __('e-mail') }}</th>
                <th class="px-4 py-2 font-semibold w-1/5">{{ __('Language') }}</th>
                <th class="px-4 py-2 font-semibold w-1/10">{{ __('Active') }}</th>
                <th class="px-4 py-2 font-semibold w-1/5">{{ __('Roles') }}</th>
                <th class="px-4 py-2 font-semibold w-1/10 text-right"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->language }}</td>
                    <td class="px-4 py-2">{{ $user->active ? __('Yes') : __('No') }}</td>
                    <td class="px-4 py-2">
                        @forelse ($user->roles as $role)
                            @if (!$loop->first)/@endif{{ $role->name }}
                        @empty
                            <span class="text-gray-400">{{ __('No roles') }}</span>
                        @endforelse
                    </td>
                    <td class="px-4 py-2 text-right">
                        @can('editUser', App\User::class)
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="px-3 py-1 bg-indigo-600 text-white text-xs font-medium rounded-md shadow hover:bg-indigo-700"
                                title="{{ __('Edit this user.') }}">
                                {{ __('Edit') }}
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
