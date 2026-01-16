@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Importar Productes</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('failures'))
                        <div class="alert alert-danger" role="alert">
                            <strong>Errors en la importació:</strong>
                            <ul>
                                @foreach (session('failures') as $failure)
                                    <li>Fila {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }} (Atribut: {{ $failure->attribute() }})</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="file">Arxiu Excel (.xlsx, .xls)</label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                            @error('file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Importar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
