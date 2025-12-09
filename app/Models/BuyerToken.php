<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BuyerToken extends Model
{
    protected $table = 'buyer_tokens';

    protected $fillable = [
        'queue_number',
        'token',
        'is_active',
        'user_id',
        'last_activity_at',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'last_activity_at'=> 'datetime',
    ];

    // scope token yang sedang bebas
    public function scopeAvailable($query)
    {
        return $query->where('is_active', false);
    }

    public function markActive($userId = null)
    {
        $this->update([
            'is_active'        => true,
            'user_id'          => $userId,
            'last_activity_at' => now(),
        ]);
    }

    public function markInactive()
    {
        $this->update([
            'is_active'        => false,
            'user_id'          => null,
            'last_activity_at' => null,
        ]);
    }

    public function isExpired(int $minutes = 60): bool
    {
        if (!$this->is_active || !$this->last_activity_at) {
            return false;
        }

        return $this->last_activity_at->lt(now()->subMinutes($minutes));
    }
}

