@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
            {{ __('Detalhes do Empréstimo') }}
        </h2>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-4">
                    <p><strong class="font-semibold">{{ __('ID:') }}</strong> {{ $emprestimo->id }}</p>
                    <p><strong class="font-semibold">{{ __('Livro:') }}</strong> {{ $emprestimo->livro->titulo ?? 'N/A' }} (ISBN: {{ $emprestimo->livro->isbn ?? 'N/A' }})</p>
                    <p><strong class="font-semibold">{{ __('Usuário:') }}</strong> {{ $emprestimo->user->name ?? 'N/A' }} ({{ $emprestimo->user->email ?? 'N/A' }})</p>
                    <p><strong class="font-semibold">{{ __('Data de Empréstimo:') }}</strong> {{ $emprestimo->data_emprestimo->format('d/m/Y') }}</p>
                    <p><strong class="font-semibold">{{ __('Data de Devolução Prevista:') }}</strong> {{ $emprestimo->data_devolucao_prevista->format('d/m/Y') }}</p>
                    <p><strong class="font-semibold">{{ __('Data de Devolução Real:') }}</strong> {{ $emprestimo->data_devolucao_real ? $emprestimo->data_devolucao_real->format('d/m/Y') : 'Pendente' }}</p>
                    <p><strong class="font-semibold">{{ __('Status:') }}</strong> {{ ucfirst($emprestimo->status) }}</p>
                    <p><strong class="font-semibold">{{ __('Criado em:') }}</strong> {{ $emprestimo->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong class="font-semibold">{{ __('Última atualização:') }}</strong> {{ $emprestimo->updated_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="flex items-center justify-start mt-4">
                    <a href="{{ route('emprestimos.edit', $emprestimo->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 focus:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Editar') }}
                    </a>
                    <a href="{{ route('emprestimos.index') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('Voltar para a Lista') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection