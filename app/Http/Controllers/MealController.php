<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Display the admin dashboard with meals.
     */
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meals = Meal::all();
        return response()->json($meals);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created meal
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Meal $meal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meal $meal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMealRequest $request, Meal $meal)
    {
        $meal->update($request->validated());
        return response()->json($meal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        $meal->delete();
        return response()->json(null, 204);
    }

    /**
     * Assign a meal to a specific day
     */
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

    /**
     * Remove a meal from a day's menu
     */
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

    /**
     * Check if a meal is assigned to a specific day
     */
    private function isAssignedToDay($meal, $day)
    {
        $assignedDays = $meal->assigned_days ?? [];
        return in_array($day, $assignedDays);
    }
}
