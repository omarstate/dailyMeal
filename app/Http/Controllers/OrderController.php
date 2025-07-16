<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('meal')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('orders.history', [
            'orders' => $orders,
            'cartCount' => $user->cartItems()->count()
        ]);
    }

    /**
     * Display active orders for admin (orders from the last 15 minutes)
     */
    public function adminActiveOrders()
    {
        // Ensure only admins can access this
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('guest.dashboard');
        }

        // Get orders from the last 15 minutes
        $cutoffTime = Carbon::now()->subMinutes(15);
        $activeOrders = Order::with(['user', 'meal'])
            ->where('created_at', '>=', $cutoffTime)
            ->whereNull('canceled_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all available meals for the dropdown
        $availableMeals = Meal::all();

        return view('admin.active-orders', [
            'activeOrders' => $activeOrders,
            'availableMeals' => $availableMeals,
            'cartCount' => Auth::user()->cartItems()->count()
        ]);
    }

    /**
     * Admin update order status or change meal
     */
    public function adminUpdateOrder(Request $request, Order $order)
    {
        // Ensure only admins can access this
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('guest.dashboard');
        }

        $validated = $request->validate([
            'action' => 'required|in:change_meal,cancel',
            'meal_id' => 'required_if:action,change_meal|exists:meals,id',
        ]);

        if ($validated['action'] === 'cancel') {
            $order->canceled_at = now();
            $order->save();
            return redirect()->back()->with('success', 'Order has been canceled.');
        } else if ($validated['action'] === 'change_meal') {
            // Change the meal
            $order->meal_id = $validated['meal_id'];
            $order->save();
            return redirect()->back()->with('success', 'Order meal has been updated.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }

    /**
     * Display all orders for admin
     */
    public function adminAllOrders(Request $request)
    {
        // Ensure only admins can access this
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('guest.dashboard');
        }

        // Start building the query
        $query = Order::with(['user', 'meal']);

        // Apply filters if provided
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->whereNull('canceled_at');
            } elseif ($request->status === 'canceled') {
                $query->whereNotNull('canceled_at');
            }
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by user name or meal name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('meal', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Get all orders with pagination
        $allOrders = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.all-orders', [
            'allOrders' => $allOrders,
            'cartCount' => Auth::user()->cartItems()->count()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function cancel(Order $order)
    {
        try {
            // Check if the order belongs to the current user
            if ($order->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to cancel this order.'
                ], 403);
            }

            // Check if the order can be canceled
            if (!$order->canBeCancelled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This order can no longer be canceled.'
                ], 400);
            }

            // Record the cancellation attempt
            $user = Auth::user();
            $user->recordCancellation();

            // Cancel the order
            $order->canceled_at = now();
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order has been canceled successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while canceling the order: ' . $e->getMessage()
            ], 500);
        }
    }
}
