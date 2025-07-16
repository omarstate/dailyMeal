<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order History - Daily Meal</title>
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

    <!-- Header -->
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
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('cart.index') }}" class="relative text-emerald-600 hover:text-emerald-800">
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.dashboard') }}" class="mr-3 text-emerald-600 hover:text-emerald-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-emerald-900">All Orders</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.active-orders') }}" class="text-emerald-600 hover:text-emerald-800">
                    View Active Orders
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-8 text-center shadow-sm">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h2 class="text-xl font-medium text-gray-900 mb-2">No order history found</h2>
                <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                <a href="{{ route('guest.dashboard') }}" class="inline-flex items-center text-emerald-500 hover:text-emerald-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Return to Menu
                </a>
            </div>
        @else
            <div class="bg-white/80 backdrop-blur-sm rounded-xl overflow-hidden shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Your Order History</h2>
                    <p class="text-sm text-gray-600">View all your past orders</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Meal
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <tr class="{{ $order->canceled_at ? 'bg-red-50' : '' }} hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $order->order_date->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $order->meal->image_url }}" alt="{{ $order->meal->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->meal->name }}</div>
                                                <div class="text-xs text-gray-500">{{ ucfirst($order->meal->type) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${{ number_format($order->meal->price, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($order->canceled_at)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Canceled
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">{{ $order->canceled_at->format('M d, h:i A') }}</div>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                            @if($order->canBeCancelled())
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Can cancel for: 
                                                    <span class="font-medium" data-order-id="{{ $order->id }}" data-deadline="{{ $order->getCancellationDeadline() }}">
                                                        {{ $order->remainingTimeToCancel() }} seconds
                                                    </span>
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(!$order->canceled_at && $order->canBeCancelled())
                                            @php
                                                $user = auth()->user();
                                                $canCancel = $user->canCancelOrders();
                                                $remainingCancellations = 3 - $user->daily_cancellations;
                                            @endphp

                                            @if(!$canCancel)
                                                @if($user->is_blocked)
                                                    <div class="text-xs text-red-600 font-medium">Blocked for {{ $user->blockTimeRemaining() }}</div>
                                                @else
                                                    <div class="text-xs text-red-600 font-medium">Max cancellations reached</div>
                                                @endif
                                            @else
                                                <button 
                                                    @click="showConfirmation('Cancel Order', 'Are you sure you want to cancel this order? You have {{ $remainingCancellations }} cancellation(s) remaining today.', () => performCancelOrder({{ $order->id }}))"
                                                    class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600"
                                                    data-order-id="{{ $order->id }}"
                                                >
                                                    Cancel Order
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-6">
                    {{ $orders->links() }}
                </div>
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
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button @click="confirmAction(); showConfirmModal = false"
                            class="px-4 py-2 bg-emerald-500 text-white rounded-md hover:bg-emerald-600 transition-colors">
                        Confirm
                    </button>
                </div>
            </div>
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

        function performCancelOrder(orderId) {
            const cancelButton = document.querySelector(`button[data-order-id="${orderId}"]`);
            if (cancelButton) {
                cancelButton.disabled = true;
                cancelButton.textContent = 'Cancelling...';
            }

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/orders/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to cancel order');
                    });
                }
                return response.json();
            })
            .then(data => {
                showToast(data.message || 'Order canceled successfully');
                // Reload the page after a short delay to show the toast
                setTimeout(() => window.location.reload(), 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(error.message || 'Failed to cancel order', true);
                if (cancelButton) {
                    cancelButton.disabled = false;
                    cancelButton.textContent = 'Cancel Order';
                }
            });
        }

        // Update countdown timers
        document.addEventListener('DOMContentLoaded', function() {
            const timers = document.querySelectorAll('[data-deadline]');
            
            if (timers.length > 0) {
                setInterval(() => {
                    timers.forEach(timer => {
                        const deadline = new Date(timer.dataset.deadline);
                        const now = new Date();
                        const remainingSeconds = Math.max(0, Math.floor((deadline - now) / 1000));
                        
                        if (remainingSeconds > 0) {
                            timer.textContent = `${remainingSeconds} seconds`;
                        } else {
                            // Reload when a timer expires
                            window.location.reload();
                        }
                    });
                }, 1000);
            }
        });
    </script>
</body>
</html> 