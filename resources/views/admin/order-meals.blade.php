<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Meals - Admin Panel - Daily Meal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-b from-emerald-100 via-emerald-200 to-emerald-300 min-h-screen">
    <!-- Top Navigation Bar -->
    <nav class="bg-white/80 backdrop-blur-sm border-b border-emerald-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <!-- Logo and Title -->
                    <div class="flex items-center space-x-3">
                        <div class="bg-emerald-500 rounded-full p-2">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-semibold text-gray-900">Daily Meal Admin</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center text-emerald-600 hover:text-emerald-800">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.cart.index') }}" class="relative text-emerald-600 hover:text-emerald-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-emerald-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $cartCount }}
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
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Toast Notification -->
        <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50"></div>

        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-2">
                <svg class="h-7 w-7 text-emerald-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-emerald-900">Order Meals</h2>
            </div>
        </div>

        <!-- Admin Info -->
        <div class="bg-white/80 border border-emerald-100 rounded-xl p-4 mb-6">
            <div class="flex items-center gap-2 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>As an admin, you can order any meal regardless of day assignment and cancel orders at any time.</p>
            </div>
        </div>

        <!-- Meal Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data="{ activeTab: 'all' }">
            @foreach($meals as $meal)
            <div x-show="activeTab === 'all' || activeTab === '{{ $meal->type }}'" x-transition class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                <!-- Meal Image -->
                <div class="relative h-48 overflow-hidden group">
                    <img 
                        src="{{ $meal->image_url }}" 
                        alt="{{ $meal->name }}"
                        class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110"
                        loading="lazy"
                        onerror="this.src='https://placehold.co/600x400/emerald/white?text=No+Image'"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent group-hover:from-black/70 transition-all duration-300"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 transform transition-transform duration-300 group-hover:translate-y-[-4px]">
                        <h3 class="text-lg font-medium text-white mb-1">{{ $meal->name }}</h3>
                        <div class="text-emerald-300 text-sm">{{ ucfirst($meal->type) }}</div>
                    </div>
                </div>
                <!-- Meal Details -->
                <div class="p-6 transform transition-all duration-300 group-hover:bg-emerald-50/50">
                    <p class="text-gray-600 text-sm mb-4">{{$meal->description}}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-emerald-600 font-medium">${{$meal->price}}</span>
                        <button 
                            onclick="addToCart('{{ $meal->id }}')"
                            class="flex items-center gap-2 bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600 transition-all duration-300 hover:shadow-md"
                        >
                            <span>Add to Cart</span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.remove('translate-x-full', 'bg-green-500', 'bg-red-500');
            toast.classList.add(isError ? 'bg-red-500' : 'bg-green-500');
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 2000);
        }

        function addToCart(mealId) {
            fetch(`/admin/cart/add/${mealId}`, {
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
                showToast(data.message || 'Item added to cart');
                setTimeout(() => window.location.reload(), 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to add item to cart', true);
            });
        }
    </script>
</body>
</html> 