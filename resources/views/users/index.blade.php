@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page" alt="Users">Users ({{ $users->total() }})</li>
@endsection

@section('create_trash')
    <div class="d-flex justify-content-between">
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm" title = "Create a user" alt = "Create a user">New</a>
        <a href="{{ route('users.trash') }}" class="btn btn-primary btn-sm" title = "View deleted users" alt = "View deleted users">Trash</a>
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
                <th>Name</th>
                <th>Email</th>
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
                        <a href="{{ route('users.edit', $user->id) }}" 
                            class="btn btn-primary btn-sm" 
                            title="Edit this user"
                            alt="Edit this user">Edit</a>
                        @if ($user->tasks_count == 0)
                            <form action="{{ route('users.delete', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-primary btn-sm"
                                    title="Delete this user"
                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>

            @empty
                <p>No users found.</p>
            @endforelse

        </table>
        {{ $users->links() }}
    </div>
@endsection
