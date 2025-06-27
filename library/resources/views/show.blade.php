@extends('layouts.navbar')

@section('title', $book->titulo)

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <img src="{{ $book->url_img }}" class="img-fluid rounded" alt="{{ $book->titulo }}">
        </div>
        <div class="col-md-8">
            <h1>{{ $book->titulo }}</h1>
            <p class="text-muted">por {{ $book->autor }}</p>
            
            <div class="mb-3">
                @if($book->disponibilidade_real)
                    <span class="badge bg-success">Disponível ({{ $book->num_exemplares }} exemplares)</span>
                @else
                    <span class="badge bg-danger">Indisponível</span>
                @endif
            </div>

            <div class="mb-4">
                <h4>Sinopse</h4>
                <p>{{ $book->sinopse }}</p>
            </div>

            <div class="book-actions">
                @auth
                    @if($book->disponibilidade_real)
                        <form action="{{ route('reservation', $book->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-book"></i> Alugar Livro
                            </button>
                        </form>
                    @endif

                    @if($userLoan && $userLoan->book_id == $book->id && $userLoan->status != 'devolvido')
                        <form action="{{ route('devolver.livro', $userLoan->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-undo"></i> Devolver Livro
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection