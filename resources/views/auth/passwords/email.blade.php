@extends('layouts.auth')

@section('title', 'Recuperar Password')

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
            <h2 class="text-2xl font-bold mb-4">Recover your Password !!</h2>

            @if (session('status'))
                <div class="text-green-600 mb-4">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <label for="email" class="block text-gray-700">Email:</label>
                <input id="email" type="email" name="email" required autofocus
                    class="w-full mt-1 p-2 border rounded-lg">

                <button type="submit" class="mt-4 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Send recovery link</button>
            </form>
        </div>         
    </div>
</div>
@endsection
