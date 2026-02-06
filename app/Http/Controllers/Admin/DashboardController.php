<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders   = Order::count();
        $totalRevenue  = Order::sum('total_amount');

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenue'
        ));
    }
}
