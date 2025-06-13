@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        {{-- Imagem do Produto --}}
        <div class="md:w-1/2">
            @if ($product->photo)
                <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}" 
                     class="w-full h-auto rounded-lg shadow-md object-cover">
            @else
                <div class="w-full h-64 bg-green-50 flex items-center justify-center rounded-lg text-green-400">
                    Sem imagem
                </div>
            @endif
        </div>

        {{-- Informações --}}
        <div class="md:w-1/2 flex flex-col justify-between">
            <div>
                <h1 class="text-3xl font-bold text-green-900 mb-3">{{ $product->name }}</h1>
                <p class="text-green-700 font-semibold mb-2">Categoria: {{ $product->category->name }}</p>

                <p class="text-green-900 text-2xl font-semibold mb-4">
                    @if ($product->discount_min_qty && $product->discount && $product->stock >= $product->discount_min_qty)
                        <span>€{{ number_format(max($product->price - $product->discount, 0), 2) }}</span>
                        <span class="line-through text-gray-400 ml-3">€{{ number_format($product->price, 2) }}</span>
                        <p class="text-green-500 text-sm mt-1">Desconto a partir de {{ $product->discount_min_qty }} unidades</p>
                    @else
                        €{{ number_format($product->price, 2) }}
                    @endif
                </p>

                <p class="text-gray-700 mb-6">{{ $product->description }}</p>

                <p class="mb-4">
                    <span class="font-semibold text-green-700">Stock:</span>
                    <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $product->stock > 0 ? 'Disponível' : 'Esgotado' }}
                    </span>
                </p>
            </div>

            {{-- Ações: Adicionar ao Carrinho + Favoritos --}}
            <div class="flex items-center space-x-4">
                {{-- Formulário para adicionar ao carrinho --}}
                <form action="{{ route('cart.add') }}" method="POST" class="flex items-center space-x-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                           class="w-20 p-2 border rounded focus:outline-green-500" />
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold">
                        Adicionar ao Carrinho
                    </button>
                </form>

                {{-- Botão Favoritos --}}
                <form method="POST" action="{{ route('favorites.toggle') }}" class="inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" style="background: none; border: none; cursor: pointer;">
                        <i
                            class="fa-heart fa-lg {{ session('favorites', collect())->contains($product->id) ? 'fas text-red-600 hover:text-gray-600' : 'far text-gray-500 hover:text-red-600' }}"
                            ></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Produtos Relacionados (4 produtos random da mesma categoria) --}}
    @if ($relatedProducts->isNotEmpty())
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-green-800 mb-6">Produtos relacionados</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach ($relatedProducts as $relProd)
                    <a href="{{ route('catalog.product.show', $relProd->id) }}"
                       class="block bg-white rounded-lg shadow-md p-4 border border-green-100 hover:shadow-lg transition">
                        @if ($relProd->photo)
                            <img src="{{ asset('storage/products/' . $relProd->photo) }}" alt="{{ $relProd->name }}"
                                 class="w-full h-40 object-cover rounded-lg mb-4">
                        @else
                            <div class="w-full h-40 bg-green-50 flex items-center justify-center rounded mb-4 text-green-400">
                                <span>Sem imagem</span>
                            </div>
                        @endif
                        <h3 class="text-lg font-semibold text-green-900">{{ $relProd->name }}</h3>
                        <p class="text-green-700 font-medium mb-2">{{ $relProd->category->name }}</p>
                        <p class="text-green-900 font-semibold">
                            €{{ number_format($relProd->price, 2) }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
