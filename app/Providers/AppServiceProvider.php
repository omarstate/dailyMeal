<?php

namespace App\Providers;

use App\Models\CartItem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartCount = 0;
            
            if (Auth::check() && Auth::user()->role === 'guest') {
                // Force a fresh query each time to avoid caching issues
                $cartCount = CartItem::query()
                    ->where('user_id', Auth::id())
                    ->toBase() // Skip model hydration for better performance
                    ->count();
            }
            
            $view->with('cartCount', $cartCount);
        });
    }
}
