@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-6">
    <div class="max-w-3xl w-full bg-white shadow-xl rounded-2xl p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">🎉 Bem-vindo ao Grocery Club!</h2>
        <p class="text-gray-600 mb-6">
            A sua conta foi verificada com sucesso. Pode agora explorar as funcionalidades disponíveis.
        </p>

        <div class="space-y-4">
            <a href="{{ route('profile.show') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg shadow transition">
                Ir para o meu Perfil
            </a>

            <a href="{{ route('users.index') }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg shadow transition">
                Gestão de Utilizadores
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
                @csrf
                <button type="submit" class="text-red-600 hover:underline">Terminar Sessão</button>
            </form>
        </div>
    </div>
</div>
@endsection
