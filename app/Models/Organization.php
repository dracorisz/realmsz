<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Organization extends Model
{
	use HasFactory;

    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
        'name',
		'website',
		'phone',
		'logo',
		'founder_id',
		'visible',
		'description',
		'address',
		'city',
		'state',
		'country',
		'postal_code',
		'industry',
		'size',
		'founded_year',
		'social_media',
		'settings'
	];

    protected $casts = [
        'visible' => 'boolean',
        'social_media' => 'json',
        'settings' => 'json',
        'founded_year' => 'integer'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function founder()
    {
        return $this->belongsTo(User::class, 'founder_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function priorities()
    {
        return $this->hasMany(Priority::class);
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }

        if (filter_var($this->logo, FILTER_VALIDATE_URL)) {
            return $this->logo;
        }

        return config('filesystems.disks.s3.url') . '/' . $this->logo;
    }

    public function getSocialMediaLinksAttribute()
    {
        return $this->social_media ?? [];
    }

    public function getSettingsAttribute($value)
    {
        return array_merge([
            'theme' => 'light',
            'timezone' => 'UTC',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
            'language' => 'en',
            'notifications' => [
                'email' => true,
                'push' => true,
                'desktop' => true
            ]
        ], json_decode($value, true) ?? []);
    }
}
