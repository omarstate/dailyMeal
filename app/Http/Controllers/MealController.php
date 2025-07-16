<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Models\Meal;
use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealController extends Controller
{
    
    public function adminDashboard()
    {
        $selectedDay = request('day', now()->format('l')); // Default to current day
        
        // Get all meals
        $allMeals = Meal::all();
        
        // Filter meals for selected day
        $meals = $allMeals->filter(function($meal) use ($selectedDay) {
            $assignedDays = $meal->assigned_days ?? [];
            return in_array($selectedDay, $assignedDays);
        });
        
        return view('admin.dashboard', [
            'selectedDay' => $selectedDay,
            'meals' => $meals,
            'availableMeals' => $allMeals,
            'daysOfWeek' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
        ]);
    }



    public function guestDashboard()
    {
        $today = now()->format('l'); // Gets current day name (e.g., "Monday")
        
        // Get all meals and filter for today
        $meals = Meal::all()->filter(function($meal) use ($today) {
            $assignedDays = $meal->assigned_days ?? [];
            return in_array($today, $assignedDays);
        });

        // Check if user has ordered today
        $hasOrderedToday = Order::where('user_id', Auth::id())
            ->whereDate('order_date', now()->toDateString())
            ->whereNull('canceled_at')
            ->exists();

        // Get cart count
        $cartCount = CartItem::where('user_id', Auth::id())->count();

        return view('guest.dashboard', [
            'today' => $today,
            'meals' => $meals,
            'hasOrderedToday' => $hasOrderedToday,
            'cartCount' => $cartCount
        ]);
    }




    public function index()
    {
        $meals = Meal::all();
        return response()->json($meals);
    }


    
    public function create()
    {
        //
    }


    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:main,salad,dessert',
            'price' => 'required|numeric|min:0'
        ]);

        $validated['assigned_days'] = [];
        $meal = Meal::create($validated);

        return response()->json($meal, 201);
    }
    

   
    public function show(Meal $meal)
    {
        //
    }

    
    public function edit(Meal $meal)
    {
        //
    }

    
    public function update(UpdateMealRequest $request, Meal $meal)
    {
        $meal->update($request->validated());
        return response()->json($meal);
    }

  
    public function destroy(Meal $meal)
    {
        $meal->delete();
        return response()->json(null, 204);
    }

   
    public function assignToDay(Request $request, Meal $meal)
    {
        $validated = $request->validate([
            'dayOfTheWeek' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday'
        ]);

        $day = $validated['dayOfTheWeek'];
        $assignedDays = $meal->assigned_days ?? [];

        // Only add the day if it's not already assigned
        if (!in_array($day, $assignedDays)) {
            $assignedDays[] = $day;
            $meal->assigned_days = $assignedDays;
            $meal->save();
        }

        return response()->json($meal);
    }

    
    public function removeFromDay(Request $request, Meal $meal)
    {
        $validated = $request->validate([
            'dayOfTheWeek' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday'
        ]);

        $day = $validated['dayOfTheWeek'];
        $assignedDays = $meal->assigned_days ?? [];

        // Remove the day and reindex the array
        $meal->assigned_days = array_values(array_diff($assignedDays, [$day]));
        $meal->save();

        return response()->json($meal);
    }

    private function isAssignedToDay($meal, $day)
    {
        $assignedDays = $meal->assigned_days ?? [];
        return in_array($day, $assignedDays);
    }
}
