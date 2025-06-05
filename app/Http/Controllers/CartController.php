<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\ShippingCost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = [];
        $total = 0;
        $shippingCost = ShippingCost::first()->cost ?? 0;

        if (Auth::check()) {
            $dbCartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            foreach ($dbCartItems as $item) {
                $cartItems[$item->product_id] = [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'effective_price' => $item->product->discount_min_qty
                        && $item->quantity >= $item->product->discount_min_qty
                        ? $item->product->price - $item->product->discount
                        : $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => ($item->product->discount_min_qty
                        && $item->quantity >= $item->product->discount_min_qty
                        ? $item->product->price - $item->product->discount
                        : $item->product->price) * $item->quantity,
                    'stock' => $item->product->stock,
                    'low_stock' => $item->quantity > $item->product->stock,
                ];
                $total += $cartItems[$item->product_id]['subtotal'];
            }
        } else {
            $sessionCart = session()->get('cart', []);
            if (!empty($sessionCart)) {
                $productIds = array_keys($sessionCart);
                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
                foreach ($sessionCart as $productId => $item) {
                    if (isset($products[$productId])) {
                        $cartItems[$productId] = [
                            'product_id' => $productId,
                            'name' => $products[$productId]->name,
                            'price' => $products[$productId]->price,
                            'effective_price' => $products[$productId]->discount_min_qty
                                && $item['quantity'] >= $products[$productId]->discount_min_qty
                                ? $products[$productId]->price - $products[$productId]->discount
                                : $products[$productId]->price,
                            'quantity' => $item['quantity'],
                            'subtotal' => ($products[$productId]->discount_min_qty
                                && $item['quantity'] >= $products[$productId]->discount_min_qty
                                ? $products[$productId]->price - $products[$productId]->discount
                                : $products[$productId]->price) * $item['quantity'],
                            'stock' => $products[$productId]->stock,
                            'low_stock' => $item['quantity'] > $products[$productId]->stock,
                        ];
                        $total += $cartItems[$productId]['subtotal'];
                    }
                }
            }
        }

        return view('cart.index', compact('cartItems', 'total', 'shippingCost'));
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if (Auth::check()) {
            Cart::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $productId],
                ['quantity' => DB::raw('quantity + ' . $quantity)]
            );
        } else {
            $cart = session()->get('cart', []);
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => ($cart[$productId]['quantity'] ?? 0) + $quantity,
            ];
            session()->put('cart', $cart);
        }

        return redirect()->route('catalog.index')->with('success', 'Produto adicionado ao carrinho!');
    }

    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        if (Auth::check()) {
            if ($quantity > 0) {
                Cart::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'product_id' => $productId
                    ],
                    [
                        'quantity' => $quantity
                    ]
                );
            } else {
                Cart::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->delete();
            }
        } else {
            $cart = session()->get('cart', []);
            if ($quantity > 0) {
                $cart[$productId] = [
                    'product_id' => $productId,
                    'quantity' => $quantity
                ];
            } else {
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Quantidade atualizada!');
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->where('product_id', $productId)->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produto removido do carrinho!');
    }

    public function clear()
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        }
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Carrinho limpo!');
    }

    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Por favor, faça login ou registe-se para completar a compra.');
        }

        if (!Auth::user()->has_paid_membership) {
            return redirect()->route('settings.edit')->with('error', 'Por favor, pague a taxa de adesão para completar a compra.');
        }

        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        }
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Compra concluída com sucesso!');
    }

    public static function syncCartAfterLogin($user)
    {
        $sessionCart = session()->get('cart', []);

        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $item) {
                Cart::updateOrCreate(
                    ['user_id' => $user->id, 'product_id' => $productId],
                    ['quantity' => DB::raw('quantity + ' . $item['quantity'])]
                );
            }
            session()->forget('cart');
        }
    }
}
?>
