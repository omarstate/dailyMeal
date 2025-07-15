<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create meals without day assignment (available meals)
        $meals = [
            [
                'name' => 'Grilled Chicken',
                'description' => 'Tender grilled chicken with herbs and spices',
                'type' => 'main',
                'price' => 12.99,
                'assigned_days' => []
            ],
            [
                'name' => 'Vegan Salad',
                'description' => 'Fresh mixed greens with quinoa and avocado',
                'type' => 'salad',
                'price' => 9.99,
                'assigned_days' => []
            ],
            [
                'name' => 'Pasta Carbonara',
                'description' => 'Creamy pasta with bacon and parmesan',
                'type' => 'main',
                'price' => 11.99,
                'assigned_days' => []
            ],
            [
                'name' => 'Caesar Salad',
                'description' => 'Crisp romaine with caesar dressing and croutons',
                'type' => 'salad',
                'price' => 10.99,
                'assigned_days' => []
            ]
        ];

        foreach ($meals as $meal) {
            Meal::create($meal);
        }

        // Create a meal assigned to multiple days
        Meal::create([
            'name' => 'Special Chicken',
            'description' => 'Special marinated grilled chicken with herbs',
            'type' => 'main',
            'price' => 14.99,
            'assigned_days' => ['Sunday', 'Wednesday']
        ]);

        // Create a meal assigned to one day
        Meal::create([
            'name' => 'Fresh Salad',
            'description' => 'Mixed greens with special dressing',
            'type' => 'salad',
            'price' => 8.99,
            'assigned_days' => ['Sunday']
        ]);
    }
}
