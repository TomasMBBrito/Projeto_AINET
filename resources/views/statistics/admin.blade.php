@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Administrative statistics</h2>

    <form method="GET" action="{{ route('statistics') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label for="month" class="block text-gray-700 font-medium">Filter by month:</label>
            <select id="month" name="month" class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">All</option>
                @php
                    $start = now()->subYears(2); // ou ajusta para quantos anos quiseres
                    $end = now();
                @endphp
                @for ($date = $end; $date >= $start; $date->subMonth())
                    @php
                        $value = $date->format('Y-m');
                        $label = $date->translatedFormat('F \d\e Y'); // ex: Junho de 2025
                    @endphp
                    <option value="{{ $value }}" @selected(request('month') == $value)>{{ ucfirst($label) }}</option>
                @endfor
            </select>
        </div>

        <div>
            <label for="user" class="block text-gray-700 font-medium">Filter by member:</label>
            <input type="text" id="user" name="user" value="{{ request('user') }}" class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label for="category" class="block text-gray-700 font-medium">Filter by category:</label>
            <select name="category_id" id="category" class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">All</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="product" class="block text-gray-700 font-medium">Filter by product:</label>
            <select name="product_id" id="product" class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">All</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-4 flex items-center space-x-4 mt-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Filter</button>
            <a href="{{ route('statistics') }}" class="text-gray-500 hover:underline">Clear</a>
        </div>
    </form>

    @if(request('month'))
        <p class="mb-6 text-gray-600">Statistics for: <strong>{{ \Carbon\Carbon::parse(request('month') . '-01')->translatedFormat('F/Y') }}</strong></p>
    @endif

    {{-- Estatísticas Resumo --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow-md rounded-2xl p-4 text-center">
            <p class="text-sm text-gray-500">Total sales (€)</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($summary['total_sales'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-white shadow-md rounded-2xl p-4 text-center">
            <p class="text-sm text-gray-500">Average Sales Per Transaction (€)</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($summary['average_sales'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-white shadow-md rounded-2xl p-4 text-center">
            <p class="text-sm text-gray-500">Maximum Sale (€)</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($summary['max_sale'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-white shadow-md rounded-2xl p-4 text-center">
            <p class="text-sm text-gray-500">Minimum Sale (€)</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($summary['min_sale'], 2, ',', '.') }}</p>
        </div>
    </div>

    {{-- Gráficos Gerais --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Monthly Sales (€)</h3>
            <canvas id="salesChart"></canvas>
        </div>


        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Members per month</h3>
            <canvas id="membersChart"></canvas>
        </div>
    </div>

    {{-- Tabelas de Dados Adicionais --}}

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Sales by Category</h3>
            <ul class="list-disc list-inside text-gray-600">
                @foreach($salesByCategory as $item)
                    <li>{{ $item->name }} – <strong>{{ number_format($item->total, 2, ',', '.') }}€</strong></li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Sales by Member</h3>
            <ul class="list-disc list-inside text-gray-600">
                @foreach ($salesByUser as $user)
                    <li>{{ $user->name }} – <strong>{{ number_format($user->total, 2, ',', '.') }}€</strong></li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Top Produtos --}}
    <div class="bg-white shadow-md rounded-2xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Top 5 Best Selling Products</h3>
        <ul class="list-disc list-inside text-gray-600">
            @foreach ($topProducts as $product)
                <li class="mb-1">{{ $product->name }} – <strong>{{ $product->total }}</strong> units</li>
            @endforeach
        </ul>
    </div>

    <div class="flex justify-end space-x-4">
        <a href="{{ route('statistics.exportXLSX', ['format' => 'xlsx']) }}"
           class="inline-block px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition">
            ⬇️ Export Excel (.xlsx)
        </a>
        <a href="{{ route('statistics.exportCSV', ['format' => 'csv']) }}"
           class="inline-block px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition">
            ⬇️ Export CSV (.csv)
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
