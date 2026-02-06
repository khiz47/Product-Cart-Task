<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    // Order listing
    public function index()
    {
        $orders = Order::latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    // Order detail
    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }
}
