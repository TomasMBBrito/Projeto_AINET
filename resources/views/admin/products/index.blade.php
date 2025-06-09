@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h2 class="text-2xl font-bold text-green-700">Gestão de Produtos</h2>
            <div class="flex space-x-2 mt-4 sm:mt-0">
                <a href="{{ route('products.create') }}"
                    class="inline-block px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                    Novo Produto
                </a>
            </div>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-6 rounded-lg shadow mb-6">
            <div>
                <label for="category" class="block text-gray-700 font-medium mb-1">Categoria</label>
                <select name="category" id="category"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todas</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="sort" class="block text-gray-700 font-medium mb-1">Ordenar por</label>
                <select name="sort" id="sort"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Padrão</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nome (A-Z)</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Preço crescente
                    </option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Preço decrescente
                    </option>
                </select>
            </div>

            <div>
                <label for="deleted" class="block text-gray-700 font-medium mb-1">Estado</label>
                <select name="deleted" id="deleted"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Ativos e eliminados</option>
                    <option value="only" {{ request('deleted') == 'only' ? 'selected' : '' }}>Apenas eliminados</option>
                    <option value="none" {{ request('deleted') == 'none' ? 'selected' : '' }}>Apenas ativos</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                    class="w-full px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                    Filtrar
                </button>
            </div>
        </form>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium">Nome</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Categoria</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Preço</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Stock</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $product->name }}</td>
                            <td class="px-6 py-4">
                                @if ($product->category && !$product->category->trashed())
                                    {{ $product->category->name }}
                                @elseif($product->category && $product->category->trashed())
                                    Categoria removida ({{ $product->category->name }})
                                @else
                                    Categoria removida (Categoria desconhecida)
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ number_format($product->price, 2) }}€</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span>{{ $product->stock }}</span>
                                    @if ($product->stock <= $product->stock_lower_limit)
                                        <span class="text-xs text-red-600">Stock baixo: ≤
                                            {{ $product->stock_lower_limit }}</span>
                                    @elseif($product->stock >= $product->stock_upper_limit)
                                        <span class="text-xs text-yellow-600">Reabastecimento: ≥
                                            {{ $product->stock_upper_limit }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                @if (!$product->trashed())
                                    <a href="{{ route('products.edit', $product) }}"
                                        class="inline-block px-4 py-2 bg-yellow-500 text-white rounded-md text-sm hover:bg-yellow-600 transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-block px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 transition">
                                            Eliminar
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('products.restore', $product->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-block px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700 transition">
                                            Restaurar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
@endsection
