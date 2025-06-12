@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4">
    <div class="max-w-md w-full bg-white shadow-xl rounded-xl p-8">
        <div class="flex flex-col items-center mb-6">
            <i data-lucide="credit-card" class="w-10 h-10 text-green-600 mb-2"></i>
            <h2 class="text-2xl font-bold text-gray-800 text-center">Criar Cartão Virtual</h2>
            <p class="text-gray-600 text-center mt-2 text-sm">
                Para prosseguir com a compra, é necessário criar um cartão virtual associado à sua conta.
            </p>
        </div>

        <form action="{{ route('card.create') }}" method="POST" class="flex flex-col space-y-4">
            @csrf
            <button type="submit" class="w-full bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700 transition">
                Criar Cartão Agora
            </button>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
