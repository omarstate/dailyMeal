<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cart - Daily Meal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-b from-amber-100 via-amber-200 to-amber-300 min-h-screen"
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

    <!-- Header -->
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.order-meals') : route('guest.dashboard') }}" class="text-gray-700 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-xl font-semibold text-gray-900">Your Cart</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6">
        @if($cartItems->isEmpty())
            <div class="bg-white rounded-xl p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h2 class="text-xl font-medium text-gray-900 mb-2">Your cart is empty</h2>
                <p class="text-gray-500 mb-4">Add a delicious meal to your cart!</p>
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.order-meals') : route('guest.dashboard') }}" class="inline-flex items-center text-orange-500 hover:text-orange-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Return to Menu
                </a>
            </div>
        @else
            @foreach($cartItems as $item)
                <div class="bg-white rounded-xl p-6 mb-6">
                    <div id="cart-item-{{ $item->id }}" class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-medium text-gray-900 mb-2">{{ $item->meal->name }}</h3>
                            <p class="text-gray-600 mb-1">{{ $item->meal->description }}</p>
                            <div class="text-orange-500 text-sm">{{ ucfirst($item->meal->type) }}</div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-green-600 font-medium text-xl">
                                ${{ number_format($item->meal->price, 2) }}
                            </div>
                            <button @click="showConfirmation('Remove Item', 'Are you sure you want to remove {{ $item->meal->name }} from your cart?', () => removeFromCart({{ $item->id }}))" 
                                    class="text-red-500 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="bg-white rounded-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-lg font-medium text-gray-900">Total</span>
                    <span class="text-2xl font-bold text-green-600">${{ number_format($item->meal->price, 2) }}</span>
                </div>
                <button @click="showConfirmation('Place Order', 'Are you sure you want to place this order? This action cannot be undone.', () => placeOrder())" 
                        class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition-colors">
                    Place Order
                </button>
            </div>
        @endif
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
                            class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set redirect URL based on user role
        const dashboardUrl = "{{ auth()->user()->role === 'admin' ? route('admin.order-meals') : route('guest.dashboard') }}";
        
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
            const itemElement = document.getElementById(`cart-item-${itemId}`).closest('.bg-white');
            itemElement.style.opacity = '0.5';

            fetch(`/cart/${itemId}`, {
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
                itemElement.style.opacity = '1';
            });
        }

        function placeOrder() {
            fetch('/cart/placeOrder', {
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
                    // Redirect to the appropriate dashboard
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