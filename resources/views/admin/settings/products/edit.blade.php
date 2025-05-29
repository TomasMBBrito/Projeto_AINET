@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Produto</h2>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select name="category_id" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected($category->id === $product->category_id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Preço (€)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control" required>{{ $product->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagem atual:</label><br>
            @if($product->photo)
                <img src="{{ asset('storage/' . $product->photo) }}" width="100"><br><br>
            @else
                <span>Nenhuma imagem</span><br><br>
            @endif
            <label class="form-label">Nova Imagem (opcional)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Quantidade mínima para desconto (opcional)</label>
            <input type="number" name="discount_min_qty" class="form-control" value="{{ $product->discount_min_qty }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Valor do desconto (€)</label>
            <input type="number" step="0.01" name="discount" class="form-control" value="{{ $product->discount }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Limite mínimo de stock</label>
            <input type="number" name="stock_lower_limit" class="form-control" value="{{ $product->stock_lower_limit }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Limite máximo de stock</label>
            <input type="number" name="stock_upper_limit" class="form-control" value="{{ $product->stock_upper_limit }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Produto</button>
    </form>
</div>
@endsection
