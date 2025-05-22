@extends('layouts.app')

@section('title', 'Recuperar Password')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Recuperar Password</h2>

    @if (session('status'))
        <div class="text-green-600 mb-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label for="email" class="block text-gray-700">Email:</label>
        <input id="email" type="email" name="email" required autofocus
            class="w-full mt-1 p-2 border rounded-lg">

        <button type="submit" class="mt-4 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Enviar link de recuperação</button>
    </form>
</div>
@endsection
