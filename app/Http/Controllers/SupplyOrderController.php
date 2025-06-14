<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SupplyOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SupplyOrderController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->when(request('low_stock'), function($q) {
                $q->where('stock', '<', DB::raw('stock_lower_limit'));
            })
            ->orderBy('stock')
            ->paginate(10);

        $supplyOrders = SupplyOrder::with(['product', 'registeredBy'])
            ->when(request('status'), function($q) {
                $q->where('status', request('status'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('supply-orders.index', [
            'products' => $products,
            'supplyOrders' => $supplyOrders,
            'statuses' => ['requested', 'completed']
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'auto_calculate' => 'sometimes|boolean'
        ]);

        if ($request->auto_calculate) {
            $product = Product::find($validated['product_id']);
            $validated['quantity'] = max(1, $product->stock_upper_limit - $product->stock);
        }

        SupplyOrder::create([
            'product_id' => $validated['product_id'],
            'registered_by_user_id' => Auth::id(),
            'status' => 'requested',
            'quantity' => $validated['quantity']
        ]);

        return back()->with('success', 'Supply order created');
    }

    public function complete(SupplyOrder $supplyOrder)
    {
        DB::transaction(function () use ($supplyOrder) {
            $supplyOrder->product->increment('stock', $supplyOrder->quantity);
            $supplyOrder->update(['status' => 'completed']);
        });

        return back()->with('success', 'Supply order completed and stock updated');
    }

    public function destroy(SupplyOrder $supplyOrder)
    {
        $supplyOrder->forceDelete();
        return back()->with('success', 'Supply order deleted');
    }
}
