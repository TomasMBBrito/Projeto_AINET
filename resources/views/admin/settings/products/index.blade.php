@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-bold text-green-800">Gestão de Produtos</h2>
        <a href="{{ route('products.create') }}" class="btn btn-success">Novo Produto</a>
    </div>

    {{-- Filtros estilo catálogo --}}
    <form method="GET" class="d-flex flex-wrap gap-3 align-items-end mb-4">
        {{-- Categoria --}}
        <div>
            <label for="category" class="form-label">Categoria</label>
            <select name="category" id="category" class="form-select">
                <option value="">Todas</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Ordenação --}}
        <div>
            <label for="sort" class="form-label">Ordenar por</label>
            <select name="sort" id="sort" class="form-select">
                <option value="">Padrão</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nome (A-Z)</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Preço crescente</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Preço decrescente</option>
            </select>
        </div>

        {{-- Mostrar eliminados --}}
        <div>
            <label for="deleted" class="form-label">Estado</label>
            <select name="deleted" id="deleted" class="form-select">
                <option value="">Ativos e eliminados</option>
                <option value="only" {{ request('deleted') == 'only' ? 'selected' : '' }}>Apenas eliminados</option>
                <option value="none" {{ request('deleted') == 'none' ? 'selected' : '' }}>Apenas ativos</option>
            </select>
        </div>

        {{-- Botão filtrar --}}
        <div>
            <button type="submit" class="btn btn-success mt-2">Filtrar</button>
        </div>
    </form>

    {{-- Mensagens --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabela de produtos --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th>Stock</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>  @if($product->category && !$product->category->trashed())
                    {{ $product->category->name }}
                @elseif($product->category && $product->category->trashed())
                    Categoria removida ({{ $product->category->name }})
                @else
                    Categoria removida (Categoria desconhecida)
                @endif</td>
                <td>{{ number_format($product->price, 2) }}€</td>
                <td>{{ $product->stock }}</td>
                <td>
                    @if(!$product->trashed())
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    @else
                        <form action="{{ route('products.restore', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Paginação --}}
    <div class="mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
