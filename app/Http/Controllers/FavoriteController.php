<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favoritesIds = session('favorites', []);

        $favoriteProducts = Product::whereIn('id', $favoritesIds)->get();

        return view('favorites.index', compact('favoriteProducts'));
    }

    public function toggle(Request $request)
    {
        $favorites = session('favorites', collect());

        $productId = $request->input('product_id');

        if ($favorites->contains($productId)) {
            $favorites = $favorites->filter(fn($id) => $id != $productId);
        } else {
            $favorites->push($productId);
        }

        session(['favorites' => $favorites]);

        return back();
    }
}
