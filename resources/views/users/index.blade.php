@extends('layouts.app')

@section('breadcrumb')
    <li class="text-sm text-gray-600">
        Users ({{ $users->count() }})
    </li>
@endsection

@section('create_trash')
    <div class="flex justify-between mb-4">
        @can('createUser', App\User::class)
            <a href="{{ route('users.create') }}"
               class="px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700"
               title="Create a user">
               New
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
        @if (session('error'))
            <p class="text-sm text-red-600 font-medium mb-3">{{ session('error') }}</p>
        @endif

        <table class="min-w-full border border-gray-200 rounded-lg divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700 text-left">
                <tr>
                    <th class="px-4 py-2 font-semibold w-1/5">Name</th>
                    <th class="px-4 py-2 font-semibold w-1/5">Email</th>
                    <th class="px-4 py-2 font-semibold w-1/10">Active</th>
                    <th class="px-4 py-2 font-semibold w-1/5">Roles</th>
                    <th class="px-4 py-2 font-semibold w-1/10 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
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
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
