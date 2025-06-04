@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tags.index') }}">Tags</a></li>
    <li class="breadcrumb-item active" aria-current="page">Trash ({{ $tags->total() }})</li>
@endsection

@section('content')
    <div class="dataTables_scrollHeadInner"
        style="box-sizing: content-box; width: 1650px; padding-right: 0px;">
        <table class="table table-striped table-bordered table-hover dataTables-taula dataTable"
            width="100%" role="grid" style="margin-left: 0px; width: 1650px;">
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
                        <form action="{{ route('tags.restore', $tag->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="btn btn-primary btn-sm"
                                onclick="return confirm('Are you sure you want to restore this tag?')">
                                Restore
                            </button>
                        </form>
                        <form action="{{ route('tags.destroy', $tag->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button 
                                class="btn btn-primary btn-sm"
                                onclick="return confirm('Are you sure you want to completely delete this tag?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

            @empty
                <p>No tags found.</p>
            @endforelse

        </table>
        {{ $tags->links() }}
    </div>
@endsection
