<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
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
