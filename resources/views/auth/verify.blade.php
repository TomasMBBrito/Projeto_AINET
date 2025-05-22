@extends('layouts.app')

@section('title', 'Verificação de Email')

@section('content')
<div class="max-w-lg mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Verifique o seu endereço de email</h2>

    <p class="mb-4">
        Antes de continuar, verifique se recebeu um email de verificação. Se não recebeu, clique no botão abaixo para reenviar.
    </p>

    @if (session('resent'))
        <div class="text-green-600 mb-4">Um novo link de verificação foi enviado para o seu email.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Reenviar Email de Verificação</button>
    </form>
</div>
@endsection
