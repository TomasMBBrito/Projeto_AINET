@extends('layouts.app')

@section('title', 'Login')

@section('content')
<!-- Container da imagem de fundo -->
<div class="absolute inset-0 z-0">
    <div class="h-full w-full bg-cover bg-center" 
         style="background-image: url(https://cms.foodclub.com/wp-content/uploads/2024/08/Module-Hero@3x.jpg)">
        <div class="h-full w-full bg-gray-900/60"></div>
    </div>
</div>

<!-- Container do formulário -->
<div class="relative z-10 flex min-h-screen items-center justify-center p-4">
    <div class="w-full max-w-md space-y-8">
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl p-8 sm:p-10">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold text-gray-800 drop-shadow-sm">Grocery Club</h2>
                <p class="mt-2 text-gray-600">Log in to access your account</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg">
                    <ul class="list-disc list-inside text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-10">
                @csrf
                <div>
                    <label for="email" class="block text-md font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" 
                           class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           required placeholder="example@example.com" autofocus>
                </div>

                <div>
                    <label for="password" class="block text-md font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" 
                           class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           required placeholder="Your Password">
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02]">
                    Login
                </button>

                <p class="text-md text-center text-black">
                    Don't have an account yet? 
                    <a href="{{ route('register') }}" class="text-blue-500 focus:outline-none focus:underline hover:underline">Sign up</a>.
                </p>
                <p class="text-md text-center text-black">
                    © {{ date('Y') }} Grocery Club has all rights reserved.
                </p>
            </form>
        </div>         
    </div>
</div>
@endsection
