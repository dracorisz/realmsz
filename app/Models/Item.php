<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
		'organization_id',
		'order'
	];

	protected $casts = [
		'archived' => 'boolean',
		'recurring' => 'boolean',
		'date' => 'datetime',
		'order' => 'integer'
	];

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function priority()
	{
		return $this->belongsTo(Priority::class);
	}

	public function status()
	{
		return $this->belongsTo(Status::class);
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'item_user')
			->withPivot(['role', 'shared_at', 'permissions'])
			->withTimestamps();
	}

	public function attachments()
	{
		return $this->hasMany(Attachment::class);
	}

	public function checklists()
	{
		return $this->hasMany(Checklist::class)->orderBy('order');
	}

	public function organization()
	{
		return $this->belongsTo(Organization::class);
	}

	public function canView(User $user)
	{
		if ($this->organization_id === $user->organization_id) {
			return true;
		}

		return $this->users()->where('user_id', $user->id)->exists();
	}

	public function canEdit(User $user)
	{
		if ($this->organization_id === $user->organization_id) {
			return true;
		}

		$pivot = $this->users()->where('user_id', $user->id)->first()?->pivot;
		return $pivot && in_array($pivot->role, ['editor', 'admin']);
	}

	public function canDelete(User $user)
	{
		if ($this->organization_id === $user->organization_id) {
			return true;
		}

		$pivot = $this->users()->where('user_id', $user->id)->first()?->pivot;
		return $pivot && $pivot->role === 'admin';
	}
}
