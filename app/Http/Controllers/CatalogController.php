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

        // Filtro por nome
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

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

        $products = $query->paginate(12)->withQueryString();

        return view('catalog.index', compact('products', 'categories'));
    }
}
?>
