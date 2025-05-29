@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Categoria</h2>

    <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Imagem Atual</label><br>
            @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" width="100"><br><br>
            @else
                <span>Nenhuma imagem</span><br><br>
            @endif
            <label for="image">Nova Imagem (opcional)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>
@endsection
