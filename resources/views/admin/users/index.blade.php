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

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <form method="GET" action="{{ route('users.index') }}" class="flex gap-2 flex-wrap">
            <input type="text" name="search" placeholder="Procurar..." class="border border-gray-300 p-2 rounded" value="{{ request('search') }}">
            <select name="type" class="border border-gray-300 p-2 rounded">
                <option value="">Todos</option>
                <option value="member" {{ request('type') == 'member' ? 'selected' : '' }}>Membro</option>
                <option value="board" {{ request('type') == 'board' ? 'selected' : '' }}>Direção</option>
                <option value="employee" {{ request('type') == 'employee' ? 'selected' : '' }}>Funcionário</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filtrar</button>
        </form>

        <a href="{{ route('users.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">New Employee</a>
    </div>

    <table class="w-full table-auto border text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="p-2">ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Blocked</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->type) }}</td>
                    <td>{{ $user->blocked ? 'Sim' : 'Não' }}</td>
                    <td class="flex flex-wrap gap-2 py-2">

                        {{-- Editar --}}
                        <a href="{{ route('users.edit', $user) }}" 
                           class="inline-flex items-center bg-blue-500 text-white px-5 py-3 rounded hover:bg-blue-600 transition">
                            Editar
                        </a>

                        {{-- Restaurar ou Cancelar --}}
                        @if ($user->trashed())
                            <form method="POST" action="{{ route('users.restore', $user->id) }}">
                                @csrf 
                                @method('PUT')
                                <button type="submit" class="inline-flex items-center bg-yellow-500 text-white px-5 py-3 rounded hover:bg-yellow-600 transition">
                                    Restore
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('users.destroy', $user) }}">
                                @csrf 
                                @method('DELETE')
                                <button onclick="return confirm('Cancel Subscription?')" 
                                        class="inline-flex items-center bg-red-500 text-white px-5 py-3 rounded hover:bg-red-600 transition">
                                    Delete account
                                </button>
                            </form>
                        @endif

                        {{-- Promover/Rebaixar --}}
                        @if (in_array($user->type, ['member', 'board']) && $user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.toggleBoard', $user) }}">
                                @csrf 
                                @method('PUT')
                                <button type="submit" class="inline-flex items-center bg-purple-500 text-white px-5 py-3 rounded hover:bg-purple-600 transition">
                                    {{ $user->type === 'member' ? 'Promote' : 'Demote' }}
                                </button>
                            </form>
                        @endif

                        {{-- Bloquear/Desbloquear --}}
                        @if ($user->type !== 'employee')
                            <form method="POST" action="{{ route('users.block', $user) }}">
                                @csrf 
                                @method('PUT')
                                <button type="submit" class="inline-flex items-center bg-orange-500 text-white px-5 py-3 rounded hover:bg-orange-600 transition">
                                    {{ $user->blocked ? 'Desbloquear' : 'Bloquear' }}
                                </button>
                            </form>
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
