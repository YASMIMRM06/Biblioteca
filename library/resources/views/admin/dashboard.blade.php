@extends('layouts.navbar')
@section('title', 'Painel Administrativo')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css" rel="stylesheet">

<div class="container py-4">
    <h1 class="mb-4">Painel Administrativo</h1>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Livros Mais Populares</h5>
                </div>
                <div class="card-body">
                    <canvas id="popularBooksChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Empréstimos nos Últimos 6 Meses</h5>
                </div>
                <div class="card-body">
                    <canvas id="loansChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Livros Alugados Atualmente</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Livro</th>
                            <th>Usuário</th>
                            <th>Data do Empréstimo</th>
                            <th>Data de Devolução</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeLoans as $loan)
                        <tr>
                            <td>{{ $loan->book->titulo }}</td>
                            <td>{{ $loan->user->name }} ({{ $loan->user->email }})</td>
                            <td>{{ $loan->created_at->format('d/m/Y') }}</td>
                            <td @if($loan->isLate) class="text-danger" @endif>
                                {{ $loan->devolution_date->format('d/m/Y') }}
                                @if($loan->isLate) (Atrasado) @endif
                            </td>
                            <td>{{ ucfirst($loan->status) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    // Gráfico de livros populares
    const popularCtx = document.getElementById('popularBooksChart').getContext('2d');
    new Chart(popularCtx, {
        type: 'bar',
        data: {
            labels: @json($popularBooks->pluck('titulo')),
            datasets: [{
                label: 'Número de Empréstimos',
                data: @json($popularBooks->pluck('loans_count')),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Gráfico de empréstimos por mês
    const months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dec'];
    const loansCtx = document.getElementById('loansChart').getContext('2d');
    new Chart(loansCtx, {
        type: 'line',
        data: {
            labels: @json($loansLastMonths->map(fn($item) => months[$item->month - 1])),
            datasets: [{
                label: 'Empréstimos por Mês',
                data: @json($loansLastMonths->pluck('count')),
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>

<style>
    .card {
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .card-header {
        background-color: #405A94;
        color: white;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .text-danger {
        font-weight: bold;
    }
</style>
@endsection