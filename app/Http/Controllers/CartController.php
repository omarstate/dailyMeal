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

class CartController extends Controller
{
    public function addToCart(Request $request, Meal $meal): JsonResponse
    {
        try {
            // Check if meal is available for today
            $today = strtolower(Carbon::today()->format('l')); // gets day name in lowercase
            if (empty($meal->assigned_days) || !in_array($today, array_map('strtolower', $meal->assigned_days))) {
                return response()->json([
                    'message' => 'This meal is currently unavailable',
                    'cart_count' => $this->getCartCount()
                ], 422);
            }

            // Check if user already has any meals in cart
            if (CartItem::forCurrentUser()->exists()) {
                return response()->json([
                    'message' => 'Only one meal allowed per day',
                    'cart_count' => $this->getCartCount()
                ], 422);
            }

            DB::beginTransaction();
            try {
                $cartItem = CartItem::create([
                    'user_id' => auth()->guard()->id(),
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
                Log::error('Failed to add meal to cart: ' . $e->getMessage(), [
                    'user_id' => auth()->guard()->id(),
                    'meal_id' => $meal->id,
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'message' => 'Failed to add meal to cart: ' . $e->getMessage(),
                    'cart_count' => $this->getCartCount()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Unexpected error in addToCart: ' . $e->getMessage(), [
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
        if ($cartItem->user_id !== auth()->guard()->id()) {
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
        $cartItems = CartItem::forCurrentUser()
            ->with('meal')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->meal->price;
        });

        return view('cart.index', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    private function getCartCount(): int
    {
        return CartItem::forCurrentUser()
            ->toBase()
            ->count();
    }

    public function placeOrder(Request $request)
    {
        $user = auth()->guard()->user();
        $today = Carbon::today();

        try {
            // Check if user already ordered today
            $alreadyOrdered = Order::where('user_id', $user->id)
                ->whereDate('order_date', $today)
                ->whereNull('canceled_at')
                ->exists();

            if ($alreadyOrdered) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'You already placed an order today.'], 422);
                }
                return redirect()->back()->with('error', 'You already placed an order today.');
            }

            // Get cart item
            $cartItem = CartItem::forCurrentUser()->first();

            if (!$cartItem) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'Your cart is empty.'], 422);
                }
                return redirect()->back()->with('error', 'Your cart is empty.');
            }

            // Check if meal is still available
            $meal = Meal::find($cartItem->meal_id);
            $todayName = strtolower($today->format('l'));
            if (!$meal || empty($meal->assigned_days) || !in_array($todayName, array_map('strtolower', $meal->assigned_days))) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'Sorry, this meal is no longer available.'], 422);
                }
                return redirect()->back()->with('error', 'Sorry, this meal is no longer available.');
            }

            // Use database transaction to ensure data consistency
            DB::beginTransaction();
            try {
                // Create the order
                Order::create([
                    'user_id' => $user->id,
                    'meal_id' => $cartItem->meal_id,
                    'order_date' => $today,
                ]);

                // Clear the cart
                $cartItem->delete();

                DB::commit();

                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'Your meal has been ordered!',
                        'cart_count' => 0
                    ]);
                }
                return redirect()->route('cart.index')->with('success', 'Your meal has been ordered!');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Order placement failed: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Sorry, there was a problem placing your order. Please try again.'], 500);
            }
            return redirect()->back()->with('error', 'Sorry, there was a problem placing your order. Please try again.');
        }
    }
} 