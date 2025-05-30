<!-- admin/settings/shipping_costs/index.blade.php - Conteúdo completo gerado com base no projeto -->
@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-bold mb-6">🚚 Gestão de Custos de Envio</h2>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="w-full text-left border-collapse">
        <thead class="bg-green-100">
            <tr>
                <th class="px-4 py-2">Código Postal / Zona</th>
                <th class="px-4 py-2">Custo (€)</th>
                <th class="px-4 py-2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shippingCosts as $cost)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ $cost->postal_code }}</td>
                <td class="px-4 py-2">€{{ number_format($cost->cost, 2) }}</td>
                <!-- <td class="px-4 py-2 space-x-2">
                    <a href="{{ route('admin.settings.shipping_costs.edit', $cost) }}"
                       class="btn bg-blue-600 hover:bg-blue-700 text-sm">Editar</a>

                    <form action="{{ route('admin.settings.shipping_costs.destroy', $cost) }}"
                          method="POST" class="inline"
                          onsubmit="return confirm('Tem a certeza que deseja remover esta entrada?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-sm">Remover</button>
                    </form>
                </td> -->
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- <div class="mt-6 flex justify-between items-center">
    <a href="{{ route('admin.settings.shipping_costs.create') }}" class="btn">➕ Novo Custo de Envio</a>
    {{ $shippingCosts->links() }}
</div> -->
@endsection
