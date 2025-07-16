<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Daily Meal</title>
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
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.all-orders') }}" class="flex items-center gap-2 bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>View Order History</span>
                    </a>
                    <a href="{{ route('admin.cart.index') }}" class="relative text-emerald-600 hover:text-emerald-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @php
                        $cartCount = App\Models\CartItem::where('user_id', auth()->id())->count();
                        @endphp
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
        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Meals Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-emerald-100 text-emerald-500">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Total Meals</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ App\Models\Meal::count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Orders Card -->
            <a href="{{ route('admin.active-orders') }}" class="block">
                <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-sm p-6 hover:bg-blue-50 transition-all duration-300 cursor-pointer">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-gray-500 text-sm">Active Orders</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ App\Models\Order::where('created_at', '>=', \Carbon\Carbon::now()->subMinutes(15))->whereNull('canceled_at')->count() }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-end text-blue-500">
                        <span class="text-sm font-medium">View Recent Orders</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Registered Users Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm">Registered Clients</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Revenue Card -->
            <a href="{{ route('admin.order-meals') }}" class="block">
                <div class="bg-emerald-500 backdrop-blur-sm rounded-lg shadow-sm p-6 hover:bg-emerald-600 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white text-emerald-500">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-white text-sm font-medium">Order Meals</p>
                            <p class="text-2xl font-bold text-white">Place Order</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-end text-white">
                        <span class="text-sm font-medium">Go to Order Page</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <div x-data="{ 
            showModal: false,
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
            <!-- Weekly Meal Plan Header -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-2">
                    <svg class="h-6 w-6 text-emerald-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-emerald-900">Weekly Meal Plan</h2>
                </div>
            </div>

            <!-- Days of Week -->
            <div class="flex justify-between mb-8">
                <div class="flex space-x-4">
                    @foreach($daysOfWeek as $day)
                        <a href="{{ route('admin.dashboard', ['day' => $day]) }}" 
                           class="px-4 py-2 rounded-lg text-center {{ $day === $selectedDay ? 'bg-emerald-500 text-white' : 'bg-white/80 backdrop-blur-sm text-gray-700' }} {{ $day === now()->format('l') ? 'relative' : '' }}">
                            {{ $day }}
                            @if($day === now()->format('l'))
                                <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">Today</span>
                            @endif
                        </a>
                    @endforeach
                </div>
                <button @click="showModal = true" class="bg-emerald-500 text-white px-4 py-2 rounded-lg flex items-center space-x-2 hover:bg-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Create New Meal</span>
                </button>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-2 gap-8">
                <!-- Selected Day's Menu -->
                <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-sm p-6">
                    <div class="flex items-center space-x-2 mb-6">
                        <svg class="h-6 w-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold">{{ $selectedDay }}'s Menu ({{ $meals->count() }} meals)</h3>
                    </div>

                    <!-- Menu Items -->
                    @foreach($meals as $meal)
                    <div class="border-b border-gray-100 last:border-0 py-4">
                        <div class="flex gap-4">
                            <div class="w-24 h-24 flex-shrink-0">
                                <img 
                                    src="{{ $meal->image_url ?? 'https://placehold.co/600x400/emerald/white?text=No+Image' }}" 
                                    alt="{{ $meal->name }}" 
                                    class="w-full h-full object-cover rounded-lg"
                                    onerror="this.src='https://placehold.co/600x400/emerald/white?text=No+Image'"
                                >
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $meal->name }}</h4>
                                        <p class="text-gray-500 text-sm mt-1">{{ $meal->description }}</p>
                                        <div class="flex items-center mt-2">
                                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded">{{ $meal->type }}</span>
                                            <span class="text-green-600 ml-3">${{ number_format($meal->price, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button class="text-red-500 hover:text-red-700" 
                                                @click="showConfirmation('Remove Meal', 'Are you sure you want to remove this meal from {{ $selectedDay }}?', () => removeMealFromDay('{{ $meal->id }}'))">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if($meals->isEmpty())
                    <div class="text-gray-500 text-center py-4">
                        No meals assigned to {{ $selectedDay }}
                    </div>
                    @endif
                </div>

                <!-- Available Meals -->
                <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-sm p-6">
                    <h3 class="text-xl font-semibold mb-6">Available Meals</h3>

                    <!-- Available Menu Items -->
                    <div class="max-h-[600px] overflow-y-auto pr-2 space-y-2 scrollbar-thin scrollbar-thumb-emerald-500 scrollbar-track-emerald-100">
                    @foreach($availableMeals as $meal)
                    <div class="border-b border-gray-100 last:border-0 py-4">
                        <div class="flex gap-4">
                            <div class="w-24 h-24 flex-shrink-0">
                                <img 
                                    src="{{ $meal->image_url ?? 'https://placehold.co/600x400/emerald/white?text=No+Image' }}" 
                                    alt="{{ $meal->name }}" 
                                    class="w-full h-full object-cover rounded-lg"
                                    onerror="this.src='https://placehold.co/600x400/emerald/white?text=No+Image'"
                                >
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $meal->name }}</h4>
                                        <p class="text-gray-500 text-sm mt-1">{{ $meal->description }}</p>
                                        <div class="flex items-center flex-wrap gap-2 mt-2">
                                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded">{{ $meal->type }}</span>
                                            <span class="text-green-600">${{ number_format($meal->price, 2) }}</span>
                                            @foreach($meal->assigned_days ?? [] as $day)
                                                <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600">{{ $day }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if(!in_array($selectedDay, $meal->assigned_days ?? []))
                                        <button class="text-emerald-500 hover:text-emerald-700"
                                                @click="showConfirmation('Assign Meal', 'Are you sure you want to assign this meal to {{ $selectedDay }}?', () => assignMealToDay('{{ $meal->id }}', '{{ $selectedDay }}'))">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if($availableMeals->isEmpty())
                    <div class="text-gray-500 text-center py-4">
                        No available meals
                    </div>
                    @endif
                </div>
            </div>

            <!-- New Meal Modal -->
            <div x-show="showModal"
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto">
                
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-black bg-opacity-50"
                     x-show="showModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"></div>

                <!-- Modal panel -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full p-6"
                         @click.away="showModal = false"
                         x-show="showModal"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">
                        
                        <!-- Modal header -->
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-xl font-semibold text-gray-900">Create New Meal</h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal form -->
                        <form @submit.prevent="submitForm">
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" id="name" name="name" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea id="description" name="description" rows="3" required
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                    <select id="type" name="type" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="main">Main</option>
                                        <option value="salad">Salad</option>
                                        <option value="dessert">Dessert</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                                    <input type="number" id="price" name="price" step="0.01" min="0" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" @click="showModal = false"
                                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-emerald-500 text-white rounded-md hover:bg-emerald-600">
                                    Create Meal
                                </button>
                            </div>
                        </form>
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

            <!-- JavaScript -->
            <script>
                function submitForm(event) {
                    const form = event.target;
                    const formData = new FormData(form);
                    const data = {
                        name: formData.get('name'),
                        description: formData.get('description'),
                        type: formData.get('type'),
                        price: parseFloat(formData.get('price'))
                    };

                    fetch('/meals', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }

                function assignMealToDay(mealId, day) {
                    fetch(`/meals/${mealId}/assign`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ dayOfTheWeek: day })
                    })
                    .then(response => response.json())
                    .then(data => {
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }

                function removeMealFromDay(mealId) {
                    fetch(`/meals/${mealId}/remove`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ dayOfTheWeek: '{{ $selectedDay }}' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }

                function addToCart(mealId) {
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
                            alert(data.message || 'Failed to add item to cart');
                            return;
                        }
                        alert(data.message || 'Item added to cart');
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to add item to cart');
                    });
                }
            </script>
        </div>
    </div>
</body>
</html> 