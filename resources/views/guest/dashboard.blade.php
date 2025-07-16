<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Guest Dashboard - Daily Meal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .lock-icon {
            display: none;
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        button[disabled]:hover .lock-icon {
            display: inline;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-b from-amber-100 via-amber-200 to-amber-300 min-h-screen">
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-orange-500 rounded-full p-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">Welcome, {{ Auth::user()->name }}</h1>
                    <p class="text-gray-500">Guest Dashboard</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    @if($cartCount > 0)
                    <span id="cartCount" class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                    @else
                    <span id="cartCount" class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">
                        0
                    </span>
                    @endif
                </a>
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="flex items-center text-gray-700 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6">
        <!-- Toast Notification -->
        <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50">
            Item added to cart
        </div>

        <!-- Date Card -->
        <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl p-6 mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ now()->format('l') }}</h2>
                    <p class="opacity-90">{{ now()->format('F d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 mb-6">
            <div class="flex items-center gap-2 text-orange-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>You haven't placed an order for today yet. Choose a meal below!</p>
            </div>
        </div>

        <!-- Available Meals Section -->
        <div>
            <div class="flex items-center gap-2 mb-6">
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"></path>
                </svg>
                <h2 class="text-xl font-bold text-gray-900">Today's Available Meals</h2>
            </div>

            <!-- Meal Cards Grid -->
             @if($meals->isEmpty())
                <h3 class="text-lg font-medium text-gray-900 mb-1"> No Meals available for today.</h3>
                @endif
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($meals as $meal)
                <div class="bg-white rounded-xl p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-1">{{$meal->name}}</h3>
                    <div class="text-orange-500 text-sm mb-3">{{$meal->type}}</div>
                    <p class="text-gray-600 text-sm mb-4">{{$meal->description}}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-green-600 font-medium">${{$meal->price}}</span>
                        <button 
                            data-meal-id="{{ $meal->id }}"
                            class="add-to-cart-btn flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors disabled:opacity-75 disabled:cursor-not-allowed disabled:bg-orange-700"
                            {{ $hasOrderedToday ? 'disabled' : '' }}
                        >
                            @if($hasOrderedToday)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Already Ordered</span>
                            @else
                                <span>Add to Cart</span>
                            @endif
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function() {
                    if (this.disabled) return;
                    const mealId = this.dataset.mealId;
                    handleAddToCart(this, mealId);
                });
            });
        });

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.remove('translate-x-full', 'bg-green-500', 'bg-red-500');
            toast.classList.add(isError ? 'bg-red-500' : 'bg-green-500');
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 2000);
        }

        function updateCartCount(count) {
            const cartCount = document.getElementById('cartCount');
            if (count > 0) {
                cartCount.textContent = count;
                cartCount.classList.remove('hidden');
            } else {
                cartCount.textContent = '0';
                cartCount.classList.add('hidden');
            }
        }

        function handleAddToCart(button, mealId) {
            const originalText = button.textContent.trim();
            button.disabled = true;
            button.textContent = 'Adding...';

            fetch(`/cart/add/${mealId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json().then(data => ({
                ok: response.ok,
                status: response.status,
                data
            })))
            .then(({ ok, status, data }) => {
                if (!ok) {
                    showToast(data.message || 'Failed to add item to cart', true);
                    return;
                }
                updateCartCount(data.cart_count);
                showToast(data.message || 'Item added to cart');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to add item to cart', true);
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = `Add to Cart${button.hasAttribute('disabled') ? '<svg class="lock-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>' : ''}`;
            });
        }
    </script>
</body>
</html> 