<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_url',
        'organization_id'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
} 