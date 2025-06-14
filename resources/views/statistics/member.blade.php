@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')

@section('title', 'Personal Statistics')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">📊 Personal Statistics</h1>

    <!-- Filtro por mês -->
    <form method="GET" class="mb-6">
        <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Choose Month:</label>
        <input type="month" name="month" id="month" value="{{ $selectedMonth }}" class="border border-gray-300 rounded px-3 py-2">
        <button type="submit" class="ml-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Filter</button>
    </form>

    @if ($selectedMonthLabel)
        <h2 class="text-xl font-semibold mb-4">📅 Statistics {{ $selectedMonthLabel }}</h2>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Total Gasto -->
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">💸 Total Spending</h3>
            @if ($spendingByMonth->isNotEmpty())
                <ul class="text-gray-800 text-lg">
                    @foreach ($spendingByMonth as $month => $total)
                        <li>
                            {{ Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}:
                            <strong>{{ number_format($total, 2, ',', '.') }}€</strong>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">No spending data this month.</p>
            @endif
        </div>

        <!-- Número de encomendas -->
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">📦 Number of orders</h3>
            <p class="text-3xl font-bold text-green-600">{{ $orderCount }}</p>
        </div>
    </div>

    <!-- GRÁFICOS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Gráfico de gastos por encomenda -->
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">🧾 Spend per order</h3>
            <canvas id="spendingChart"></canvas>
        </div>

        <!-- Gráfico de carregamentos -->
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">💳 Card top-ups</h3>
            <canvas id="creditChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const spendingLabels = {!! json_encode($orderSpending->keys()) !!};
    const spendingValues = {!! json_encode($orderSpending->values()) !!};
    const creditLabels = {!! json_encode($creditsAdded->keys()) !!};
    const creditValues = {!! json_encode($creditsAdded->values()) !!};

    new Chart(document.getElementById('spendingChart'), {
        type: 'line',
        data: {
            labels: spendingLabels,
            datasets: [{
                label: 'Valor (€)',
                data: spendingValues,
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderRadius: 5,
                fill: true
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });

    new Chart(document.getElementById('creditChart'), {
        type: 'line',
        data: {
            labels: creditLabels,
            datasets: [{
                label: 'Carregamento (€)',
                data: creditValues,
                borderColor: 'rgba(34,197,94,1)',
                backgroundColor: 'rgba(34,197,94,0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
