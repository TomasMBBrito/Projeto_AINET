@extends('layouts.app')

@section('title', 'Encomendas/Stock')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-6">
    <div class="max-w-3xl w-full shadow-xl rounded-2xl p-8 bg-white">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Encomendas/Stock</h2>
        <p class="text-gray-600 mb-6">Aqui você pode gerenciar encomendas e stock.</p>
        <!-- Adicione o conteúdo de encomendas/stock aqui -->
        <a href="{{ route('home') }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg mt-4">
            Voltar ao Dashboard
        </a>
    </div>
</div>
@endsection
