@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-4">
        <h1 class="text-2xl font-bold text-green-700">Carrinho de Compras</h1>

        @if (session('success'))
            <div class="mt-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if (empty($cartItems))
            <p class="mt-4 text-gray-600">O teu carrinho está vazio.</p>
            <a href="{{ route('catalog.index') }}" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Voltar ao Catálogo
            </a>
        @else
            <div class="mt-4">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">Produto</th>
                            <th class="px-4 py-2 text-left">Preço Unitário</th>
                            <th class="px-4 text-nowrap py-2 text-left">Quantidade</th>
                            <th class="px-4 py-2 text-left">Subtotal</th>
                            <th class="px-4 py-2 text-left">Stock</th>
                            <th class="px-4 py-2 text-left">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $item['name'] }}</td>
                                <td class="px-4 py-2">
                                    @if ($item['price'] != $item['effective_price'])
                                        <span class="text-green-700">€{{ number_format($item['effective_price'], 2) }}</span>
                                        <span class="line-through text-gray-400 ml-2">€{{ number_format($item['price'], 2) }}</span>
                                    @else
                                        €{{ number_format($item['price'], 2) }}
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0"
                                            class="w-16 p-1 border rounded mr-2">
                                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-sm">
                                            Atualizar
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-2">€{{ number_format($item['subtotal'], 2) }}</td>
                                <td class="px-4 py-2">
                                    @if ($item['low_stock'])
                                        <span class="text-red-500 font-semibold">Stock baixo: {{ $item['stock'] }} (entrega pode demorar)</span>
                                    @else
                                        <span class="text-green-600 font-semibold">Stock: {{ $item['stock'] }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                        <button type="submit"> type="submit" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-sm">
                                            Remover
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 text-right">
                    <p class="text-sm">Subtotal: €{{ number_format($total, 2) }}</p>
                    <p class="text-sm">Custo de Envio: €{{ number_format($shippingCost, 2) }}</p>
                    <p class="text-lg font-bold">Total: €{{ number_format($total + $shippingCost, 2) }}</p>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <div class="inline-flex">
                        <a href="{{ route('catalog.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition mr-4">
                            Voltar ao Catálogo
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                                Limpar Carrinho
                            </button>
                        </form>
                    </div>
                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            Finalizar Compra
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
```

#### 4. **Atualizar a Página do Catálogo (`catalog/index.blade.php`)**
A página do catálogo já está configurada com o formulário para adicionar itens, mas para melhorar a experiência do utilizador, adicionarei uma mensagem de sucesso flutuante (toast) usando Tailwind CSS e JavaScript simples.

<xaiArtifact artifact_id="0f8fbe8c-d1d2-4f33-817e-fa79225daaa7" artifact_version_id="c328e91c-a0a5-4065-a435-92fd6fb7a257" title="catalog/index.blade.php" contentType="text/html">
@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Toast Notification -->
        @if (session('success'))
            <div id="toast" class="fixed bottom-4 right-4 bg-green-100 text-green-700 p-4 rounded-lg shadow-lg flex items-center">
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('toast').style.display='none'" class="ml-4 text-green-500">
                    &times;
                </button>
            </div>
        @endif

        <!-- Restante do código existente -->
        <div style="display: flex; align-items: center; justify-content-between; margin-bottom: 1rem;">
            <a href="{{ route('home') }}" style="display: inline-flex; align-items-center; color: #15803d; font-weight: 500; text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.25rem; width: 1.25rem; margin-right: 0.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        <h1 class="text-3xl font-bold text-green-800 mb-6">Product Catalog</h1>

        <!-- Filtros -->
        <form method="GET" class="flex flex-wrap gap-4 mb-8">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                class="border border-green-300 rounded px-4 py-2 shadow-sm focus:ring-green-500"/>
            <select name="category" class="border border-green-300 rounded px-3 py-2 shadow-sm focus:ring-green-500">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            <select name="sort" class="border border-green-300 rounded px-4 py-2 shadow-sm focus:ring-green-500">
                <option value="">Order by</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Growing)</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (Degrowing)</option>
            </select>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Filter</button>
        </form>

        <!-- Grid de Produtos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($products as $product)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-4 flex flex-col justify-between">
                <!-- Imagem -->
                @if ($product->photo)
                    <img src="{{ asset('storage/products/' . $product->photo) }}"
                        alt="{{ $product->name }}"
                        class="w-40 h-40 object-cover mx-auto rounded-lg shadow-md">
                    </img>
                @else
                    <div class="w-full h-40 bg-green-50 flex items-center justify-center rounded mb-3 text-green-400">
                        <span>No image</span>
                    </div>
                @endif

                <!-- Nome e Categoria -->
                <div>
                    <h2 class="text-lg font-semibold text-green-900">{{ $product->name }}</h2>
                    <p class="text-sm text-green-600">{{ $product->category->name }}</p>
                </div>

                <!-- Preço e Desconto -->
                <div class="mt-3">
                    @if ($product->discount_min_qty && $product->discount)
                        <p class="text-sm text-green-700 font-medium">
                            {{ number_format($product->price - $product->discount, 2) }} €
                            <span class="line-through text-gray-400 ml-2">{{ number_format($product->price, 2) }} €</span>
                        </p>
                        <p class="text-xs text-green-500">
                            Discount from {{ $product->discount_min_qty }} units
                        </p>
                    @else
                        <p class="text-sm text-green-900 font-medium">
                            {{ number_format($product->price, 2) }} €
                        </p>
                    @endif
                </div>

                <!-- Descrição -->
                <p class="text-sm text-gray-600 mt-2">{{ $product->description }}</p>

                <!-- Controles -->
                <div class="mt-4 flex items-center justify-between">
                    @if ($product->stock <= 0)
                        <span class="text-xs text-red-500 font-semibold">Out of Stock</span>
                    @else
                        <span class="text-xs text-green-600 font-semibold">In Stock: {{ $product->stock }}</span>
                    @endif
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="number" name="quantity" value="1" min="1" class="w-16 p-1 border rounded mr-2">
                        <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700 text-sm">
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Paginação -->
        @if ($products->hasMorePages())
            <div class="mt-10 flex justify-center w-full">
                <nav role="navigation" class="flex flex-wrap justify-center w-full max-w-4xl rounded-lg shadow-md bg-white border border-gray-300 overflow-hidden">
                    <!-- Previous Page -->
                    @if ($products->onFirstPage())
                        <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 cursor-not-allowed">‹</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 text-sm text-green-600 hover:bg-green-50">›</a>
                    @endif

                    <!-- Pages -->
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if ($page == $products->currentPage())
                        <span class="px-4 text-sm font-bold py-2 text-white bg-green-600">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 text-sm text-green-600 hover:bg-green-50">{{ $page }}</a>
                    @endif
                    @endforeach

                    <!-- Next Page -->
                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 text-sm text-green-600 hover:bg-green-50">›</a>
                    @else
                        <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 cursor-not-allowed">›</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
</div>
@endsection
```

#### 5. **Atualizar o `AuthController` para Sincronização**
Certifique-se de que o carrinho seja sincronizado após o login. Ajuste o método `login` no `AuthController.php`:

<xaiArtifact artifact_id="49c7415e-3153-4dd0-b25f-19ed2f539ed7" artifact_version_id="66ef91e6-bc80-47f1-b16e-c461f63ce39f" title="AuthController.php" contentType="text/php">
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;

class AuthController extends Controller
{
    // Outros métodos...

    public function login(Request $request)
    {
        $credentials = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Sincronizar o carrinho
            CartController::syncCartAfterLogin(Auth::user());

            return redirect()->intended('home');
        }

        return back()->withErrors([
            'error' => 'As credenciais fornecidas não correspondem aos nossos registos.',
        ])->onlyInput('email');
    }

    // Outros métodos...
}
?>
