@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8 bg-white rounded-lg shadow">

        <h2 class="text-2xl font-bold text-green-700 mb-6">Criar Novo Produto</h2>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-gray-700 font-medium mb-1">Nome</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Categoria</label>
                <select name="category_id" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Preço (€)</label>
                <input type="number" step="0.01" name="price" required value="{{ old('price') }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Stock</label>
                <input type="number" name="stock" required value="{{ old('stock') }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Descrição</label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Imagem (opcional)</label>
                <input type="file" name="image"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Quantidade mínima para desconto (opcional)</label>
                <input type="number" name="discount_min_qty" value="{{ old('discount_min_qty') }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Valor do desconto (€)</label>
                <input type="number" step="0.01" name="discount" value="{{ old('discount') }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Limite mínimo de stock</label>
                <input type="number" name="stock_lower_limit" required value="{{ old('stock_lower_limit', 5) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Limite máximo de stock</label>
                <input type="number" name="stock_upper_limit" required value="{{ old('stock_upper_limit', 20) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition">
                Criar Produto
            </button>
        </form>
    </div>
@endsection
