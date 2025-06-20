@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
            {{ __('Editar Reserva') }}
        </h2>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('reservas.update', $reserva->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="livro_id" class="block font-medium text-sm text-gray-700">{{ __('Livro') }}</label>
                            <select id="livro_id" name="livro_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Selecione um Livro</option>
                                @foreach ($livros as $livro)
                                    <option value="{{ $livro->id }}" {{ old('livro_id', $reserva->livro_id) == $livro->id ? 'selected' : '' }}>
                                        {{ $livro->titulo }} (ISBN: {{ $livro->isbn }}) - Status: {{ ucfirst($livro->status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('livro_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="user_id" class="block font-medium text-sm text-gray-700">{{ __('Usuário') }}</label>
                            <select id="user_id" name="user_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Selecione um Usuário</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $reserva->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="data_reserva" class="block font-medium text-sm text-gray-700">{{ __('Data da Reserva') }}</label>
                            <input id="data_reserva" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="datetime-local" name="data_reserva" value="{{ old('data_reserva', $reserva->data_reserva ? $reserva->data_reserva->format('Y-m-d\TH:i') : '') }}" required />
                            @error('data_reserva')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-700">{{ __('Status') }}</label>
                            <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="pendente" {{ old('status', $reserva->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="cancelada" {{ old('status', $reserva->status) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                <option value="concluida" {{ old('status', $reserva->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Atualizar') }}
                        </button>
                        <a href="{{ route('reservas.index') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Cancelar') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection