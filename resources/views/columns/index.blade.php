@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page" alt="Columns">Columns ({{ $columns->total() }})</li>
@endsection

@section('create_trash')
    <div class="d-flex justify-content-between">
        <a href="{{ route('columns.create') }}" class="btn btn-primary btn-sm" title = "Create a column" alt = "Create a column">New</a>
        <a href="{{ route('columns.trash') }}" class="btn btn-primary btn-sm" title = "View deleted columns" alt = "View deleted columns">Trash</a>
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
                <th>Column</th>
                <th>Colour</th>
                <th>Active</th>
                <th></th>
            </tr>

            @forelse ($columns as $column)
                <tr>
                    <td>
                        {{ $column->getUpperName() }}
                    </td>
                    <td class="p-2 text-white badge mr-5"
                        style="background-color: {{ $column->colour }};">
                        {{ $column->colour }}
                    </td>
                    <td>{{ $column->active }}</td>
                    <td>
                        <a href="{{ route('columns.edit', $column->id) }}" 
                            class="btn btn-primary btn-sm" 
                            title="Edit this column"
                            alt="Edit this column">Edit</a>
                        @if ($column->tasks_count == 0)
                            <form action="{{ route('columns.delete', $column->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-primary btn-sm"
                                    title="Delete this column"
                                    onclick="return confirm('Are you sure you want to delete this column?')">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>

            @empty
                <p>No columns found.</p>
            @endforelse

        </table>
        {{ $columns->links() }}
    </div>
@endsection
