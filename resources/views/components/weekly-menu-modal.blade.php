@props(['meals'])

<div x-data="{ open: false }" @keydown.escape.window="open = false">
    <!-- Trigger Button -->
    <button @click="open = true" class="flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
        </svg>
        View Weekly Menu
    </button>

    <!-- Modal -->
    <div x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <!-- Background overlay -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black opacity-50"></div>

        <!-- Modal content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="open = false"
                 class="relative bg-white rounded-xl shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto p-6">
                
                <!-- Header with slide-down animation -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-300 delay-150"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Weekly Menu</h2>
                    <button @click="open = false" 
                            class="text-gray-500 hover:text-gray-700 hover:rotate-90 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Days tabs with slide-up animation -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-300 delay-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-4"
                     x-data="{ activeDay: 'all' }" 
                     class="mb-6">
                    <div class="flex space-x-2 overflow-x-auto pb-2">
                        <button 
                            @click="activeDay = 'all'"
                            :class="{ 'bg-orange-500 text-white': activeDay === 'all', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeDay !== 'all' }"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            All Days
                        </button>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <button 
                                @click="activeDay = '{{ $day }}'"
                                :class="{ 'bg-orange-500 text-white': activeDay === '{{ $day }}', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeDay !== '{{ $day }}' }"
                                class="px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap">
                                {{ $day }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Meals grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                        @foreach($meals as $meal)
                            <div 
                                x-show="activeDay === 'all' || @js($meal->assigned_days).includes(activeDay)"
                                x-transition:enter="transform transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transform transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                                class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all duration-200">
                                    
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

                                    <div class="p-4">
                                        <p class="text-gray-600 text-sm mb-4">{{ $meal->description }}</p>
                                        
                                        <!-- Available days badges -->
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @foreach($meal->assigned_days as $day)
                                                <span 
                                                    :class="{'ring-2 ring-orange-500 ring-offset-2': activeDay === '{{ $day }}' || activeDay === 'all'}"
                                                    class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded-full transition-all duration-200">
                                                    {{ $day }}
                                                </span>
                                            @endforeach
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <span class="text-green-600 font-medium">${{ $meal->price }}</span>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 