@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page" alt="Users">Users ({{ $users->total() }})</li>
@endsection

@section('create_trash')
    <div class="d-flex justify-content-between">
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm" title = "Create a user" alt = "Create a user">New</a>
    </div>
@endsection

@section('content')
    <div class="dataTables_scrollHeadInner"
        style="box-sizing: content-box; width: 1650px; padding-right: 0px;">

        @if (session('error'))
            <p class="help is-danger" style="color:#d8000c">{{ session('error') }}</p>
        @endif

        <table class="table table-striped table-bordered table-hover dataTables-taula dataTable"
            width="100%" role="grid" style="margin-left: 0px; width: 1650px;">
            <tr>
                <th width="20%">Name</th>
                <th width="20%">Email</th>
                <th width="10%">Active</th>
                <th width="20%">Roles</th>
                <th width="10%"></th>
                <th></th>
            </tr>

            @forelse ($users as $user)
                <tr>
                    <td>
                        {{ $user->name }}
                    </td>
                    <td>
                        {{ $user->email }}
                    </td>
                    <td>
                        {{ $user->active ? 'Yes' : 'No' }}
                    </td>
                    <td>
                        @forelse ($user->roles as $role)
                            @if (!$loop->first) / @endif
                            {{ $role->name }}
                        @empty
                            
                        @endforelse
                    </td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" 
                            class="btn btn-primary btn-sm" 
                            title="Edit this user"
                            alt="Edit this user">Edit</a>
                    </td>
                </tr>

            @empty
                <p>No users found.</p>
            @endforelse

        </table>
        {{ $users->links() }}
    </div>
@endsection
