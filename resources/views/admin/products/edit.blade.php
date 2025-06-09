@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8 bg-white rounded-lg shadow">

        <h2 class="text-2xl font-bold text-green-700 mb-6">Editar Produto</h2>

        @if (session('stock_warning'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded">
                <p class="font-bold">Atenção!</p>
                <p>{{ session('stock_warning.message') }}</p>

                <form method="POST" action="{{ route('products.update', $product) }}" class="mt-4 flex space-x-3">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="confirm_stock_limit" value="1">

                    <input type="hidden" name="name" value="{{ old('name', request('name', $product->name)) }}">
                    <input type="hidden" name="category_id"
                        value="{{ old('category_id', request('category_id', $product->category_id)) }}">
                    <input type="hidden" name="price" value="{{ old('price', request('price', $product->price)) }}">
                    <input type="hidden" name="stock" value="{{ old('stock', request('stock', $product->stock)) }}">
                    <input type="hidden" name="description"
                        value="{{ old('description', request('description', $product->description)) }}">
                    <input type="hidden" name="discount_min_qty"
                        value="{{ old('discount_min_qty', request('discount_min_qty', $product->discount_min_qty)) }}">
                    <input type="hidden" name="discount"
                        value="{{ old('discount', request('discount', $product->discount)) }}">
                    <input type="hidden" name="stock_lower_limit"
                        value="{{ old('stock_lower_limit', request('stock_lower_limit', $product->stock_lower_limit)) }}">
                    <input type="hidden" name="stock_upper_limit"
                        value="{{ old('stock_upper_limit', request('stock_upper_limit', $product->stock_upper_limit)) }}">

                    <button type="submit"
                        class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition">
                        Confirmar Atualização
                    </button>
                    <a href="{{ route('products.edit', $product) }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Cancelar
                    </a>
                </form>
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-gray-700 font-medium mb-1">Nome</label>
                <input type="text" name="name" required value="{{ old('name', $product->name) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Categoria</label>
                <select name="category_id" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Preço (€)</label>
                <input type="number" step="0.01" name="price" required value="{{ old('price', $product->price) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Stock</label>
                <input type="number" name="stock" required value="{{ old('stock', $product->stock) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Descrição</label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Imagem atual:</label>
                @if ($product->photo)
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="Imagem atual" class="w-24 rounded mb-3">
                @else
                    <span class="text-gray-500 mb-3 inline-block">Nenhuma imagem</span>
                @endif

                <label class="block text-gray-700 font-medium mb-1">Nova Imagem (opcional)</label>
                <input type="file" name="image"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Quantidade mínima para desconto (opcional)</label>
                <input type="number" name="discount_min_qty"
                    value="{{ old('discount_min_qty', $product->discount_min_qty) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Valor do desconto (€)</label>
                <input type="number" step="0.01" name="discount" value="{{ old('discount', $product->discount) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Limite mínimo de stock</label>
                <input type="number" name="stock_lower_limit" required
                    value="{{ old('stock_lower_limit', $product->stock_lower_limit) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Limite máximo de stock</label>
                <input type="number" name="stock_upper_limit" required
                    value="{{ old('stock_upper_limit', $product->stock_upper_limit) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition">
                Atualizar Produto
            </button>
        </form>
    </div>
@endsection
