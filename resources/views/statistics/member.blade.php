@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Estatísticas Pessoais</h2>
    @if ($selectedMonthLabel)
        <p class="text-gray-600 mb-4">Estatísticas para: <strong>{{ $selectedMonthLabel }}</strong></p>
    @endif

    <div class="bg-white shadow-md rounded-2xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Total de Compras Realizadas</h3>
        <p class="text-3xl font-bold text-green-600">{{ $orderCount }}</p>
    </div>

    <form method="GET" action="{{ route('statistics') }}" class="mb-6">
        <div class="flex items-center space-x-4">
            <label for="month" class="text-gray-700 font-medium">Filtrar por mês:</label>
            <input type="month" id="month" name="month" value="{{ request('month') }}"
                class="border border-gray-300 rounded px-3 py-2">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Filtrar
            </button>
            <a href="{{ route('statistics') }}"
            class="text-gray-500 hover:underline">Limpar</a>
        </div>
    </form>

    <div class="bg-white shadow-md rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Gastos Mensais</h3>
        <canvas id="spendingChart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const spendingChart = new Chart(document.getElementById('spendingChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($spendingByMonth->keys()) !!},
            datasets: [{
                label: 'Gastos (€)',
                data: {!! json_encode($spendingByMonth->values()) !!},
                backgroundColor: 'rgba(56, 142, 60, 0.6)',
                borderColor: 'rgba(56, 142, 60, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            scales: {
                // x: { title: { display: true, text: 'Mês' } },
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
