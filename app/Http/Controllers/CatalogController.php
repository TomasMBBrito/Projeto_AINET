<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CatalogController extends Controller
{
    public function index(Request $request)
{
    $categories = Category::orderBy('name')->get();

    $query = Product::with('category');

    // Filtro por categoria
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // Ordenação
    if ($request->filled('sort')) {
        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }
    } else {
        $query->orderBy('name', 'asc');
    }

    $products = $query->get();

    return view('catalog.index', compact('products', 'categories'));
}

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Exemplo simples de carrinho na sessão
        $cart = session()->get('cart', []);

        $productId = $request->product_id;
        $quantity = $request->quantity;

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }
}
