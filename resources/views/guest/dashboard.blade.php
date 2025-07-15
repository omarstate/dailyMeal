<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Guest Dashboard - Daily Meal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    <h1 class="text-xl font-semibold text-gray-900">Welcome, Guest User</h1>
                    <p class="text-gray-500">Guest Dashboard</p>
                </div>
            </div>
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6">
        <!-- Date Card -->
        <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl p-6 mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <div class="text-white">
                    <h2 class="text-2xl font-bold">Tuesday</h2>
                    <p class="opacity-90">July 15, 2025</p>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Vegan Salad -->
                <div class="bg-white rounded-xl p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Vegan Salad</h3>
                    <div class="text-orange-500 text-sm mb-3">salad</div>
                    <p class="text-gray-600 text-sm mb-4">Fresh mixed greens with quinoa and avocado</p>
                    <div class="flex justify-between items-center">
                        <span class="text-green-600 font-medium">$ 9.99</span>
                        <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                            Order Now
                        </button>
                    </div>
                </div>

                <!-- Veggie Burger -->
                <div class="bg-white rounded-xl p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Veggie Burger</h3>
                    <div class="text-orange-500 text-sm mb-3">main</div>
                    <p class="text-gray-600 text-sm mb-4">Plant-based burger with fresh toppings</p>
                    <div class="flex justify-between items-center">
                        <span class="text-green-600 font-medium">$ 10.99</span>
                        <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                            Order Now
                        </button>
                    </div>
                </div>

                <!-- Grilled Chicken -->
                <div class="bg-white rounded-xl p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Grilled Chicken</h3>
                    <div class="text-orange-500 text-sm mb-3">main</div>
                    <p class="text-gray-600 text-sm mb-4">Tender grilled chicken with herbs and spices</p>
                    <div class="flex justify-between items-center">
                        <span class="text-green-600 font-medium">$ 12.99</span>
                        <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                            Order Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 