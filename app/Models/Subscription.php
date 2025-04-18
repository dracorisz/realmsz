<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'status',
        'trial_ends_at',
        'ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               ($this->ends_at === null || $this->ends_at->isFuture());
    }

    public function isOnTrial()
    {
        return $this->trial_ends_at !== null && 
               $this->trial_ends_at->isFuture();
    }

    public function hasFeature($feature)
    {
        return in_array($feature, $this->package->features);
    }
}
