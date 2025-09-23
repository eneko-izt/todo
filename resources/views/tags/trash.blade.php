@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tags.index') }}" alt="Tags">Tags</a></li>
    <li class="breadcrumb-item active" aria-current="page">Trash ({{ $tags->total() }})</li>
@endsection

@section('content')
    <div class="dataTables_scrollHeadInner"
        style="box-sizing: content-box; width: 1650px; padding-right: 0px;">
        <table id="myTable" class="table table-striped table-bordered table-hover dataTables-taula dataTable"
            width="100%" role="grid" style="margin-left: 0px; width: 1650px;">
            <thead>
                <tr>
                    <th>Tag</th>
                    <th>Colour</th>
                    <th>Active</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>

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
                        @if (auth()->user()->can('deleteTag', App\Tag::class))
                            <form action="{{ route('tags.restore', $tag->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary btn-sm"
                                    title="Restore this tag"
                                    onclick="return confirm('Are you sure you want to restore this tag?')">
                                    Restore
                                </button>
                            </form>
                            <form action="{{ route('tags.destroy', $tag->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-primary btn-sm"
                                    title="Delete this tag"
                                    onclick="return confirm('Are you sure you want to completely delete this tag?')">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>

            @empty
                <p>No tags found.</p>
            @endforelse

            </tbody>
            
        </table>
        {{ $tags->links() }}
    </div>
@endsection
