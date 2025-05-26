@extends('layouts.app')

@section('breadcrumb')
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-10">
                            <h3>{{ Date('Y-m-d') }}</h3>
                        </div>
                        <div class="col-md-2 text-right">
                            <a href="{{ route('welcome') }}" class="btn btn-primary">Home</a>
                            <a href="{{ route('home') }}" class="btn btn-primary">Go to
                                Dashboard</a>
                            <a href="{{ route('columns.index') }}"
                                class="btn btn-primary">Columns</a>
                            <a href="https://www.naiz.eus/" class="btn btn-primary">Naiz</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
