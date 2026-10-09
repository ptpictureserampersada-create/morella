<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengabdianActivity extends Model
{
    protected $table = 'pengabdian_activities';

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $hidden = ['sort_order'];
}
