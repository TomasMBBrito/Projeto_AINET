@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Criar Novo Produto</h2>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select name="category_id" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Preço (€)</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Stock Inicial</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagem (opcional)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Quantidade mínima para desconto (opcional)</label>
            <input type="number" name="discount_min_qty" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Valor do desconto (€)</label>
            <input type="number" step="0.01" name="discount" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Limite mínimo de stock</label>
            <input type="number" name="stock_lower_limit" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Limite máximo de stock</label>
            <input type="number" name="stock_upper_limit" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar Produto</button>
    </form>
</div>
@endsection
