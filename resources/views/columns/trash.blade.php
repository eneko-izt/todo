@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('columns.index') }}" alt="Columns">Columns</a></li>
    <li class="breadcrumb-item active" aria-current="page">Trash ({{ $columns->total() }})</li>
@endsection

@section('content')
    <div class="dataTables_scrollHeadInner"
        style="box-sizing: content-box; width: 1650px; padding-right: 0px;">
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
                        <form action="{{ route('columns.restore', $column->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="btn btn-primary btn-sm"
                                title="Restore this column"
                                onclick="return confirm('Are you sure you want to restore this column?')">
                                Restore
                            </button>
                        </form>
                        <form action="{{ route('columns.destroy', $column->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button 
                                class="btn btn-primary btn-sm"
                                title="Delete this column"
                                onclick="return confirm('Are you sure you want to completely delete this column?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

            @empty
                <p>No columns found.</p>
            @endforelse

        </table>
        {{ $columns->links() }}
    </div>
@endsection
