<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaLink extends Model
{
    protected $table = 'social_media_links';

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;
    
    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = ['sort_order'];
}

