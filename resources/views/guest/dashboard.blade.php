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
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div class="text-white">
                        <h2 class="text-2xl font-bold">{{ now()->format('l') }}</h2>
                        <p class="opacity-90">{{ now()->format('F d, Y') }}</p>
                    </div>
                </div>
                @php
                    $allMeals = \App\Models\Meal::all();
                @endphp
                <x-weekly-menu-modal :meals="$allMeals" />
            </div>
        </div>

        <!-- Order Status -->
        <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 mb-6">
            <div class="flex items-center gap-2 text-orange-600">
                @if($isAdmin)
                <div class="w-full flex flex-col items-center gap-3">
                    <h2 class="text-xl font-bold">Admin Mode: You can order multiple meals without restrictions</h2>
                    <p class="text-sm">As an admin, you can order any meal regardless of day assignment and cancel orders at any time.</p>
                </div>
                @elseif($hasOrderedToday)
                <div class="w-full flex flex-col items-center gap-3">
                    <h2 class="text-xl font-bold">Already ordered today's meal. Comeback tomorrow!</h2>
                    @php
                        $order = auth()->guard()->user()->orders()
                            ->whereDate('order_date', now()->toDateString())
                            ->whereNull('canceled_at')
                            ->first();
                    @endphp
                    @if($order && $order->canBeCancelled())
                        <div class="flex flex-col items-center gap-2" 
                            data-deadline="{{ $order->getCancellationDeadline() }}"
                            id="cancelSection"
                        >
                            <p class="text-sm">You can still cancel this order and choose another meal</p>
                            <p class="text-sm font-medium">Time remaining: <span id="remainingTime">0</span>m <span id="remainingSeconds">0</span>s</p>
                            
                            @php
                                $user = auth()->guard()->user();
                                $canCancel = $user->canCancelOrders();
                                $remainingCancellations = 3 - $user->daily_cancellations;
                            @endphp

                            @if(!$canCancel)
                                @if($user->is_blocked)
                                    <p class="text-sm text-red-600 font-medium">You're blocked from cancelling orders for {{ $user->blockTimeRemaining() }}</p>
                                @else
                                    <p class="text-sm text-red-600 font-medium">You've reached the maximum number of cancellations for today</p>
                                @endif
                            @else
                                <p class="text-sm text-orange-600">You have {{ $remainingCancellations }} cancellation{{ $remainingCancellations !== 1 ? 's' : '' }} remaining today</p>
                                <button 
                                    @click="showConfirmation('Cancel Order', 'Are you sure you want to cancel this order? This action cannot be undone.', () => performCancelOrder({{ $order->id }}))"
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors"
                                    {{ !$canCancel ? 'disabled' : '' }}
                                    data-order-id="{{ $order->id }}"
                                >
                                    Cancel Order
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
                @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>You haven't placed an order for today yet. Choose a meal below!</p>
                @endif
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
                <div class="bg-white rounded-xl overflow-hidden">
                    <!-- Meal Image -->
                    <div class="relative h-48 overflow-hidden">
                        <img 
                            src="{{ $meal->image_url }}" 
                            alt="{{ $meal->name }}"
                            class="w-full h-full object-cover transform transition-transform duration-300 hover:scale-110"
                            loading="lazy"
                            onerror="this.src='https://placehold.co/600x400/orange/white?text=Meal+Image'"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <h3 class="text-lg font-medium text-white mb-1">{{ $meal->name }}</h3>
                            <div class="text-orange-300 text-sm">{{ $meal->type }}</div>
                        </div>
                    </div>
                    <!-- Meal Details -->
                    <div class="p-4">
                        <p class="text-gray-600 text-sm mb-4">{{$meal->description}}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-medium">${{$meal->price}}</span>
                            <button 
                                data-meal-id="{{ $meal->id }}"
                                @click="showConfirmation('Add to Cart', 'Are you sure you want to add {{ $meal->name }} to your cart?', () => handleAddToCart($event.target, {{ $meal->id }}))"
                                class="add-to-cart-btn flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors disabled:opacity-75 disabled:cursor-not-allowed disabled:bg-orange-700"
                                {{ $hasOrderedToday && !$isAdmin ? 'disabled' : '' }}
                            >
                                @if($hasOrderedToday && !$isAdmin)
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
                </div>
                @endforeach
            </div>
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
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button @click="confirmAction(); showConfirmModal = false"
                            class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function() {
                    if (this.disabled) return;
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
            if (button.disabled) return;
            
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

        function cancelOrder(orderId) {
            Alpine.data('app').showConfirmation(
                'Cancel Order',
                'Are you sure you want to cancel this order? This action cannot be undone.',
                () => performCancelOrder(orderId)
            );
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

        // Update remaining time every second
        function initializeTimer() {
            const cancelSection = document.getElementById('cancelSection');
            if (!cancelSection) return;

            const remainingTimeElement = document.getElementById('remainingTime');
            const remainingSecondsElement = document.getElementById('remainingSeconds');
            const deadline = new Date(cancelSection.dataset.deadline);

            function updateTimer() {
                const now = new Date();
                const totalSeconds = Math.max(0, Math.floor((deadline - now) / 1000));

                if (totalSeconds > 0) {
                    const minutes = Math.floor(totalSeconds / 60);
                    const seconds = totalSeconds % 60;
                    remainingTimeElement.textContent = minutes;
                    remainingSecondsElement.textContent = seconds;
                } else {
                    window.location.reload();
                }
            }

            // Update immediately and then every second
            updateTimer();
            return setInterval(updateTimer, 1000);
        }

        initializeTimer();
    </script>
</body>
</html> 