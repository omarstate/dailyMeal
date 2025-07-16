<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meal_id',
        'order_date',
        'canceled_at'
    ];

    protected $casts = [
        'order_date' => 'date',
        'canceled_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    /**
     * Check if the order can be cancelled (within 3 minutes of ordering)
     */
    public function canBeCancelled(): bool
    {
        if ($this->canceled_at !== null) {
            return false;
        }

        // Allow cancellation within 3 minutes of creation
        return $this->created_at->addMinutes(3)->isFuture();
    }

    /**
     * Get the remaining time to cancel in seconds
     */
    public function remainingTimeToCancel(): int
    {
        if (!$this->canBeCancelled()) {
            return 0;
        }

        $deadline = $this->created_at->addMinutes(3);
        return max(0, now()->diffInSeconds($deadline));
    }

    /**
     * Get the cancellation deadline timestamp
     */
    public function getCancellationDeadline(): string
    {
        return $this->created_at->addMinutes(3)->toIso8601String();
    }
}
