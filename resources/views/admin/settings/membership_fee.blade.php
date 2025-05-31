@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Configurações Gerais</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Taxa de Adesão (€)</label>
            <input type="number" step="0.01" name="membership_fee" 
                   class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="{{ old('membership_fee', $setting->membership_fee) }}" required>
        </div>

        <button type="submit" 
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            Atualizar
        </button>
    </form>
</div>
@endsection
