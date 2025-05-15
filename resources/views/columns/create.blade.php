@extends('layouts.app')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('columns.index') }}">Columns</a></li>
            <li class="breadcrumb-item active" aria-current="page">New</li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="container">
        <div class="field">
            <label class="label" for="name">Name</label>
            <div class="control">
                <input class="input" type="text" name="name" id="name" maxlength="255" required>
            </div>
        </div>
        <div class="field">
            <label class="label" for="colour">Colour</label>
            <div class="control">
                <input class="input" type="text" name="colour" id="colour" maxlength="10" required>
            </div>
        </div>
        <div class="field">
            <div class="control">
                <input type="checkbox" name="active" id="active" checked>
                <label class="label" for="active">Active</label>
            </div>
        </div>
        <div class="field is-grouped">
            <div class="control">
                <button class="button is-link" type="">New</button>
            </div>
        </div>
    </div>

@endsection