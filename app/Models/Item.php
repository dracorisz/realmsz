<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
	use HasFactory;

	public static function boot()
	{
		parent::boot();

		static::creating(function ($item) {
			$item->organization_id = Auth::user()->organization_id;
		});
	}

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'category_id',
		'priority_id',
		'status_id',
		'title',
		'description',
		'closure',
		'date',
		'recurring_interval',
		'recurring',
		'archived',
		'image',
	];

	public function category()
	{
		return $this->belongsTo(Category::class, 'category_id', 'id');
	}

	public function priority()
	{
		return $this->belongsTo(Priority::class, 'priority_id', 'id');
	}

	public function status()
	{
		return $this->belongsTo(Status::class, 'status_id', 'id');
	}

	public function users()
	{
		return $this->belongsToMany(User::class);
	}
}
