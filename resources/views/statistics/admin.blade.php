@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📈 Estatísticas Administrativas</h2>

    <form method="GET" action="{{ route('statistics') }}" class="mb-6 flex items-center space-x-4">
        <label for="month" class="text-gray-700 font-medium">Filtrar por mês:</label>
        <input type="month" id="month" name="month" value="{{ request('month') }}" class="border border-gray-300 rounded px-3 py-2">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Filtrar</button>
        <a href="{{ route('statistics') }}" class="text-gray-500 hover:underline">Limpar</a>
    </form>

    @if(request('month'))
        <p class="mb-6 text-gray-600">Estatísticas para: <strong>{{ \Carbon\Carbon::parse(request('month') . '-01')->translatedFormat('F/Y') }}</strong></p>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">💶 Vendas Mensais (€)</h3>
            <canvas id="salesChart"></canvas>
        </div>

        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">👥 Membros por Mês</h3>
            <canvas id="membersChart"></canvas>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-2xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">🥇 Top 5 Produtos Mais Vendidos</h3>
        <ul class="list-disc list-inside text-gray-600">
            @foreach ($topProducts as $product)
                <li class="mb-1">{{ $product->name }} – <strong>{{ $product->total }}</strong> unidades</li>
            @endforeach
        </ul>
    </div>

    <div class="text-right">
        <a href="{{ route('statistics.export') }}"
           class="inline-block px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition">
            ⬇️ Exportar Vendas (.xlsx)
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($salesByMonth->keys()) !!},
            datasets: [{
                label: 'Vendas (€)',
                data: {!! json_encode($salesByMonth->values()) !!},
                fill: true,
                backgroundColor: 'rgba(56, 142, 60,0.2)',
                borderColor: 'rgba(56, 142, 60,1)',
                tension: 0.3
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('membersChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($membersCountByMonth->keys()) !!},
            datasets: [{
                label: 'Membros',
                data: {!! json_encode($membersCountByMonth->values()) !!},
                backgroundColor: 'rgba(56, 142, 60,0.7)',
                borderColor: 'rgba(56, 142, 60,1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });
</script>
@endsection
