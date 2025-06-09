<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\ShippingCost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem as ItemOrder;
use App\Models\Operation;


class CartController extends Controller
{
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
            if (isset($products[$productId])) {
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
                ];
                $total += $cartItems[$productId]['subtotal'];
            }
        }

        // Calcular custo de envio baseado no valor total
        $shippingCost = ShippingCost::getShippingCostForOrderTotal($total);
    }

        //
        // if (Auth::check()) {
        //     $dbCartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        //     foreach ($dbCartItems as $item) {
        //         $cartItems[$item->product_id] = [
        //             'product_id' => $item->product_id,
        //             'name' => $item->product->name,
        //             'price' => $item->product->price,
        //             'effective_price' => $item->product->discount_min_qty
        //                 && $item->quantity >= $item->product->discount_min_qty
        //                 ? $item->product->price - $item->product->discount
        //                 : $item->product->price,
        //             'quantity' => $item->quantity,
        //             'subtotal' => ($item->product->discount_min_qty
        //                 && $item->quantity >= $item->product->discount_min_qty
        //                 ? $item->product->price - $item->product->discount
        //                 : $item->product->price) * $item->quantity,
        //             'stock' => $item->product->stock,
        //             'low_stock' => $item->quantity > $item->product->stock,
        //         ];
        //         $total += $cartItems[$item->product_id]['subtotal'];
        //     }
        // } else {
        //     $sessionCart = session()->get('cart', []);
        //     if (!empty($sessionCart)) {
        //         $productIds = array_keys($sessionCart);
        //         $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        //         foreach ($sessionCart as $productId => $item) {
        //             if (isset($products[$productId])) {
        //                 $cartItems[$productId] = [
        //                     'product_id' => $productId,
        //                     'name' => $products[$productId]->name,
        //                     'price' => $products[$productId]->price,
        //                     'effective_price' => $products[$productId]->discount_min_qty
        //                         && $item['quantity'] >= $products[$productId]->discount_min_qty
        //                         ? $products[$productId]->price - $products[$productId]->discount
        //                         : $products[$productId]->price,
        //                     'quantity' => $item['quantity'],
        //                     'subtotal' => ($products[$productId]->discount_min_qty
        //                         && $item['quantity'] >= $products[$productId]->discount_min_qty
        //                         ? $products[$productId]->price - $products[$productId]->discount
        //                         : $products[$productId]->price) * $item['quantity'],
        //                     'stock' => $products[$productId]->stock,
        //                     'low_stock' => $item['quantity'] > $products[$productId]->stock,
        //                 ];
        //                 $total += $cartItems[$productId]['subtotal'];
        //             }
        //         }
        //     }
        // }

    // Obter NIF e morada do utilizador se estiver autenticado
    $nif = Auth::check() ? Auth::user()->nif : '';
    $delivery_address = Auth::check() ? Auth::user()->default_delivery_address : '';

    return view('cart.index', compact('cartItems', 'total', 'shippingCost', 'nif', 'delivery_address'));
}

    private function calculateEffectivePrice($product, $quantity)
    {
        return ($product->discount_min_qty && $quantity >= $product->discount_min_qty)
            ? $product->price - $product->discount
            : $product->price;
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $currentQuantity = $cart[$productId]['quantity'] ?? 0;
        $cart[$productId] = [
            'product_id' => $productId,
            'quantity' => ($cart[$productId]['quantity'] ?? 0) + $quantity,
        ];
        session()->put('cart', $cart);

        // if (Auth::check()) {
        //     Cart::updateOrCreate(
        //         ['user_id' => Auth::id(), 'product_id' => $productId],
        //         ['quantity' => DB::raw('quantity + ' . $quantity)]
        //     );
        // } else {
        //     $cart = session()->get('cart', []);
        //     $cart[$productId] = [
        //         'product_id' => $productId,
        //         'quantity' => ($cart[$productId]['quantity'] ?? 0) + $quantity,
        //     ];
        //     session()->put('cart', $cart);
        // }

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

        // if (Auth::check()) {
        //     if ($quantity > 0) {
        //         Cart::updateOrCreate(
        //             [
        //                 'user_id' => Auth::id(),
        //                 'product_id' => $productId
        //             ],
        //             [
        //                 'quantity' => $quantity
        //             ]
        //         );
        //     } else {
        //         Cart::where('user_id', Auth::id())
        //             ->where('product_id', $productId)
        //             ->delete();
        //     }
        // } else {
        //     // $cart = session()->get('cart', []);
        //     // if ($quantity > 0) {
        //     //     $cart[$productId] = [
        //     //         'product_id' => $productId,
        //     //         'quantity' => $quantity
        //     //     ];
        //     // } else {
        //     //     unset($cart[$productId]);
        //     // }
        //     // session()->put('cart', $cart);
        // }

        return redirect()->route('cart.index')->with('success', 'Quantidade atualizada!');
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        // if (Auth::check()) {
        //     Cart::where('user_id', Auth::id())->where('product_id', $productId)->delete();
        // } else {
        //     $cart = session()->get('cart', []);
        //     unset($cart[$productId]);
        //     session()->put('cart', $cart);
        // }

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
        'nif' => 'nullable|string|max:9',
        'delivery_address' => 'required|string|max:500',
    ]);

    if (!Auth::check()) {
        session()->put('checkout_data', $request->only(['nif', 'delivery_address']));
        return redirect()->route('login')->with('error', 'Por favor, faça login para completar a compra.');
    }

    $user = Auth::user();

    //Verificar se o utilizador é um membro do clube (member ou board)
    if (!in_array($user->type, ['member', 'board'])) {
        return redirect()->route('settings.edit')->with('error', 'Apenas membros do clube podem realizar compras.');
    }

    //Verificar se o utilizador está bloqueado
    if ($user->blocked) {
        return redirect()->route('cart.index')->with('error', 'A tua conta está bloqueada. Contacta o suporte.');
    }

    //Calcular o total e verificar stock
    $cartItems = [];
    $totalItems = 0;
    $hasLowStock = false;
    $sessionCart = session()->get('cart', []);

    if (empty($sessionCart)) {
        return redirect()->route('cart.index')->with('error', 'O carrinho está vazio.');
    }

    $productIds = array_keys($sessionCart);
    $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

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
                'name' => $products[$productId]->name, // Para notificação
            ];
            $totalItems += $cartItems[$productId]['subtotal'];
            if ($products[$productId]->stock <= 0 || $item['quantity'] > $products[$productId]->stock) {
                $hasLowStock = true;
            }
        }
    }

    $shippingCost = ShippingCost::getShippingCostForOrderTotal($totalItems);
    $total = $totalItems + $shippingCost;

    //Verificar saldo do cartão virtual
    $card = $user->card; // Assumindo relação 'card' no modelo User
    if (!$card || $card->balance < $total) {
        return back()->with('error', 'Saldo insuficiente no cartão virtual.');
    }else if ($card) {
        // Verificar se o cartão está bloqueado
        if ($card->blocked) {
            // Se o cartão estiver bloqueado, não podemos processar o pagamento
            return back()->with('error', 'O teu cartão virtual está bloqueado. Contacta o suporte.');
        }
        if ($card->balance >= $total) {
            $card->decrement('balance', $total);
        }
    }

    //Criar o pedido
    $order = Order::create([
        'member_id' => $user->id,
        'status' => 'pending', // Usamos 'pending' devido à restrição da migração
        'date' => now()->toDateString(),
        'total_items' => $totalItems,
        'shipping_cost' => $shippingCost,
        'total' => $total,
        'nif' => $request->input('nif'),
        'delivery_address' => $request->input('delivery_address'),
        'pdf_receipt' => null,
        'cancel_reason' => null,
        'custom' => null,
    ]);

    //Criar os itens do pedido
    foreach ($cartItems as $item) {
        ItemOrder::create([
            'order_id' => $order->id,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'unit_price' => $item['unit_price'],
            'discount' => $item['discount'],
            'subtotal' => $item['subtotal'],
            'custom' => null,
        ]);
    }

    //Registrar o débito na tabela operations
    Operation::create([
        'card_id' => $card->id,
        'type' => 'debit',
        'value' => $total,
        'date' => now()->toDateString(),
        'debit_type' => 'order',
        'order_id' => $order->id,
        'payment_type' => $user->default_payment_type,
        'payment_reference' => $user->default_payment_reference,
        'custom' => null,
    ]);

    //Limpar o carrinho
    session()->forget('cart');

    //Preparar notificação
    $message = 'Compra concluída com sucesso! Pedido #' . $order->id . ' está a ser preparado.';
    if ($hasLowStock) {
        $message .= ' Aviso: Alguns produtos estão com stock insuficiente ou esgotados, o que pode atrasar a entrega.';
    }

    return redirect()->route('orders-stock.index',)->with('success', $message);

    //NÃO ESTÁ A FUNCIONAR*/
    }


    private function calculateTotal()
    {
        $cartItems = [];
        $total = 0;
        $sessionCart = session()->get('cart', []);

        if (!empty($sessionCart)) {
            $productIds = array_keys($sessionCart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($sessionCart as $productId => $item) {
                if (isset($products[$productId])) {
                    $effectivePrice = $this->calculateEffectivePrice($products[$productId], $item['quantity']);
                    $total += $effectivePrice * $item['quantity'];
                }
            }

            $shippingCost = ShippingCost::getShippingCostForOrderTotal($total);
            $total += $shippingCost;
        }

        return $total;
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

        // Restaurar dados de checkout se existirem
        if (session()->has('checkout_data')) {
            $checkoutData = session()->get('checkout_data');
            $user->update([
                'nif' => $checkoutData['nif'] ?? $user->nif,
                'address' => $checkoutData['address'] ?? $user->address
            ]);
            session()->forget('checkout_data');
        }
    }
}
?>
