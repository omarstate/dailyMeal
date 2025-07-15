<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MealController;
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
    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [MealController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // Meal Management Routes
        Route::post('/meals', [MealController::class, 'store'])->name('meals.store');
        Route::put('/meals/{meal}', [MealController::class, 'update'])->name('meals.update');
        Route::delete('/meals/{meal}', [MealController::class, 'destroy'])->name('meals.destroy');
        Route::put('/meals/{meal}/assign', [MealController::class, 'assignToDay'])->name('meals.assign');
        Route::put('/meals/{meal}/remove', [MealController::class, 'removeFromDay'])->name('meals.remove');
    });

    // Guest Routes
    Route::middleware(['role:guest'])->group(function () {
        Route::get('/dashboard', function () { return view('guest.dashboard'); });
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
