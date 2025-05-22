@extends('layouts.app')

@section('title', 'Nova Password')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Nova Password</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <label for="email" class="block text-gray-700">Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full mt-1 p-2 border rounded-lg" required>

        <label for="password" class="block mt-4 text-gray-700">Nova Password:</label>
        <input type="password" name="password" class="w-full mt-1 p-2 border rounded-lg" required>

        <label for="password-confirm" class="block mt-4 text-gray-700">Confirmar Password:</label>
        <input type="password" name="password_confirmation" class="w-full mt-1 p-2 border rounded-lg" required>

        <button type="submit" class="mt-6 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Redefinir Password</button>
    </form>
</div>
@endsection
