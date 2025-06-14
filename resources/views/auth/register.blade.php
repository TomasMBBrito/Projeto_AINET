@extends('layouts.auth')

@section('title', 'Registo')

@section('content')
<!-- Fundo com imagem -->
<div class="absolute inset-0 z-0">
    <div class="h-full w-full bg-cover bg-center" 
         style="background-image: url(https://cms.foodclub.com/wp-content/uploads/2024/08/Module-Hero@3x.jpg)">
        <div class="h-full w-full bg-gray-900/60"></div>
    </div>
</div>

<!-- Formulário alinhado à esquerda -->
<div class="relative z-10 flex items-center justify-center h-screen overflow-auto p-6">
    <div class="w-full max-w-2xl space-y-8">
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl p-8 sm:p-10 max-h-[90vh] overflow-y-auto">
            <div class="text-left mb-6">
                <h2 class="text-4xl font-bold text-gray-800 drop-shadow-sm">Grocery Club</h2>
                <p class="mt-2 text-gray-600">Create an account and join us :></p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg">
                    <ul class="list-disc list-inside text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-md font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-md font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-md font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-md font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-md font-medium text-gray-700 mb-1">Gender</label>
                        <select name="gender"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            required>
                            <option value="">Selecione</option>
                            <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Male</option>
                            <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-md font-medium text-gray-700 mb-1">User's photo</label>
                        <input type="file" name="photo"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-md font-medium text-gray-700 mb-1">NIF</label>
                        <input type="text" name="nif" value="{{ old('nif') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-md font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" name="default_delivery_address" value="{{ old('default_delivery_address') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-md font-medium text-gray-700 mb-1">Payment Type</label>
                        <select name="default_payment_type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">None</option>
                            <option value="Visa" {{ old('default_payment_type') === 'Visa' ? 'selected' : '' }}>Visa</option>
                            <option value="PayPal" {{ old('default_payment_type') === 'PayPal' ? 'selected' : '' }}>PayPal</option>
                            <option value="MB WAY" {{ old('default_payment_type') === 'MB WAY' ? 'selected' : '' }}>MB WAY</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-md font-medium text-gray-700 mb-1">Reference</label>
                        <input type="text" name="default_payment_reference" value="{{ old('default_payment_reference') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02]">
                    Register
                </button>

                <p class="text-md text-center text-black">
                    Already have an account? <a href="{{ route('login') }}" class="text-green-500 hover:underline">Login</a>.
                </p>
                <p class="text-md text-center text-black">
                    © {{ date('Y') }} Grocery Club has all rights reserved.
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
