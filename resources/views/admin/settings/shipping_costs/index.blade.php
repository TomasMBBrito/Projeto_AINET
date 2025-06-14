<!-- admin/settings/shipping_costs/index.blade.php - Conteúdo completo gerado com base no projeto -->
@extends('layouts.app')

@section('content')
    <div class="px-6"> <!-- Adicionado espaçamento lateral -->

        <h2 class="text-3xl font-bold mb-6">🚚 Shipping Cost Management</h2>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="w-full text-left border-collapse">
                <thead class="bg-green-100">
                    <tr>
                        <th class="px-4 py-2">Minimum Amount (€)</th>
                        <th class="px-4 py-2">Maximum Amount (€)</th>
                        <th class="px-4 py-2">Shipping Cost (€)</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shippingCosts as $cost)
                        <tr>
                            <td class="p-2 border">{{ $cost->min_value_threshold }}</td>
                            <td class="p-2 border">{{ $cost->max_value_threshold }}</td>
                            <td class="p-2 border">
                                {{ $cost->shipping_cost == 0 ? 'Free' : number_format($cost->shipping_cost, 2, ',', '.') . ' €' }}
                            </td>
                            <td class="p-2 border">
                                <a href="{{ route('admin.settings.shipping_costs.edit', $cost) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.settings.shipping_costs.destroy', $cost) }}" method="POST"
                                    class="inline-block ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Eliminate</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-between items-center">
            <a href="{{ route('admin.settings.shipping_costs.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                ➕ New Shipping Cost
            </a>
            {{ $shippingCosts->links() }}
        </div>

    </div> <!-- Fecho do espaçamento -->
@endsection
