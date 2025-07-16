<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;
use App\Models\CartItem;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'daily_cancellations',
        'last_cancellation_at',
        'is_blocked',
        'block_expires_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_cancellation_at' => 'datetime',
        'block_expires_at' => 'datetime',
        'is_blocked' => 'boolean',
    ];

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the cart items for the user.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Check if user can cancel orders
     */
    public function canCancelOrders(): bool
    {
        // If user is blocked and block hasn't expired
        if ($this->is_blocked && $this->block_expires_at && $this->block_expires_at->isFuture()) {
            return false;
        }

        // Reset daily cancellations if last cancellation was on a different day
        if ($this->last_cancellation_at && !$this->last_cancellation_at->isToday()) {
            $this->update([
                'daily_cancellations' => 0,
                'is_blocked' => false,
                'block_expires_at' => null
            ]);
            return true;
        }

        // Maximum 3 cancellations per day
        return $this->daily_cancellations < 3;
    }

    /**
     * Record a cancellation attempt
     */
    public function recordCancellation(): void
    {
        $this->daily_cancellations++;
        $this->last_cancellation_at = now();
        
        // If user has exceeded limit, block them for 24 hours
        if ($this->daily_cancellations >= 3) {
            $this->is_blocked = true;
            $this->block_expires_at = now()->addHours(24);
        }
        
        $this->save();
    }

    /**
     * Get time remaining until block expires
     */
    public function blockTimeRemaining(): ?string
    {
        if (!$this->is_blocked || !$this->block_expires_at) {
            return null;
        }

        return now()->diffForHumans($this->block_expires_at, ['parts' => 2]);
    }
}
