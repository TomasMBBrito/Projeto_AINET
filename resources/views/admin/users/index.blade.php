@extends('layouts.app')

@section('title', 'Gestão de Utilizadores')

@section('content')
<div class="max-w-7xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-3xl font-bold mb-6">Utilizadores</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <form method="GET" action="{{ route('users.index') }}" class="flex gap-2">
            <input type="text" name="search" placeholder="Procurar..." class="border p-2 rounded"
                value="{{ request('search') }}">
            <select name="type" class="border p-2 rounded">
                <option value="">Todos</option>
                <option value="member" {{ request('type') == 'member' ? 'selected' : '' }}>Membro</option>
                <option value="board" {{ request('type') == 'board' ? 'selected' : '' }}>Direção</option>
                <option value="employee" {{ request('type') == 'employee' ? 'selected' : '' }}>Funcionário</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filtrar</button>
        </form>

        <a href="{{ route('users.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Novo Funcionário</a>
    </div>

    <table class="w-full table-auto border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Bloqueado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-b">
                    <td class="p-2">{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->type }}</td>
                    <td>{{ $user->blocked ? 'Sim' : 'Não' }}</td>
                    <td class="flex gap-2 py-2">
                        <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:underline">Editar</a>

                        @if ($user->trashed())
                            <form method="POST" action="{{ route('users.restore', $user->id) }}">
                                @csrf @method('PUT')
                                <button class="text-yellow-600 hover:underline">Restaurar</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('users.destroy', $user) }}">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Cancelar subscrição?')" class="text-red-600 hover:underline">
                                    Cancelar
                                </button>
                            </form>
                        @endif

                        @if (in_array($user->type, ['member', 'board']) && $user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.toggleBoard', $user) }}">
                                @csrf @method('PUT')
                                <button class="text-purple-600 hover:underline">
                                    {{ $user->type === 'member' ? 'Promover a direção' : 'Rebaixar para membro' }}
                                </button>
                            </form>
                        @endif

                        @if ($user->type !== 'employee')
                            <form method="POST" action="{{ route('users.block', $user) }}">
                                @csrf @method('PUT')
                                <button class="text-orange-600 hover:underline">
                                    {{ $user->blocked ? 'Desbloquear' : 'Bloquear' }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection
