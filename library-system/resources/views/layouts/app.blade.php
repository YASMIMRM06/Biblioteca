<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Library System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main>
            <div class="flex">
                <aside class="w-64 bg-gray-800 text-white p-4 min-h-screen">
                    <nav>
                        <ul>
                            <li class="mb-2"><a href="{{ route('dashboard') }}" class="block p-2 hover:bg-gray-700 rounded">Dashboard</a></li>
                            @can('isLibrarian') {{-- Check if user is librarian --}}
                            <li class="mb-2"><a href="{{ route('users.index') }}" class="block p-2 hover:bg-gray-700 rounded">Manage Users</a></li>
                            <li class="mb-2"><a href="{{ route('books.index') }}" class="block p-2 hover:bg-gray-700 rounded">Manage Books</a></li>
                            <li class="mb-2"><a href="{{ route('publishers.index') }}" class="block p-2 hover:bg-gray-700 rounded">Manage Publishers</a></li>
                            <li class="mb-2"><a href="{{ route('loans.index') }}" class="block p-2 hover:bg-gray-700 rounded">Manage Loans</a></li>
                            <li class="mb-2"><a href="{{ route('returns.create') }}" class="block p-2 hover:bg-gray-700 rounded">Register Return</a></li>
                            <li class="mb-2"><a href="{{ route('fines.index') }}" class="block p-2 hover:bg-gray-700 rounded">Manage Fines</a></li>
                            <li class="mb-2"><a href="{{ route('reservations.index') }}" class="block p-2 hover:bg-gray-700 rounded">Manage Reservations</a></li>
                            <li class="mb-2"><a href="{{ route('reports.index') }}" class="block p-2 hover:bg-gray-700 rounded">Generate Reports</a></li>
                            {{-- <li class="mb-2"><a href="{{ route('delays.notify') }}" class="block p-2 hover:bg-gray-700 rounded">Notify Delays</a></li> --}} {{-- Requires separate implementation --}}
                            @endcan
                            @can('isUser') {{-- Check if user is a regular user --}}
                            <li class="mb-2"><a href="{{ route('books.search') }}" class="block p-2 hover:bg-gray-700 rounded">Search Books</a></li>
                            <li class="mb-2"><a href="{{ route('reservations.create') }}" class="block p-2 hover:bg-gray-700 rounded">Make Reservation</a></li>
                            <li class="mb-2"><a href="{{ route('reservations.my') }}" class="block p-2 hover:bg-gray-700 rounded">My Reservations</a></li>
                            <li class="mb-2"><a href="{{ route('loans.my') }}" class="block p-2 hover:bg-gray-700 rounded">My Loans</a></li>
                            {{-- <li class="mb-2"><a href="{{ route('loans.renew') }}" class="block p-2 hover:bg-gray-700 rounded">Renew Loan</a></li> --}} {{-- Can be part of my_loans view --}}
                            @endcan
                        </ul>
                    </nav>
                </aside>
                <div class="flex-1 p-6">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Warning!</strong>
                            <span class="block sm:inline">{{ session('warning') }}</span>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>