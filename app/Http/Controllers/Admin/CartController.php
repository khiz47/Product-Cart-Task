<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        $userId = 1; // hardcoded as per task

        $cart = Cart::with(['items.product'])
            ->where('user_id', $userId)
            ->first();

        $items = [];
        $totalQuantity = 0;
        $grandTotal = 0;

        if ($cart && $cart->items->count()) {
            foreach ($cart->items as $item) {
                $subtotal = $item->price * $item->quantity;

                $items[] = [
                    'product_name' => $item->product->name,
                    'price'        => $item->price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $subtotal,
                ];

                $totalQuantity += $item->quantity;
                $grandTotal += $subtotal;
            }
        }

        return view('admin.cart.index', compact(
            'items',
            'totalQuantity',
            'grandTotal'
        ));
    }
}
