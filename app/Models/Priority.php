<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
	use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'organization_id',
		'icon_id',
		'name',
		'description',
		'color',
		'show_icon'
	];

	public function organization()
	{
		return $this->belongsTo(Organization::class, 'organization_id', 'id');
	}

	public function icon()
	{
		return $this->belongsTo(Icon::class, 'icon_id', 'id');
	}
}
