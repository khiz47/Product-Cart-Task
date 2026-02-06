<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CartApiController extends Controller
{
    public function addToCart(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        try {
            $userId = 1; // hardcoded as per task

            DB::transaction(function () use ($request, $userId) {

                $cart = Cart::firstOrCreate([
                    'user_id' => $userId
                ]);

                $product = Product::findOrFail($request->product_id);

                $cartItem = CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $product->id)
                    ->first();

                if ($cartItem) {
                    $cartItem->increment('quantity', $request->quantity);
                } else {
                    CartItem::create([
                        'cart_id'   => $cart->id,
                        'product_id' => $product->id,
                        'quantity'  => $request->quantity,
                        'price'     => $product->price,
                    ]);
                }
            });

            return response()->json([
                'status' => true,
                'message' => 'Product added to cart successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add product to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function cartList(): \Illuminate\Http\JsonResponse
    {
        try {
            $userId = 1; // hardcoded as per task

            $cart = \App\Models\Cart::with(['items.product.images'])
                ->where('user_id', $userId)
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Cart is empty',
                    'data' => [
                        'items' => [],
                        'total_quantity' => 0,
                        'subtotal' => 0,
                        'grand_total' => 0,
                    ]
                ]);
            }

            $items = [];
            $totalQuantity = 0;
            $subtotal = 0;

            foreach ($cart->items as $item) {
                $itemSubtotal = $item->price * $item->quantity;

                $items[] = [
                    'cart_item_id' => $item->id,
                    'product_id'   => $item->product->id,
                    'name'         => $item->product->name,
                    'price'        => $item->price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $itemSubtotal,
                    'images'       => $item->product->images->map(function ($img) {
                        return asset('storage/' . $img->image_path);
                    }),
                ];

                $totalQuantity += $item->quantity;
                $subtotal += $itemSubtotal;
            }

            return response()->json([
                'status' => true,
                'message' => 'Cart fetched successfully',
                'data' => [
                    'items' => $items,
                    'total_quantity' => $totalQuantity,
                    'subtotal' => $subtotal,
                    'grand_total' => $subtotal, // no tax/shipping yet
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCartItem(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity'     => 'required|integer|min:1',
        ]);

        try {
            $cartItem = \App\Models\CartItem::find($request->cart_item_id);

            if (!$cartItem) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart item not found'
                ], 404);
            }

            $cartItem->update([
                'quantity' => $request->quantity
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Cart item updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeCartItem($id): \Illuminate\Http\JsonResponse
    {
        try {
            $cartItem = \App\Models\CartItem::find($id);

            if (!$cartItem) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart item not found'
                ], 404);
            }

            $cartItem->delete();

            return response()->json([
                'status' => true,
                'message' => 'Cart item removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to remove cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkout(): \Illuminate\Http\JsonResponse
    {
        try {
            $userId = 1;

            $cart = \App\Models\Cart::with('items.product')
                ->where('user_id', $userId)
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart is empty'
                ], 400);
            }

            $total = 0;
            foreach ($cart->items as $item) {
                $total += $item->price * $item->quantity;
            }

            // Create order
            $order = \App\Models\Order::create([
                'user_id' => $userId,
                'total_amount' => $total,
                'payment_status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ]);
            }

            // Stripe Payment Intent
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $total * 100, // in paise/cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);

            $order->update([
                'payment_intent_id' => $paymentIntent->id,
            ]);

            // Clear cart
            $cart->items()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Checkout initiated',
                'data' => [
                    'order_id' => $order->id,
                    'client_secret' => $paymentIntent->client_secret,
                    'amount' => $total
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Checkout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
