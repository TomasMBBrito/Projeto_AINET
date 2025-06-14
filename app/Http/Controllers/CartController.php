<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ShippingCost;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Operation;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use Illuminate\Routing\Controller as BaseController;

class CartController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except(['index', 'add', 'update', 'remove', 'clearCart']);
    }

    public function index()
    {
        $cartItems = [];
        $total = 0;
        $shippingCost = 0;

        $sessionCart = session()->get('cart', []);
        if (!empty($sessionCart)) {
            $productIds = array_keys($sessionCart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($sessionCart as $productId => $item) {
                $effectivePrice = $this->calculateEffectivePrice($products[$productId], $item['quantity']);

                $cartItems[$productId] = [
                    'product_id' => $productId,
                    'name' => $products[$productId]->name,
                    'price' => $products[$productId]->price,
                    'effective_price' => $effectivePrice,
                    'quantity' => $item['quantity'],
                    'subtotal' => $effectivePrice * $item['quantity'],
                    'stock' => $products[$productId]->stock,
                    'low_stock' => $item['quantity'] > $products[$productId]->stock,
                    'discount_min_qty' => $products[$productId]->discount_min_qty,
                ];
                $total += $cartItems[$productId]['subtotal'];
            }

            $shippingCost = ShippingCost::getShippingCostForOrderTotal($total);
        }

        $nif = Auth::check() ? Auth::user()->nif : '';
        $delivery_address = Auth::check() ? Auth::user()->default_delivery_address : '';

        return view('cart.index', compact('cartItems', 'total', 'shippingCost', 'nif', 'delivery_address'));
    }

    private function calculateEffectivePrice($product, $quantity)
    {
        if ($product->discount_min_qty && $product->discount > 0 && $quantity >= $product->discount_min_qty) {
            return max($product->price - $product->discount, 0);
        }
        return $product->price;
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $cart = session()->get('cart', []);
        $cart[$productId] = [
            'product_id' => $productId,
            'quantity' => ($cart[$productId]['quantity'] ?? 0) + $quantity,
        ];
        session()->put('cart', $cart);

        return redirect()->route('catalog.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        $cart = session()->get('cart', []);
        if ($quantity > 0) {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        } else {
            unset($cart[$productId]);
        }
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Updated quantity!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->input('product_id');

        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }

    public function clearCart(Request $request)
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart successfully cleaned!');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'nif' => 'nullable|string|max:9|regex:/^[0-9]{9}$/',
            'delivery_address' => 'required|string|max:500',
        ]);

        if (!Auth::check()) {
            session()->put('checkout_data', $request->only(['nif', 'delivery_address']));
            return redirect()->route('login')->with('error', 'Please log in to complete your purchase..');
        }

        $user = Auth::user();
        $card = Card::where('id', $user->id)->first();

        if (!$card) {
            return redirect()->route('card.index')->with('error', 'A virtual card is required to continue.');
        }

        if (!in_array($user->type, ['member', 'board'])) {
            return redirect()->route('membership.pay')->with('error', 'Only club members can make purchases.');
        }

        if ($user->blocked) {
            return redirect()->route('cart.index')->with('error', 'Your account is blocked. Contact support.');
        }

        $sessionCart = session()->get('cart', []);
        if (empty($sessionCart)) {
            return redirect()->route('cart.index')->with('error', 'The cart is empty.');
        }

        $productIds = array_keys($sessionCart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $cartItems = [];
        $totalItems = 0;
        $hasLowStock = false;

        foreach ($sessionCart as $productId => $item) {
            if (isset($products[$productId])) {
                $effectivePrice = $this->calculateEffectivePrice($products[$productId], $item['quantity']);
                $discount = $products[$productId]->price - $effectivePrice;
                $cartItems[$productId] = [
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'unit_price' => $effectivePrice,
                    'discount' => $discount,
                    'subtotal' => $effectivePrice * $item['quantity'],
                    'name' => $products[$productId]->name,
                ];
                $totalItems += $cartItems[$productId]['subtotal'];

                if ($products[$productId]->stock <= 0 || $item['quantity'] > $products[$productId]->stock) {
                    $hasLowStock = true;
                }
            }
        }

        if ($hasLowStock) {
            return redirect()->route('cart.index')->with('error', 'Some products are out of stock or exceed available stock.');
        }

        $shippingCost = ShippingCost::getShippingCostForOrderTotal($totalItems);
        $total = $totalItems + $shippingCost;

        session()->put('order_data', [
            'cartItems' => $cartItems,
            'totalItems' => $totalItems,
            'shippingCost' => $shippingCost,
            'total' => $total,
            'nif' => $request->input('nif'),
            'delivery_address' => $request->input('delivery_address'),
        ]);

        return redirect()->route('cart.confirm');
    }

    public function showConfirm()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Log in to continue.');
        }

        $orderData = session()->get('order_data');
        if (!$orderData) {
            return redirect()->route('cart.index')->with('error', 'There is no data to confirm the purchase.');
        }

        return view('cart.confirm', compact('orderData'));
    }

    public function finalize(Request $request)
    {


        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Log in to continue.');
        }

        $user = Auth::user();
        $card = Card::where('id', $user->id)->first();
        $orderData = session()->get('order_data');

        if (!$orderData) {
            return redirect()->route('cart.index')->with('error', 'There is no data to finalize the purchase.');
        }

        if (!$card) {
            return redirect()->route('card.index')->with('error', 'Virtual card not found.');
        }

        if ($card->balance < $orderData['total']) {
            return redirect()->route('card.credit')->with('error', 'Insufficient balance on virtual card. Please add funds.');
        }

        DB::beginTransaction();
        try {
            $card->balance -= $orderData['total'];
            $card->save();

            $order = Order::create([
                'member_id' => $user->id,
                'status' => 'pending',
                'date' => now()->toDateString(),
                'total_items' => $orderData['totalItems'],
                'shipping_cost' => $orderData['shippingCost'],
                'total' => $orderData['total'],
                'nif' => $orderData['nif'] ?: null,
                'delivery_address' => $orderData['delivery_address'],
                'pdf_receipt' => null,
                'cancel_reason' => null,
            ]);

            foreach ($orderData['cartItems'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product = Product::find($item['product_id']);
                $product->stock -= $item['quantity'];
                $product->save();
            }

            Operation::create([
                'card_id' => $card->id,
                'type' => 'debit',
                'value' => $orderData['total'],
                'date' => now()->toDateString(),
                'debit_type' => 'order',
                'order_id' => $order->id,
                'payment_type' => null,
                'payment_reference' => null,
            ]);

            session()->forget(['cart', 'order_data']);

            DB::commit();
            return redirect()->route('purchase.index')->with('success', 'Purchase completed successfully! Order #' . $order->id . ' is being prepared.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.confirm')->with('error', 'Error completing purchase. Try again.');
        }
    }

    public static function syncCartAfterLogin($user)
    {
        if (session()->has('checkout_data')) {
            $checkoutData = session()->get('checkout_data');
            $user->update([
                'nif' => $checkoutData['nif'] ?? $user->nif,
                'default_delivery_address' => $checkoutData['delivery_address'] ?? $user->default_delivery_address,
            ]);
            session()->forget('checkout_data');
        }
    }
}
