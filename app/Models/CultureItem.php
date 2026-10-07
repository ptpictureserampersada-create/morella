<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CultureItem extends Model
{
    protected $table = 'culture_items';

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $hidden = ['sort_order'];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'published' => 'boolean',
        ];
    }
}
