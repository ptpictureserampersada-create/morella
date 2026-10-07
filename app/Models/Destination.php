<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $hidden = ['sort_order'];

    protected function casts(): array
    {
        return [
            'coordinates' => 'array',
            'facilities' => 'array',
            'galleryImages' => 'array',
            'ticketPriceNum' => 'integer',
            'featured' => 'boolean',
            'published' => 'boolean',
        ];
    }
}
