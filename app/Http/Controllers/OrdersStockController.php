<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class OrdersStockController extends Controller
{
     public function index()
    {
        $orders = Order::with('product')
               ->where('member_id', auth()->id())
               ->get();
        return view('orders-stock.index',compact('orders'));
    }
    
}
