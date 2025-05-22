@extends('layouts.app')

@section('title', 'Novo Funcionário')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-6">Adicionar Funcionário</h2>

    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf

        <label class="block font-medium">Nome</label>
        <input type="text" name="name" required class="w-full mb-4 p-2 border rounded">

        <label class="block font-medium">Email</label>
        <input type="email" name="email" required class="w-full mb-4 p-2 border rounded">

        <label class="block font-medium">Password</label>
        <input type="password" name="password" required class="w-full mb-4 p-2 border rounded">

        <label class="block font-medium">Género</label>
        <select name="gender" class="w-full mb-4 p-2 border rounded">
            <option value="M">Masculino</option>
            <option value="F">Feminino</option>
        </select>

        <label class="block font-medium">Foto de perfil</label>
        <input type="file" name="photo" class="w-full mb-4 p-2 border rounded">

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Guardar</button>
    </form>
</div>
@endsection
