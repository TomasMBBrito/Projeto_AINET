@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-green-800 mb-6">Favorites' list</h1>

    @if ($favoriteProducts->isEmpty())
        <p class="text-gray-600">You didn't add anything to your list yet.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($favoriteProducts as $product)
                <div class="relative bg-white rounded-lg shadow-md hover:shadow-xl transition p-4 border border-green-100 flex flex-col h-full">
                    <a href="{{ route('catalog.product.show', $product->id) }}"
                       class="block flex-grow">
                        {{-- Imagem --}}
                        @if ($product->photo)
                            <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}"
                                class="w-40 h-40 object-cover mx-auto rounded-lg shadow-md mb-3">
                        @else
                            <div class="w-full h-40 bg-green-50 flex items-center justify-center rounded mb-3 text-green-400">
                                <span>No image</span>
                            </div>
                        @endif

                        {{-- Nome e Categoria --}}
                        <h2 class="text-lg font-semibold text-green-900">{{ $product->name }}</h2>
                        <p class="text-sm text-green-600">{{ $product->category->name }}</p>

                        {{-- Preço e Desconto --}}
                        <div class="mt-3">
                            @if ($product->discount_min_qty && $product->discount && $product->stock >= $product->discount_min_qty)
                                <p class="text-sm text-green-700 font-medium">
                                    €{{ number_format(max($product->price - $product->discount, 0), 2) }}
                                    <span class="line-through text-gray-400 ml-2">€{{ number_format($product->price, 2) }}</span>
                                </p>
                                <p class="text-xs text-green-500">
                                    Discount from {{ $product->discount_min_qty }} units
                                </p>
                            @else
                                <p class="text-sm text-green-900 font-medium">
                                    €{{ number_format($product->price, 2) }}
                                </p>
                            @endif
                        </div>

                        {{-- Descrição --}}
                        <p class="text-sm text-gray-600 mt-2">{{ $product->description }}</p>

                        {{-- Stock --}}
                        <span class="text-xs block mt-4 {{ $product->stock <= 0 ? 'text-red-500' : 'text-green-600' }} font-semibold">
                            {{ $product->stock <= 0 ? 'Out of stock' : 'In stock' }}
                        </span>
                    </a>

                    {{-- Forms centralizados embaixo --}}
                    <div class="mt-4 flex flex-col items-center gap-3">
                        <form action="{{ route('cart.add') }}" method="POST" class="flex w-full space-x-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <input type="number" name="quantity" value="1" min="1"
                                class="w-1/2 p-2 border rounded text-sm" />

                            <button type="submit"
                                class="w-1/2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-semibold px-4 py-2">
                                Add to cart
                            </button>
                        </form>

                        <form action="{{ route('favorites.toggle') }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="text-red-600 hover:text-red-700 flex items-center space-x-2">
                                <i class="fas fa-heart fa-lg"></i>
                                <span>Remove from favorites</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
