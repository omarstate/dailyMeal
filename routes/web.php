<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminCartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    // Order Routes (available to both admin and guest)
    Route::get('/orders/history', [OrderController::class, 'index'])->name('orders.history');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [MealController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // Meal Management Routes
        Route::post('/meals', [MealController::class, 'store'])->name('meals.store');
        Route::put('/meals/{meal}', [MealController::class, 'update'])->name('meals.update');
        Route::delete('/meals/{meal}', [MealController::class, 'destroy'])->name('meals.destroy');
        Route::put('/meals/{meal}/assign', [MealController::class, 'assignToDay'])->name('meals.assign');
        Route::put('/meals/{meal}/remove', [MealController::class, 'removeFromDay'])->name('meals.remove');
        
        // Admin Order Routes
        Route::get('/admin/order-meals', [MealController::class, 'adminOrderMeals'])->name('admin.order-meals');
        Route::get('/admin/active-orders', [OrderController::class, 'adminActiveOrders'])->name('admin.active-orders');
        Route::get('/admin/all-orders', [OrderController::class, 'adminAllOrders'])->name('admin.all-orders');
        Route::post('/admin/orders/{order}/update', [OrderController::class, 'adminUpdateOrder'])->name('admin.orders.update');
        
        // Admin Cart Routes
        Route::get('/admin/cart', [AdminCartController::class, 'getCart'])->name('admin.cart.index');
        Route::post('/admin/cart/add/{meal}', [AdminCartController::class, 'addToCart'])->name('admin.cart.add');
        Route::delete('/admin/cart/{cartItem}', [AdminCartController::class, 'removeFromCart'])->name('admin.cart.remove');
        Route::post('/admin/cart/placeOrder', [AdminCartController::class, 'placeOrder'])->name('admin.cart.placeOrder');
    });

    // Guest Routes
    Route::middleware(['role:guest'])->group(function () {
        Route::get('/dashboard', [MealController::class, 'guestDashboard'])->name('guest.dashboard');
        
        // Guest Cart Routes
        Route::get('/cart', [CartController::class, 'getCart'])->name('cart.index');
        Route::post('/cart/add/{meal}', [CartController::class, 'addToCart'])->name('cart.add');
        Route::delete('/cart/{cartItem}', [CartController::class, 'removeFromCart'])->name('cart.remove');
        Route::post('/cart/placeOrder', [CartController::class, 'placeOrder']);
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
