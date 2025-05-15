<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Traits\HasProfilePhoto;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use App\Models\UserConnection;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasTimestamps;

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
        'show_in_network',
        'bio',
        'location',
        'website',
        'social_links',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'show_in_network' => 'boolean',
        'social_links' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'user_id', 'id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->with('plan')
            ->first();
    }

    public function hasActiveSubscription()
    {
        return $this->activeSubscription() !== null;
    }

    public function hasFeature($feature)
    {
        $subscription = $this->activeSubscription();
        return $subscription && $subscription->hasFeature($feature);
    }

    protected function getSubscriptionAttribute()
    {
        return $this->activeSubscription();
    }

    public function connections()
    {
        return $this->hasMany(UserConnection::class);
    }

    public function connectedUsers()
    {
        return $this->hasMany(UserConnection::class, 'connection_id');
    }

    public function pendingConnections()
    {
        return $this->connections()->where('status', 'pending');
    }

    public function acceptedConnections()
    {
        return $this->connections()->where('status', 'accepted');
    }

    public function sentConnectionRequests()
    {
        return $this->belongsToMany(User::class, 'user_connections', 'user_id', 'connection_id')
            ->wherePivot('status', 'pending');
    }

    public function receivedConnectionRequests()
    {
        return $this->hasMany(UserConnection::class, 'receiver_id')->where('status', 'pending');
    }

    /**
     * Get the user's activities.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function hasConnection(User $user)
    {
        return $this->connections()->where('id', $user->id)->exists();
    }

    public function organizations()
    {
        return $this->belongsToMany(\App\Models\Organization::class, 'organization_user')
            ->withPivot('role')
            ->withTimestamps();
    }
}
