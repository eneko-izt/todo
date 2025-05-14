@extends('layouts.app')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('columns.index') }}">Columns</a></li>
            <li class="breadcrumb-item active" aria-current="page">Trash ({{ $columns->total() }})</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container">
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
                            {{ $column->name() }}
                        </td>
                        <td class="p-2 text-white badge mr-5"
                            style="background-color: {{ $column->colour }};">
                            {{ $column->colour }}
                        </td>
                        <td>{{ $column->active }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm">Restore</button>
                        </td>
                    </tr>

                @empty
                    <p>No columns found.</p>
                @endforelse

            </table>
            {{ $columns->links() }}
        </div>
    </div>
@endsection
