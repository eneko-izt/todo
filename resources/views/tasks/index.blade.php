@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Tasks</li>
@endsection

@section('create_trash')
    <div class="d-flex justify-content-between">
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
                <th>Id</th>
                <th>Active</th>
                <th>Text</th>
                <th></th>
            </tr>

            @forelse ($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->active }}</td>
                    <td>{{ $task->text }}</td>
                </tr>

            @empty
                <p>No tags found.</p>
            @endforelse

        </table>
    </div>
@endsection
