<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class PurchaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Recupera apenas as compras do membro autenticado, com paginação
        $orders = Order::where('member_id', $user->id)
                        ->orderBy('date', 'desc')
                        ->paginate(20); // Limita para 20 por página

        return view('purchases.index', compact('orders'));
    }
}
