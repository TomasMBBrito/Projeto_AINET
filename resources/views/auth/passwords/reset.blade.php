@extends('layouts.auth')

@section('title', 'New Password')

@section('content')
<div class="absolute inset-0 z-0">
    <div class="h-full w-full bg-cover bg-center"
         style="background-image: url(https://cms.foodclub.com/wp-content/uploads/2024/08/Module-Hero@3x.jpg)">
        <div class="h-full w-full bg-gray-900/60"></div>
    </div>
</div>
<div class=" relative z-10 max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-4">New Password</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        {{-- Token enviado por email --}}
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <label for="email" class="block text-gray-700">Email:</label>
        <input type="email" name="email" value="{{ $email ?? old('email') }}" class="w-full mt-1 p-2 border rounded-lg" required>

        <label for="password" class="block mt-4 text-gray-700">New Password:</label>
        <input type="password" name="password" class="w-full mt-1 p-2 border rounded-lg" required autofocus>

        <label for="password-confirm" class="block mt-4 text-gray-700">Confirm Password:</label>
        <input type="password" name="password_confirmation" class="w-full mt-1 p-2 border rounded-lg" required>

        <button type="submit" class="mt-6 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Reset Password</button>
    </form>
</div>
@endsection
