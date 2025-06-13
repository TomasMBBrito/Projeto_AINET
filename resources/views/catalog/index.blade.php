@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header with Back to Dashboard and Navbar --}}
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            {{-- Back to Dashboard --}}
            <a href="{{ route('home') }}"
                style="display: inline-flex; align-items: center; color: #15803d; font-weight: 500; text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.25rem; width: 1.25rem; margin-right: 0.5rem;"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        <h1 class="text-3xl font-bold text-green-800 mb-6">Product catalog</h1>

        {{-- Filtros --}}
        <form method="GET" class="flex flex-wrap gap-4 mb-8">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                class="border border-green-300 rounded px-3 py-2 shadow-sm focus:ring-green-500" />
            <select name="category" class="border border-green-300 rounded px-3 py-2 shadow-sm focus:ring-green-500">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <select name="sort" class="border border-green-300 rounded px-3 py-2 shadow-sm focus:ring-green-500">
                <option value="">Order by</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (growing)</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (degrowing)
                </option>
            </select>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Filter</button>
        </form>

        {{-- Grid de Produtos --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="relative">
                    <a href="{{ route('catalog.product.show', $product->id) }}"
                    class="block bg-white rounded-lg shadow-md hover:shadow-xl transition p-4 border border-green-100 h-full">
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

                        <form method="POST" action="{{ route('favorites.toggle') }}" class="inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" style="background: none; border: none; cursor: pointer;">
                                <i
                                    class="fa-heart fa-lg {{ session('favorites', collect())->contains($product->id) ? 'fas text-red-600 hover:text-gray-600' : 'far text-gray-500 hover:text-red-600' }}"
                                    ></i>
                            </button>
                        </form>

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

                    {{-- Botão fora da âncora para evitar conflitos de clique --}}
                    <div class="absolute bottom-4 right-4">
                        <form action="{{ route('cart.add') }}" method="POST" class="flex items-center">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="quantity" value="1" min="1"
                                class="w-16 p-1 border rounded mr-2 text-sm" />
                            <button type="submit"
                                class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                                Add to cart
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- Paginação --}}
        @if ($products->hasPages())
            <div class="mt-10 flex justify-center w-full">
                <nav role="navigation"
                    class="flex flex-wrap justify-center w-full max-w-4xl rounded-lg shadow-md bg-white border border-gray-300 overflow-hidden">
                    {{-- Página anterior --}}
                    @if ($products->onFirstPage())
                        <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 cursor-not-allowed">‹</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}"
                            class="px-4 py-2 text-sm text-green-600 hover:bg-green-50">‹</a>
                    @endif

                    {{-- Páginas --}}
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                            <span class="px-4 py-2 text-sm font-bold text-white bg-green-600">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-4 py-2 text-sm text-green-600 hover:bg-green-50">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Página seguinte --}}
                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}"
                            class="px-4 py-2 text-sm text-green-600 hover:bg-green-50">›</a>
                    @else
                        <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 cursor-not-allowed">›</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
@endsection
