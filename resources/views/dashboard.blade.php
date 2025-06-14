@extends('layouts.app')

@section('title', 'Welcome to Grocery Club')

@section('content')
    <!-- Banner Principal -->
    <div class="relative bg-green-100 h-[70vh] flex items-center justify-center text-center">
        <div class="absolute inset-0 bg-cover bg-center opacity-40"
            style="background-image: url('https://images.unsplash.com/photo-1613162300594-b1c104b91c49?auto=format&fit=crop&w=1950&q=80');">
        </div>
        <div class="relative z-10">
            <h1 class="text-5xl font-bold text-green-900 mb-4">Welcome to our Grocery Club 🛒</h1>
            <p class="text-xl text-green-800 mb-6">Where fresh products with lots of love are delivered to your doorstep.</p>
            <a href="{{ route('catalog.index') }}"
                class="btn px-6 py-3 bg-green-600 text-white hover:bg-green-700 rounded-full text-lg">
                Explore our products ->
            </a>
        </div>
    </div>

    <!-- Produtos em Destaque -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">🌟 Highlights of the week</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach ($featuredProducts ?? [] as $product)
                @if (is_object($product))
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300">
                        <img src="{{ $product->photo ? asset('storage/products/' . $product->photo) : asset('storage/products/product_no_image.png') }}"
                            alt="{{ $product->name }}" class="w-40 h-40 object-cover mx-auto rounded-lg shadow-md">
                        <div class="p-4 text-center">
                            <h3 class="font-semibold text-lg text-gray-800">{{ $product->name }}</h3>
                            <p class="text-green-600 font-bold text-xl mt-2">€{{ number_format($product->price, 2) }}</p>
                            <p class="text-gray-600 mt-2">{{ $product->description }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    <!-- Benefícios -->
    <section class="bg-white border-t py-16">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            <div>
                <div class="text-green-600 text-4xl mb-2">🚚</div>
                <h4 class="font-bold text-xl mb-1">Fast Delivery</h4>
                <p class="text-gray-600">Orders are deliveried on the same day, with quality garanteed.</p>
            </div>
            <div>
                <div class="text-green-600 text-4xl mb-2">🍎</div>
                <h4 class="font-bold text-xl mb-1">Freshness garanteed</h4>
                <p class="text-gray-600">Fruits, vegetables and groceries selected with the greatest care.</p>
            </div>
            <div>
                <div class="text-green-600 text-4xl mb-2">💳</div>
                <h4 class="font-bold text-xl mb-1">Safe payments</h4>
                <p class="text-gray-600">You can use MB WAY, PayPal, or even your Visa card</p>
            </div>
        </div>
    </section>
@endsection
