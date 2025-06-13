<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favoritesIds = session('favorites', []);

        // Buscar os produtos cujos IDs estão nos favoritos
        $favoriteProducts = Product::whereIn('id', $favoritesIds)->get();

        return view('favorites.index', compact('favoriteProducts'));
    }
    // // Adicionar produto aos favoritos na sessão
    // public function add(Request $request)
    // {
    //     $request->validate(['product_id' => 'required|integer']);

    //     $favorites = session()->get('favorites', []);

    //     if (!in_array($request->product_id, $favorites)) {
    //         $favorites[] = $request->product_id;
    //         session()->put('favorites', $favorites);
    //     }

    //     return back()->with('success', 'Produto adicionado aos favoritos.');
    // }

    // // Remover produto dos favoritos na sessão
    // public function remove(Request $request)
    // {
    //     $request->validate(['product_id' => 'required|integer']);

    //     $favorites = session()->get('favorites', []);

    //     if (($key = array_search($request->product_id, $favorites)) !== false) {
    //         unset($favorites[$key]);
    //         session()->put('favorites', array_values($favorites)); // reindexar
    //     }

    //     return back()->with('success', 'Produto removido dos favoritos.');
    // }

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
