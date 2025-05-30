@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Tags ({{ $tags->total() }})</li>
@endsection

@section('create_trash')
    <div class="d-flex justify-content-between">
        <a href="{{ route('tags.create') }}" class="btn btn-primary btn-sm">New</a>
        <a href="{{ route('tags.trash') }}" class="btn btn-primary btn-sm">Trash</a>
    </div>
@endsection

@section('content')
    <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; width: 1650px; padding-right: 0px;">

        @if (session('error'))
            <p class="help is-danger" style="color:#d8000c">{{ session('error') }}</p>
        @endif

        <table 
            class="table table-striped table-bordered table-hover dataTables-taula dataTable"
            width="100%" 
            role="grid" 
            style="margin-left: 0px; width: 1650px;">
            <tr>
                <th>Tag</th>
                <th>Colour</th>
                <th>Active</th>
                <th></th>
            </tr>

            @forelse ($tags as $tag)
                <tr>
                    <td>
                        {{ $tag->getUpperName() }}
                    </td>
                    <td class="p-2 text-white badge mr-5"
                        style="background-color: {{ $tag->colour }};">
                        {{ $tag->colour }}
                    </td>
                    <td>{{ $tag->active }}</td>
                    <td>
                        <a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        @if ($tag->tasks_count == 0)
                            <form action="{{ route('tags.delete', $tag->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-primary btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this tag?')">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>

            @empty
                <p>No tags found.</p>
            @endforelse

        </table>
        {{ $tags->links() }}
    </div>
@endsection
