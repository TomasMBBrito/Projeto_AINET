@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 bg-white rounded-lg shadow">

    <h2 class="text-2xl font-bold text-green-700 mb-6">Editar Produto</h2>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 font-medium mb-1">Nome</label>
            <input type="text" name="name" required
                value="{{ $product->name }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Categoria</label>
            <select name="category_id" required
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected($category->id === $product->category_id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Preço (€)</label>
            <input type="number" step="0.01" name="price" required
                value="{{ $product->price }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Stock</label>
            <input type="number" name="stock" required
                value="{{ $product->stock }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Descrição</label>
            <textarea name="description" rows="4" required
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ $product->description }}</textarea>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Imagem atual:</label>
            @if($product->photo)
                <img src="{{ asset('storage/products/' . $product->photo) }}" alt="Imagem atual" class="w-24 rounded mb-3">
            @else
                <span class="text-gray-500 mb-3 inline-block">Nenhuma imagem</span>
            @endif

            <label class="block text-gray-700 font-medium mb-1">Nova Imagem (opcional)</label>
            <input type="file" name="image"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Quantidade mínima para desconto (opcional)</label>
            <input type="number" name="discount_min_qty"
                value="{{ $product->discount_min_qty }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Valor do desconto (€)</label>
            <input type="number" step="0.01" name="discount"
                value="{{ $product->discount }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Limite mínimo de stock</label>
            <input type="number" name="stock_lower_limit" required
                value="{{ $product->stock_lower_limit }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Limite máximo de stock</label>
            <input type="number" name="stock_upper_limit" required
                value="{{ $product->stock_upper_limit }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>

        <button type="submit"
            class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition">
            Atualizar Produto
        </button>
    </form>
</div>
@endsection
