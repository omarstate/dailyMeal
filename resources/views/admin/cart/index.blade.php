<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Cart - Daily Meal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-b from-emerald-100 via-emerald-200 to-emerald-300 min-h-screen"
      x-data="{ 
        showConfirmModal: false,
        confirmAction: null,
        confirmMessage: '',
        confirmTitle: '',
        showConfirmation(title, message, action) {
            this.confirmTitle = title;
            this.confirmMessage = message;
            this.confirmAction = action;
            this.showConfirmModal = true;
        }
      }">
    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50"></div>

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
                    <a href="{{ route('admin.order-meals') }}" class="flex items-center text-emerald-600 hover:text-emerald-800">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Order Meals
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
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-2">
                <svg class="h-7 w-7 text-emerald-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-emerald-900">Admin Cart</h2>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.order-meals') }}" class="flex items-center gap-2 bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Meals</span>
                </a>
            </div>
        </div>

        <!-- Admin Info -->
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 mb-6">
            <div class="flex items-center gap-2 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>As an admin, you can order multiple meals and cancel orders at any time.</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($cartItems->isEmpty())
                <div class="p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h2 class="text-xl font-medium text-gray-900 mb-2">Your cart is empty</h2>
                    <p class="text-gray-500 mb-4">Add meals to your cart from the Order Meals page.</p>
                    <a href="{{ route('admin.order-meals') }}" class="inline-flex items-center text-emerald-500 hover:text-emerald-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Browse Meals
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-emerald-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-emerald-800 uppercase tracking-wider">Meal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-emerald-800 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-emerald-800 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-emerald-800 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <tr id="cart-item-{{ $item->id }}" class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($item->meal->image_url)
                                                <img src="{{ $item->meal->image_url }}" alt="{{ $item->meal->name }}" class="h-10 w-10 rounded-full object-cover mr-3" onerror="this.src='https://placehold.co/200x200/emerald/white?text=No+Image'">
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $item->meal->name }}</div>
                                                <div class="text-gray-500 text-sm truncate max-w-xs">{{ $item->meal->description }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                            {{ ucfirst($item->meal->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-emerald-600 font-medium">
                                        ${{ number_format($item->meal->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="showConfirmation('Remove Item', 'Are you sure you want to remove {{ $item->meal->name }} from your cart?', () => removeFromCart({{ $item->id }}))" 
                                                class="text-red-500 hover:text-red-600">
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-lg font-medium text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-emerald-600">${{ number_format($total, 2) }}</span>
                    </div>
                    <button @click="showConfirmation('Place Order', 'Are you sure you want to place this order?', () => placeOrder())" 
                            class="w-full bg-emerald-500 text-white py-3 rounded-lg hover:bg-emerald-600 transition-colors">
                        Place Order
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-show="showConfirmModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto">
        
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50"
             x-show="showConfirmModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Modal panel -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6"
                 @click.away="showConfirmModal = false"
                 x-show="showConfirmModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Modal header -->
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-xl font-semibold text-gray-900" x-text="confirmTitle">Confirm Action</h3>
                    <button @click="showConfirmModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal content -->
                <div class="mb-6">
                    <p class="text-gray-600" x-text="confirmMessage"></p>
                </div>

                <!-- Modal footer -->
                <div class="flex justify-end space-x-3">
                    <button @click="showConfirmModal = false"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button @click="confirmAction(); showConfirmModal = false"
                            class="px-4 py-2 bg-emerald-500 text-white rounded-md hover:bg-emerald-600">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set redirect URL
        const dashboardUrl = "{{ route('admin.order-meals') }}";
        
        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.remove('translate-x-full', 'bg-green-500', 'bg-red-500');
            toast.classList.add(isError ? 'bg-red-500' : 'bg-green-500');
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 2000);
        }

        function removeFromCart(itemId) {
            const itemElement = document.getElementById(`cart-item-${itemId}`);
            if (itemElement) {
                itemElement.style.opacity = '0.5';
            }

            fetch(`/admin/cart/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to remove item');
                return response.json();
            })
            .then(data => {
                showToast('Meal removed from cart');
                window.location.reload();
            })
            .catch(error => {
                showToast('Failed to remove meal', true);
                console.error('Error:', error);
                if (itemElement) {
                    itemElement.style.opacity = '1';
                }
            });
        }

        function placeOrder() {
            fetch('/admin/cart/placeOrder', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showToast(data.error, true);
                } else if (data.message) {
                    showToast(data.message);
                    setTimeout(() => {
                        window.location.href = dashboardUrl;
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('There was a problem placing your order. Please try again.', true);
            });
        }
    </script>
</body>
</html> 