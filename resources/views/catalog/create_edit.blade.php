@extends('layouts.app')

@section('title', isset($product) ? 'Editar Produto' : 'Novo Produto')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6">
        {{ isset($product) ? 'Editar Produto' : 'Novo Produto' }}
    </h1>

    <form method="POST" action="{{ isset($product) ? route('catalog.update', $product->id) : route('catalog.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div>
            <label for="name" class="block font-semibold">Name</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full p-2 border rounded-lg">
        </div>

        <div>
            <label for="category_id" class="block font-semibold">Categoria</label>
            <select name="category_id" class="w-full p-2 border rounded-lg">
                <option value="">Select</option>
                @foreach ($categories as $id => $name)
                    <option value="{{ $id }}" {{ (old('category_id', $product->category_id ?? '') == $id) ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="price" class="block font-semibold">Price (€)</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" class="w-full p-2 border rounded-lg">
        </div>

        <div>
            <label for="stock" class="block font-semibold">Stock</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock ?? '') }}" class="w-full p-2 border rounded-lg">
        </div>

        <div>
            <label for="description" class="block font-semibold">Description</label>
            <textarea name="description" class="w-full p-2 border rounded-lg" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div>
            <label for="discount_min_qty" class="block font-semibold">Minimum quantity for discount</label>
            <input type="number" name="discount_min_qty" value="{{ old('discount_min_qty', $product->discount_min_qty ?? '') }}" class="w-full p-2 border rounded-lg">
        </div>

        <div>
            <label for="discount" class="block font-semibold">Discount percentage (%)</label>
            <input type="number" name="discount" step="0.01" value="{{ old('discount', $product->discount ?? '') }}" class="w-full p-2 border rounded-lg">
        </div>

        <div>
            <label for="photo" class="block font-semibold">Photo (upload)</label>
            <input type="file" name="photo" class="w-full p-2 border rounded-lg">
            @if(isset($product) && $product->photo)
                <img src="{{ asset('storage/' . $product->photo) }}" class="mt-2 h-24">
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                {{ isset($product) ? 'Atualizar' : 'Salvar' }}
            </button>
        </div>
    </form>
</div>
@endsection
