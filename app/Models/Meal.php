<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'price',
        'assigned_days',
        'image_url'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'assigned_days' => 'array'
    ];

    /**
     * Get the URL for the meal's image
     */
    public function getImageUrlAttribute($value): string
    {
        return $value ?? 'https://placehold.co/600x400/orange/white?text=Meal+Image';
    }
}
