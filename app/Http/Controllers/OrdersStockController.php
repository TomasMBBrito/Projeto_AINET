<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrdersStockController extends Controller
{
     public function index()
    {
        return view('orders-stock.index');
    }
}
