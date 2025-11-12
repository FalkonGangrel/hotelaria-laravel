<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-g">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- O @yield funciona como um espaço reservado. A view filha definirá o título. --}}
    <title>@yield('title', 'Hotelaria Laravel')</title>

    <!-- CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Pequeno ajuste para o ícone de ordenação */
        th a { text-decoration: none; color: inherit; }
        th a:hover { color: #fff; }
    </style>
</head>
<body>
    
    <!-- BARRA DE NAVEGAÇÃO CONSISTENTE EM TODAS AS PÁGINAS -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Hotelaria App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('produtos.index') }}">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('fornecedores.index') }}">Fornecedores</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTEÚDO PRINCIPAL DA PÁGINA -->
    <main class="container mt-4">
        {{-- Aqui é onde o conteúdo específico de cada página será injetado. --}}
        @yield('content')
    </main>

    <!-- JavaScript do Bootstrap (Opcional, mas boa prática) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>