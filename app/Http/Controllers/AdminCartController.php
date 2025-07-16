<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Meal;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminCartController extends Controller
{
    public function addToCart(Request $request, Meal $meal): JsonResponse
    {
        try {
            $user = auth()->guard()->user();
            
            // Ensure user is admin
            if ($user->role !== 'admin') {
                return response()->json([
                    'message' => 'Unauthorized access',
                ], 403);
            }

            DB::beginTransaction();
            try {
                $cartItem = CartItem::create([
                    'user_id' => $user->id,
                    'meal_id' => $meal->id
                ]);

                if (!$cartItem) {
                    throw new \Exception('Failed to create cart item');
                }

                DB::commit();

                return response()->json([
                    'message' => 'Meal added to cart',
                    'cart_count' => $this->getCartCount()
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to add meal to admin cart: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'meal_id' => $meal->id,
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'message' => 'Failed to add meal to cart: ' . $e->getMessage(),
                    'cart_count' => $this->getCartCount()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Unexpected error in admin addToCart: ' . $e->getMessage(), [
                'user_id' => auth()->guard()->id(),
                'meal_id' => $meal->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'An unexpected error occurred',
                'cart_count' => $this->getCartCount()
            ], 500);
        }
    }

    public function removeFromCart(CartItem $cartItem): JsonResponse
    {
        $user = auth()->guard()->user();
        
        // Ensure user is admin
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }
        
        if ($cartItem->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        DB::beginTransaction();
        try {
            $cartItem->delete();
            DB::commit();

            return response()->json([
                'message' => 'Item removed from cart',
                'cart_count' => $this->getCartCount()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to remove item from cart',
                'cart_count' => $this->getCartCount()
            ], 500);
        }
    }

    public function getCart()
    {
        $user = auth()->guard()->user();
        
        // Ensure user is admin
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }
        
        $cartItems = CartItem::where('user_id', $user->id)
            ->with('meal')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->meal->price;
        });

        return view('admin.cart.index', [
            'cartItems' => $cartItems,
            'total' => $total,
            'cartCount' => $this->getCartCount()
        ]);
    }

    private function getCartCount(): int
    {
        return CartItem::where('user_id', auth()->guard()->id())
            ->toBase()
            ->count();
    }

    public function placeOrder(Request $request)
    {
        $user = auth()->guard()->user();
        $today = Carbon::today();
        
        // Ensure user is admin
        if ($user->role !== 'admin') {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }
            abort(403, 'Unauthorized access');
        }

        try {
            // Get cart items
            $cartItems = CartItem::where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'Your cart is empty.'], 422);
                }
                return redirect()->back()->with('error', 'Your cart is empty.');
            }

            // Use database transaction to ensure data consistency
            DB::beginTransaction();
            try {
                foreach ($cartItems as $cartItem) {
                    // Create the order
                    Order::create([
                        'user_id' => $user->id,
                        'meal_id' => $cartItem->meal_id,
                        'order_date' => $today,
                    ]);

                    // Clear the cart item
                    $cartItem->delete();
                }

                DB::commit();

                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'Your meal(s) have been ordered!',
                        'cart_count' => 0
                    ]);
                }
                return redirect()->route('admin.order-meals')->with('success', 'Your meal(s) have been ordered!');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Admin order placement failed: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Sorry, there was a problem placing your order. Please try again.'], 500);
            }
            return redirect()->back()->with('error', 'Sorry, there was a problem placing your order. Please try again.');
        }
    }
} 