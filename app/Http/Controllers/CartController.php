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

        return redirect()->route('catalog.index')->with('success', 'Produto adicionado ao carrinho!');
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

        return redirect()->route('cart.index')->with('success', 'Quantidade atualizada!');
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

        return redirect()->route('cart.index')->with('success', 'Produto removido do carrinho!');
    }

    public function clearCart(Request $request)
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Carrinho limpo com sucesso!');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'nif' => 'nullable|string|max:9|regex:/^[0-9]{9}$/',
            'delivery_address' => 'required|string|max:500',
        ]);

        if (!Auth::check()) {
            session()->put('checkout_data', $request->only(['nif', 'delivery_address']));
            return redirect()->route('login')->with('error', 'Por favor, faça login para completar a compra.');
        }

        $user = Auth::user();
        $card = Card::where('id', $user->id)->first();

        if (!$card) {
            return redirect()->route('card.index')->with('error', 'É necessário um cartão virtual para continuar.');
        }

        if (!in_array($user->type, ['member', 'board'])) {
            return redirect()->route('membership.pay')->with('error', 'Apenas membros do clube podem realizar compras.');
        }

        if ($user->blocked) {
            return redirect()->route('cart.index')->with('error', 'A tua conta está bloqueada. Contacta o suporte.');
        }

        $sessionCart = session()->get('cart', []);
        if (empty($sessionCart)) {
            return redirect()->route('cart.index')->with('error', 'O carrinho está vazio.');
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
            return redirect()->route('cart.index')->with('error', 'Alguns produtos estão sem stock ou excedem o stock disponível.');
        }

        $shippingCost = ShippingCost::getShippingCostForOrderTotal($totalItems);
        $total = $totalItems + $shippingCost;

        // Store order data in session for confirmation
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
            return redirect()->route('login')->with('error', 'Faça login para continuar.');
        }

        $orderData = session()->get('order_data');
        if (!$orderData) {
            return redirect()->route('cart.index')->with('error', 'Não há dados para confirmar a compra.');
        }

        return view('cart.confirm', compact('orderData'));
    }

    public function finalize(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Faça login para continuar.');
        }

        $user = Auth::user();
        $card = Card::where('id', $user->id)->first();
        $orderData = session()->get('order_data');

        if (!$orderData) {
            return redirect()->route('cart.index')->with('error', 'Não há dados para finalizar a compra.');
        }

        if (!$card) {
            return redirect()->route('card.index')->with('error', 'Cartão virtual não encontrado.');
        }

        // Validate card balance
        if ($card->balance < $orderData['total']) {
            return redirect()->route('card.credit')->with('error', 'Saldo insuficiente no cartão virtual. Por favor, adicione fundos.');
        }

        // Start a database transaction
        DB::beginTransaction();
        try {
            // Debit the card
            $card->balance -= $orderData['total'];
            $card->save();

            // Create order
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

            // Create order items and update stock
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

            // Record operation
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

            // Clear session data
            session()->forget(['cart', 'order_data']);

            DB::commit();
            return redirect()->route('purchase.index')->with('success', 'Compra concluída com sucesso! Pedido #' . $order->id . ' está a ser preparado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.confirm')->with('error', 'Erro ao finalizar a compra. Tente novamente.');
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
