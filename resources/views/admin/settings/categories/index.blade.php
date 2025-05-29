@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Categorias</h2>

    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Nova Categoria</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Imagem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" width="50">
                    @endif
                </td>
                <td>
                    @if(!$category->trashed())
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    @else
                        <form action="{{ route('categories.restore', $category->id) }}" method="POST" style="display:inline;">
                            @csrf 
                            <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
