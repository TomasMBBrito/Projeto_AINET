@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Criar Nova Categoria</h2>

    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6 space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-gray-700 font-semibold mb-2">Nome</label>
            <input type="text" name="name" id="name"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   required>
        </div>

        <div>
            <label for="image" class="block text-gray-700 font-semibold mb-2">Imagem (opcional)</label>
            <input type="file" name="image" id="image"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-green-600 file:text-white file:cursor-pointer">
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-3 bg-green-600 text-white text-base font-semibold rounded-lg hover:bg-green-700 transition">
                Guardar
            </button>
        </div>
    </form>
</div>
@endsection
