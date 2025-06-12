<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Order::with(['items.product', 'member'])
            ->orderBy('date', 'desc');

        if ($user->type === 'member') {
            $query->where('member_id', $user->id);
        } elseif (in_array($user->type, ['employee', 'board'])) {
            // Employees and board can see all orders
            $query->when(request('status'), function($q) {
                $q->where('status', request('status'));
            });
        }

        $orders = $query->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
            'statuses' => ['pending', 'completed', 'canceled']
        ]);
    }

    public function complete(Order $order)
    {
        DB::transaction(function () use ($order) {
            // Verify all products have enough stock
            foreach ($order->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    return back()->with('error', "Product {$item->product->name} doesn't have enough stock");
                }
            }

            // Update stock
            foreach ($order->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            // Update order status
            $order->update(['status' => 'completed']);

            // Here would go the PDF generation and email sending (to be implemented later)
        });

        return back()->with('success', 'Order marked as completed');
    }

    public function cancel(Order $order, Request $request)
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($order, $validated) {
            // Only pending orders can be canceled
            if ($order->status !== 'pending') {
                return back()->with('error', 'Only pending orders can be canceled');
            }

            // Update order status
            $order->update([
                'status' => 'canceled',
                'cancel_reason' => $validated['cancel_reason']
            ]);

            // Refund to member's card
            Operation::create([
                'card_id' => $order->member->card->id,
                'type' => 'credit',
                'value' => $order->total,
                'date' => now(),
                'credit_type' => 'order_cancellation',
                'order_id' => $order->id
            ]);

            // Update card balance
            $order->member->card->increment('balance', $order->total);
        });

        return back()->with('success', 'Order canceled and amount refunded');
    }
}
