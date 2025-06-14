@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Estatísticas @if(auth()->user()->is_admin) Administrativas @else Pessoais @endif</h2>

    <form method="GET" action="{{ route('statistics') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label for="month" class="block text-gray-700 font-medium">Filtrar por mês:</label>
            <input type="month" id="month" name="month" value="{{ request('month') }}" class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label for="user" class="block text-gray-700 font-medium">Filtrar por membro:</label>
            <input type="text" id="user" name="user" value="{{ request('user') }}" class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label for="category" class="block text-gray-700 font-medium">Filtrar por categoria:</label>
            <select name="category_id" id="category" class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">Todas</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="product" class="block text-gray-700 font-medium">Filtrar por produto:</label>
            <select name="product_id" id="product" class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">Todos</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-4 flex items-center space-x-4 mt-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Filtrar</button>
            <a href="{{ route('statistics') }}" class="text-gray-500 hover:underline">Limpar</a>
        </div>
    </form>

    @if(request('month'))
        <p class="mb-6 text-gray-600">Estatísticas para: <strong>{{ \Carbon\Carbon::parse(request('month') . '-01')->translatedFormat('F/Y') }}</strong></p>
    @endif

    {{-- Estatísticas Resumo --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow-md rounded-2xl p-4 text-center">
            <p class="text-sm text-gray-500">Total de Vendas (€)</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($summary['total_sales'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-white shadow-md rounded-2xl p-4 text-center">
            <p class="text-sm text-gray-500">Venda Média por Transação (€)</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($summary['average_sales'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-white shadow-md rounded-2xl p-4 text-center">
            <p class="text-sm text-gray-500">Venda Máxima (€)</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($summary['max_sale'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-white shadow-md rounded-2xl p-4 text-center">
            <p class="text-sm text-gray-500">Venda Mínima (€)</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($summary['min_sale'], 2, ',', '.') }}</p>
        </div>
    </div>

    {{-- Gráficos Gerais --}}
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

    {{-- Tabelas de Dados Adicionais --}}
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">📦 Vendas por Categoria</h3>
            <ul class="list-disc list-inside text-gray-600">
                @foreach($salesByCategory as $item)
                    <li>{{ $item->name }} – <strong>{{ number_format($item->total, 2, ',', '.') }}€</strong></li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">👤 Vendas por Membro</h3>
            <ul class="list-disc list-inside text-gray-600">
                @foreach ($salesByUser as $user)
                    <li>{{ $user->name }} – <strong>{{ number_format($user->total, 2, ',', '.') }}€</strong></li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Top Produtos --}}
    <div class="bg-white shadow-md rounded-2xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Top 5 Produtos Mais Vendidos</h3>
        <ul class="list-disc list-inside text-gray-600">
            @foreach ($topProducts as $product)
                <li class="mb-1">{{ $product->name }} – <strong>{{ $product->total }}</strong> unidades</li>
            @endforeach
        </ul>
    </div>

    {{-- Exportações --}}
    <div class="flex justify-end space-x-4">
        <a href="{{ route('statistics.exportXLSX', ['format' => 'xlsx']) }}"
           class="inline-block px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition">
            ⬇️ Exportar Excel (.xlsx)
        </a>
        <a href="{{ route('statistics.exportCSV', ['format' => 'csv']) }}"
           class="inline-block px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition">
            ⬇️ Exportar CSV (.csv)
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
