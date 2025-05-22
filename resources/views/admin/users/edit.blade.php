@extends('layouts.app')

@section('title', 'Editar Utilizador')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-6">Editar Utilizador</h2>

    <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <label class="block font-medium">Nome</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full mb-4 p-2 border rounded">

        <label class="block font-medium">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full mb-4 p-2 border rounded">

        <label class="block font-medium">Género</label>
        <select name="gender" class="w-full mb-4 p-2 border rounded">
            <option value="M" {{ $user->gender === 'M' ? 'selected' : '' }}>Masculino</option>
            <option value="F" {{ $user->gender === 'F' ? 'selected' : '' }}>Feminino</option>
        </select>

        <label class="block font-medium">Nova Password <small>(opcional)</small></label>
        <input type="password" name="password" class="w-full mb-4 p-2 border rounded">

        <label class="block font-medium">Foto de perfil</label>
        <input type="file" name="photo" class="w-full mb-4 p-2 border rounded">

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Atualizar</button>
    </form>
</div>
@endsection
