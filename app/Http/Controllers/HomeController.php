<?php
namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Buscar 4 produtos aleatórios com stock
        $featuredProducts = Product::where('stock', '>', 0)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('dashboard', compact('featuredProducts'));
    }
}
