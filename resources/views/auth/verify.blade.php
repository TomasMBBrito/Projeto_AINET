@extends('layouts.app')

@section('title', 'Verificação de Email')

@section('content')
<div class="absolute inset-0 z-0">
    <div class="h-full w-full bg-cover bg-center" 
         style="background-image: url(https://cms.foodclub.com/wp-content/uploads/2024/08/Module-Hero@3x.jpg)">
        <div class="h-full w-full bg-gray-900/60"></div>
    </div>
</div>
<div class="relative z-10 flex min-h-screen items-center justify-center p-4">
    <div class="w-full max-w-md space-y-8">
        <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
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

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('verification.check') }}" class="mt-4">
            @csrf
            <button type="submit" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">
                Já verifiquei o email
            </button>
        </form>
      </div>
    </div>            
</div>
@endsection
