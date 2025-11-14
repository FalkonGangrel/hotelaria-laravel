<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Garante que o layout ocupe a altura toda */
        body, html { height: 100%; }
        main { display: flex; flex-wrap: nowrap; height: 100vh; }

        /* Pequeno ajuste para o ícone de ordenação */
        th a { text-decoration: none; color: inherit; }
        th a:hover { color: #fff; }

        .content-area { flex-grow: 1; overflow-y: auto; padding: 2rem; }
    </style>
</head>
<body>
    <main>
        {{-- Inclui a nossa nova sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Área de Conteúdo Principal --}}
        <div class="content-area">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <!-- JavaScript do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>