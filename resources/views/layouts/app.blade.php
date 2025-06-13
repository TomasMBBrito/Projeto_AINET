<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Grocery Club')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        rel="stylesheet"
    />
    <!-- Adicione outros links de CSS ou fontes conforme necessário -->
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Header -->
    @include('layouts.partials.header')

    <!-- Conteúdo Principal -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Adicione outros scripts conforme necessário -->
</body>
</html>
