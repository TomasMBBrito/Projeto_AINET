@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8 bg-white rounded-lg shadow">

        <h2 class="text-2xl font-bold text-green-700 mb-6">Create New Product</h2>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-gray-700 font-medium mb-1">Name</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Category</label>
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
                <label class="block text-gray-700 font-medium mb-1">Price (€)</label>
                <input type="number" step="0.01" name="price" required value="{{ old('price') }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Stock</label>
                <input type="number" name="stock" required value="{{ old('stock') }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Description</label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Image (optional)</label>
                <input type="file" name="image"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Minimum quantity for discount (optional)</label>
                <input type="number" name="discount_min_qty" value="{{ old('discount_min_qty') }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Discount amount (€)</label>
                <input type="number" step="0.01" name="discount" value="{{ old('discount') }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Minimum stock limit</label>
                <input type="number" name="stock_lower_limit" required value="{{ old('stock_lower_limit', 5) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Maximum stock limit</label>
                <input type="number" name="stock_upper_limit" required value="{{ old('stock_upper_limit', 20) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition">
                Create Product
            </button>
        </form>
    </div>
@endsection
