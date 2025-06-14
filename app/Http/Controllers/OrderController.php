<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CompleteOrderRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\OrderCompletedMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Order::with(['products', 'member']);

        if ($user->type === 'member') {
            $query->where('member_id', $user->id);
        } elseif (in_array($user->type, ['employee', 'board'])) {
            // Employees and board can see all orders
            $query->when(request('status'), function($q) {
                $q->where('status', request('status'));
            });
        }

        $orders = $query->paginate(20); // Paginação só no fim

        return view('orders.index', [
            'orders' => $orders,
            'statuses' => ['pending', 'completed', 'canceled']
        ]);
    }


    public function show(Order $order)
    {
        // Carrega os produtos da encomenda
        $order->load('products');

        return view('orders.show', compact('order'));
    }

    public function complete(Order $order)
    {
        $user = Auth::user();

        // Permitir apenas admin e employee
        if (!in_array($user->type, ['employee', 'board'])) {
            abort(403, 'Unauthorized action.');
        }

        // Verificar se o pedido está pendente
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be completed.');
        }

        DB::beginTransaction();

        try {
            // Verificar stock de todos os produtos
            foreach ($order->products as $product) {
                if ($product->stock < $product->order_item->quantity) {
                    DB::rollBack();
                    return back()->with('error', "The product '{$product->name}' doesn`t heave enough stock.");
                }
            }

            // Atualizar stock
            foreach ($order->products as $product) {
                $product->decrement('stock', $product->order_item->quantity);
            }

            // Atualizar o estado da encomenda
            $order->update(['status' => 'completed']);

            // (Opcional) Gerar PDF e enviar email
            // Envia email ao cliente
            Mail::to($order->member->email)->send(new OrderCompletedMail($order));

            DB::commit();
            return back()->with('success', 'Order complete.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'ERROR ! Could not complete the order.');
        }
    }

    public function showCancelForm(Order $order)
    {
        // Opcional: garantir que só 'board' e 'employee' podem cancelar
        if (!in_array(Auth::user()->type, ['employee', 'board'])) {
            abort(403);
        }

        // Redirecionar para a view do formulário de cancelamento
        return view('orders.cancel', compact('order'));
    }

    public function cancel(Order $order, Request $request)
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:255'
        ]);

        // Verificar antes se o pedido está pendente
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be canceled');
        }

        // Verificar se o membro tem cartão para fazer reembolso
        $operation = Operation::whereHas('order', function ($q) use ($order) {
            $q->where('member_id', $order->member_id);
        })->latest()->first();

        $card = $operation?->card;
        if (!$card) {
            return back()->with('error', 'Member does not have a card to refund.');
        }

        try {
            DB::transaction(function () use ($order, $validated, $card) {
                $order->status = 'canceled';
                $order->cancel_reason = $validated['cancel_reason'] ;
                $order->save();

                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock += $item->quantity;
                    $product->save();
                }
                // // Atualizar status da encomenda
                // $order->update([
                //     'status' => 'canceled',
                //     'cancel_reason' => $validated['cancel_reason']
                // ]);

                // Criar operação de crédito para reembolso
                Operation::create([
                    'card_id' => $card->id,
                    'type' => 'credit',
                    'value' => $order->total,
                    'date' => now(),
                    'credit_type' => 'order_cancellation',
                    'order_id' => $order->id
                ]);

                // Incrementar saldo do cartão
                $card->increment('balance', $order->total);
            });

            return redirect()->route('orders.show', $order->id)->with('success', 'Order canceled and amount refunded');
        } catch (\Exception $e) {
            return back()->with('error', 'Error canceling order: ' . $e->getMessage());
        }
    }

    public function generateInvoice(Order $order)
    {
        if ($order->status !== 'completed') {
            abort(403, 'Invoice only available for completed orders.');
        }

        $pdf = PDF::loadView('orders.invoice', compact('order'));
        return $pdf->download('invoice_order_'.$order->id.'.pdf');
    }

}
