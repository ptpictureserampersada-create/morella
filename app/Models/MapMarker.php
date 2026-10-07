<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapMarker extends Model
{
    protected $table = 'map_markers';

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $hidden = ['sort_order'];

    protected function casts(): array
    {
        return [
            'mapX' => 'integer',
            'mapY' => 'integer',
            'rating' => 'float',
        ];
    }
}
