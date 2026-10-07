<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $hidden = ['sort_order'];

    protected function casts(): array
    {
        return [
            'adultCount' => 'integer',
            'childCount' => 'integer',
            'pricePerTicket' => 'integer',
            'cleanlinessFee' => 'integer',
            'totalAmount' => 'integer',
        ];
    }
}
