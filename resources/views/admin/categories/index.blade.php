@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gestão de Categorias</h2>
        <a href="{{ route('categories.create') }}"
           class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 transition">
            + Nova Categoria
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Imagem</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-600">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-900 font-medium">{{ $category->name }}</td>
                        <td class="px-6 py-4">
                            @if($category->image)
                                <img src="{{ asset('storage/categories/' . $category->image) }}"
                                     alt="Imagem da categoria"
                                     class="w-20 h-20 object-cover rounded-md border">
                            @else
                                <span class="text-sm text-gray-500 italic">Sem imagem</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            @if(!$category->trashed())
                                <a href="{{ route('categories.edit', $category) }}"
                                   class="inline-block px-5 py-2 bg-yellow-500 text-white text-base font-semibold rounded-md hover:bg-yellow-600 transition">
                                    Editar
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Tem a certeza que deseja eliminar esta categoria?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-5 py-2 bg-red-600 text-white text-base font-semibold rounded-md hover:bg-red-700 transition">
                                        Eliminar
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('categories.restore', $category->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit"
                                            class="px-5 py-2 bg-green-600 text-white text-base font-semibold rounded-md hover:bg-green-700 transition">
                                        Restaurar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Nenhuma categoria encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
