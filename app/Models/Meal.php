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
        'assigned_days'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'assigned_days' => 'array'
    ];
}
